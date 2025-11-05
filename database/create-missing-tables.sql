-- ===== CREAR TABLAS FALTANTES =====
-- Script para crear las tablas opcionales que faltan en la base de datos
-- Ejecutar este script en phpMyAdmin o MySQL CLI

-- ===== TABLA DE USUARIOS (si no existe) =====
-- Nota: Ya existe la tabla 'users', pero algunos modelos pueden usar 'usuarios'
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    avatar VARCHAR(255),
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    activo BOOLEAN DEFAULT TRUE,
    email_verificado BOOLEAN DEFAULT FALSE,
    token_verificacion VARCHAR(255),
    token_recuperacion VARCHAR(255),
    fecha_token_recuperacion DATETIME,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE CONTACTOS =====
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(200),
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_leido (leido),
    INDEX idx_fecha_envio (fecha_envio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE NEWSLETTER =====
-- Nota: Ya existe 'newsletter_subscriptions', pero algunos modelos pueden usar 'newsletter'
CREATE TABLE IF NOT EXISTS newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    nombre VARCHAR(100),
    activo BOOLEAN DEFAULT TRUE,
    fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token_confirmacion VARCHAR(255),
    confirmado BOOLEAN DEFAULT FALSE,
    INDEX idx_email (email),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE CONFIGURACIÓN =====
CREATE TABLE IF NOT EXISTS configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion TEXT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== INSERTAR DATOS INICIALES DE CONFIGURACIÓN =====
-- Solo insertar si la tabla está vacía o si no existen estas claves
INSERT IGNORE INTO configuracion (clave, valor, descripcion) VALUES
('site_name', 'Filá Mariscales de Caballeros Templarios', 'Nombre del sitio web'),
('site_description', 'Página oficial de la Filá Mariscales de Caballeros Templarios de Elche', 'Descripción del sitio'),
('contact_email', 'info@filamariscales.com', 'Email de contacto principal'),
('contact_phone', '+34 965 123 456', 'Teléfono de contacto'),
('address', 'Elche, Alicante, España', 'Dirección de la filá'),
('social_facebook', '', 'URL de Facebook'),
('social_instagram', '', 'URL de Instagram'),
('social_twitter', '', 'URL de Twitter'),
('social_youtube', '', 'URL de YouTube'),
('maintenance_mode', '0', 'Modo mantenimiento (0=no, 1=sí)');

-- ===== VERIFICACIÓN =====
-- Verificar que las tablas se crearon correctamente
SELECT 
    'usuarios' as tabla,
    CASE WHEN EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'usuarios') 
         THEN '✓ Creada' 
         ELSE '✗ Error' 
    END as estado
UNION ALL
SELECT 
    'contactos' as tabla,
    CASE WHEN EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'contactos') 
         THEN '✓ Creada' 
         ELSE '✗ Error' 
    END as estado
UNION ALL
SELECT 
    'newsletter' as tabla,
    CASE WHEN EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'newsletter') 
         THEN '✓ Creada' 
         ELSE '✗ Error' 
    END as estado
UNION ALL
SELECT 
    'configuracion' as tabla,
    CASE WHEN EXISTS (SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'configuracion') 
         THEN '✓ Creada' 
         ELSE '✗ Error' 
    END as estado;

