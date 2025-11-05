<?php
/**
 * Archivo de prueba para verificar acceso y configuración
 * Accede a: https://tudominio.com/test-access.php
 * IMPORTANTE: Elimina este archivo después de probar
 */

echo "<h1>✅ Prueba de Acceso</h1>";
echo "<p><strong>Estado:</strong> El servidor está respondiendo correctamente</p>";

echo "<h2>Información del Servidor</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "\n";
echo "</pre>";

echo "<h2>Verificación de Archivos</h2>";
$files = [
    '.htaccess' => 'Raíz del proyecto',
    'public/index.php' => 'Punto de entrada principal',
    'src/config/config.php' => 'Configuración',
    'public/.htaccess' => 'Configuración de public'
];

foreach ($files as $file => $desc) {
    $exists = file_exists($file);
    $readable = $exists ? (is_readable($file) ? 'Sí' : 'No') : 'N/A';
    $color = $exists ? 'green' : 'red';
    echo "<p style='color: $color;'>";
    echo "<strong>$file</strong> ($desc): ";
    echo $exists ? "✅ Existe (Legible: $readable)" : "❌ No existe";
    echo "</p>";
}

echo "<h2>Prueba de Conexión a Base de Datos</h2>";
if (file_exists('src/config/config.php')) {
    require_once 'src/config/config.php';
    
    echo "<p>Intentando conectar a la base de datos...</p>";
    try {
        $db = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS
        );
        echo "<p style='color: green;'>✅ <strong>Conexión exitosa a la base de datos</strong></p>";
        echo "<p>Host: " . DB_HOST . "</p>";
        echo "<p>Base de datos: " . DB_NAME . "</p>";
        echo "<p>Usuario: " . DB_USER . "</p>";
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ <strong>Error de conexión:</strong> " . $e->getMessage() . "</p>";
        echo "<p>Verifica las credenciales en src/config/config.php</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No se puede cargar la configuración</p>";
}

echo "<h2>Prueba de Acceso a public/index.php</h2>";
if (file_exists('public/index.php')) {
    echo "<p>✅ El archivo public/index.php existe</p>";
    echo "<p><a href='public/index.php'>Intentar acceder a public/index.php</a></p>";
} else {
    echo "<p style='color: red;'>❌ El archivo public/index.php no existe</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (test-access.php) después de completar las pruebas por seguridad.</p>";

