<?php
/**
 * Script de Verificación Completa de Base de Datos
 * Muestra información detallada sobre el estado de la conexión y las tablas
 */
chdir(dirname(__DIR__));
require_once 'src/config/config.php';

// Permitir HTML o texto plano según preferencia
$useHtml = isset($_GET['html']) && $_GET['html'] == '1';

if ($useHtml) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Verificación de Base de Datos</title>';
    echo '<style>body{font-family:monospace;padding:20px;background:#f5f5f5;} .ok{color:green;} .error{color:red;} .info{color:blue;} table{border-collapse:collapse;width:100%;margin:10px 0;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#4CAF50;color:white;}</style></head><body>';
    $nl = '<br>';
} else {
    header('Content-Type: text/plain; charset=utf-8');
    $nl = "\n";
}

// Forzar timeouts bajos para evitar que quede colgado
@ini_set('default_socket_timeout', '5');
@ini_set('mysql.connect_timeout', '5');
@ini_set('pdo_mysql.default_socket_timeout', '5');

echo "=== VERIFICACIÓN COMPLETA DE BASE DE DATOS ==={$nl}{$nl}";

// Información del entorno
echo "📋 INFORMACIÓN DEL ENTORNO{$nl}";
echo str_repeat('=', 50) . $nl;
echo "PHP Version: " . PHP_VERSION . $nl;
echo "Servidor: " . ($_SERVER['HTTP_HOST'] ?? 'CLI') . $nl;
echo "Fecha/Hora: " . date('Y-m-d H:i:s') . $nl;

// Verificar archivo .env
$envFile = dirname(__DIR__) . '/.env';
echo $nl . "Archivo .env: " . (file_exists($envFile) ? "✓ Existe" : "✗ No existe") . $nl;

// Constantes de base de datos
echo $nl . "🔧 CONFIGURACIÓN DE BASE DE DATOS{$nl}";
echo str_repeat('=', 50) . $nl;
echo 'DB_HOST: ' . (defined('DB_HOST') ? DB_HOST : '❌ NO DEFINIDO') . $nl;
echo 'DB_NAME: ' . (defined('DB_NAME') ? DB_NAME : '❌ NO DEFINIDO') . $nl;
echo 'DB_USER: ' . (defined('DB_USER') ? DB_USER : '❌ NO DEFINIDO') . $nl;
echo 'DB_PASS: ' . (defined('DB_PASS') ? (strlen(DB_PASS) > 0 ? '***' . substr(DB_PASS, -2) : 'VACÍO') : '❌ NO DEFINIDO') . $nl;

// Intentar conectar
echo $nl . "🔌 INTENTANDO CONECTAR...{$nl}";
echo str_repeat('=', 50) . $nl;

try {
    // Limpiar DB_HOST
    $host = DB_HOST;
    if (strpos($host, ':') !== false) {
        $parts = explode(':', $host);
        $host = $parts[0];
    }
    $port = getenv('DB_PORT');
    
    $dsn = 'mysql:host=' . $host . ($port ? (';port=' . $port) : '') . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    echo "DSN: {$dsn}" . $nl;
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    echo "✅ CONEXIÓN EXITOSA{$nl}{$nl}";
    
    // Información del servidor MySQL
    echo "📊 INFORMACIÓN DEL SERVIDOR MYSQL{$nl}";
    echo str_repeat('=', 50) . $nl;
    
    $stmt = $pdo->query('SELECT VERSION() AS version, NOW() AS server_time, DATABASE() AS current_db');
    $serverInfo = $stmt->fetch();
    echo "Versión MySQL: " . ($serverInfo['version'] ?? 'N/A') . $nl;
    echo "Hora del servidor: " . ($serverInfo['server_time'] ?? 'N/A') . $nl;
    echo "Base de datos actual: " . ($serverInfo['current_db'] ?? 'N/A') . $nl;
    
    // Listar todas las tablas
    echo $nl . "📋 TABLAS EN LA BASE DE DATOS{$nl}";
    echo str_repeat('=', 50) . $nl;
    
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "⚠️ No se encontraron tablas en la base de datos{$nl}";
        echo "💡 Es posible que necesites importar el archivo schema.sql{$nl}";
    } else {
        echo "Total de tablas encontradas: " . count($tables) . $nl . $nl;
        
        if ($useHtml) {
            echo '<table><tr><th>Tabla</th><th>Registros</th><th>Estado</th></tr>';
        }
        
        // Tablas principales que deberían existir
        $mainTables = [
            'users' => 'Usuarios',
            'usuarios' => 'Usuarios',
            'noticias' => 'Noticias',
            'eventos' => 'Eventos',
            'galeria' => 'Galería',
            'productos' => 'Productos',
            'pedidos' => 'Pedidos',
            'contactos' => 'Contactos',
            'newsletter' => 'Newsletter',
            'newsletter_subscriptions' => 'Suscripciones Newsletter',
            'documentos' => 'Documentos',
            'visitas' => 'Visitas',
            'configuracion' => 'Configuración'
        ];
        
        $foundTables = [];
        $missingTables = [];
        
        foreach ($mainTables as $table => $name) {
            if (in_array($table, $tables)) {
                $foundTables[] = $table;
                try {
                    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                    $count = $countStmt->fetch()['count'];
                    $status = "✓ OK ({$count} registros)";
                    if ($useHtml) {
                        echo "<tr><td>{$name}</td><td>{$count}</td><td class='ok'>{$status}</td></tr>";
                    } else {
                        echo "  ✓ {$name} ({$table}): {$count} registros{$nl}";
                    }
                } catch (Exception $e) {
                    $status = "⚠ Error al contar";
                    if ($useHtml) {
                        echo "<tr><td>{$name}</td><td>N/A</td><td class='error'>{$status}</td></tr>";
                    } else {
                        echo "  ⚠ {$name} ({$table}): Error al contar registros{$nl}";
                    }
                }
            } else {
                $missingTables[] = $table;
            }
        }
        
        // Mostrar otras tablas encontradas
        $otherTables = array_diff($tables, array_keys($mainTables));
        if (!empty($otherTables)) {
            echo $nl . "Otras tablas encontradas:{$nl}";
            foreach ($otherTables as $table) {
                try {
                    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                    $count = $countStmt->fetch()['count'];
                    if ($useHtml) {
                        echo "<tr><td>{$table}</td><td>{$count}</td><td class='info'>Otra tabla</td></tr>";
                    } else {
                        echo "  ℹ {$table}: {$count} registros{$nl}";
                    }
                } catch (Exception $e) {
                    if ($useHtml) {
                        echo "<tr><td>{$table}</td><td>N/A</td><td class='error'>Error</td></tr>";
                    } else {
                        echo "  ⚠ {$table}: Error al contar{$nl}";
                    }
                }
            }
        }
        
        if ($useHtml) {
            echo '</table>';
        }
        
        // Advertencias sobre tablas faltantes
        if (!empty($missingTables)) {
            echo $nl . "⚠️ TABLAS FALTANTES (opcionales):{$nl}";
            foreach ($missingTables as $table) {
                echo "  - {$mainTables[$table]} ({$table}){$nl}";
            }
        }
    }
    
    // Prueba de consulta simple
    echo $nl . "🧪 PRUEBA DE CONSULTAS{$nl}";
    echo str_repeat('=', 50) . $nl;
    
    // Probar consulta SELECT
    try {
        $stmt = $pdo->query('SELECT 1 as test');
        $result = $stmt->fetch();
        echo "✅ Consulta SELECT: OK{$nl}";
    } catch (Exception $e) {
        echo "❌ Consulta SELECT: Error - " . $e->getMessage() . $nl;
    }
    
    // Probar INSERT (rollback)
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->query("SELECT 'test' as test");
        $pdo->rollBack();
        echo "✅ Transacciones: OK{$nl}";
    } catch (Exception $e) {
        echo "❌ Transacciones: Error - " . $e->getMessage() . $nl;
    }
    
    echo $nl . "✅ ESTADO GENERAL: BASE DE DATOS FUNCIONANDO CORRECTAMENTE{$nl}";
    
} catch (Throwable $e) {
    http_response_code(500);
    echo "❌ ERROR DE CONEXIÓN{$nl}";
    echo str_repeat('=', 50) . $nl;
    echo "Tipo: " . get_class($e) . $nl;
    echo "Mensaje: " . $e->getMessage() . $nl;
    echo $nl . "💡 SUGERENCIAS:{$nl}";
    echo "  1. Verifica que las credenciales en .env o config.php sean correctas{$nl}";
    echo "  2. Verifica que el servidor MySQL esté corriendo{$nl}";
    echo "  3. Verifica que la base de datos exista{$nl}";
    echo "  4. Verifica que el usuario tenga permisos de acceso{$nl}";
}

if ($useHtml) {
    echo '<hr><p><small>Para ver en formato texto, accede sin el parámetro ?html=1</small></p></body></html>';
} else {
    echo $nl . str_repeat('=', 50) . $nl;
    echo "💡 Para ver en formato HTML, agrega ?html=1 a la URL{$nl}";
}


