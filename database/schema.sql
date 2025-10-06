-- ===== BASE DE DATOS FILÁ MARISCALES =====
-- Versión: 2.0.0
-- Fecha: 2024

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS fila_mariscales_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fila_mariscales_web;

-- ===== TABLA DE USUARIOS =====
CREATE TABLE usuarios (
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
);

-- ===== TABLA DE NOTICIAS =====
CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    resumen TEXT,
    contenido LONGTEXT NOT NULL,
    imagen_url VARCHAR(255),
    autor_id INT,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activa BOOLEAN DEFAULT TRUE,
    destacada BOOLEAN DEFAULT FALSE,
    vistas INT DEFAULT 0,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha_publicacion (fecha_publicacion),
    INDEX idx_activa (activa),
    INDEX idx_destacada (destacada)
);

-- ===== TABLA DE EVENTOS =====
CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha DATE NOT NULL,
    hora TIME,
    lugar VARCHAR(255),
    tipo ENUM('ensayo', 'presentacion', 'desfile', 'cena', 'reunion', 'otro') DEFAULT 'otro',
    imagen_url VARCHAR(255),
    precio DECIMAL(10,2) DEFAULT 0,
    capacidad INT,
    inscripciones_abiertas BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha),
    INDEX idx_activo (activo),
    INDEX idx_tipo (tipo)
);

-- ===== TABLA DE GALERÍA =====
CREATE TABLE galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200),
    descripcion TEXT,
    imagen_url VARCHAR(255) NOT NULL,
    thumb_url VARCHAR(255),
    categoria VARCHAR(100),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activa BOOLEAN DEFAULT TRUE,
    orden INT DEFAULT 0,
    INDEX idx_categoria (categoria),
    INDEX idx_activa (activa),
    INDEX idx_orden (orden)
);

-- ===== TABLA DE PRODUCTOS =====
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2),
    imagen_url VARCHAR(255),
    categoria VARCHAR(100),
    stock INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    destacado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo),
    INDEX idx_destacado (destacado),
    INDEX idx_precio (precio)
);

-- ===== TABLA DE PEDIDOS =====
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    estado ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    total DECIMAL(10,2) NOT NULL,
    direccion_envio TEXT,
    telefono VARCHAR(20),
    email VARCHAR(150),
    notas TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_pedido (fecha_pedido)
);

-- ===== TABLA DE DETALLES DE PEDIDO =====
CREATE TABLE pedido_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT,
    nombre_producto VARCHAR(200) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL,
    INDEX idx_pedido (pedido_id)
);

-- ===== TABLA DE CONTACTOS =====
CREATE TABLE contactos (
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
);

-- ===== TABLA DE NEWSLETTER =====
CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    nombre VARCHAR(100),
    activo BOOLEAN DEFAULT TRUE,
    fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token_confirmacion VARCHAR(255),
    confirmado BOOLEAN DEFAULT FALSE,
    INDEX idx_email (email),
    INDEX idx_activo (activo)
);

-- ===== TABLA DE DOCUMENTOS =====
CREATE TABLE documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    archivo_url VARCHAR(255) NOT NULL,
    tipo ENUM('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'otro') DEFAULT 'otro',
    categoria VARCHAR(100),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    descargas INT DEFAULT 0,
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo),
    INDEX idx_tipo (tipo)
);

-- ===== TABLA DE VISITAS =====
CREATE TABLE visitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    user_agent TEXT,
    pagina VARCHAR(255),
    fecha_visita TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha_visita (fecha_visita),
    INDEX idx_pagina (pagina)
);

-- ===== TABLA DE CONFIGURACIÓN =====
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descripcion TEXT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
);

-- ===== INSERTAR DATOS INICIALES =====

-- Usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, rol, activo, email_verificado) VALUES
('Administrador', 'admin@filamariscales.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Configuración inicial
INSERT INTO configuracion (clave, valor, descripcion) VALUES
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

-- Noticias de ejemplo
INSERT INTO noticias (titulo, resumen, contenido, imagen_url, autor_id, destacada) VALUES
('Presentación de la Filá 2024', 'La Filá Mariscales se presenta oficialmente para las fiestas de Moros y Cristianos 2024', 'La Filá Mariscales de Caballeros Templarios se presenta oficialmente para las fiestas de Moros y Cristianos 2024 con nuevas incorporaciones y actividades programadas para toda la temporada.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1, TRUE),
('Cena de Hermandad', 'Celebramos nuestra tradicional cena de hermandad', 'Celebramos nuestra tradicional cena de hermandad donde todos los miembros de la filá se reúnen para compartir momentos especiales y fortalecer los lazos de amistad.', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1, FALSE),
('Ensayo General', 'Preparación final para el desfile', 'Preparación final para el desfile de Moros y Cristianos con el ensayo general en el punto de encuentro oficial de la ciudad.', 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1, FALSE);

-- Eventos de ejemplo
INSERT INTO eventos (titulo, descripcion, fecha, hora, lugar, tipo, imagen_url) VALUES
('Presentación de la Filá', 'Presentación oficial de la Filá Mariscales para las fiestas 2024', '2024-10-15', '20:00:00', 'Sede Social', 'presentacion', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'),
('Cena de Hermandad', 'Cena de hermandad para todos los miembros de la filá', '2024-10-20', '21:00:00', 'Restaurante El Rincón', 'cena', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'),
('Ensayo General', 'Ensayo general del desfile de Moros y Cristianos', '2024-10-25', '18:00:00', 'Punto de encuentro: Ayuntamiento', 'ensayo', 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');

-- Galería de ejemplo
INSERT INTO galeria (titulo, descripcion, imagen_url, thumb_url, categoria, orden) VALUES
('Desfile de Moros y Cristianos 2023', 'Momento del desfile principal', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 'desfiles', 1),
('Cena de Hermandad', 'Momentos de la cena de hermandad', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 'eventos', 2),
('Presentación de la Filá', 'Acto de presentación oficial', 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 'eventos', 3),
('Actuación Musical', 'Momento de la actuación musical', 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80', 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 'musica', 4);

-- Productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, imagen_url, categoria, stock, destacado) VALUES
('Camiseta Oficial', 'Camiseta oficial de la Filá Mariscales con el escudo de los Caballeros Templarios', 25.00, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'ropa', 50, TRUE),
('Gorra Templaria', 'Gorra con el escudo de los Caballeros Templarios bordado', 18.00, 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'accesorios', 30, FALSE),
('Bandera de la Filá', 'Bandera oficial de la Filá Mariscales para desfiles', 35.00, 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'banderas', 20, TRUE),
('Insignia Dorada', 'Insignia dorada de los Caballeros Templarios', 12.00, 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'accesorios', 100, FALSE);

-- ===== VISTAS ÚTILES =====

-- Vista de noticias activas
CREATE VIEW vista_noticias_activas AS
SELECT 
    n.*,
    u.nombre as autor_nombre
FROM noticias n
LEFT JOIN usuarios u ON n.autor_id = u.id
WHERE n.activa = TRUE
ORDER BY n.fecha_publicacion DESC;

-- Vista de eventos próximos
CREATE VIEW vista_eventos_proximos AS
SELECT *
FROM eventos
WHERE activo = TRUE 
AND fecha >= CURDATE()
ORDER BY fecha ASC, hora ASC;

-- Vista de productos activos
CREATE VIEW vista_productos_activos AS
SELECT *
FROM productos
WHERE activo = TRUE
ORDER BY destacado DESC, nombre ASC;

-- Vista de galería activa
CREATE VIEW vista_galeria_activa AS
SELECT *
FROM galeria
WHERE activa = TRUE
ORDER BY orden ASC, fecha_subida DESC;

-- ===== PROCEDIMIENTOS ALMACENADOS =====

-- Procedimiento para obtener estadísticas del dashboard
DELIMITER //
CREATE PROCEDURE GetDashboardStats()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM usuarios WHERE activo = TRUE) as total_usuarios,
        (SELECT COUNT(*) FROM noticias WHERE activa = TRUE) as total_noticias,
        (SELECT COUNT(*) FROM eventos WHERE activo = TRUE AND fecha >= CURDATE()) as eventos_proximos,
        (SELECT COUNT(*) FROM productos WHERE activo = TRUE) as total_productos,
        (SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente') as pedidos_pendientes,
        (SELECT COUNT(*) FROM contactos WHERE leido = FALSE) as mensajes_no_leidos,
        (SELECT COUNT(*) FROM visitas WHERE DATE(fecha_visita) = CURDATE()) as visitas_hoy;
END //
DELIMITER ;

-- Procedimiento para limpiar tokens expirados
DELIMITER //
CREATE PROCEDURE CleanExpiredTokens()
BEGIN
    UPDATE usuarios 
    SET token_recuperacion = NULL, fecha_token_recuperacion = NULL 
    WHERE fecha_token_recuperacion < DATE_SUB(NOW(), INTERVAL 1 HOUR);
END //
DELIMITER ;

-- ===== TRIGGERS =====

-- Trigger para generar número de pedido automáticamente
DELIMITER //
CREATE TRIGGER tr_generate_pedido_number
BEFORE INSERT ON pedidos
FOR EACH ROW
BEGIN
    IF NEW.numero_pedido IS NULL OR NEW.numero_pedido = '' THEN
        SET NEW.numero_pedido = CONCAT('PED-', YEAR(NOW()), '-', LPAD(MONTH(NOW()), 2, '0'), '-', LPAD(DAY(NOW()), 2, '0'), '-', LPAD((SELECT COUNT(*) + 1 FROM pedidos WHERE DATE(fecha_pedido) = CURDATE()), 4, '0'));
    END IF;
END //
DELIMITER ;

-- ===== ÍNDICES ADICIONALES PARA OPTIMIZACIÓN =====

-- Índices compuestos para consultas frecuentes
CREATE INDEX idx_noticias_fecha_activa ON noticias(fecha_publicacion, activa);
CREATE INDEX idx_eventos_fecha_activa ON eventos(fecha, activo);
CREATE INDEX idx_productos_categoria_activo ON productos(categoria, activo);
CREATE INDEX idx_galeria_categoria_activa ON galeria(categoria, activa);

-- ===== COMENTARIOS FINALES =====
-- Esta base de datos está optimizada para:
-- 1. Gestión de usuarios y autenticación
-- 2. Sistema de noticias y eventos
-- 3. Galería de imágenes
-- 4. Tienda online con carrito
-- 5. Sistema de contactos y newsletter
-- 6. Panel de administración completo
-- 7. Estadísticas y analytics

-- Para usar esta base de datos:
-- 1. Ejecutar este script en MySQL
-- 2. Configurar las credenciales en api/config/database.php
-- 3. El usuario admin por defecto es: admin@filamariscales.com / password