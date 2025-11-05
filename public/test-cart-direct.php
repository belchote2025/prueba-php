<?php
/**
 * Prueba directa de la ruta cart/info
 * Accede a: https://tudominio.com/public/test-cart-direct.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';
require_once 'src/controllers/CartController.php';

echo "<h1>🔍 Prueba Directa de Cart/Info</h1>";

// Simular la ruta cart/info
$_GET['url'] = 'cart/info';

// Parsear la URL
$url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

echo "<h2>URL Parseado</h2>";
echo "<pre>" . print_r($url, true) . "</pre>";

if ($url[0] === 'cart' && isset($url[1]) && $url[1] === 'info') {
    echo "<p style='color: green; font-size: 20px;'>✅✅✅ RUTA CART/INFO DETECTADA CORRECTAMENTE ✅✅✅</p>";
    
    echo "<h2>Probando CartController->getCartInfo()</h2>";
    try {
        $cartController = new CartController();
        $cartController->getCartInfo();
        echo "<p style='color: green;'>✅ Método ejecutado correctamente</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ La ruta no se parseó correctamente</p>";
}

echo "<hr>";
echo "<h2>Ahora prueba acceder directamente a:</h2>";
echo "<p><a href='" . URL_ROOT . "/cart/info' target='_blank'>" . URL_ROOT . "/cart/info</a></p>";
echo "<p>Si funciona, deberías ver JSON con información del carrito.</p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";

