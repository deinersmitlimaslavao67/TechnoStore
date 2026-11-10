
<p align="center">
  <img src="banner/banner.png" alt="TechnoStore Banner" width="100%">
</p>

<p align="center">
  <img src="logo/logo.png" alt="TechnoStore Logo" width="120">
</p>

# TechnoStore 🚀

<p align="center">
  <b>Plataforma de comercio electrónico y gestión de catálogo digital desarrollada en PHP.</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%20%2F%208.x-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Dependencies-Composer-885630?style=flat-square&logo=composer&logoColor=white" alt="Composer">
  <img src="https://img.shields.io/badge/Status-Activo-success?style=flat-square" alt="Status">
</p>

---

## 📋 Descripción del Proyecto

**TechnoStore** es una solución web integral diseñada para la gestión de productos tecnológicos, inventarios, procesamiento de correos electrónicos mediante PHPMailer, generación de reportes avanzados y exportación de datos a hojas de cálculo. Cuenta con una arquitectura modular limpia y soporte para bases de datos relacionales.

---

## 📂 Estructura del Repositorio

La arquitectura del proyecto se organiza de la siguiente manera:

* **`index.php`**: Punto de entrada principal de la aplicación y vista de usuario/frontend.
* **`api.php`**: Endpoints backend para el manejo de peticiones asíncronas y lógica de datos.
* **`config.php`**: Archivo central de configuración de la conexión a la base de datos y parámetros del sistema.
* **`mailer.php`**: Componente de gestión y envío de correos electrónicos integrado con PHPMailer.
* **`reporte.php`**: Módulo para la generación y visualización de reportes del sistema.
* **`xlsx.php`**: Utilidad encargada del procesamiento y exportación de archivos en formato Excel.
* **`bdcatalogo.sql`**: Script SQL con el esquema de la base de datos y datos iniciales del catálogo.
* **`Anexos/`**: Documentación técnica del proyecto, incluyendo diagramas MER y las Especificaciones de Requisitos de Software (SRS en `Anexos/SRS/SRS_TechnoStore.pdf`).
* **`vendor/`**: Dependencias externas gestionadas mediante Composer (incluyendo PHPMailer).

---

## ⚙️ Requisitos del Sistema

Antes de iniciar, asegúrate de contar con lo siguiente:

* Servidor web local (como Apache o Nginx) con **PHP >= 7.4**.
* **MySQL** o MariaDB.
* **Composer** instalado para la gestión de dependencias.

---

## 🔧 Instalación y Configuración

1. **Clonar el repositorio e instalar dependencias:**
   ```bash
   git clone https://github.com/deinersmitlimaslavao67/TechnoStore.git
   cd TechnoStore
   composer install
   ```

2. **Crear la base de datos:** inicia Apache y MySQL en XAMPP, abre `http://localhost/phpmyadmin` e importa `bdcatalogo.sql`. El script crea la base `catalogo_ecommerce` y las tablas, vistas, procedimientos y datos de ejemplo.

3. **Configurar la conexión:** por defecto se usan `127.0.0.1:3306`, la base `catalogo_ecommerce`, el usuario `root` y contraseña vacía (valores estándar de XAMPP). Si tu MySQL usa otros valores, configura en el entorno de Apache `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASS`. La contraseña no debe guardarse en Git.

4. **Abrir la tienda:** visita `http://localhost/TechnoStore/`. Si la conexión falla, revisa el log de errores de PHP/Apache y confirma que MySQL está iniciado, que importaste el esquema y que las credenciales corresponden.

El envío de correo está desactivado por defecto y no es necesario para usar la tienda. Para activarlo, configura `MAIL_ENABLED=true`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USER`, `MAIL_PASS` y `MAIL_FROM` en el entorno de Apache; luego instala las dependencias con Composer.
