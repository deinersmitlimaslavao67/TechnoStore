<?php
// API de la tienda: usa procedimientos almacenados y vistas de MySQL.
session_start();
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/config.php';
require __DIR__ . '/mailer.php';   // notificaciones por correo

function out(array $d = []) { echo json_encode(['success' => true] + $d, JSON_UNESCAPED_UNICODE); exit; }
function fail(string $m, int $c = 400) { http_response_code($c); echo json_encode(['success' => false, 'error' => $m], JSON_UNESCAPED_UNICODE); exit; }

// Conexión: acepta DB_HOST con puerto, ej. '127.0.0.1:8080' (PDO necesita host y puerto separados)
[$host, $port] = array_pad(explode(':', DB_HOST, 2), 2, '3306');
try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=" . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    // En producción quita el detalle del mensaje
    fail('No se pudo conectar a MySQL (' . $host . ':' . $port . '): ' . $e->getMessage(), 500);
}

// Ejecuta una consulta/CALL y devuelve las filas (o [] si no hay resultado)
function q(string $sql, array $p = []): array {
    global $db;
    $st = $db->prepare($sql);
    $st->execute($p);
    $rows = $st->columnCount() ? $st->fetchAll() : [];
    $st->closeCursor();
    return $rows;
}

// Transacción: si algo falla se deshace todo
function tx(callable $f) {
    global $db;
    $db->beginTransaction();
    try { $r = $f(); $db->commit(); return $r; }
    catch (Throwable $e) { $db->rollBack(); if ($e instanceof RuntimeException) fail($e->getMessage()); throw $e; }
}

function geoJson(string $url, array $payload = null): array {
    $options = [
        'http' => [
            'method' => $payload === null ? 'GET' : 'POST',
            'header' => "Accept: application/json\r\nUser-Agent: TechnoStore/1.0\r\n" . ($payload === null ? '' : "Content-Type: application/json\r\n"),
            'content' => $payload === null ? '' : json_encode($payload, JSON_UNESCAPED_UNICODE),
            'timeout' => 12,
            'ignore_errors' => true,
        ],
    ];
    $json = @file_get_contents($url, false, stream_context_create($options));
    $data = $json === false ? null : json_decode($json, true);
    return is_array($data) ? $data : [];
}

function countryNames(): array {
    $data = geoJson('https://restcountries.com/v3.1/all?fields=name');
    $countries = [];
    foreach ($data as $country) {
        $name = $country['name']['common'] ?? '';
        if ($name !== '') $countries[] = $name;
    }
    sort($countries, SORT_NATURAL | SORT_FLAG_CASE);
    return array_values(array_unique($countries));
}
// Precio histórico: guarda en cada línea de pedido el precio con el que se compró.
// Se aplica sola una vez por sesión; es idempotente (no hace falta ningún script).
function migrar(): void {
    global $db;
    if (!empty($_SESSION['mig_ok'])) return;
    try {
        $addUsuarioColumn = [
            'pais' => 'ALTER TABLE usuario ADD COLUMN pais VARCHAR(100) NULL AFTER telefono',
            'ciudad' => 'ALTER TABLE usuario ADD COLUMN ciudad VARCHAR(100) NULL AFTER pais',
            'barrio' => 'ALTER TABLE usuario ADD COLUMN barrio VARCHAR(100) NULL AFTER ciudad',
        ];
        foreach ($addUsuarioColumn as $column => $sql) {
            if (!q("SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuario' AND COLUMN_NAME = ?", [$column])) {
                try { $db->exec($sql); } catch (PDOException $e) { if (($e->errorInfo[1] ?? 0) != 1060) throw $e; }
            }
        }
        $addPedidoColumn = [
            'pais_envio' => 'ALTER TABLE pedido ADD COLUMN pais_envio VARCHAR(100) NULL AFTER direccion_envio',
            'ciudad_envio' => 'ALTER TABLE pedido ADD COLUMN ciudad_envio VARCHAR(100) NULL AFTER pais_envio',
            'barrio_envio' => 'ALTER TABLE pedido ADD COLUMN barrio_envio VARCHAR(100) NULL AFTER ciudad_envio',
        ];
        foreach ($addPedidoColumn as $column => $sql) {
            if (!q("SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pedido' AND COLUMN_NAME = ?", [$column])) {
                try { $db->exec($sql); } catch (PDOException $e) { if (($e->errorInfo[1] ?? 0) != 1060) throw $e; }
            }
        }
        if (!q("SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'detalle_pedido' AND COLUMN_NAME = 'precio_unitario'")) {
            try { $db->exec('ALTER TABLE detalle_pedido ADD COLUMN precio_unitario DECIMAL(12,2) NULL AFTER cantidad'); }
            catch (PDOException $e) { if (($e->errorInfo[1] ?? 0) != 1060) throw $e; }   // 1060 = otra petición ya la creó
        }
        // Pedidos ya confirmados que aún no tienen precio guardado: se les fija el precio actual del producto
        $db->exec("UPDATE detalle_pedido d JOIN pedido pe ON pe.id_pedido = d.id_pedido JOIN producto p ON p.id_producto = d.id_producto
                   SET d.precio_unitario = p.precio WHERE d.precio_unitario IS NULL AND pe.estado <> 'CARRITO'");
        $_SESSION['mig_ok'] = 1;
    } catch (PDOException $e) {
        fail('No se pudo actualizar la base de datos (precio histórico de pedidos): ' . $e->getMessage(), 500);
    }
}

// Valida nombre/apellido/teléfono/dirección (perfil propio y edición por el administrador)
function datosPerfil(): array {
    global $v;
    $n = $v('nombre'); $ap = $v('apellido'); $t = $v('telefono'); $d = $v('direccion');
    $pais = $v('pais'); $ciudad = $v('ciudad'); $barrio = $v('barrio');
    if ($n === '' || $ap === '') fail('Nombre y apellido son obligatorios');
    if (mb_strlen($n) > 80 || mb_strlen($ap) > 80) fail('Nombre y apellido admiten máximo 80 caracteres');
    if (mb_strlen($t) > 20) fail('El teléfono admite máximo 20 caracteres');
    if (mb_strlen($d) > 255) fail('La dirección admite máximo 255 caracteres');
    foreach (['País' => $pais, 'Ciudad' => $ciudad, 'Barrio' => $barrio] as $etiqueta => $valor) {
        if (mb_strlen($valor) > 100) fail("$etiqueta admite máximo 100 caracteres");
    }
    return [$n, $ap, $t ?: null, $pais ?: null, $ciudad ?: null, $barrio ?: null, $d ?: null];
}

function cartId(int $uid) {
    return q("SELECT id_pedido FROM pedido WHERE id_usuario = ? AND estado = 'CARRITO' ORDER BY id_pedido DESC LIMIT 1", [$uid])[0]['id_pedido'] ?? null;
}

$in = $_POST + $_GET;
$a  = $in['action'] ?? '';
$v  = fn(string $k, $d = '') => isset($in[$k]) ? trim((string)$in[$k]) : $d;

// Ruta de imagen segura: relativa (img/logo.png) o URL http(s), sin comillas, paréntesis ni espacios
function urlSegura(string $s): bool {
    return mb_strlen($s) <= 255 && (
        preg_match('#^https?://[^\s"\'()<>\\\\]+$#i', $s) === 1 ||
        preg_match('#^(?!/)[A-Za-z0-9_\-./%]+$#', $s) === 1
    );
}

function me() { return $_SESSION['user'] ?? null; }
function needLogin() { $u = me(); if (!$u) fail('Debes iniciar sesión', 401); return $u; }
function needAdmin() { $u = needLogin(); if ($u['rol'] !== 'ADMINISTRADOR') fail('Solo para administradores', 403); return $u; }

migrar();

try {
    switch ($a) {
        /* ---------- Público ---------- */
        case 'obtener_tienda':        out(['data' => q('SELECT nombre_tienda, descripcion_tienda, logo_url, banner_url, color_primario, color_secundario,
                                                         correo_contacto, telefono_contacto
                                                  FROM personalizacion_tienda ORDER BY id_personalizacion DESC LIMIT 1')[0] ?? null]);
        case 'listar_catalogo':       out(['data' => q('CALL sp_listar_catalogo()')]);
        case 'obtener_vista_catalogo':out(['data' => q('SELECT * FROM v_catalogo_publico ORDER BY categoria, nombre')]);
        case 'obtener_destacados':    out(['data' => q('SELECT * FROM v_productos_destacados')]);
        case 'buscar_productos':      out(['data' => q('CALL sp_buscar_productos(?)', [$v('q')])]);
        case 'listar_categorias':     out(['data' => q('CALL sp_listar_categorias()')]);
        case 'obtener_paises':
            out(['data' => countryNames()]);
        case 'obtener_ciudades':
            $pais = $v('pais');
            if ($pais === '') fail('Selecciona un país');
            $query = $v('q');
            if ($query === '') out(['data' => []]);
            $search = rawurlencode($query . ', ' . $pais);
            $data = geoJson("https://nominatim.openstreetmap.org/search?format=jsonv2&limit=12&addressdetails=1&featuretype=city&q=$search");
            $cities = [];
            foreach ($data as $place) {
                $address = $place['address'] ?? [];
                $city = $address['city'] ?? $address['town'] ?? $address['municipality'] ?? $address['village'] ?? '';
                if ($city !== '' && stripos($place['display_name'] ?? '', $pais) !== false) $cities[] = $city;
            }
            sort($cities, SORT_NATURAL | SORT_FLAG_CASE);
            out(['data' => $cities]);

        /* ---------- Sesión ---------- */
        case 'sesion':  $u = me(); $u ? out(['user' => $u]) : fail('Sin sesión', 401);
        case 'logout':  $_SESSION = []; session_destroy(); out();
        case 'login':
            $r = q('CALL sp_login_usuario(?)', [$v('correo')])[0] ?? null;
            if (!$r) fail('Correo o contraseña incorrectos', 401);
            $ok = password_verify($v('password'), $r['contrasena_hash']);
            // Usuarios de ejemplo del .sql (hash falso): la primera vez entran con 123456
            // y se les guarda un hash real automáticamente. No hace falta ningún script.
            if (!$ok && strpos($r['contrasena_hash'], '$2y$10$hashEjemplo') === 0 && $v('password') === '123456') {
                q('UPDATE usuario SET contrasena_hash = ? WHERE id_usuario = ?', [password_hash('123456', PASSWORD_DEFAULT), $r['id_usuario']]);
                $ok = true;
            }
            if (!$ok) fail('Correo o contraseña incorrectos', 401);
            session_regenerate_id(true);
            unset($r['contrasena_hash']);
            $_SESSION['user'] = $r;
            out(['user' => $r]);
        case 'registrar':
            if ($v('nombre') === '' || $v('apellido') === '' || $v('correo') === '') fail('Completa nombre, apellido y correo');
            if (!filter_var($v('correo'), FILTER_VALIDATE_EMAIL)) fail('Correo no válido');
            if (mb_strlen($v('telefono')) > 20 || mb_strlen($v('direccion')) > 255) fail('Teléfono o dirección demasiado largos');
            foreach ([$v('pais'), $v('ciudad'), $v('barrio')] as $parte) if (mb_strlen($parte) > 100) fail('País, ciudad o barrio demasiado largos');
            if (strlen($v('password')) < 6) fail('La contraseña debe tener al menos 6 caracteres');
            $id = q('CALL sp_registrar_usuario(?,?,?,?,?,?)', [
                $v('nombre'), $v('apellido'), $v('correo'),
                password_hash($v('password'), PASSWORD_DEFAULT), $v('telefono') ?: null, $v('direccion') ?: null
            ])[0]['id_usuario'];
            q('UPDATE usuario SET pais = ?, ciudad = ?, barrio = ? WHERE id_usuario = ?',
              [$v('pais') ?: null, $v('ciudad') ?: null, $v('barrio') ?: null, $id]);
            session_regenerate_id(true);
            // El rol siempre es CLIENTE (lo fija el procedimiento, nunca el formulario)
            $_SESSION['user'] = ['id_usuario' => $id, 'nombre' => $v('nombre'), 'apellido' => $v('apellido'), 'correo' => $v('correo'), 'rol' => 'CLIENTE'];
            out(['user' => $_SESSION['user']]);

        /* ---------- Cliente (usa SIEMPRE el id de la sesión) ---------- */
        case 'agregar_carrito':
            $u = needLogin();
            q('CALL sp_agregar_al_carrito(?,?,?)', [$u['id_usuario'], (int)$v('id_producto'), max(1, (int)$v('cantidad', 1))]);
            out(['message' => 'Producto añadido al carrito']);
        case 'pedidos_usuario':
            $u = needLogin();
            out(['data' => q("SELECT pe.id_pedido, pe.estado, pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago,
                              pe.pais_envio, pe.ciudad_envio, pe.barrio_envio, pe.direccion_envio,
                              (SELECT COALESCE(SUM(d.cantidad * COALESCE(d.precio_unitario, pr.precio)), 0)
                                 FROM detalle_pedido d JOIN producto pr ON pr.id_producto = d.id_producto
                                WHERE d.id_pedido = pe.id_pedido) AS total
                       FROM pedido pe WHERE pe.id_usuario = ?
                       ORDER BY pe.fecha_creacion DESC, pe.id_pedido DESC", [$u['id_usuario']])]);
        case 'detalle_pedido':
            $u = needLogin();
            $id = (int)$v('id_pedido');
            if ($u['rol'] !== 'ADMINISTRADOR' && !q('SELECT 1 FROM pedido WHERE id_pedido=? AND id_usuario=?', [$id, $u['id_usuario']]))
                fail('Ese pedido no es tuyo', 403);
            out(['data' => q('SELECT d.id_detalle, d.id_pedido, pr.id_producto, pr.nombre AS producto, pr.marca, d.cantidad,
                              COALESCE(d.precio_unitario, pr.precio) AS precio_unitario,
                              d.cantidad * COALESCE(d.precio_unitario, pr.precio) AS subtotal
                       FROM detalle_pedido d JOIN producto pr ON pr.id_producto = d.id_producto
                       WHERE d.id_pedido = ? ORDER BY d.id_detalle', [$id])]);

        case 'ver_carrito':
            $u = needLogin();
            $items = q("SELECT d.id_detalle, p.id_producto, p.nombre, p.marca, p.imagen_url, c.nombre AS categoria,
                               p.precio, p.stock, d.cantidad, (d.cantidad * p.precio) AS subtotal
                        FROM pedido pe
                        JOIN detalle_pedido d ON d.id_pedido = pe.id_pedido
                        JOIN producto p ON p.id_producto = d.id_producto
                        JOIN categoria c ON c.id_categoria = p.id_categoria
                        WHERE pe.id_usuario = ? AND pe.estado = 'CARRITO' ORDER BY d.id_detalle", [$u['id_usuario']]);
            $address = q('SELECT direccion, pais, ciudad, barrio FROM usuario WHERE id_usuario = ?', [$u['id_usuario']])[0] ?? [];
            out(['data' => $items, 'direccion' => $address['direccion'] ?? '', 'pais' => $address['pais'] ?? '',
                 'ciudad' => $address['ciudad'] ?? '', 'barrio' => $address['barrio'] ?? '']);
        case 'actualizar_carrito':   // cantidad <= 0 quita el producto
            $u = needLogin(); $pid = (int)$v('id_producto'); $n = (int)$v('cantidad');
            $cid = cartId($u['id_usuario']);
            if (!$cid) fail('Tu carrito está vacío');
            if ($n <= 0) { q('DELETE FROM detalle_pedido WHERE id_pedido = ? AND id_producto = ?', [$cid, $pid]); out(); }
            $stock = q('SELECT stock FROM producto WHERE id_producto = ? AND activo = TRUE', [$pid])[0]['stock'] ?? null;
            if ($stock === null) fail('El producto ya no está disponible');
            if ($n > (int)$stock) fail("Solo hay $stock unidades disponibles");
            q('UPDATE detalle_pedido SET cantidad = ? WHERE id_pedido = ? AND id_producto = ?', [$n, $cid, $pid]);
            out();
        case 'vaciar_carrito':
            $u = needLogin();
            if ($cid = cartId($u['id_usuario'])) q('DELETE FROM detalle_pedido WHERE id_pedido = ?', [$cid]);
            out();
        case 'confirmar_pedido':
            $u = needLogin(); $cid = cartId($u['id_usuario']);
            $dir = $v('direccion_envio'); $mp = $v('metodo_pago');
            $pais = $v('pais_envio'); $ciudad = $v('ciudad_envio'); $barrio = $v('barrio_envio');
            if (!$cid) fail('Tu carrito está vacío');
            if (mb_strlen($dir) < 6) fail('Escribe una dirección de envío válida');
            if ($pais === '' || $ciudad === '' || $barrio === '') fail('Completa país, ciudad y barrio');
            foreach ([$pais, $ciudad, $barrio] as $parte) if (mb_strlen($parte) > 100) fail('País, ciudad o barrio demasiado largos');
            if (!in_array($mp, ['Tarjeta crédito', 'PSE', 'Nequi', 'Contraentrega'], true)) fail('Elige un método de pago');
            tx(function () use ($cid, $dir, $pais, $ciudad, $barrio, $mp) {
                $items = q('SELECT d.id_producto, d.cantidad, p.nombre, p.stock FROM detalle_pedido d
                            JOIN producto p ON p.id_producto = d.id_producto WHERE d.id_pedido = ? FOR UPDATE', [$cid]);
                if (!$items) throw new RuntimeException('Tu carrito está vacío');
                foreach ($items as $i)
                    if ($i['cantidad'] > $i['stock']) throw new RuntimeException("Stock insuficiente de {$i['nombre']} (quedan {$i['stock']})");
                foreach ($items as $i) q('UPDATE producto SET stock = stock - ? WHERE id_producto = ?', [$i['cantidad'], $i['id_producto']]);
                // Congela el precio de compra de cada línea
                q('UPDATE detalle_pedido d JOIN producto p ON p.id_producto = d.id_producto SET d.precio_unitario = p.precio WHERE d.id_pedido = ?', [$cid]);
                q("UPDATE pedido SET estado = 'PENDIENTE', fecha_compra = NOW(), metodo_pago = ?, direccion_envio = ?, pais_envio = ?, ciudad_envio = ?, barrio_envio = ? WHERE id_pedido = ?", [$mp, $dir, $pais, $ciudad, $barrio, $cid]);
            });
            notificarPedido((int)$cid);   // correo: pedido recibido
            out(['id_pedido' => $cid]);
        case 'cancelar_pedido':   // solo pedidos PENDIENTE del propio usuario; devuelve el stock
            $u = needLogin(); $id = (int)$v('id_pedido');
            tx(function () use ($u, $id) {
                $p = q('SELECT estado FROM pedido WHERE id_pedido = ? AND id_usuario = ? FOR UPDATE', [$id, $u['id_usuario']])[0] ?? null;
                if (!$p) throw new RuntimeException('Pedido no encontrado');
                if ($p['estado'] !== 'PENDIENTE') throw new RuntimeException('Solo puedes cancelar pedidos pendientes');
                q('UPDATE producto p JOIN detalle_pedido d ON d.id_producto = p.id_producto SET p.stock = p.stock + d.cantidad WHERE d.id_pedido = ?', [$id]);
                q("UPDATE pedido SET estado = 'CANCELADO' WHERE id_pedido = ?", [$id]);
            });
            notificarPedido($id);   // correo: pedido cancelado
            out();

        /* ---------- Administrador ---------- */
        case 'admin_productos': needAdmin(); out(['data' => q('CALL sp_admin_listar_productos()')]);
        case 'obtener_producto':needAdmin(); out(['data' => q('CALL sp_obtener_producto(?)', [(int)$v('id_producto')])[0] ?? null]);
        case 'crear_producto':
        case 'actualizar_producto':
            needAdmin();
            if ($v('nombre') === '' || (float)$v('precio') < 0 || (int)$v('stock') < 0) fail('Revisa nombre, precio y stock');
            if ($v('imagen_url') !== '' && !urlSegura($v('imagen_url'))) fail('La imagen debe ser una ruta como img/foto.jpg o una URL http(s) sin espacios');
            $f = [(int)$v('id_categoria'), $v('nombre'), $v('marca') ?: null, $v('descripcion') ?: null, $v('especificaciones') ?: null,
                  $v('precio', '0'), (int)$v('stock'), $v('imagen_url') ?: null, $v('destacado') ? 1 : 0];
            if ($a === 'crear_producto') {
                q('CALL sp_crear_producto(?,?,?,?,?,?,?,?,?)', $f);
            } else {
                q('CALL sp_actualizar_producto(?,?,?,?,?,?,?,?,?,?)', array_merge([(int)$v('id_producto')], $f));
            }
            out();
        case 'eliminar_producto': needAdmin(); q('CALL sp_eliminar_producto(?)', [(int)$v('id_producto')]); out();
        case 'actualizar_stock':  needAdmin(); q('CALL sp_actualizar_stock(?,?)', [$v('nombre_producto'), (int)$v('nuevo_stock')]); out();

        case 'crear_categoria':
            needAdmin(); if ($v('nombre') === '') fail('El nombre es obligatorio');
            q('CALL sp_crear_categoria(?,?)', [$v('nombre'), $v('descripcion') ?: null]); out();
        case 'actualizar_categoria':
            needAdmin(); if ($v('nombre') === '') fail('El nombre es obligatorio');
            q('CALL sp_actualizar_categoria(?,?,?)', [(int)$v('id_categoria'), $v('nombre'), $v('descripcion') ?: null]); out();
        case 'eliminar_categoria': needAdmin(); q('CALL sp_eliminar_categoria(?)', [(int)$v('id_categoria')]); out();

        /* ----- Pedidos (administrador) ----- */
        case 'admin_pedidos':
            needAdmin();
            out(['data' => q("SELECT pe.id_pedido, pe.estado, pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago,
                              pe.pais_envio, pe.ciudad_envio, pe.barrio_envio, pe.direccion_envio,
                                     u.nombre, u.apellido, u.correo, u.telefono,
                                     (SELECT COALESCE(SUM(d.cantidad * COALESCE(d.precio_unitario, pr.precio)), 0)
                                        FROM detalle_pedido d JOIN producto pr ON pr.id_producto = d.id_producto
                                       WHERE d.id_pedido = pe.id_pedido) AS total
                              FROM pedido pe JOIN usuario u ON u.id_usuario = pe.id_usuario
                              WHERE pe.estado <> 'CARRITO'
                              ORDER BY pe.fecha_creacion DESC, pe.id_pedido DESC")]);
        case 'cambiar_estado_pedido':   // flujo: PENDIENTE → PAGADO → ENVIADO → ENTREGADO; cancelar devuelve el stock
            needAdmin();
            $id = (int)$v('id_pedido'); $nuevo = $v('estado');
            $flujo = ['PENDIENTE' => ['PAGADO', 'CANCELADO'], 'PAGADO' => ['ENVIADO', 'CANCELADO'], 'ENVIADO' => ['ENTREGADO', 'CANCELADO']];
            tx(function () use ($id, $nuevo, $flujo) {
                $p = q('SELECT estado FROM pedido WHERE id_pedido = ? FOR UPDATE', [$id])[0] ?? null;
                if (!$p) throw new RuntimeException('Pedido no encontrado');
                if (!in_array($nuevo, $flujo[$p['estado']] ?? [], true))
                    throw new RuntimeException("No se puede pasar un pedido de {$p['estado']} a $nuevo");
                if ($nuevo === 'CANCELADO')
                    q('UPDATE producto p JOIN detalle_pedido d ON d.id_producto = p.id_producto SET p.stock = p.stock + d.cantidad WHERE d.id_pedido = ?', [$id]);
                q('UPDATE pedido SET estado = ? WHERE id_pedido = ?', [$nuevo, $id]);
            });
            notificarPedido($id);   // correo: avisa al cliente del nuevo estado
            out();

        /* ----- Personalización de la tienda ----- */
        case 'tienda_admin':
            needAdmin();
            out(['data' => q('SELECT * FROM personalizacion_tienda ORDER BY id_personalizacion DESC LIMIT 1')[0] ?? null]);
        case 'guardar_tienda':
            $u = needAdmin();
            $nom = $v('nombre_tienda'); $c1 = $v('color_primario'); $c2 = $v('color_secundario');
            $mail = $v('correo_contacto'); $tel = $v('telefono_contacto'); $logo = $v('logo_url'); $ban = $v('banner_url');
            if ($nom === '' || mb_strlen($nom) > 80) fail('El nombre de la tienda es obligatorio (máx. 80 caracteres)');
            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $c1) || !preg_match('/^#[0-9a-fA-F]{6}$/', $c2)) fail('Los colores deben tener formato #RRGGBB');
            if ($mail !== '' && (!filter_var($mail, FILTER_VALIDATE_EMAIL) || mb_strlen($mail) > 120)) fail('Correo de contacto no válido');
            if (mb_strlen($tel) > 20) fail('El teléfono de contacto es demasiado largo (máx. 20)');
            foreach ([$logo, $ban] as $img)
                if ($img !== '' && !urlSegura($img)) fail('Las imágenes deben ser una ruta como img/logo.png o una URL http(s) sin espacios');
            $vals = [$nom, $logo ?: null, $c1, $c2, $ban ?: null, $v('descripcion_tienda') ?: null, $mail ?: null, $tel ?: null, $u['id_usuario']];
            /* Cada guardado crea una personalización nueva (relación 1:N con USUARIO); la tienda aplica la más reciente */
            q('INSERT INTO personalizacion_tienda (nombre_tienda, logo_url, color_primario, color_secundario, banner_url,
                                descripcion_tienda, correo_contacto, telefono_contacto, id_usuario) VALUES (?,?,?,?,?,?,?,?,?)', $vals);
            out();

        case 'listar_usuarios':
            needAdmin();
            out(['data' => q('SELECT id_usuario, nombre, apellido, correo, telefono, pais, ciudad, barrio, direccion, rol FROM usuario WHERE activo = TRUE ORDER BY fecha_registro DESC, id_usuario DESC')]);
        case 'cambiar_rol':
            $u = needAdmin();
            if ((int)$v('id_usuario') === (int)$u['id_usuario']) fail('No puedes cambiar tu propio rol');
            q('CALL sp_cambiar_rol(?,?)', [(int)$v('id_usuario'), $v('rol')]); out();
        case 'eliminar_usuario':
            $u = needAdmin();
            if ((int)$v('id_usuario') === (int)$u['id_usuario']) fail('No puedes eliminar tu propia cuenta');
            q('CALL sp_eliminar_usuario(?)', [(int)$v('id_usuario')]); out();

        /* ---------- Usuarios: edición por el administrador ---------- */
        case 'admin_actualizar_usuario':
            $adm = needAdmin();
            $uid = (int)$v('id_usuario');
            [$n, $ap, $t, $pais, $ciudad, $barrio, $d] = datosPerfil();
            if (!filter_var($v('correo'), FILTER_VALIDATE_EMAIL) || mb_strlen($v('correo')) > 120) fail('Correo no válido');
            $pw = $v('password');
            if ($pw !== '' && strlen($pw) < 6) fail('La contraseña debe tener al menos 6 caracteres');
            if (!q('SELECT 1 FROM usuario WHERE id_usuario = ? AND activo = TRUE', [$uid])) fail('Usuario no encontrado', 404);
            q('UPDATE usuario SET nombre = ?, apellido = ?, correo = ?, telefono = ?, pais = ?, ciudad = ?, barrio = ?, direccion = ? WHERE id_usuario = ?', [$n, $ap, $v('correo'), $t, $pais, $ciudad, $barrio, $d, $uid]);
            if ($pw !== '') q('UPDATE usuario SET contrasena_hash = ? WHERE id_usuario = ?', [password_hash($pw, PASSWORD_DEFAULT), $uid]);
            if ($uid === (int)$adm['id_usuario']) { $_SESSION['user']['nombre'] = $n; $_SESSION['user']['apellido'] = $ap; $_SESSION['user']['correo'] = $v('correo'); }
            out();

        /* ---------- Mi perfil (cliente o administrador sobre su propia cuenta) ---------- */
        case 'perfil_obtener':
            $u = needLogin();
            out(['data' => q('SELECT id_usuario, nombre, apellido, correo, telefono, pais, ciudad, barrio, direccion, rol FROM usuario WHERE id_usuario = ?', [$u['id_usuario']])[0] ?? null]);
        case 'perfil_actualizar':
            $u = needLogin();
            [$n, $ap, $t, $pais, $ciudad, $barrio, $d] = datosPerfil();
            q('UPDATE usuario SET nombre = ?, apellido = ?, telefono = ?, pais = ?, ciudad = ?, barrio = ?, direccion = ? WHERE id_usuario = ?', [$n, $ap, $t, $pais, $ciudad, $barrio, $d, $u['id_usuario']]);
            $_SESSION['user']['nombre'] = $n; $_SESSION['user']['apellido'] = $ap;
            out(['user' => $_SESSION['user']]);
        case 'cambiar_password':
            $u = needLogin();
            $actual = $v('password_actual'); $nueva = $v('password_nueva');
            $hash = q('SELECT contrasena_hash FROM usuario WHERE id_usuario = ?', [$u['id_usuario']])[0]['contrasena_hash'] ?? '';
            $okPw = password_verify($actual, $hash) || (strpos($hash, '$2y$10$hashEjemplo') === 0 && $actual === '123456');
            if (!$okPw) fail('La contraseña actual no es correcta');
            if (strlen($nueva) < 6) fail('La nueva contraseña debe tener al menos 6 caracteres');
            q('UPDATE usuario SET contrasena_hash = ? WHERE id_usuario = ?', [password_hash($nueva, PASSWORD_DEFAULT), $u['id_usuario']]);
            session_regenerate_id(true);
            out(['message' => 'Contraseña actualizada']);

        /* ---------- Restaurar elementos dados de baja ---------- */
        case 'listar_eliminados':
            needAdmin();
            switch ($v('tipo')) {
                case 'producto':  out(['data' => q('SELECT p.id_producto, p.id_categoria, p.nombre, p.marca, c.nombre AS categoria, c.activa AS cat_activa, p.precio, p.stock, p.imagen_url
                                                   FROM producto p JOIN categoria c ON c.id_categoria = p.id_categoria WHERE p.activo = FALSE ORDER BY p.nombre')]);
                case 'categoria': out(['data' => q('SELECT id_categoria, nombre, descripcion FROM categoria WHERE activa = FALSE ORDER BY nombre')]);
                case 'usuario':   out(['data' => q('SELECT id_usuario, nombre, apellido, correo, telefono, rol FROM usuario WHERE activo = FALSE ORDER BY nombre')]);
            }
            fail('Tipo no válido');
        case 'restaurar':
            needAdmin();
            $rid = (int)$v('id');
            switch ($v('tipo')) {
                case 'producto':
                    $r = q('SELECT c.activa FROM producto p JOIN categoria c ON c.id_categoria = p.id_categoria WHERE p.id_producto = ? AND p.activo = FALSE', [$rid])[0] ?? null;
                    if (!$r) fail('Producto no encontrado entre los eliminados', 404);
                    if (!(int)$r['activa']) fail('Primero restaura la categoría de este producto');
                    q('UPDATE producto SET activo = TRUE WHERE id_producto = ?', [$rid]); break;
                case 'categoria':
                    if (!q('SELECT 1 FROM categoria WHERE id_categoria = ? AND activa = FALSE', [$rid])) fail('Categoría no encontrada entre las eliminadas', 404);
                    q('UPDATE categoria SET activa = TRUE WHERE id_categoria = ?', [$rid]); break;
                case 'usuario':
                    if (!q('SELECT 1 FROM usuario WHERE id_usuario = ? AND activo = FALSE', [$rid])) fail('Usuario no encontrado entre los eliminados', 404);
                    q('UPDATE usuario SET activo = TRUE WHERE id_usuario = ?', [$rid]); break;
                default: fail('Tipo no válido');
            }
            out();

        /* ---------- Subida de imágenes (administrador) ---------- */
        case 'subir_imagen':
            needAdmin();
            $f = $_FILES['archivo'] ?? null;
            if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) fail('Elige una imagen');
            if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE || $f['size'] > 2 * 1024 * 1024) fail('La imagen supera el máximo de 2 MB');
            if ($f['error'] !== UPLOAD_ERR_OK) fail('No se pudo subir el archivo');
            $info = @getimagesize($f['tmp_name']);
            $ext = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'][$info[2] ?? 0] ?? null;
            if (!$ext) fail('Formato no válido: usa JPG, PNG, GIF o WEBP');
            $dir = __DIR__ . '/img/uploads';
            if (!is_dir($dir) && !@mkdir($dir, 0755, true)) fail('No se pudo crear la carpeta img/uploads (revisa permisos)', 500);
            $nombreArchivo = bin2hex(random_bytes(8)) . '.' . $ext;   // nombre aleatorio y extensión decidida por el servidor
            if (!move_uploaded_file($f['tmp_name'], "$dir/$nombreArchivo")) fail('No se pudo guardar la imagen (revisa permisos de img/uploads)', 500);
            out(['ruta' => "img/uploads/$nombreArchivo"]);

        /* ---------- Público: detalle de un producto ---------- */
        case 'detalle_producto':
            $r = q('SELECT * FROM v_catalogo_publico WHERE id_producto = ?', [(int)$v('id_producto')])[0] ?? null;
            $r ? out(['data' => $r]) : fail('Este producto ya no está disponible', 404);

        default: fail('Acción no válida', 404);
    }
} catch (PDOException $e) {
    $i = $e->errorInfo ?? [];
    if (($i[0] ?? '') === '45000')      fail($i[2] ?? 'Operación no permitida');          // mensajes de SIGNAL
    if (($i[1] ?? 0) == 1062)           fail('Ya existe un registro con ese correo o nombre');
    fail('Error de base de datos', 500);
}