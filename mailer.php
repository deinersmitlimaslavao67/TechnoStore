<?php
// Notificaciones por correo cuando cambia el estado de un pedido.
// Usa PHPMailer (vendor/) y las funciones q() de api.php.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

require_once __DIR__ . '/vendor/autoload.php';

const MAIL_ESTADOS = [
    'PENDIENTE' => ['Pedido recibido',   '#B45309', 'Recibimos tu pedido y lo estamos revisando. Te avisaremos cuando confirmemos el pago.'],
    'PAGADO'    => ['Pago confirmado',   '#15803D', 'Confirmamos el pago de tu pedido. En breve lo prepararemos para el envío.'],
    'ENVIADO'   => ['Pedido enviado',    '#1D4ED8', 'Tu pedido va en camino a la dirección de envío indicada.'],
    'ENTREGADO' => ['Pedido entregado',  '#15803D', '¡Tu pedido fue entregado! Gracias por comprar con nosotros.'],
    'CANCELADO' => ['Pedido cancelado',  '#B91C1C', 'Tu pedido fue cancelado. Si no lo solicitaste tú, comunícate con la tienda.'],
];

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function cop($n): string { return '$' . number_format((float)$n, 0, ',', '.'); }

/**
 * Envía al correo registrado del cliente el estado actual del pedido.
 * NUNCA lanza excepciones: si el correo falla, el cambio de estado ya quedó guardado.
 */
function notificarPedido(int $idPedido): bool {
    if (!MAIL_ENABLED) return false;
    try {
        $p = q('SELECT pe.id_pedido, pe.estado, pe.metodo_pago, pe.direccion_envio, u.nombre, u.correo
                  FROM pedido pe JOIN usuario u ON u.id_usuario = pe.id_usuario
                 WHERE pe.id_pedido = ?', [$idPedido])[0] ?? null;
        if (!$p || !isset(MAIL_ESTADOS[$p['estado']]) || !filter_var($p['correo'], FILTER_VALIDATE_EMAIL)) return false;

        $items = q('SELECT pr.nombre, d.cantidad, COALESCE(d.precio_unitario, pr.precio) AS precio
                      FROM detalle_pedido d JOIN producto pr ON pr.id_producto = d.id_producto
                     WHERE d.id_pedido = ? ORDER BY d.id_detalle', [$idPedido]);
        $tienda = q('SELECT nombre_tienda FROM personalizacion_tienda ORDER BY id_personalizacion DESC LIMIT 1')[0]['nombre_tienda'] ?? MAIL_FROM_NAME;

        [$titulo, $color, $mensaje] = MAIL_ESTADOS[$p['estado']];
        $total = 0; $filas = '';
        foreach ($items as $i) {
            $sub = $i['cantidad'] * $i['precio']; $total += $sub;
            $filas .= '<tr><td style="padding:6px 0">' . h($i['nombre']) . ' × ' . (int)$i['cantidad'] . '</td>'
                    . '<td style="padding:6px 0;text-align:right">' . cop($sub) . '</td></tr>';
        }

        $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden">'
              . '<div style="background:' . $color . ';color:#fff;padding:18px 24px"><h2 style="margin:0">' . h($titulo) . '</h2>'
              . '<div style="opacity:.9">Pedido #' . (int)$p['id_pedido'] . '</div></div>'
              . '<div style="padding:24px;color:#111827">'
              . '<p>Hola ' . h($p['nombre']) . ',</p><p>' . h($mensaje) . '</p>'
              . '<table style="width:100%;border-collapse:collapse;border-top:1px solid #e5e7eb;margin-top:12px">' . $filas
              . '<tr><td style="padding:10px 0;border-top:1px solid #e5e7eb"><b>Total</b></td>'
              . '<td style="padding:10px 0;border-top:1px solid #e5e7eb;text-align:right"><b>' . cop($total) . '</b></td></tr></table>'
              . '<p style="font-size:14px;color:#4b5563">Dirección de envío: ' . h((string)$p['direccion_envio']) . '<br>'
              . 'Método de pago: ' . h((string)$p['metodo_pago']) . '</p></div>'
              . '<div style="background:#f9fafb;padding:12px 24px;font-size:12px;color:#6b7280">' . h($tienda) . '</div></div>';

        $m = new PHPMailer(true);
        $m->isSMTP();
        $m->Host       = MAIL_HOST;
        $m->SMTPAuth   = true;
        $m->Username   = MAIL_USER;
        $m->Password   = MAIL_PASS;
        $m->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $m->Port       = MAIL_PORT;
        $m->Timeout    = 10;                       // no dejar colgada la tienda si el SMTP no responde
        $m->CharSet    = 'UTF-8';
        $m->setFrom(MAIL_FROM, $tienda);
        $m->addAddress($p['correo'], $p['nombre'] . '');
        $m->isHTML(true);
        $m->Subject = "$titulo - Pedido #{$p['id_pedido']}";
        $m->Body    = $html;
        $m->AltBody = "Hola {$p['nombre']}, $titulo (Pedido #{$p['id_pedido']}). $mensaje Total: " . cop($total);
        $m->send();
        return true;
    } catch (Throwable $e) {
        error_log('[notificarPedido] pedido ' . $idPedido . ': ' . $e->getMessage());
        return false;
    }
}
