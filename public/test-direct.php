<?php
/**
 * Prueba directa del router sin .htaccess
 * Accede a: https://tudominio.com/public/test-direct.php?url=cart/info
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';
require_once 'src/controllers/Controller.php';
require_once 'src/controllers/Pages.php';
require_once 'src/controllers/CartController.php';

echo "<h1>🔍 Prueba Directa del Router</h1>";

// Simular la URL que debería venir del .htaccess
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [''];

echo "<h2>URL Parseado</h2>";
echo "<pre>";
echo "\$_GET['url']: " . (isset($_GET['url']) ? $_GET['url'] : 'NO DEFINIDO') . "\n";
echo "URL parseado: " . print_r($url, true) . "\n";
echo "</pre>";

if (!empty($url[0]) && $url[0] === 'cart' && isset($url[1]) && $url[1] === 'info') {
    echo "<h2>Probando CartController->getCartInfo()</h2>";
    try {
        $cartController = new CartController();
        $cartController->getCartInfo();
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<h2>Prueba Manual</h2>";
    echo "<p>Accede con: <code>?url=cart/info</code></p>";
    echo "<p><a href='?url=cart/info'>Probar cart/info</a></p>";
    echo "<p><a href='?url=order/wishlist/info'>Probar order/wishlist/info</a></p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar las pruebas.</p>";

