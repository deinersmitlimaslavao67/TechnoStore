<?php
/**
 * Configuración global y función de conexión a la base de datos
 */

// --- Base de Datos ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'catalogo_ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');

function conectar(): ?PDO {
    static $conexion = null;

    if ($conexion instanceof PDO) {
        return $conexion;
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        
        $conexion = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Mayor seguridad en consultas preparadas
        ]);
        error_log("Conexión a la base de datos establecida correctamente.");
        return $conexion;
    } catch (PDOException $e) {
        // En producción, es recomendable registrar el error en un archivo de log 
        // en lugar de mostrarlo directamente en pantalla por seguridad:
        // error_log("Error de conexión: " . $e->getMessage());
        
        error_log("Error en la conexión a la base de datos.");
        return null;
    }
}

// Intentamos conectar
$db = conectar();

if ($db) {
    echo "¡Conexión establecida correctamente y lista para usarse!";
} else {
    echo "No se pudo conectar a la base de datos. Revisa los logs del servidor.";
}

// --- Configuración de Correo (Notificaciones) ---
define('MAIL_ENABLED', true);                       // false = deshabilita el envío (útil en desarrollo)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);                           // 587 = STARTTLS
define('MAIL_USER', 'limasdeiner@gmail.com');       // Cuenta que envía los correos
define('MAIL_PASS', 'goegissdhltjmgkl');            // Contraseña de aplicación de Google
define('MAIL_FROM', 'limasdeiner@gmail.com');
define('MAIL_FROM_NAME', 'tecnoshop');