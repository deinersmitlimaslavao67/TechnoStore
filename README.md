
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

* **`index.php`**: Punto de entrada principal y vista de usuario; carga los scripts y estilos organizados en sus carpetas.
* **`assets/css/styles.css`**: Estilos de la tienda, separados de la vista.
* **`backend/endpoints/`**: Endpoints de la API y generación de reportes.
* **`backend/config/`**: Configuración de la conexión a la base de datos.
* **`backend/services/`**: Servicio de notificaciones por correo.
* **`backend/libraries/`**: Generador de archivos Excel.
* **`features/feedback/`**: Reportes autenticados de errores y sugerencias, captura de errores JavaScript, endpoint, estilos y esquema de almacenamiento. Los administradores pueden revisar y actualizar el estado de los reportes. La tabla se crea automáticamente al usar la función por primera vez; se aceptan hasta 20 reportes por usuario cada 10 minutos.
* **`features/project-resources/`**: Visor web del video, documentos SRS, diagrama MER, PAC, herramientas de calidad y manuales. Las categorías se mantienen visibles aunque estén vacías; los archivos compatibles se detectan automáticamente en sus carpetas.
* **`database/bdcatalogo.sql`**: Esquema de base de datos y datos iniciales.
* **`banner/`** y **`logo/`**: Recursos gráficos de la tienda.
* **`Anexos/`**: Documentación del proyecto. Se conserva el SRS original en `Anexos/SRS/SRS_TechnoStore.pdf` y la edición 2.0 se publica en `Anexos/SRS/SRS_TechnoStore_v2.0.pdf`. El modelo de datos implementado está en `Anexos/MER/MER OFICIAL.drawio`.
* **`PAC/`**: Carpeta para los documentos del plan de aseguramiento de calidad.
* **`Herramientas de calidad/`**: Carpeta para los documentos de las herramientas de calidad aplicadas.
* **`Anexos/Manuales/Usuario/`** y **`Anexos/Manuales/Tecnico/`**: Carpetas para agregar el manual de usuario y el manual técnico.
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

2. **Crear la base de datos:** inicia Apache y MySQL en XAMPP, abre `http://localhost/phpmyadmin` e importa `database/bdcatalogo.sql`. El script crea la base `catalogo_ecommerce` y las tablas, vistas, procedimientos y datos de ejemplo.

3. **Configurar la conexión:** por defecto se usan `127.0.0.1:3306`, la base `catalogo_ecommerce`, el usuario `root` y contraseña vacía (valores estándar de XAMPP). Si tu MySQL usa otros valores, configura en el entorno de Apache `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASS`. La contraseña no debe guardarse en Git.

4. **Abrir la tienda:** visita `http://localhost/TechnoStore/`. Si la conexión falla, revisa el log de errores de PHP/Apache y confirma que MySQL está iniciado, que importaste el esquema y que las credenciales corresponden.

El envío de correo está desactivado por defecto y no es necesario para usar la tienda. Para activarlo, configura `MAIL_ENABLED=true`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION` (`tls` para el puerto 587 o `ssl` para el 465), `MAIL_USER`, `MAIL_PASS` y `MAIL_FROM` en el entorno de Apache; luego instala las dependencias con Composer. Usa como `MAIL_FROM` una dirección autorizada por el proveedor SMTP. En Google Groups, además, el grupo destinatario debe permitir mensajes del remitente autenticado; el código no puede cambiar las reglas de recepción del grupo. `APP_URL` debe apuntar a la URL pública base de la tienda (por ejemplo, `https://tienda.example`) para que los enlaces de recuperación lleguen al dominio correcto. Si el envío falla, el cambio de estado del pedido se conserva y la interfaz muestra una advertencia.

La tienda incluye referencias a las normas colombianas de comercio electrónico, protección al consumidor y datos personales. Antes de operar públicamente, el responsable debe completar y publicar su identidad legal, NIT, domicilio y política integral de tratamiento de datos, además de verificar los textos con asesoría jurídica. Las normas ISO/IEC e IEEE son estándares técnicos, no leyes ni una certificación automática.

## 📎 Recursos y anexos

El botón **Ver anexos y video** del pie de página consulta el catálogo del servidor cada vez que se abre. Para agregar documentos, copia un archivo directamente en la carpeta de su categoría:

* SRS: `Anexos/SRS/`.
* Modelo entidad-relación: `Anexos/MER/`.
* PAC: `PAC/`.
* Herramientas de calidad: `Herramientas de calidad/`.
* Manual de usuario: `Anexos/Manuales/Usuario/`.
* Manual técnico: `Anexos/Manuales/Tecnico/`.

Se listan archivos PDF, Draw.io, documentos de Office/OpenDocument, hojas de cálculo, presentaciones, CSV e imágenes JPG, PNG, GIF y WEBP. Los PDF e imágenes tienen vista previa; los diagramas Draw.io se muestran de forma interactiva y los otros tipos se ofrecen como enlace. No se publica el script de base de datos en el visor.
