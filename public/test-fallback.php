<?php
/**
 * Prueba directa del fallback
 * Accede a: https://tudominio.com/public/test-fallback.php?test=cart/info
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Prueba del Fallback</h1>";

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$requestUri = strtok($requestUri, '?');

echo "<h2>REQUEST_URI Original</h2>";
echo "<pre>" . htmlspecialchars($requestUri) . "</pre>";

echo "<h2>Simulación del Fallback</h2>";
$_GET['url'] = null; // Resetear

if (preg_match('#/public/(.+)$#', $requestUri, $matches)) {
    $extracted = $matches[1];
    echo "<p style='color: green;'>✅ Extraído: <code>" . htmlspecialchars($extracted) . "</code></p>";
    $_GET['url'] = $extracted;
} elseif (preg_match('#^/public/?$#', $requestUri)) {
    echo "<p style='color: blue;'>ℹ️ Es la raíz /public</p>";
    $_GET['url'] = '';
} elseif (strpos($requestUri, '/public/') === false) {
    if ($requestUri !== '/' && $requestUri !== '') {
        $extracted = ltrim($requestUri, '/');
        echo "<p style='color: orange;'>⚠️ Sin /public/, extraído: <code>" . htmlspecialchars($extracted) . "</code></p>";
        $_GET['url'] = $extracted;
    } else {
        echo "<p style='color: blue;'>ℹ️ Es la raíz</p>";
        $_GET['url'] = '';
    }
}

echo "<h2>URL Parseado</h2>";
if (isset($_GET['url'])) {
    $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    echo "<pre>" . print_r($url, true) . "</pre>";
    
    if ($url[0] === 'cart' && isset($url[1]) && $url[1] === 'info') {
        echo "<p style='color: green; font-size: 20px;'>✅✅✅ RUTA CART/INFO DETECTADA CORRECTAMENTE ✅✅✅</p>";
        echo "<p>El router debería funcionar correctamente.</p>";
    }
} else {
    echo "<p style='color: red;'>❌ No se pudo extraer la URL</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";

