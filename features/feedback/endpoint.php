<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function feedbackResponse(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$user = $_SESSION['user'] ?? null;
if (!is_array($user) || empty($user['id_usuario'])) {
    feedbackResponse(['success' => false, 'error' => 'Debes iniciar sesión para usar los reportes.'], 401);
}

require_once __DIR__ . '/../../backend/config/config.php';
if (!($db instanceof PDO)) {
    feedbackResponse(['success' => false, 'error' => 'No se pudo conectar con la base de datos.'], 500);
}

function feedbackEnsureSchema(PDO $db): void
{
    static $ready = false;
    if ($ready) return;

    $exists = $db->prepare(
        'SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $exists->execute(['feedback_reportes']);
    if (!$exists->fetchColumn()) {
        $schemaPath = __DIR__ . '/schema.sql';
        if (!is_file($schemaPath)) throw new RuntimeException('No se encontró el esquema de reportes.');
        $schema = file_get_contents($schemaPath);
        if ($schema === false || $db->exec($schema) === false) {
            throw new RuntimeException('No se pudo preparar el registro de reportes.');
        }
    }
    $ready = true;
}

try {
    feedbackEnsureSchema($db);
    $action = (string)($_GET['action'] ?? '');
    $isAdmin = ($user['rol'] ?? '') === 'ADMINISTRADOR';

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
        if (!$isAdmin) feedbackResponse(['success' => false, 'error' => 'Solo para administradores.'], 403);
        $rows = $db->query(
            "SELECT r.id_reporte, r.tipo, r.origen, r.detalle, r.stack_trace, r.pagina, r.estado,
                    r.fecha_creacion, u.nombre, u.apellido, u.correo
               FROM feedback_reportes r
               JOIN usuario u ON u.id_usuario = r.id_usuario
              ORDER BY r.fecha_creacion DESC, r.id_reporte DESC
              LIMIT 200"
        )->fetchAll();
        feedbackResponse(['success' => true, 'data' => $rows]);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        feedbackResponse(['success' => false, 'error' => 'Método no permitido.'], 405);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) feedbackResponse(['success' => false, 'error' => 'Solicitud no válida.'], 400);

    if ($action === 'submit') {
        $type = (string)($input['tipo'] ?? '');
        $origin = (string)($input['origen'] ?? 'USUARIO');
        $detail = trim((string)($input['detalle'] ?? ''));
        $stack = trim((string)($input['stack_trace'] ?? ''));
        $page = trim((string)($input['pagina'] ?? ''));

        if (!in_array($type, ['ERROR', 'SUGERENCIA'], true)) {
            feedbackResponse(['success' => false, 'error' => 'Selecciona un tipo de reporte válido.'], 422);
        }
        if (!in_array($origin, ['USUARIO', 'JAVASCRIPT'], true)) $origin = 'USUARIO';
        if ($detail === '' || mb_strlen($detail, 'UTF-8') > 2000) {
            feedbackResponse(['success' => false, 'error' => 'El reporte debe tener entre 1 y 2000 caracteres.'], 422);
        }

        $recent = $db->prepare(
            'SELECT COUNT(*) FROM feedback_reportes WHERE id_usuario = ? AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)'
        );
        $recent->execute([(int)$user['id_usuario']]);
        if ((int)$recent->fetchColumn() >= 20) {
            feedbackResponse(['success' => false, 'error' => 'Alcanzaste el límite temporal de reportes. Inténtalo más tarde.'], 429);
        }

        $insert = $db->prepare(
            'INSERT INTO feedback_reportes (id_usuario, tipo, origen, detalle, stack_trace, pagina)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $insert->execute([
            (int)$user['id_usuario'],
            $type,
            $origin,
            $detail,
            $origin === 'JAVASCRIPT' && $stack !== '' ? mb_substr($stack, 0, 6000, 'UTF-8') : null,
            mb_substr($page, 0, 500, 'UTF-8'),
        ]);
        feedbackResponse(['success' => true, 'id_reporte' => (int)$db->lastInsertId()], 201);
    }

    if ($action === 'status') {
        if (!$isAdmin) feedbackResponse(['success' => false, 'error' => 'Solo para administradores.'], 403);
        $id = filter_var($input['id_reporte'] ?? null, FILTER_VALIDATE_INT);
        $status = (string)($input['estado'] ?? '');
        if (!$id || !in_array($status, ['NUEVO', 'EN_REVISION', 'RESUELTO'], true)) {
            feedbackResponse(['success' => false, 'error' => 'Identificador o estado no válido.'], 422);
        }
        $update = $db->prepare('UPDATE feedback_reportes SET estado = ? WHERE id_reporte = ?');
        $update->execute([$status, $id]);
        if ($update->rowCount() === 0) {
            $exists = $db->prepare('SELECT 1 FROM feedback_reportes WHERE id_reporte = ?');
            $exists->execute([$id]);
            if (!$exists->fetchColumn()) feedbackResponse(['success' => false, 'error' => 'Reporte no encontrado.'], 404);
        }
        feedbackResponse(['success' => true]);
    }

    feedbackResponse(['success' => false, 'error' => 'Acción no válida.'], 404);
} catch (Throwable $e) {
    error_log('[TechnoStore feedback] ' . $e->getMessage());
    feedbackResponse(['success' => false, 'error' => 'No se pudo procesar el reporte.'], 500);
}
