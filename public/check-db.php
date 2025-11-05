<?php
// Comprobación de conexión a la base de datos en producción (con timeout corto y debug)
chdir(dirname(__DIR__));
require_once 'src/config/config.php';

header('Content-Type: text/plain; charset=utf-8');

// Forzar timeouts bajos para evitar que quede colgado si el host/puerto no responden
@ini_set('default_socket_timeout', '5');
@ini_set('mysql.connect_timeout', '5');
@ini_set('pdo_mysql.default_socket_timeout', '5');

echo "=== DIAGNÓSTICO DE CONEXIÓN ===\n\n";
echo "Leyendo variables de entorno...\n";
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    echo ".env existe: SÍ\n";
    echo "Contenido del .env (primeras líneas):\n";
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (array_slice($lines, 0, 6) as $line) {
        if (strpos(trim($line), '#') !== 0) {
            echo "  " . trim($line) . "\n";
        }
    }
} else {
    echo ".env existe: NO\n";
}

echo "\nConstantes PHP definidas:\n";
echo 'DB_HOST=' . (defined('DB_HOST') ? DB_HOST : 'NO DEFINIDO') . "\n";
echo 'DB_NAME=' . (defined('DB_NAME') ? DB_NAME : 'NO DEFINIDO') . "\n";
echo 'DB_USER=' . (defined('DB_USER') ? DB_USER : 'NO DEFINIDO') . "\n";
echo 'DB_PASS=' . (defined('DB_PASS') ? (strlen(DB_PASS) > 0 ? '***' . substr(DB_PASS, -2) : 'VACÍO') : 'NO DEFINIDO') . "\n";

echo "\nIntentando conectar...\n";

try {
    // Limpiar DB_HOST (quitar puerto si viene en formato host:puerto)
    $host = DB_HOST;
    if (strpos($host, ':') !== false) {
        $parts = explode(':', $host);
        $host = $parts[0];
    }
    $port = getenv('DB_PORT');
    
    $dsn = 'mysql:host=' . $host . ($port ? (';port=' . $port) : '') . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    echo "DSN: mysql:host=" . $host . ($port ? (';port=' . $port) : '') . ";dbname=" . DB_NAME . "\n";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5
    ]);
    echo "\n✓ CONECTADO\n";
    $stmt = $pdo->query('SELECT NOW() AS now');
    $row = $stmt->fetch();
    echo "OK - Hora del servidor: " . ($row['now'] ?? 'unknown') . "\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "\n✗ ERROR\n";
    echo "Tipo: " . get_class($e) . "\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
}


