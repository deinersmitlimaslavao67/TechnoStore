<?php
/**
 * Configuración compartida de base de datos y correo.
 * Sobrescribe los valores mediante variables de entorno en cada instalación.
 */

function configEnv(string $name, string $default = ''): string
{
    $value = getenv($name);
    return $value === false ? $default : $value;
}

define('DB_HOST', configEnv('DB_HOST', '127.0.0.1'));
define('DB_PORT', (int)configEnv('DB_PORT', '3306'));
define('DB_NAME', configEnv('DB_NAME', 'catalogo_ecommerce'));
define('DB_USER', configEnv('DB_USER', 'root'));
define('DB_PASS', configEnv('DB_PASS'));

function conectar(): ?PDO
{
    try {
        $host = DB_HOST;
        $port = DB_PORT;
        if (preg_match('/^(.+):([0-9]+)$/', $host, $matches)) {
            $host = $matches[1];
            $port = (int)$matches[2];
        }

        return new PDO(
            'mysql:host=' . $host . ';port=' . $port . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('[TechnoStore] Error de conexión a MySQL: ' . $e->getMessage());
        return null;
    }
}

$db = conectar();

define('MAIL_ENABLED', filter_var(configEnv('MAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN));
define('MAIL_HOST', configEnv('MAIL_HOST', 'smtp.gmail.com'));
define('MAIL_PORT', (int)configEnv('MAIL_PORT', '587'));
define('MAIL_USER', configEnv('MAIL_USER'));
define('MAIL_PASS', configEnv('MAIL_PASS'));
define('MAIL_FROM', configEnv('MAIL_FROM', MAIL_USER));
define('MAIL_FROM_NAME', configEnv('MAIL_FROM_NAME', 'TechnoStore'));
