<?php
/**
 * Prueba sin redirecciones - acceso directo
 * Accede a: https://tudominio.com/public/test-no-redirect.php?url=historia
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Prueba Sin Redirecciones</h1>";

// Simular directamente la ruta
if (isset($_GET['url'])) {
    $_GET['url'] = $_GET['url'];
} else {
    $_GET['url'] = 'historia'; // Por defecto
}

echo "<h2>URL Simulada</h2>";
echo "<pre>";
echo "\$_GET['url']: " . htmlspecialchars($_GET['url']) . "\n";
echo "</pre>";

// Parsear la URL
$url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

echo "<h2>URL Parseado</h2>";
echo "<pre>" . print_r($url, true) . "</pre>";

if ($url[0] === 'historia') {
    echo "<h2>✅ Ruta 'historia' detectada correctamente</h2>";
    echo "<p>El router debería funcionar correctamente.</p>";
    echo "<p><a href='?url=historia'>Probar de nuevo</a></p>";
} else {
    echo "<h2>Prueba con diferentes rutas</h2>";
    echo "<ul>";
    echo "<li><a href='?url=historia'>Historia</a></li>";
    echo "<li><a href='?url=cart/info'>Cart Info</a></li>";
    echo "<li><a href='?url='>Raíz</a></li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";

