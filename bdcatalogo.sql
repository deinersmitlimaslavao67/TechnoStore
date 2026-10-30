CREATE DATABASE IF NOT EXISTS catalogo_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE catalogo_ecommerce;



-- TABLAS

-- ---------- USUARIO ----------
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
  nombre          VARCHAR(80)  NOT NULL,
  apellido        VARCHAR(80)  NOT NULL,
  correo          VARCHAR(120) NOT NULL UNIQUE,
  contrasena_hash VARCHAR(255) NOT NULL,
  telefono        VARCHAR(20)  NULL,
  pais            VARCHAR(100) NULL,
  ciudad          VARCHAR(100) NULL,
  barrio          VARCHAR(100) NULL,
  direccion       VARCHAR(255) NULL,
  rol             ENUM('ADMINISTRADOR','CLIENTE') NOT NULL DEFAULT 'CLIENTE',
  fecha_registro  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  activo          BOOLEAN      NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- ---------- CATEGORIA ----------
CREATE TABLE IF NOT EXISTS categoria (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(80)  NOT NULL UNIQUE,
  descripcion  VARCHAR(255) NULL,
  activa       BOOLEAN      NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- ---------- PRODUCTO ----------
CREATE TABLE IF NOT EXISTS producto (
  id_producto         INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria        INT           NOT NULL,
  nombre              VARCHAR(150)  NOT NULL,
  marca               VARCHAR(80)   NULL,
  descripcion         TEXT          NULL,
  especificaciones    TEXT          NULL,
  precio              DECIMAL(12,2) NOT NULL,
  stock               INT           NOT NULL DEFAULT 0,
  imagen_url          VARCHAR(255)  NULL,
  destacado           BOOLEAN       NOT NULL DEFAULT FALSE,
  activo              BOOLEAN       NOT NULL DEFAULT TRUE,
  fecha_creacion      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                    ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_producto_precio CHECK (precio >= 0),
  CONSTRAINT chk_producto_stock  CHECK (stock  >= 0),
  CONSTRAINT fk_producto_categoria
    FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_producto_categoria (id_categoria),
  INDEX idx_producto_nombre (nombre)
) ENGINE=InnoDB;

-- ---------- PEDIDO ----------
CREATE TABLE IF NOT EXISTS pedido (
  id_pedido       INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario      INT          NOT NULL,
  estado          ENUM('CARRITO','PENDIENTE','PAGADO','ENVIADO','ENTREGADO','CANCELADO')
                  NOT NULL DEFAULT 'CARRITO',
  fecha_creacion  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_compra    DATETIME     NULL,
  metodo_pago     VARCHAR(40)  NULL,
  direccion_envio VARCHAR(255) NULL,
  pais_envio      VARCHAR(100) NULL,
  ciudad_envio    VARCHAR(100) NULL,
  barrio_envio    VARCHAR(100) NULL,
  CONSTRAINT fk_pedido_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_pedido_usuario (id_usuario),
  INDEX idx_pedido_estado (estado)
) ENGINE=InnoDB;

-- ---------- DETALLE_PEDIDO ----------
CREATE TABLE IF NOT EXISTS detalle_pedido (
  id_detalle  INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido   INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad    INT NOT NULL DEFAULT 1,
  CONSTRAINT chk_detalle_cantidad CHECK (cantidad > 0),
  CONSTRAINT fk_detalle_pedido
    FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detalle_producto
    FOREIGN KEY (id_producto) REFERENCES producto (id_producto)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  UNIQUE KEY uq_detalle_pedido_producto (id_pedido, id_producto)
) ENGINE=InnoDB;

-- ---------- PERSONALIZACION_TIENDA ----------
CREATE TABLE IF NOT EXISTS personalizacion_tienda (
  id_personalizacion  INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario          INT          NOT NULL,
  nombre_tienda       VARCHAR(80)  NOT NULL,
  logo_url            VARCHAR(255) NULL,
  color_primario      VARCHAR(7)   NOT NULL DEFAULT '#1F4E79',
  color_secundario    VARCHAR(7)   NOT NULL DEFAULT '#FFF2CC',
  banner_url          VARCHAR(255) NULL,
  descripcion_tienda  TEXT         NULL,
  correo_contacto     VARCHAR(120) NULL,
  telefono_contacto   VARCHAR(20)  NULL,
  fecha_actualizacion DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                                   ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_personalizacion_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_personalizacion_usuario (id_usuario)
) ENGINE=InnoDB;



INSERT INTO usuario (nombre, apellido, correo, contrasena_hash, telefono, direccion, rol) VALUES
('Laura',  'Gómez',    'admin@tienda.com',   '$2y$10$hashEjemploAdmin000000000000000000000000000000000000', '3001112233', 'Cra 5 #10-20, Neiva',  'ADMINISTRADOR'),
('Carlos', 'Ramírez',  'carlos@correo.com',  '$2y$10$hashEjemploCliente01000000000000000000000000000000000', '3012223344', 'Calle 8 #4-15, Neiva',  'CLIENTE'),
('María',  'Fernández','maria@correo.com',   '$2y$10$hashEjemploCliente02000000000000000000000000000000000', '3023334455', 'Av. 26 #30-50, Bogotá', 'CLIENTE'),
('Andrés', 'Torres',   'andres@correo.com',  '$2y$10$hashEjemploCliente03000000000000000000000000000000000', '3034445566', 'Cra 15 #7-80, Cali',    'CLIENTE'),
('Sofía',  'Martínez', 'sofia@correo.com',   '$2y$10$hashEjemploCliente04000000000000000000000000000000000', '3045556677', 'Calle 50 #20-10, Medellín', 'CLIENTE');



INSERT INTO categoria (nombre, descripcion, activa) VALUES
('Celulares',   'Teléfonos inteligentes y accesorios',      TRUE),
('Computadores','Portátiles, de escritorio y componentes',   TRUE),
('Audio',       'Audífonos, parlantes y barras de sonido',   TRUE),
('Hogar',       'Electrodomésticos y artículos para el hogar', TRUE),
('Gaming',      'Consolas, controles y periféricos',         TRUE),
('Descontinuados','Categoría sin uso actual',                FALSE);

INSERT INTO producto
  (id_categoria, nombre, marca, descripcion, especificaciones, precio, stock, imagen_url, destacado, activo) VALUES
(1, 'Smartphone Galaxy A55',     'Samsung', 'Teléfono gama media con gran pantalla',      'Pantalla 6.6" AMOLED, 8GB RAM, 256GB, cámara 50MP',  1599000.00, 25, 'https://exitocol.vteximg.com.br/arquivos/ids/34507068/Celular-SAMSUNG-A55-5G-256-GB-8-GB-RAM-Azul-Hielo-3526200_a.jpg?v=639215576761370000',   TRUE,  FALSE),
(1, 'iPhone 14 128GB',           'Apple',   'Smartphone con chip A15 Bionic',             'Pantalla 6.1" OLED, 128GB, cámara dual 12MP',        3299000.00, 10, 'https://exitocol.vtexassets.com/arquivos/ids/34061868/iphone-14-reacondicionado-5g-128-gb-medianoche.jpg?v=639196498825870000',     TRUE,  TRUE),
(1, 'Redmi Note 13',             'Xiaomi',  'Equilibrio entre precio y rendimiento',      'Pantalla 6.67" AMOLED, 6GB RAM, 128GB',               899000.00, 40, 'https://i02.appmifile.com/mi-com-product/fly-birds/redmi-note-13/PC/bac9e4d29124ae838486e7f567d14361.jpg', FALSE, TRUE),
(2, 'Portátil IdeaPad 3',        'Lenovo',  'Portátil para estudio y oficina',            'Ryzen 5, 8GB RAM, SSD 512GB, 15.6" FHD',             2199000.00, 15, 'https://i5.walmartimages.com.mx/mg/gm/3pp/asr/6b4634cf-ab91-4c75-bdce-1551c5019f93.e8cda182e2ed4e20283a3d92c5fca2fc.jpeg?odnHeight=2000&odnWidth=2000&odnBg=ffffff', TRUE, TRUE),
(2, 'MacBook Air M2',            'Apple',   'Ultraportátil ligera y potente',             'Chip M2, 8GB RAM, SSD 256GB, 13.6" Retina',          5199000.00,  6, 'https://upload.wikimedia.org/wikipedia/commons/9/9f/M2_Macbook_Air_Starlight_model.jpg', TRUE, TRUE),
(2, 'Monitor 24" Full HD',       'LG',      'Monitor IPS para oficina y hogar',           '24" IPS, 1920x1080, 75Hz, HDMI',                      599000.00, 30, 'https://www.lg.com/ae/images/consumer-monitors/md07587749/gallery/medium01.jpg', FALSE, TRUE),
(3, 'Audífonos WH-CH520',        'Sony',    'Audífonos Bluetooth con micrófono',          'Bluetooth 5.2, 50h de batería',                       229000.00, 50, 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6533/6533161cv12d.jpg', FALSE, TRUE),
(3, 'Parlante Flip 6',           'JBL',     'Parlante portátil resistente al agua',       'IP67, 12h de batería, Bluetooth',                     469000.00, 20, 'https://cellularstockpile.com/wp-content/uploads/2024/01/jbl-flip-6-waterproof-portable-bluetooth-speaker.jpg', TRUE, TRUE),
(4, 'Freidora de aire 4L',       'Oster',   'Cocina con poco aceite',                     '4L, 1500W, temporizador 60 min',                      289000.00,  3, 'https://buydominion.com/wp-content/uploads/2021/07/Freidora-de-Aire-Digital-4-L-CKSTAF40D-2-930x930.jpg', FALSE, TRUE),
(4, 'Aspiradora robot',          'Xiaomi',  'Limpieza automática con programación',       'Succión 4000Pa, app móvil, 2h de autonomía',          899000.00,  8, 'https://i02.appmifile.com/mi-com-product/fly-birds/xiaomi-robot-vacuum-s20/pc/8de5475d68e7c37bea0b893f13b79edf.jpg', FALSE, TRUE),
(5, 'Consola PlayStation 5',     'Sony',    'Consola de videojuegos de nueva generación', 'SSD 825GB, 4K, control DualSense',                   2799000.00,  5, 'https://i5.walmartimages.com/seo/Sony-PlayStation-5-PS5-Digital-Console-Slim_330f0b1b-c9b6-4d17-8875-f35fea51bdfd.587fde46f23ab38eb3197552e46f5305.jpeg', TRUE, TRUE),
(5, 'Control Xbox inalámbrico',  'Microsoft','Control compatible con PC y Xbox',          'Bluetooth, 40h de batería',                           249000.00, 35, 'https://m.media-amazon.com/images/I/61H321AbQuL._AC_SL1500_.jpg', FALSE, TRUE),
(2, 'Teclado mecánico K552',     'Redragon','Teclado mecánico compacto',                  'Switch rojo, retroiluminado, formato TKL',            159000.00,  0, 'https://redragonadria.com/wp-content/uploads/2021/03/K552-2-1-1.jpg', FALSE, FALSE);


INSERT INTO pedido (id_pedido, id_usuario, estado, fecha_creacion, fecha_compra, metodo_pago, direccion_envio) VALUES
(1, 2, 'ENTREGADO', '2026-09-01 10:15:00', '2026-09-01 10:30:00', 'Tarjeta crédito', 'Calle 8 #4-15, Neiva'),
(2, 3, 'ENVIADO',   '2026-09-20 14:00:00', '2026-09-20 14:12:00', 'PSE',             'Av. 26 #30-50, Bogotá'),
(3, 2, 'PAGADO',    '2026-10-05 09:00:00', '2026-10-05 09:20:00', 'Nequi',           'Calle 8 #4-15, Neiva'),
(4, 4, 'PENDIENTE', '2026-10-10 16:45:00', NULL,                  'Contraentrega',   'Cra 15 #7-80, Cali'),
(5, 5, 'CARRITO',   '2026-10-12 11:30:00', NULL,                  NULL,              NULL),
(6, 3, 'CANCELADO', '2026-10-02 18:00:00', NULL,                  'PSE',             'Av. 26 #30-50, Bogotá');


INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad) VALUES
(1, 1, 1),
(1, 7, 2),
(2, 5, 1),
(2, 6, 1),
(3, 8, 1),
(3, 12, 2),
(4, 11, 1),
(4, 9, 1),
(5, 3, 1),
(5, 7, 1),
(6, 2, 1);


INSERT INTO personalizacion_tienda

  (id_usuario, nombre_tienda, logo_url, color_primario, color_secundario, banner_url,
   descripcion_tienda, correo_contacto, telefono_contacto) VALUES
(1, 'TecnoShop Huila', 'logo/logo.png', '#1F4E79', '#FFF2CC', 'banner/banner.png',
 'Tu tienda de tecnología con los mejores precios y envíos a todo el país.',
 'contacto@tienda.com', '3001112233');


-- CONSULTAS (SELECT)

-- 4.1 Todos los productos activos con su categoría
SELECT p.id_producto, p.nombre, p.marca, c.nombre AS categoria, p.precio, p.stock
FROM producto p
JOIN categoria c ON c.id_categoria = p.id_categoria
WHERE p.activo = TRUE
ORDER BY c.nombre, p.nombre;

-- 4.2 Productos destacados
SELECT nombre, marca, precio
FROM producto
WHERE destacado = TRUE AND activo = TRUE;

-- 4.3 Buscar productos por nombre o marca (ejemplo: "sony")
SELECT id_producto, nombre, marca, precio
FROM producto
WHERE nombre LIKE '%sony%' OR marca LIKE '%sony%';

-- 4.4 Productos en un rango de precio
SELECT nombre, precio
FROM producto
WHERE precio BETWEEN 500000 AND 2500000
ORDER BY precio ASC;

-- 4.5 Productos con stock bajo (5 unidades o menos)
SELECT nombre, stock
FROM producto
WHERE stock <= 5 AND activo = TRUE
ORDER BY stock;

-- 4.6 Cantidad de productos por categoría
SELECT c.nombre AS categoria, COUNT(p.id_producto) AS total_productos
FROM categoria c
LEFT JOIN producto p ON p.id_categoria = c.id_categoria
GROUP BY c.id_categoria, c.nombre
ORDER BY total_productos DESC;

-- 4.7 Pedidos de un usuario (ejemplo: id_usuario = 2)
SELECT id_pedido, estado, fecha_creacion, fecha_compra, metodo_pago
FROM pedido
WHERE id_usuario = 2
ORDER BY fecha_creacion DESC;

-- 4.8 Detalle de un pedido con subtotal (ejemplo: id_pedido = 1)
SELECT d.id_detalle, pr.nombre AS producto, d.cantidad, pr.precio,
       (d.cantidad * pr.precio) AS subtotal
FROM detalle_pedido d
JOIN producto pr ON pr.id_producto = d.id_producto
WHERE d.id_pedido = 1;



-- VISTAS


-- 5.1 Catálogo público: solo productos y categorías activas, con stock
CREATE OR REPLACE VIEW v_catalogo_publico AS
SELECT p.id_producto, p.nombre, p.marca, p.descripcion, p.especificaciones,
       p.precio, p.stock, p.imagen_url, p.destacado,
       c.id_categoria, c.nombre AS categoria
FROM producto p
JOIN categoria c ON c.id_categoria = p.id_categoria
WHERE p.activo = TRUE AND c.activa = TRUE AND p.stock > 0;

-- 5.2 Productos destacados
CREATE OR REPLACE VIEW v_productos_destacados AS
SELECT id_producto, nombre, marca, precio, imagen_url, categoria
FROM v_catalogo_publico
WHERE destacado = TRUE;

-- 5.3 Alerta de inventario bajo (para el administrador)
CREATE OR REPLACE VIEW v_stock_bajo AS
SELECT p.id_producto, p.nombre, p.marca, c.nombre AS categoria, p.stock
FROM producto p
JOIN categoria c ON c.id_categoria = p.id_categoria
WHERE p.activo = TRUE AND p.stock <= 5;

-- 5.4 Detalle de pedidos con subtotales
CREATE OR REPLACE VIEW v_detalle_pedido_completo AS
SELECT d.id_detalle, d.id_pedido, pr.id_producto, pr.nombre AS producto,
       pr.marca, d.cantidad, pr.precio AS precio_unitario,
       (d.cantidad * pr.precio) AS subtotal
FROM detalle_pedido d
JOIN producto pr ON pr.id_producto = d.id_producto;



-- ---------- Ejemplos de uso de las vistas ----------
SELECT * FROM v_catalogo_publico ORDER BY categoria, nombre;
SELECT * FROM v_productos_destacados;
SELECT * FROM v_stock_bajo;
SELECT * FROM v_detalle_pedido_completo WHERE id_pedido = 1;

USE catalogo_ecommerce;

DELIMITER $$


-- 1. Registrar usuario (rol CLIENTE)
-- CALL sp_registrar_usuario('Pedro','Pérez','pedro@correo.com','hash123','3001234567','Calle 1 #2-3');
DROP PROCEDURE IF EXISTS sp_registrar_usuario$$
CREATE PROCEDURE sp_registrar_usuario(
  IN p_nombre    VARCHAR(80),
  IN p_apellido  VARCHAR(80),
  IN p_correo    VARCHAR(120),
  IN p_hash      VARCHAR(255),
  IN p_telefono  VARCHAR(20),
  IN p_direccion VARCHAR(255)
)
BEGIN

  INSERT INTO usuario (nombre, apellido, correo, contrasena_hash, telefono, direccion, rol)
  VALUES (p_nombre, p_apellido, p_correo, p_hash, p_telefono, p_direccion, 'CLIENTE');

  SELECT LAST_INSERT_ID() AS id_usuario;
END$$


-- 2. Crear categoría
-- CALL sp_crear_categoria('Accesorios','Fundas, cables y cargadores');
DROP PROCEDURE IF EXISTS sp_crear_categoria$$
CREATE PROCEDURE sp_crear_categoria(
  IN p_nombre      VARCHAR(80),
  IN p_descripcion VARCHAR(255)
)
BEGIN
 

  INSERT INTO categoria (nombre, descripcion) VALUES (p_nombre, p_descripcion);

  SELECT LAST_INSERT_ID() AS id_categoria;
END$$


-- 3. Crear producto
-- CALL sp_crear_producto(3,'Audífonos Pro','JBL','Cancelación de ruido','Bluetooth 5.3',349000,15,'img/jbl-pro.jpg',TRUE);
DROP PROCEDURE IF EXISTS sp_crear_producto$$
CREATE PROCEDURE sp_crear_producto(
  IN p_id_categoria     INT,
  IN p_nombre           VARCHAR(150),
  IN p_marca            VARCHAR(80),
  IN p_descripcion      TEXT,
  IN p_especificaciones TEXT,
  IN p_precio           DECIMAL(12,2),
  IN p_stock            INT,
  IN p_imagen_url       VARCHAR(255),
  IN p_destacado        BOOLEAN
)
BEGIN
  
  INSERT INTO producto
    (id_categoria, nombre, marca, descripcion, especificaciones, precio, stock, imagen_url, destacado)
  VALUES
    (p_id_categoria, p_nombre, p_marca, p_descripcion, p_especificaciones, p_precio, p_stock, p_imagen_url, p_destacado);

  SELECT LAST_INSERT_ID() AS id_producto;
END$$


-- 4. Agregar producto al carrito (crea el carrito si no existe; suma si ya está)
-- CALL sp_agregar_al_carrito(2, 6, 1);
DROP PROCEDURE IF EXISTS sp_agregar_al_carrito$$
CREATE PROCEDURE sp_agregar_al_carrito(
  IN p_id_usuario  INT,
  IN p_id_producto INT,
  IN p_cantidad    INT
)
BEGIN
  DECLARE v_id_pedido  INT DEFAULT NULL;
  DECLARE v_stock      INT DEFAULT NULL;
  DECLARE v_en_carrito INT DEFAULT 0;

  IF p_cantidad <= 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cantidad debe ser mayor a 0';
  END IF;

  IF NOT EXISTS (SELECT 1 FROM usuario WHERE id_usuario = p_id_usuario AND activo = TRUE) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no existe o está inactivo';
  END IF;

  SELECT stock INTO v_stock
  FROM producto
  WHERE id_producto = p_id_producto AND activo = TRUE;

  IF v_stock IS NULL THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Producto no existe o está inactivo';
  END IF;

  SELECT id_pedido INTO v_id_pedido
  FROM pedido
  WHERE id_usuario = p_id_usuario AND estado = 'CARRITO'
  ORDER BY id_pedido DESC
  LIMIT 1;

  IF v_id_pedido IS NULL THEN
    INSERT INTO pedido (id_usuario, estado) VALUES (p_id_usuario, 'CARRITO');
    SET v_id_pedido = LAST_INSERT_ID();
  END IF;

  SELECT COALESCE(SUM(cantidad), 0) INTO v_en_carrito
  FROM detalle_pedido
  WHERE id_pedido = v_id_pedido AND id_producto = p_id_producto;

  IF v_en_carrito + p_cantidad > v_stock THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuficiente para esa cantidad';
  END IF;

  INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad)
  VALUES (v_id_pedido, p_id_producto, p_cantidad)
  ON DUPLICATE KEY UPDATE cantidad = cantidad + p_cantidad;

  SELECT v_id_pedido AS id_pedido;
END$$

-- 6. Actualizar stock de un producto
-- CALL sp_actualizar_stock(9, 20);
DROP PROCEDURE IF EXISTS sp_actualizar_stock$$
DELIMITER $$

CREATE PROCEDURE sp_actualizar_stock(
    IN p_nombre_producto VARCHAR(100),
    IN p_nuevo_stock INT
)
BEGIN
    -- Validar que el stock no sea negativo
    IF p_nuevo_stock < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El stock no puede ser negativo';
    END IF;
    
    -- Validar que el producto exista por su nombre
    IF NOT EXISTS (SELECT 1 FROM producto WHERE nombre = p_nombre_producto) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto no existe';
    END IF;

    -- Actualizar el stock
    UPDATE producto 
    SET stock = p_nuevo_stock 
    WHERE nombre = p_nombre_producto;

    -- Retornar el resultado actualizado
    SELECT id_producto, nombre, stock 
    FROM producto 
    WHERE nombre = p_nombre_producto;
END$$






-- 8. Login: devuelve datos del usuario (la contraseña se verifica en la app)
-- CALL sp_login_usuario('admin@tienda.com');
DROP PROCEDURE IF EXISTS sp_login_usuario$$
CREATE PROCEDURE sp_login_usuario(
  IN p_correo VARCHAR(120)
)
BEGIN
  SELECT id_usuario, nombre, apellido, correo, contrasena_hash, rol
  FROM usuario
  WHERE correo = p_correo AND activo = TRUE;
END$$


-- 9. Catálogo público (activos y con stock)
-- CALL sp_listar_catalogo();
DROP PROCEDURE IF EXISTS sp_listar_catalogo$$
CREATE PROCEDURE sp_listar_catalogo()
BEGIN
  SELECT p.id_producto, p.nombre, p.marca, c.nombre AS categoria,
         p.precio, p.stock, p.imagen_url, p.destacado
  FROM producto p
  JOIN categoria c ON c.id_categoria = p.id_categoria
  WHERE p.activo = TRUE AND c.activa = TRUE AND p.stock > 0
  ORDER BY p.destacado DESC, c.nombre, p.nombre;
END$$


-- 10. Buscar por nombre o marca
-- CALL sp_buscar_productos('sony');
DROP PROCEDURE IF EXISTS sp_buscar_productos$$
CREATE PROCEDURE sp_buscar_productos(
  IN p_texto VARCHAR(100)
)
BEGIN
  SELECT p.id_producto, p.nombre, p.marca, c.nombre AS categoria, p.precio, p.stock
  FROM producto p
  JOIN categoria c ON c.id_categoria = p.id_categoria
  WHERE p.activo = TRUE
    AND (p.nombre LIKE CONCAT('%', p_texto, '%')
         OR p.marca LIKE CONCAT('%', p_texto, '%'))
  ORDER BY p.nombre;
END$$


-- 11. Productos de una categoría
-- CALL sp_productos_por_categoria(1);
DROP PROCEDURE IF EXISTS sp_productos_por_categoria$$
DELIMITER $$

CREATE PROCEDURE sp_productos_por_categoria(
    IN p_nombre_categoria VARCHAR(100)
)
BEGIN
    SELECT p.id_producto, p.nombre, p.marca, p.precio, p.stock, p.imagen_url
    FROM producto p
    JOIN categoria c ON c.id_categoria = p.id_categoria
    WHERE c.nombre LIKE CONCAT('%', p_nombre_categoria, '%')
      AND p.activo = TRUE
    ORDER BY p.nombre;
END$$






-- 14. Historial de pedidos de un usuario con total
-- CALL sp_pedidos_usuario(2);
DROP PROCEDURE IF EXISTS sp_pedidos_usuario$$
CREATE PROCEDURE sp_pedidos_usuario(
  IN p_id_usuario INT
)
BEGIN
  SELECT pe.id_pedido, pe.estado, pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago,
         COALESCE(SUM(d.cantidad * pr.precio), 0) AS total
  FROM pedido pe
  LEFT JOIN detalle_pedido d ON d.id_pedido = pe.id_pedido
  LEFT JOIN producto pr ON pr.id_producto = d.id_producto
  WHERE pe.id_usuario = p_id_usuario
  GROUP BY pe.id_pedido, pe.estado, pe.fecha_creacion, pe.fecha_compra, pe.metodo_pago
  ORDER BY pe.fecha_creacion DESC;
END$$


-- =====================================================================
-- AGREGADOS PARA QUE FUNCIONE LA APLICACIÓN WEB (CRUD + panel admin)
-- =====================================================================

-- Búsqueda: ahora devuelve también imagen y destacado
DROP PROCEDURE IF EXISTS sp_buscar_productos$$
CREATE PROCEDURE sp_buscar_productos(IN p_texto VARCHAR(100))
BEGIN
  SELECT p.id_producto, p.nombre, p.marca, c.nombre AS categoria, p.precio, p.stock,
         p.imagen_url, p.destacado
  FROM producto p
  JOIN categoria c ON c.id_categoria = p.id_categoria
  WHERE p.activo = TRUE AND c.activa = TRUE AND p.stock > 0
    AND (p.nombre LIKE CONCAT('%', p_texto, '%') OR p.marca LIKE CONCAT('%', p_texto, '%'))
  ORDER BY p.nombre;
END$$

-- Tienda
DROP PROCEDURE IF EXISTS sp_obtener_tienda$$
CREATE PROCEDURE sp_obtener_tienda()
BEGIN
  SELECT nombre_tienda, descripcion_tienda, logo_url, color_primario, color_secundario
  FROM personalizacion_tienda ORDER BY id_personalizacion LIMIT 1;
END$$

-- Categorías
DROP PROCEDURE IF EXISTS sp_listar_categorias$$
CREATE PROCEDURE sp_listar_categorias()
BEGIN
  SELECT id_categoria, nombre, descripcion FROM categoria WHERE activa = TRUE ORDER BY nombre;
END$$

DROP PROCEDURE IF EXISTS sp_actualizar_categoria$$
CREATE PROCEDURE sp_actualizar_categoria(IN p_id INT, IN p_nombre VARCHAR(80), IN p_descripcion VARCHAR(255))
BEGIN
  IF NOT EXISTS (SELECT 1 FROM categoria WHERE id_categoria = p_id AND activa = TRUE) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La categoría no existe';
  END IF;
  UPDATE categoria SET nombre = p_nombre, descripcion = p_descripcion WHERE id_categoria = p_id;
  SELECT p_id AS id_categoria;
END$$

DROP PROCEDURE IF EXISTS sp_eliminar_categoria$$
CREATE PROCEDURE sp_eliminar_categoria(IN p_id INT)
BEGIN
  IF EXISTS (SELECT 1 FROM producto WHERE id_categoria = p_id AND activo = TRUE) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No puedes eliminar una categoría que tiene productos activos';
  END IF;
  UPDATE categoria SET activa = FALSE WHERE id_categoria = p_id;
  SELECT p_id AS id_categoria;
END$$

-- Productos (panel admin: incluye agotados, descripción y especificaciones)
DROP PROCEDURE IF EXISTS sp_admin_listar_productos$$
CREATE PROCEDURE sp_admin_listar_productos()
BEGIN
  SELECT p.id_producto, p.id_categoria, c.nombre AS categoria, p.nombre, p.marca,
         p.descripcion, p.especificaciones, p.precio, p.stock, p.imagen_url, p.destacado
  FROM producto p JOIN categoria c ON c.id_categoria = p.id_categoria
  WHERE p.activo = TRUE ORDER BY p.nombre;
END$$

DROP PROCEDURE IF EXISTS sp_obtener_producto$$
CREATE PROCEDURE sp_obtener_producto(IN p_id INT)
BEGIN
  SELECT id_producto, id_categoria, nombre, marca, descripcion, especificaciones,
         precio, stock, imagen_url, destacado
  FROM producto WHERE id_producto = p_id AND activo = TRUE;
END$$

DROP PROCEDURE IF EXISTS sp_actualizar_producto$$
CREATE PROCEDURE sp_actualizar_producto(
  IN p_id INT, IN p_id_categoria INT, IN p_nombre VARCHAR(150), IN p_marca VARCHAR(80),
  IN p_descripcion TEXT, IN p_especificaciones TEXT, IN p_precio DECIMAL(12,2),
  IN p_stock INT, IN p_imagen_url VARCHAR(255), IN p_destacado BOOLEAN)
BEGIN
  IF NOT EXISTS (SELECT 1 FROM producto WHERE id_producto = p_id AND activo = TRUE) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El producto no existe';
  END IF;
  IF p_precio < 0 OR p_stock < 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Precio y stock no pueden ser negativos';
  END IF;
  UPDATE producto SET id_categoria = p_id_categoria, nombre = p_nombre, marca = p_marca,
         descripcion = p_descripcion, especificaciones = p_especificaciones, precio = p_precio,
         stock = p_stock, imagen_url = p_imagen_url, destacado = p_destacado
  WHERE id_producto = p_id;
  SELECT p_id AS id_producto;
END$$

-- Baja lógica: conserva el historial de pedidos
DROP PROCEDURE IF EXISTS sp_eliminar_producto$$
CREATE PROCEDURE sp_eliminar_producto(IN p_id INT)
BEGIN
  UPDATE producto SET activo = FALSE WHERE id_producto = p_id;
  SELECT p_id AS id_producto;
END$$

-- Usuarios (panel admin)
DROP PROCEDURE IF EXISTS sp_listar_usuarios$$
CREATE PROCEDURE sp_listar_usuarios()
BEGIN
  SELECT id_usuario, nombre, apellido, correo, rol FROM usuario WHERE activo = TRUE ORDER BY fecha_registro DESC;
END$$

DROP PROCEDURE IF EXISTS sp_cambiar_rol$$
CREATE PROCEDURE sp_cambiar_rol(IN p_id INT, IN p_rol VARCHAR(20))
BEGIN
  IF p_rol NOT IN ('ADMINISTRADOR','CLIENTE') THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Rol no válido';
  END IF;
  UPDATE usuario SET rol = p_rol WHERE id_usuario = p_id;
  SELECT p_id AS id_usuario;
END$$

DROP PROCEDURE IF EXISTS sp_eliminar_usuario$$
CREATE PROCEDURE sp_eliminar_usuario(IN p_id INT)
BEGIN
  UPDATE usuario SET activo = FALSE WHERE id_usuario = p_id;
  SELECT p_id AS id_usuario;
END$$

DELIMITER ;

-- La vista de destacados ahora incluye stock y destacado (la app los necesita)
CREATE OR REPLACE VIEW v_productos_destacados AS
SELECT id_producto, nombre, marca, precio, stock, imagen_url, destacado, categoria
FROM v_catalogo_publico
WHERE destacado = TRUE;
