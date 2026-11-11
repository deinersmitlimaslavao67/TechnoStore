CREATE TABLE IF NOT EXISTS feedback_reportes (
    id_reporte BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo ENUM('ERROR', 'SUGERENCIA') NOT NULL,
    origen ENUM('USUARIO', 'JAVASCRIPT') NOT NULL DEFAULT 'USUARIO',
    detalle TEXT NOT NULL,
    stack_trace TEXT NULL,
    pagina VARCHAR(500) NOT NULL DEFAULT '',
    estado ENUM('NUEVO', 'EN_REVISION', 'RESUELTO') NOT NULL DEFAULT 'NUEVO',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_feedback_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_feedback_estado_fecha (estado, fecha_creacion),
    INDEX idx_feedback_usuario_fecha (id_usuario, fecha_creacion)
) ENGINE=InnoDB;
