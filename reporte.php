<?php
// =====================================================================
// reporte.php - Descarga de reportes en Excel (.xlsx). Solo ADMINISTRADOR.
//
// Archivo NUEVO: no modifica api.php, config.php ni la base de datos.
// Usa la misma sesión y la misma conexión que api.php.
//
//   reporte.php?tipo=pedidos_entregados
//   reporte.php?tipo=pedidos_pendientes
//   reporte.php?tipo=pedidos_enviados
//   reporte.php?tipo=usuarios_con_pedidos
//   reporte.php?tipo=usuarios_sin_pedidos
//   reporte.php?tipo=productos_mas_menos_comprados
//
// Si todo sale bien responde con el archivo Excel; si algo falla responde
// un JSON { "success": false, "error": "mensaje" } (el frontend lo muestra).
// =====================================================================

ini_set('display_errors', '0');          // un aviso de PHP no debe corromper la descarga
date_default_timezone_set('America/Bogota');
session_start();

require_once __DIR__ . '/config.php';    // DB_HOST, DB_NAME, DB_USER, DB_PASS
require_once __DIR__ . '/xlsx.php';      // clase XlsxWriter

function fallo(string $mensaje, int $codigo = 400): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => $mensaje], JSON_UNESCAPED_UNICODE);
    exit;
}

// ---------------------------------------------------------------------
// Solo el administrador (misma sesión que usa api.php: $_SESSION['user'])
// ---------------------------------------------------------------------
$usuario = $_SESSION['user'] ?? null;
if (!$usuario) fallo('Debes iniciar sesión para descargar reportes', 401);
if (($usuario['rol'] ?? '') !== 'ADMINISTRADOR') fallo('Solo para administradores', 403);

// Conexión (igual que api.php: DB_HOST puede traer puerto, ej. 127.0.0.1:3307)
[$host, $port] = array_pad(explode(':', DB_HOST, 2), 2, '3306');
try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=" . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    error_log('[reporte.php] ' . $e->getMessage());
    fallo('No se pudo conectar a MySQL', 500);
}

function consultar(PDO $db, string $sql, array $params = []): array {
    $st = $db->prepare($sql);
    $st->execute($params);
    $filas = $st->fetchAll();
    $st->closeCursor();
    return $filas;
}

// Agrega la columna "Posición" (1, 2, 3...) a los rankings de productos
function conPosicion(array $filas): array {
    foreach ($filas as $i => $f) $filas[$i] = ['posicion' => $i + 1] + $f;
    return $filas;
}

// Precio con el que se vendió cada línea (si no está guardado, el precio actual del producto)
const PRECIO = 'COALESCE(d.precio_unitario, pr.precio)';
// Pedidos que cuentan como "comprados": ya confirmados y no cancelados
const ESTADOS_COMPRA = "'PENDIENTE','PAGADO','ENVIADO','ENTREGADO'";

$tipo = (string)($_GET['tipo'] ?? '');

// Columnas: [ 'Título en Excel', 'campo de la consulta', 'texto | entero | dinero' ]
$colPedidos = [
    ['# Pedido', 'id_pedido', 'entero'], ['Cliente', 'cliente', 'texto'], ['Correo', 'correo', 'texto'],
    ['Teléfono', 'telefono', 'texto'], ['Fecha de creación', 'fecha_creacion', 'texto'],
    ['Fecha de compra', 'fecha_compra', 'texto'], ['Método de pago', 'metodo_pago', 'texto'],
    ['Dirección de envío', 'direccion_envio', 'texto'], ['Unidades', 'unidades', 'entero'], ['Total', 'total', 'dinero'],
];
$colDetalle = [
    ['# Pedido', 'id_pedido', 'entero'], ['Producto', 'producto', 'texto'], ['Marca', 'marca', 'texto'],
    ['Categoría', 'categoria', 'texto'], ['Cantidad', 'cantidad', 'entero'],
    ['Precio unitario', 'precio_unitario', 'dinero'], ['Subtotal', 'subtotal', 'dinero'],
];
$colProductos = [
    ['Posición', 'posicion', 'entero'], ['ID', 'id_producto', 'entero'], ['Producto', 'nombre', 'texto'],
    ['Marca', 'marca', 'texto'], ['Categoría', 'categoria', 'texto'], ['Unidades vendidas', 'unidades_vendidas', 'entero'],
    ['Pedidos', 'pedidos', 'entero'], ['Ingresos', 'ingresos', 'dinero'], ['Stock actual', 'stock', 'entero'],
    ['Estado', 'estado_producto', 'texto'],
];

// Reportes de pedidos: tipo => [estado en la BD, nombre del archivo, nombre de la hoja]
$porEstado = [
    'pedidos_entregados' => ['ENTREGADO', 'Pedidos_Entregados', 'Pedidos entregados'],
    'pedidos_pendientes' => ['PENDIENTE', 'Pedidos_Pendientes', 'Pedidos pendientes'],
    'pedidos_enviados'   => ['ENVIADO',   'Pedidos_Enviados',   'Pedidos enviados'],
];

try {
    $excel = new XlsxWriter();

    if (isset($porEstado[$tipo])) {
        [$estado, $archivo, $hoja] = $porEstado[$tipo];

        $pedidos = consultar($db, '
            SELECT pe.id_pedido, CONCAT(u.nombre, " ", u.apellido) AS cliente, u.correo, u.telefono,
                   pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago, pe.direccion_envio,
                   COALESCE(SUM(d.cantidad), 0) AS unidades,
                   COALESCE(SUM(d.cantidad * ' . PRECIO . '), 0) AS total
              FROM pedido pe
              JOIN usuario u ON u.id_usuario = pe.id_usuario
              LEFT JOIN detalle_pedido d ON d.id_pedido = pe.id_pedido
              LEFT JOIN producto pr ON pr.id_producto = d.id_producto
             WHERE pe.estado = ?
             GROUP BY pe.id_pedido, u.nombre, u.apellido, u.correo, u.telefono,
                      pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago, pe.direccion_envio
             ORDER BY COALESCE(pe.fecha_compra, pe.fecha_creacion) DESC, pe.id_pedido DESC', [$estado]);

        $detalle = consultar($db, '
            SELECT pe.id_pedido, pr.nombre AS producto, pr.marca, c.nombre AS categoria, d.cantidad,
                   ' . PRECIO . ' AS precio_unitario, d.cantidad * ' . PRECIO . ' AS subtotal
              FROM pedido pe
              JOIN detalle_pedido d ON d.id_pedido = pe.id_pedido
              JOIN producto pr ON pr.id_producto = d.id_producto
              JOIN categoria c ON c.id_categoria = pr.id_categoria
             WHERE pe.estado = ?
             ORDER BY pe.id_pedido DESC, pr.nombre', [$estado]);

        $excel->agregarHoja($hoja, $colPedidos, $pedidos, ['unidades', 'total']);
        $excel->agregarHoja('Detalle de productos', $colDetalle, $detalle, ['cantidad', 'subtotal']);
        $nombre = 'Reporte_' . $archivo;

    } elseif ($tipo === 'usuarios_con_pedidos') {
        // Pedido "hecho" = cualquier pedido que no sea carrito (los cancelados se cuentan aparte)
        $filas = consultar($db, '
            SELECT u.id_usuario, u.nombre, u.apellido, u.correo, u.telefono, u.rol,
                   IF(u.activo, "Activo", "Eliminado") AS estado_cuenta,
                   COUNT(*) AS pedidos_realizados,
                   SUM(t.estado = "CANCELADO") AS cancelados,
                   COALESCE(SUM(CASE WHEN t.estado <> "CANCELADO" THEN t.total ELSE 0 END), 0) AS total_comprado,
                   MAX(t.fecha_creacion) AS ultimo_pedido
              FROM usuario u
              JOIN (SELECT pe.id_pedido, pe.id_usuario, pe.estado, pe.fecha_creacion,
                           COALESCE(SUM(d.cantidad * ' . PRECIO . '), 0) AS total
                      FROM pedido pe
                      LEFT JOIN detalle_pedido d ON d.id_pedido = pe.id_pedido
                      LEFT JOIN producto pr ON pr.id_producto = d.id_producto
                     WHERE pe.estado <> "CARRITO"
                     GROUP BY pe.id_pedido, pe.id_usuario, pe.estado, pe.fecha_creacion) t ON t.id_usuario = u.id_usuario
             GROUP BY u.id_usuario, u.nombre, u.apellido, u.correo, u.telefono, u.rol, u.activo
             ORDER BY pedidos_realizados DESC, total_comprado DESC, u.nombre');
        $excel->agregarHoja('Usuarios con pedidos', [
            ['ID', 'id_usuario', 'entero'], ['Nombre', 'nombre', 'texto'], ['Apellido', 'apellido', 'texto'],
            ['Correo', 'correo', 'texto'], ['Teléfono', 'telefono', 'texto'], ['Rol', 'rol', 'texto'],
            ['Cuenta', 'estado_cuenta', 'texto'], ['Pedidos realizados', 'pedidos_realizados', 'entero'],
            ['Pedidos cancelados', 'cancelados', 'entero'], ['Total comprado', 'total_comprado', 'dinero'],
            ['Último pedido', 'ultimo_pedido', 'texto'],
        ], $filas, ['pedidos_realizados', 'cancelados', 'total_comprado']);
        $nombre = 'Reporte_Usuarios_Con_Pedidos';

    } elseif ($tipo === 'usuarios_sin_pedidos') {
        // Clientes activos que nunca han confirmado un pedido (tener solo el carrito lleno no cuenta)
        $filas = consultar($db, '
            SELECT u.id_usuario, u.nombre, u.apellido, u.correo, u.telefono, u.direccion, u.fecha_registro,
                   IF(EXISTS (SELECT 1 FROM pedido c WHERE c.id_usuario = u.id_usuario AND c.estado = "CARRITO"), "Sí", "No") AS carrito_abierto
              FROM usuario u
             WHERE u.rol = "CLIENTE" AND u.activo = TRUE
               AND NOT EXISTS (SELECT 1 FROM pedido pe WHERE pe.id_usuario = u.id_usuario AND pe.estado <> "CARRITO")
             ORDER BY u.fecha_registro DESC, u.id_usuario DESC');
        $excel->agregarHoja('Usuarios sin pedidos', [
            ['ID', 'id_usuario', 'entero'], ['Nombre', 'nombre', 'texto'], ['Apellido', 'apellido', 'texto'],
            ['Correo', 'correo', 'texto'], ['Teléfono', 'telefono', 'texto'], ['Dirección', 'direccion', 'texto'],
            ['Fecha de registro', 'fecha_registro', 'texto'], ['Carrito abierto', 'carrito_abierto', 'texto'],
        ], $filas);
        $nombre = 'Reporte_Usuarios_Sin_Pedidos';

    } elseif ($tipo === 'productos_mas_menos_comprados') {
        $ventas = '(SELECT d.id_producto, SUM(d.cantidad) AS unidades, COUNT(DISTINCT d.id_pedido) AS pedidos,
                           SUM(d.cantidad * ' . PRECIO . ') AS ingresos
                      FROM detalle_pedido d
                      JOIN pedido pe ON pe.id_pedido = d.id_pedido
                      JOIN producto pr ON pr.id_producto = d.id_producto
                     WHERE pe.estado IN (' . ESTADOS_COMPRA . ')
                     GROUP BY d.id_producto)';

        $mas = consultar($db, '
            SELECT pr.id_producto, pr.nombre, pr.marca, c.nombre AS categoria, v.unidades AS unidades_vendidas,
                   v.pedidos, v.ingresos, pr.stock, IF(pr.activo, "Activo", "Eliminado") AS estado_producto
              FROM producto pr
              JOIN categoria c ON c.id_categoria = pr.id_categoria
              JOIN ' . $ventas . ' v ON v.id_producto = pr.id_producto
             ORDER BY v.unidades DESC, v.ingresos DESC, pr.nombre
             LIMIT 10');

        $menos = consultar($db, '
            SELECT pr.id_producto, pr.nombre, pr.marca, c.nombre AS categoria, COALESCE(v.unidades, 0) AS unidades_vendidas,
                   COALESCE(v.pedidos, 0) AS pedidos, COALESCE(v.ingresos, 0) AS ingresos, pr.stock, "Activo" AS estado_producto
              FROM producto pr
              JOIN categoria c ON c.id_categoria = pr.id_categoria
              LEFT JOIN ' . $ventas . ' v ON v.id_producto = pr.id_producto
             WHERE pr.activo = TRUE
             ORDER BY unidades_vendidas ASC, pr.nombre
             LIMIT 10');

        $sumar = ['unidades_vendidas', 'pedidos', 'ingresos'];
        $excel->agregarHoja('Más comprados', $colProductos, conPosicion($mas), $sumar);
        $excel->agregarHoja('Menos comprados', $colProductos, conPosicion($menos), $sumar);
        $nombre = 'Reporte_Productos_Mas_y_Menos_Comprados';

    } else {
        fallo('Tipo de reporte no válido');
    }

    // El archivo se arma completo ANTES de enviar cabeceras: si algo falla, no queda una descarga rota
    $excel->enviar($nombre . '_' . date('Y-m-d') . '.xlsx');

} catch (PDOException $e) {
    error_log('[reporte.php] ' . $e->getMessage());
    // 1054 = columna desconocida: detalle_pedido.precio_unitario la crea api.php al iniciar sesión
    fallo((($e->errorInfo[1] ?? 0) == 1054)
        ? 'La base de datos aún no está actualizada. Cierra sesión, vuelve a entrar y reintenta.'
        : 'Error de base de datos al generar el reporte', 500);
} catch (Throwable $e) {
    error_log('[reporte.php] ' . $e->getMessage());
    fallo($e instanceof RuntimeException ? $e->getMessage() : 'Error interno al generar el reporte', 500);
}
