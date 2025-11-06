<?php
/**
 * Script de Diagnóstico - Verificar .env
 * Eliminar este archivo después de usar
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico .env</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #dc143c; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #004085; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .path { font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico de Archivo .env</h1>
        
        <?php
        // Ruta donde busca el archivo
        $envFile = dirname(__DIR__) . '/.env';
        $envFileAbsolute = realpath($envFile);
        
        echo "<h2>📁 Información de Rutas</h2>";
        echo "<p><strong>Ruta esperada:</strong> <span class='path'>" . htmlspecialchars($envFile) . "</span></p>";
        echo "<p><strong>Ruta absoluta:</strong> <span class='path'>" . ($envFileAbsolute ? htmlspecialchars($envFileAbsolute) : 'NO ENCONTRADA') . "</span></p>";
        
        // Verificar si existe
        echo "<h2>✅ Verificación de Archivo</h2>";
        if (file_exists($envFile)) {
            echo "<div class='success'>✓ El archivo .env EXISTE</div>";
            
            // Verificar permisos
            $perms = fileperms($envFile);
            $readable = is_readable($envFile);
            echo "<p><strong>Permisos:</strong> " . substr(sprintf('%o', $perms), -4) . "</p>";
            echo "<p><strong>Legible:</strong> " . ($readable ? '✓ SÍ' : '✗ NO') . "</p>";
            
            // Leer contenido
            echo "<h2>📄 Contenido del Archivo</h2>";
            $content = file_get_contents($envFile);
            
            if ($content === false) {
                echo "<div class='error'>✗ No se pudo leer el archivo</div>";
            } else {
                echo "<pre>" . htmlspecialchars($content) . "</pre>";
                
                // Parsear .env de forma más robusta
                echo "<h2>🔧 Análisis de Variables</h2>";
                $env = [];
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                if ($lines !== false) {
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line) || strpos($line, '#') === 0) {
                            continue;
                        }
                        if (strpos($line, '=') !== false) {
                            list($key, $value) = explode('=', $line, 2);
                            $key = trim($key);
                            $value = trim($value);
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
                
                // Fallback a parse_ini_file
                if (empty($env)) {
                    $env = parse_ini_file($envFile);
                    if ($env === false) {
                        $env = [];
                    }
                }
                
                if (empty($env)) {
                    echo "<div class='error'>✗ Error al parsear el archivo .env (ambos métodos fallaron)</div>";
                } else {
                    echo "<div class='info'>Variables encontradas:</div>";
                    echo "<ul>";
                    foreach ($env as $key => $value) {
                        $displayValue = ($key === 'DB_PASS') ? str_repeat('*', strlen($value)) : $value;
                        $isEmpty = empty($value);
                        $status = $isEmpty ? '⚠️ VACÍA' : '✓ OK';
                        echo "<li><strong>$key</strong> = <code>$displayValue</code> $status</li>";
                    }
                    echo "</ul>";
                    
                    // Verificar credenciales de producción
                    echo "<h2>🎯 Verificación de Credenciales</h2>";
                    $dbHost = trim($env['DB_HOST'] ?? '');
                    $dbName = trim($env['DB_NAME'] ?? '');
                    $dbUser = trim($env['DB_USER'] ?? '');
                    $dbPass = $env['DB_PASS'] ?? '';
                    
                    $allOk = !empty($dbHost) && !empty($dbName) && !empty($dbUser);
                    
                    if ($allOk) {
                        echo "<div class='success'>✓ Todas las credenciales están configuradas</div>";
                        echo "<ul>";
                        echo "<li>DB_HOST: " . htmlspecialchars($dbHost) . "</li>";
                        echo "<li>DB_NAME: " . htmlspecialchars($dbName) . "</li>";
                        echo "<li>DB_USER: " . htmlspecialchars($dbUser) . "</li>";
                        echo "<li>DB_PASS: " . (empty($dbPass) ? '⚠️ VACÍA' : '✓ Configurada (' . strlen($dbPass) . ' caracteres)') . "</li>";
                        echo "</ul>";
                    } else {
                        echo "<div class='error'>✗ Faltan credenciales:</div>";
                        echo "<ul>";
                        echo "<li>DB_HOST: " . (empty($dbHost) ? '✗ FALTA' : '✓ ' . htmlspecialchars($dbHost)) . "</li>";
                        echo "<li>DB_NAME: " . (empty($dbName) ? '✗ FALTA' : '✓ ' . htmlspecialchars($dbName)) . "</li>";
                        echo "<li>DB_USER: " . (empty($dbUser) ? '✗ FALTA' : '✓ ' . htmlspecialchars($dbUser)) . "</li>";
                        echo "<li>DB_PASS: " . (empty($dbPass) ? '✗ FALTA' : '✓ Configurada') . "</li>";
                        echo "</ul>";
                    }
                }
            }
        } else {
            echo "<div class='error'>✗ El archivo .env NO EXISTE</div>";
            echo "<h2>📝 Instrucciones</h2>";
            echo "<ol>";
            echo "<li>Crea el archivo <span class='path'>.env</span> en la raíz del proyecto</li>";
            echo "<li>Agrega este contenido:</li>";
            echo "</ol>";
            echo "<pre>DB_HOST=localhost
DB_NAME=u600265163_HAggBlS0j_pruebaphp2
DB_USER=u600265163_HAggBlS0j_pruebaphp2
DB_PASS=Belchote1#</pre>";
        }
        
        // Información del servidor
        echo "<h2>🖥️ Información del Servidor</h2>";
        echo "<ul>";
        echo "<li><strong>HTTP_HOST:</strong> " . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A') . "</li>";
        echo "<li><strong>SERVER_NAME:</strong> " . htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'N/A') . "</li>";
        echo "<li><strong>SCRIPT_FILENAME:</strong> " . htmlspecialchars($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "</li>";
        echo "<li><strong>DOCUMENT_ROOT:</strong> " . htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</li>";
        echo "</ul>";
        
        // Verificar directorio raíz
        echo "<h2>📂 Estructura de Directorios</h2>";
        $rootDir = dirname(__DIR__);
        echo "<p><strong>Directorio raíz del proyecto:</strong> <span class='path'>" . htmlspecialchars($rootDir) . "</span></p>";
        
        if (is_dir($rootDir)) {
            echo "<p><strong>Contenido del directorio raíz:</strong></p>";
            $items = scandir($rootDir);
            echo "<ul>";
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $path = $rootDir . '/' . $item;
                $type = is_dir($path) ? '📁' : '📄';
                $highlight = ($item === '.env') ? ' <strong style="color: #dc143c;">← DEBE ESTAR AQUÍ</strong>' : '';
                echo "<li>$type $item$highlight</li>";
            }
            echo "</ul>";
        }
        ?>
        
        <hr>
        <p><small>⚠️ <strong>IMPORTANTE:</strong> Elimina este archivo después de usar por seguridad.</small></p>
    </div>
</body>
</html>

