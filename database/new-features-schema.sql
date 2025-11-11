-- ===== NUEVAS FUNCIONALIDADES - ESQUEMA DE BASE DE DATOS =====
-- Versión: 3.0.0
-- Fecha: 2025
-- Este script agrega las tablas necesarias para las nuevas funcionalidades
-- SIN afectar las tablas existentes
-- 
-- NOTA: Este archivo contiene solo las tablas que están siendo utilizadas en el código.
-- Las tablas no utilizadas han sido eliminadas para mantener la base de datos limpia.

-- ===== TABLA DE COMENTARIOS EN BLOG =====
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noticia_id INT NOT NULL,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    comentario TEXT NOT NULL,
    comentario_padre_id INT NULL,
    aprobado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_noticia (noticia_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_aprobado (aprobado),
    INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE RESERVAS DE EVENTOS =====
CREATE TABLE IF NOT EXISTS reservas_eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    num_personas INT DEFAULT 1,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'completada') DEFAULT 'pendiente',
    codigo_reserva VARCHAR(50) UNIQUE NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    notas TEXT,
    INDEX idx_evento (evento_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_codigo_reserva (codigo_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE VIDEOS =====
CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    url_video VARCHAR(500),
    url_thumbnail VARCHAR(255),
    tipo ENUM('youtube', 'vimeo', 'local', 'otro') DEFAULT 'youtube',
    categoria VARCHAR(100),
    tags VARCHAR(500) DEFAULT NULL,
    evento_id INT,
    duracion INT,
    vistas INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_evento (evento_id),
    INDEX idx_activo (activo),
    INDEX idx_tags (tags(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE CUOTAS DE SOCIOS =====
CREATE TABLE IF NOT EXISTS cuotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    año INT NOT NULL,
    mes INT,
    monto DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'pagada', 'vencida', 'cancelada') DEFAULT 'pendiente',
    fecha_vencimiento DATE,
    fecha_pago DATE,
    metodo_pago VARCHAR(50),
    referencia_pago VARCHAR(100),
    notas TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_vencimiento (fecha_vencimiento),
    UNIQUE KEY unique_cuota (usuario_id, año, mes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== ÍNDICES ADICIONALES =====

-- Índices compuestos para consultas frecuentes
CREATE INDEX IF NOT EXISTS idx_comentarios_noticia_aprobado ON comentarios(noticia_id, aprobado);
CREATE INDEX IF NOT EXISTS idx_reservas_evento_estado ON reservas_eventos(evento_id, estado);
CREATE INDEX IF NOT EXISTS idx_cuotas_usuario_estado ON cuotas(usuario_id, estado);
