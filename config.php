<?php
// Ajusta estos datos a tu servidor MySQL (XAMPP/WAMP: usuario root, sin clave)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'catalogo_ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');
function conectar(): PDO {
    return new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
}

// ---------- Correo (notificaciones de pedidos) ----------
// Gmail: activa la verificación en 2 pasos y crea una "Contraseña de aplicación"
// en https://myaccount.google.com/apppasswords (16 letras, sin espacios).
define('MAIL_ENABLED', true);                       // false = no envía correos (útil en desarrollo)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);                        // 587 = STARTTLS
define('MAIL_USER', 'limasdeiner@gmail.com');       // cuenta que ENVÍA los correos
define('MAIL_PASS', 'goegissdhltjmgkl');         // contraseña de aplicación (NO tu clave normal)
define('MAIL_FROM', 'limasdeiner@gmail.com');
define('MAIL_FROM_NAME', 'tecnoshop');
