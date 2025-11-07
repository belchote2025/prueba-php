-- ===== NUEVAS FUNCIONALIDADES - ESQUEMA DE BASE DE DATOS =====
-- Versión: 3.0.0
-- Fecha: 2025
-- Este script agrega las tablas necesarias para las nuevas funcionalidades
-- SIN afectar las tablas existentes

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
    evento_id INT,
    duracion INT,
    vistas INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_evento (evento_id),
    INDEX idx_activo (activo)
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

-- ===== TABLA DE VOTACIONES =====
CREATE TABLE IF NOT EXISTS votaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('publica', 'privada', 'socios') DEFAULT 'publica',
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    resultados_visibles BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_fecha_fin (fecha_fin),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE OPCIONES DE VOTACIÓN =====
CREATE TABLE IF NOT EXISTS opciones_votacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    votacion_id INT NOT NULL,
    texto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    orden INT DEFAULT 0,
    INDEX idx_votacion (votacion_id),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE VOTOS =====
CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    votacion_id INT NOT NULL,
    opcion_id INT NOT NULL,
    usuario_id INT,
    ip_address VARCHAR(45),
    fecha_voto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_votacion (votacion_id),
    INDEX idx_opcion (opcion_id),
    INDEX idx_usuario (usuario_id),
    UNIQUE KEY unique_voto_usuario (votacion_id, usuario_id),
    UNIQUE KEY unique_voto_ip (votacion_id, ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE ENCUESTAS =====
CREATE TABLE IF NOT EXISTS encuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('satisfaccion', 'feedback', 'sugerencia', 'otro') DEFAULT 'feedback',
    activa BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE RESPUESTAS DE ENCUESTAS =====
CREATE TABLE IF NOT EXISTS respuestas_encuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    encuesta_id INT NOT NULL,
    usuario_id INT,
    nombre VARCHAR(100),
    email VARCHAR(150),
    respuesta TEXT NOT NULL,
    puntuacion INT,
    fecha_respuesta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_encuesta (encuesta_id),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE LOGROS/BADGES =====
CREATE TABLE IF NOT EXISTS logros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(255),
    tipo ENUM('participacion', 'evento', 'voluntariado', 'especial') DEFAULT 'participacion',
    puntos INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE LOGROS DE USUARIOS =====
CREATE TABLE IF NOT EXISTS logros_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    logro_id INT NOT NULL,
    fecha_obtencion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_logro (logro_id),
    UNIQUE KEY unique_logro_usuario (usuario_id, logro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE VOLUNTARIADO =====
CREATE TABLE IF NOT EXISTS voluntariado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha DATE NOT NULL,
    hora TIME,
    lugar VARCHAR(255),
    horas_requeridas DECIMAL(4,2),
    personas_necesarias INT,
    estado ENUM('abierto', 'completo', 'finalizado', 'cancelado') DEFAULT 'abierto',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE INSCRIPCIONES DE VOLUNTARIADO =====
CREATE TABLE IF NOT EXISTS inscripciones_voluntariado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voluntariado_id INT NOT NULL,
    usuario_id INT NOT NULL,
    horas_realizadas DECIMAL(4,2) DEFAULT 0,
    estado ENUM('inscrito', 'completado', 'cancelado') DEFAULT 'inscrito',
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_completado TIMESTAMP NULL,
    INDEX idx_voluntariado (voluntariado_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    UNIQUE KEY unique_inscripcion (voluntariado_id, usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE DONACIONES =====
CREATE TABLE IF NOT EXISTS donaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50),
    estado ENUM('pendiente', 'completada', 'fallida', 'cancelada') DEFAULT 'pendiente',
    referencia_pago VARCHAR(100),
    mensaje TEXT,
    mostrar_publicamente BOOLEAN DEFAULT FALSE,
    fecha_donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_donacion (fecha_donacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE SORTEOS/RIFAS =====
CREATE TABLE IF NOT EXISTS sorteos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio_numero DECIMAL(10,2) NOT NULL,
    fecha_sorteo DATETIME NOT NULL,
    premio TEXT,
    estado ENUM('abierto', 'cerrado', 'sorteado', 'cancelado') DEFAULT 'abierto',
    numero_ganador INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha_sorteo (fecha_sorteo),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE NÚMEROS DE SORTEO =====
CREATE TABLE IF NOT EXISTS numeros_sorteo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sorteo_id INT NOT NULL,
    usuario_id INT,
    numero INT NOT NULL,
    nombre VARCHAR(100),
    email VARCHAR(150),
    telefono VARCHAR(20),
    estado ENUM('disponible', 'vendido', 'ganador') DEFAULT 'disponible',
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_sorteo (sorteo_id),
    INDEX idx_numero (numero),
    INDEX idx_estado (estado),
    UNIQUE KEY unique_numero_sorteo (sorteo_id, numero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE NOTIFICACIONES =====
CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo ENUM('evento', 'noticia', 'cuota', 'reserva', 'general') DEFAULT 'general',
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    url VARCHAR(255),
    leida BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_leida (leida),
    INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE HERMANAMIENTOS =====
CREATE TABLE IF NOT EXISTS hermanamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    logo_url VARCHAR(255),
    web_url VARCHAR(255),
    email VARCHAR(150),
    telefono VARCHAR(20),
    pais VARCHAR(100),
    ciudad VARCHAR(100),
    fecha_hermanamiento DATE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE EVENTOS DE HERMANAMIENTOS =====
CREATE TABLE IF NOT EXISTS eventos_hermanamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hermanamiento_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha DATE,
    lugar VARCHAR(255),
    tipo ENUM('intercambio', 'visita', 'colaboracion', 'otro') DEFAULT 'intercambio',
    INDEX idx_hermanamiento (hermanamiento_id),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE UNIFORMES =====
CREATE TABLE IF NOT EXISTS uniformes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('uniforme', 'accesorio', 'prenda') DEFAULT 'uniforme',
    talla VARCHAR(20),
    cantidad_total INT DEFAULT 0,
    cantidad_disponible INT DEFAULT 0,
    imagen_url VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE PRÉSTAMOS DE UNIFORMES =====
CREATE TABLE IF NOT EXISTS prestamos_uniformes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uniforme_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_prevista DATE,
    fecha_devolucion_real DATE,
    estado ENUM('prestado', 'devuelto', 'perdido', 'danado') DEFAULT 'prestado',
    notas TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_uniforme (uniforme_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE CERTIFICADOS =====
CREATE TABLE IF NOT EXISTS certificados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo ENUM('participacion', 'merito', 'voluntariado', 'especial') DEFAULT 'participacion',
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    evento_id INT,
    fecha_emision DATE NOT NULL,
    codigo_verificacion VARCHAR(50) UNIQUE NOT NULL,
    archivo_url VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo (tipo),
    INDEX idx_codigo_verificacion (codigo_verificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE PARTITURAS/MÚSICA =====
CREATE TABLE IF NOT EXISTS partituras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    archivo_url VARCHAR(255) NOT NULL,
    tipo_archivo ENUM('pdf', 'midi', 'mp3', 'wav', 'otro') DEFAULT 'pdf',
    categoria VARCHAR(100),
    autor VARCHAR(100),
    duracion INT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TABLA DE SUSCRIPCIONES PUSH =====
CREATE TABLE IF NOT EXISTS suscripciones_push (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    endpoint VARCHAR(500) NOT NULL,
    p256dh VARCHAR(255) NOT NULL,
    auth VARCHAR(255) NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_activa (activa),
    UNIQUE KEY unique_endpoint (endpoint)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===== TRIGGERS PARA CÓDIGOS AUTOMÁTICOS =====
-- Nota: Los triggers se crearán después de las tablas mediante el script de instalación

-- ===== ÍNDICES ADICIONALES =====

-- Índices compuestos para consultas frecuentes
CREATE INDEX idx_comentarios_noticia_aprobado ON comentarios(noticia_id, aprobado);
CREATE INDEX idx_reservas_evento_estado ON reservas_eventos(evento_id, estado);
CREATE INDEX idx_cuotas_usuario_estado ON cuotas(usuario_id, estado);
CREATE INDEX idx_votos_votacion_opcion ON votos(votacion_id, opcion_id);

