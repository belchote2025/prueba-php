-- Tabla para guardar preferencias de personalización
CREATE TABLE IF NOT EXISTS personalizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('color', 'fuente', 'animacion', 'general') NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    valor TEXT NOT NULL,
    descripcion VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_tipo_nombre (tipo, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar valores por defecto (solo si no existen)
INSERT IGNORE INTO personalizacion (tipo, nombre, valor, descripcion) VALUES
('color', 'primary-color', '#8B0000', 'Color principal'),
('color', 'secondary-color', '#DC143C', 'Color secundario'),
('color', 'success-color', '#228B22', 'Color de éxito'),
('color', 'info-color', '#4682B4', 'Color informativo'),
('color', 'warning-color', '#FF4500', 'Color de advertencia'),
('color', 'danger-color', '#8B0000', 'Color de peligro'),
('fuente', 'font-family', 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif', 'Familia de fuente principal'),
('fuente', 'font-size-base', '1rem', 'Tamaño de fuente base'),
('animacion', 'card-animation', 'fadeIn', 'Animación para cards'),
('animacion', 'button-animation', 'pulse', 'Animación para botones'),
('animacion', 'transition-speed', '0.3s', 'Velocidad de transiciones'),
('general', 'border-radius', '8px', 'Radio de bordes'),
('general', 'shadow-intensity', 'medium', 'Intensidad de sombras');

