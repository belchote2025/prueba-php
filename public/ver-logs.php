<?php
// Script para ver los últimos logs de PHP
header('Content-Type: text/html; charset=utf-8');

// Rutas posibles de logs en XAMPP
$logPaths = [
    'C:\xampp\php\logs\php_error_log',
    'C:\xampp\apache\logs\error.log',
    ini_get('error_log'),
    __DIR__ . '/../logs/php_error.log'
];

$logs = [];
foreach ($logPaths as $path) {
    if ($path && file_exists($path)) {
        $logs[$path] = file($path);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ver Logs de PHP</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .log-file { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; max-height: 500px; overflow-y: auto; }
        h2 { color: #007bff; }
    </style>
</head>
<body>
    <h1>📋 Logs de PHP</h1>
    
    <?php if (empty($logs)): ?>
        <div class="log-file">
            <p>No se encontraron archivos de log en las ubicaciones estándar.</p>
            <p>Ubicaciones buscadas:</p>
            <ul>
                <?php foreach ($logPaths as $path): ?>
                    <li><?php echo htmlspecialchars($path ?: 'NO DEFINIDO'); ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Para ver los logs manualmente:</strong></p>
            <ol>
                <li>Abre el archivo: <code>C:\xampp\php\logs\php_error_log</code></li>
                <li>O busca en: <code>C:\xampp\apache\logs\error.log</code></li>
                <li>Busca líneas que contengan "DEBUG"</li>
            </ol>
        </div>
    <?php else: ?>
        <?php foreach ($logs as $path => $lines): ?>
            <div class="log-file">
                <h2>📄 <?php echo htmlspecialchars($path); ?></h2>
                <p><strong>Últimas 50 líneas (buscando DEBUG):</strong></p>
                <pre><?php
                    $debugLines = [];
                    foreach (array_reverse($lines) as $line) {
                        if (stripos($line, 'DEBUG') !== false || stripos($line, 'ERROR') !== false || stripos($line, 'video') !== false) {
                            $debugLines[] = htmlspecialchars($line);
                            if (count($debugLines) >= 50) break;
                        }
                    }
                    echo implode('', array_reverse($debugLines));
                ?></pre>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <hr>
    <p><a href="<?php echo URL_ROOT; ?>/admin/dashboard">← Volver al Dashboard</a></p>
    <p><a href="<?php echo URL_ROOT; ?>/debug-url.php">Ver Debug de URL</a></p>
</body>
</html>

