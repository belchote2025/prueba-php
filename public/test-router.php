<?php
/**
 * Archivo de prueba para verificar el routing
 * Accede a: https://tudominio.com/public/test-router.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Prueba de Router</h1>";

echo "<h2>URL Parseado Manualmente</h2>";
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [''];

echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "\$_GET['url']: " . (isset($_GET['url']) ? $_GET['url'] : 'NO DEFINIDO') . "\n";
echo "URL parseado: " . print_r($url, true) . "\n";
echo "</pre>";

echo "<h2>Simular Router</h2>";
if (!empty($url[0])) {
    if ($url[0] === 'cart' && isset($url[1]) && $url[1] === 'info') {
        echo "<p style='color: green;'>✅ Ruta 'cart/info' detectada correctamente</p>";
        echo "<p>El router debería llamar a: <code>\$cartController->getCartInfo()</code></p>";
    } elseif ($url[0] === 'order' && isset($url[1]) && $url[1] === 'wishlist' && isset($url[2]) && $url[2] === 'info') {
        echo "<p style='color: green;'>✅ Ruta 'order/wishlist/info' detectada correctamente</p>";
        echo "<p>El router debería llamar a: <code>\$orderController->getWishlistInfo()</code></p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Ruta no reconocida: " . htmlspecialchars($url[0]) . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ No hay URL definida (ruta raíz)</p>";
}

echo "<h2>Prueba Directa del Router</h2>";
echo "<p><a href='" . URL_ROOT . "/cart/info' target='_blank'>Probar: " . URL_ROOT . "/cart/info</a></p>";
echo "<p>Si funciona, deberías ver JSON con información del carrito.</p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar las pruebas por seguridad.</p>";

