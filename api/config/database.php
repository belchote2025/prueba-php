<?php
/**
 * Configuración de Base de Datos
 * Filá Mariscales Web - Versión 2.0.0
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'fila_mariscales_web');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'Filá Mariscales');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/fila-mariscales-web');
define('API_URL', 'http://localhost/fila-mariscales-web/api');

// Configuración de seguridad
define('JWT_SECRET', 'fila_mariscales_secret_key_2024');
define('JWT_EXPIRY', 3600); // 1 hora
define('PASSWORD_MIN_LENGTH', 8);

// Configuración de archivos
define('UPLOAD_PATH', '../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);

// Configuración de email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', 'noreply@filamariscales.com');
define('SMTP_FROM_NAME', 'Filá Mariscales');

// Configuración de paginación
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Configuración de cache
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hora

// Configuración de logs
define('LOG_ENABLED', true);
define('LOG_PATH', '../logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configuración de CORS
define('CORS_ENABLED', true);
define('CORS_ORIGINS', ['http://localhost', 'http://localhost:3000', 'http://127.0.0.1']);

// Configuración de rate limiting
define('RATE_LIMIT_ENABLED', true);
define('RATE_LIMIT_REQUESTS', 100); // requests per hour
define('RATE_LIMIT_WINDOW', 3600); // 1 hour

// Configuración de backup
define('BACKUP_ENABLED', true);
define('BACKUP_PATH', '../backups/');
define('BACKUP_RETENTION_DAYS', 30);

// Configuración de mantenimiento
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'El sitio está en mantenimiento. Volveremos pronto.');

// Configuración de analytics
define('ANALYTICS_ENABLED', true);
define('TRACK_VISITS', true);
define('TRACK_USER_AGENTS', true);

// Configuración de notificaciones
define('NOTIFICATIONS_ENABLED', true);
define('EMAIL_NOTIFICATIONS', true);
define('PUSH_NOTIFICATIONS', false);

// Configuración de social media
define('SOCIAL_FACEBOOK', '');
define('SOCIAL_INSTAGRAM', '');
define('SOCIAL_TWITTER', '');
define('SOCIAL_YOUTUBE', '');

// Configuración de pagos (si se implementa)
define('PAYMENT_ENABLED', false);
define('PAYPAL_CLIENT_ID', '');
define('PAYPAL_CLIENT_SECRET', '');
define('STRIPE_PUBLIC_KEY', '');
define('STRIPE_SECRET_KEY', '');

// Configuración de mapas
define('GOOGLE_MAPS_API_KEY', '');
define('DEFAULT_LATITUDE', 38.2622);
define('DEFAULT_LONGITUDE', -0.7011);

// Configuración de idiomas
define('DEFAULT_LANGUAGE', 'es');
define('SUPPORTED_LANGUAGES', ['es', 'en', 'ca']);

// Configuración de timezone
date_default_timezone_set('Europe/Madrid');

// Configuración de errores
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Configuración de memoria y tiempo
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 30);

// Configuración de headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Configuración de CORS
if (CORS_ENABLED) {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (in_array($origin, CORS_ORIGINS) || in_array('*', CORS_ORIGINS)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    }
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Credentials: true');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Configuración de compresión
if (extension_loaded('zlib') && !ob_get_level()) {
    ob_start('ob_gzhandler');
}

// Configuración de cache headers
if (CACHE_ENABLED) {
    header('Cache-Control: public, max-age=' . CACHE_DURATION);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + CACHE_DURATION) . ' GMT');
}

// Configuración de logs
if (LOG_ENABLED && !is_dir(LOG_PATH)) {
    mkdir(LOG_PATH, 0755, true);
}

// Función para logging
function writeLog($level, $message, $context = []) {
    if (!LOG_ENABLED) return;
    
    $logFile = LOG_PATH . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
    $logEntry = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Función para obtener configuración de la base de datos
function getDatabaseConfig() {
    return [
        'host' => DB_HOST,
        'dbname' => DB_NAME,
        'username' => DB_USER,
        'password' => DB_PASS,
        'charset' => DB_CHARSET,
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ]
    ];
}

// Función para verificar mantenimiento
function checkMaintenanceMode() {
    if (MAINTENANCE_MODE) {
        http_response_code(503);
        header('Retry-After: 3600');
        echo json_encode([
            'success' => false,
            'message' => MAINTENANCE_MESSAGE,
            'maintenance' => true
        ]);
        exit();
    }
}

// Función para verificar rate limiting
function checkRateLimit($ip) {
    if (!RATE_LIMIT_ENABLED) return true;
    
    $cacheFile = LOG_PATH . 'rate_limit_' . md5($ip) . '.json';
    $now = time();
    
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if ($data['timestamp'] > $now - RATE_LIMIT_WINDOW) {
            if ($data['count'] >= RATE_LIMIT_REQUESTS) {
                http_response_code(429);
                echo json_encode([
                    'success' => false,
                    'message' => 'Demasiadas solicitudes. Inténtalo más tarde.',
                    'retry_after' => RATE_LIMIT_WINDOW
                ]);
                exit();
            }
            $data['count']++;
        } else {
            $data = ['count' => 1, 'timestamp' => $now];
        }
    } else {
        $data = ['count' => 1, 'timestamp' => $now];
    }
    
    file_put_contents($cacheFile, json_encode($data));
    return true;
}

// Función para sanitizar entrada
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Función para validar email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función para generar token seguro
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Función para hash de contraseña
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Función para verificar contraseña
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Función para generar respuesta JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}

// Función para manejar errores
function handleError($message, $statusCode = 500, $logLevel = 'ERROR') {
    writeLog($logLevel, $message);
    jsonResponse([
        'success' => false,
        'message' => $message
    ], $statusCode);
}

// Función para manejar éxito
function handleSuccess($data = null, $message = 'Operación exitosa') {
    $response = [
        'success' => true,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    jsonResponse($response);
}

// Función para obtener IP del cliente
function getClientIP() {
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Función para obtener User Agent
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

// Función para crear directorio si no existe
function ensureDirectoryExists($path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// Función para limpiar archivos temporales
function cleanTempFiles($path, $maxAge = 3600) {
    if (!is_dir($path)) return;
    
    $files = glob($path . '*');
    $now = time();
    
    foreach ($files as $file) {
        if (is_file($file) && ($now - filemtime($file)) > $maxAge) {
            unlink($file);
        }
    }
}

// Función para backup de base de datos
function backupDatabase() {
    if (!BACKUP_ENABLED) return false;
    
    ensureDirectoryExists(BACKUP_PATH);
    
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $filepath = BACKUP_PATH . $filename;
    
    $command = sprintf(
        'mysqldump --host=%s --user=%s --password=%s %s > %s',
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        $filepath
    );
    
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        writeLog('INFO', "Backup creado: $filename");
        return $filepath;
    } else {
        writeLog('ERROR', "Error creando backup: " . implode("\n", $output));
        return false;
    }
}

// Función para limpiar backups antiguos
function cleanOldBackups() {
    if (!BACKUP_ENABLED) return;
    
    $files = glob(BACKUP_PATH . 'backup_*.sql');
    $cutoff = time() - (BACKUP_RETENTION_DAYS * 24 * 60 * 60);
    
    foreach ($files as $file) {
        if (filemtime($file) < $cutoff) {
            unlink($file);
            writeLog('INFO', "Backup eliminado: " . basename($file));
        }
    }
}

// Inicialización
checkMaintenanceMode();
checkRateLimit(getClientIP());

// Limpiar archivos temporales cada 100 requests
if (rand(1, 100) === 1) {
    cleanTempFiles(LOG_PATH . 'temp/');
    cleanOldBackups();
}

// Log de inicio
writeLog('INFO', 'API iniciada', [
    'ip' => getClientIP(),
    'user_agent' => getUserAgent(),
    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
    'method' => $_SERVER['REQUEST_METHOD'] ?? ''
]);
?>