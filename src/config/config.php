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
    $env = parse_ini_file($envFile);
    
    // Detectar si estamos en producción (hosting) o desarrollo local
    $isProduction = (
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '.local') === false
    );
    
    if ($isProduction) {
        // PRODUCCIÓN: Usar credenciales de hosting (pueden estar comentadas, usar valores directos)
        // Si están definidas en .env, usarlas; si no, usar las hardcodeadas para Hostinger
        define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
        define('DB_NAME', $env['DB_NAME'] ?? 'u600265163_HAggBlS0j_pruebaphp2');
        define('DB_USER', $env['DB_USER'] ?? 'u600265163_HAggBlS0j_pruebaphp2');
        define('DB_PASS', $env['DB_PASS'] ?? 'Belchote1#');
    } else {
        // DESARROLLO LOCAL: Usar credenciales de XAMPP
        define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
        define('DB_NAME', $env['DB_NAME'] ?? 'mariscales_db');
        define('DB_USER', $env['DB_USER'] ?? 'root');
        define('DB_PASS', $env['DB_PASS'] ?? '');
    }
} else {
    // Valores por defecto si no existe .env
    // Detectar entorno
    $isProduction = (
        strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === false &&
        strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') === false
    );
    
    if ($isProduction) {
        // Producción sin .env
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'u600265163_HAggBlS0j_pruebaphp2');
        define('DB_USER', 'u600265163_HAggBlS0j_pruebaphp2');
        define('DB_PASS', 'Belchote1#');
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
    
    // Detectar si estamos ejecutando desde public/index.php
    // El script será algo como: /public/index.php cuando accedemos desde /public/
    if (strpos($script, '/public/index.php') !== false) {
        // Estamos ejecutando desde public/index.php
        // Extraer la parte antes de /public/
        $beforePublic = substr($script, 0, strpos($script, '/public/'));
        // Si beforePublic está vacío, estamos en la raíz del dominio
        // URL_ROOT debe incluir /public
        if ($beforePublic === '') {
            $path = '/public';
        } else {
            // Hay una subcarpeta antes de /public/ (ej: /prueba-php/public/)
            $path = $beforePublic . '/public';
        }
    } elseif ($script === '/index.php' || (strpos($script, '/index.php') !== false && strpos($script, '/public/') === false)) {
        // El document root está configurado directamente en public/, el script es solo /index.php
        // En este caso, URL_ROOT debe ser solo el dominio (sin /public)
        $path = '';
    } else {
        // Caso por defecto: usar el request URI para detectar
        // Si el request URI contiene /public/, estamos en una estructura donde public/ está en la URL
        if (strpos($requestUri, '/public/') === 0 || strpos($requestUri, '/public') === 0) {
            $path = '/public';
        } else {
            // Por defecto, asumir que estamos en public/ y la URL base debe incluir /public
            // Esto es lo más común cuando el document root está en public_html
            $path = '/public';
        }
    }
    
    return $protocol . $host . $path;
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
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporal para debug
ini_set('display_startup_errors', 1);

// Timezone
date_default_timezone_set('Europe/Madrid');

// Autoload classes
spl_autoload_register(function($className) {
    $paths = [
        dirname(__DIR__) . "/models/",
        dirname(__DIR__) . "/controllers/"
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
