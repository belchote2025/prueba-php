<?php
// Prevent output before session start
ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
// Intentar cargar desde .env si existe
$envFile = dirname(dirname(__DIR__)) . '/.env';
if (file_exists($envFile)) {
    // Parsear .env de forma más robusta
    $env = [];
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines !== false) {
        foreach ($lines as $line) {
            // Ignorar comentarios y líneas vacías
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            
            // Separar clave y valor
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remover comillas si las tiene
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
                
                if (!empty($key)) {
                    $env[$key] = $value;
                }
            }
        }
    }
    
    // Si el parsing manual falló, intentar con parse_ini_file como fallback
    if (empty($env)) {
        $env = parse_ini_file($envFile);
        if ($env === false) {
            $env = [];
        }
    }
    
    // Detectar si estamos en producción (hosting) o desarrollo local
    $isProduction = (
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '.local') === false
    );
    
    if ($isProduction) {
        // PRODUCCIÓN: Usar credenciales de .env
        // Verificar que estén definidas (pueden estar vacías pero deben existir)
        $dbHost = trim($env['DB_HOST'] ?? '');
        $dbName = trim($env['DB_NAME'] ?? '');
        $dbUser = trim($env['DB_USER'] ?? '');
        $dbPass = $env['DB_PASS'] ?? ''; // Puede ser string vacío
        
        // Si no están configuradas, mostrar error claro con instrucciones
        if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
            die('ERROR: Las credenciales de base de datos deben estar configuradas en el archivo .env para producción.<br><br>' .
                'Por favor, edita el archivo .env en la raíz del proyecto y asegúrate de que estas líneas estén descomentadas y tengan valores:<br>' .
                'DB_HOST=localhost<br>' .
                'DB_NAME=tu_base_datos<br>' .
                'DB_USER=tu_usuario<br>' .
                'DB_PASS=tu_contraseña<br><br>' .
                'Si estás en desarrollo local, el sistema detectará automáticamente localhost y usará credenciales por defecto.');
        }
        
        define('DB_HOST', $dbHost);
        define('DB_NAME', $dbName);
        define('DB_USER', $dbUser);
        define('DB_PASS', $dbPass);
    } else {
        // DESARROLLO LOCAL: SIEMPRE usar credenciales de XAMPP (ignorar .env si tiene credenciales de producción)
        // Esto previene errores cuando el .env tiene credenciales de producción
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'mariscales_db');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        
        error_log("INFO: Usando credenciales de desarrollo local (XAMPP) - host: localhost, db: mariscales_db, user: root");
    }
} else {
    // Valores por defecto si no existe .env
    // Detectar entorno
    $isProduction = (
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') === false
    );
    
    if ($isProduction) {
        // Producción sin .env - ERROR: No permitir
        die('ERROR CRÍTICO: El archivo .env es obligatorio en producción. Por favor, crea el archivo .env con las credenciales de base de datos.');
    } else {
        // Desarrollo local sin .env
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'mariscales_db');
        define('DB_USER', 'root');
        define('DB_PASS', '');
    }
}

// Application paths
define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_ROOT', dirname(dirname(__DIR__)) . '/public');

// Detect URL root dynamically (works in both local and hosting)
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? '';
    
    // Obtener el script actual y el request URI
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    
    // Detectar si estamos en desarrollo local (XAMPP)
    $isLocal = (
        strpos($host, 'localhost') !== false || 
        strpos($host, '127.0.0.1') !== false ||
        strpos($documentRoot, 'xampp') !== false ||
        strpos($documentRoot, 'htdocs') !== false
    );
    
    // Si estamos en local y el script contiene la subcarpeta, extraerla
    if ($isLocal) {
        // El script puede ser: /prueba-php/public/index.php o /public/index.php
        if (preg_match('#/([^/]+)/public/#', $script, $matches)) {
            // Hay una subcarpeta antes de /public/ (ej: /prueba-php/public/)
            $subfolder = $matches[1];
            $path = '/' . $subfolder . '/public';
        } elseif (preg_match('#/public/#', $script)) {
            // Solo /public/ sin subcarpeta
            $path = '/public';
        } elseif (preg_match('#/([^/]+)/public/#', $requestUri, $matches)) {
            // Intentar extraer de REQUEST_URI si el script no tiene la info
            $subfolder = $matches[1];
            $path = '/' . $subfolder . '/public';
        } else {
            // Por defecto en local, asumir que puede haber subcarpeta
            // Intentar detectar desde DOCUMENT_ROOT
            if (preg_match('#/([^/]+)/public#', $documentRoot, $matches)) {
                $subfolder = $matches[1];
                $path = '/' . $subfolder . '/public';
            } else {
                $path = '/public';
            }
        }
    } else {
        // Producción: el document root suele estar en public/ directamente
        if (strpos($script, '/public/index.php') !== false) {
            $beforePublic = substr($script, 0, strpos($script, '/public/'));
            $path = ($beforePublic ? $beforePublic : '') . '/public';
        } elseif ($script === '/index.php') {
            $path = '';
        } else {
            $path = '/public';
        }
    }
    
    $fullUrl = $protocol . $host . $path;
    
    return $fullUrl;
}

// Define URL_ROOT - detecta automáticamente el entorno
if (!defined('URL_ROOT')) {
    $baseUrl = getBaseUrl();
    // Normalizar: asegurar que termine sin barra
    $baseUrl = rtrim($baseUrl, '/');
    define('URL_ROOT', $baseUrl);
}

// Application settings
define('SITE_NAME', 'Filá Mariscales de Caballeros Templarios de Elche');
define('APP_VERSION', '1.0.0');

// Error reporting (set to 0 in production)
// E_STRICT está deprecado y eliminado en PHP 8, usar E_ALL directamente
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporal para debug
ini_set('display_startup_errors', 1);

// Timezone
date_default_timezone_set('Europe/Madrid');

// Autoload classes
spl_autoload_register(function($className) {
    $paths = [
        dirname(__DIR__) . "/models/",
        dirname(__DIR__) . "/controllers/",
        dirname(__DIR__) . "/services/",
        dirname(__DIR__) . "/helpers/"
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Helper functions
require_once 'helpers.php';

// Cargar servicios esenciales
require_once dirname(__DIR__) . '/services/ErrorHandler.php';
require_once dirname(__DIR__) . '/services/InputSanitizer.php';
require_once dirname(__DIR__) . '/services/Validator.php';
require_once dirname(__DIR__) . '/services/CacheHelper.php';
require_once dirname(__DIR__) . '/services/ImageOptimizer.php';
require_once dirname(__DIR__) . '/services/FileUploadService.php';

// Cargar helpers adicionales
require_once dirname(__DIR__) . '/helpers/CsrfHelper.php';
