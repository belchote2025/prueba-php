<?php
/**
 * Archivo de depuración para ver exactamente qué está pasando con las rutas
 * Accede a: https://tudominio.com/public/debug-routing.php?test=cart/info
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Depuración Completa de Routing</h1>";

echo "<h2>Variables del Servidor</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NO DEFINIDO') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NO DEFINIDO') . "\n";
echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>Parámetros</h2>";
echo "<pre>";
echo "\$_GET: " . print_r($_GET, true) . "\n";
echo "\$_GET['url']: " . (isset($_GET['url']) ? $_GET['url'] : 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>URL_ROOT</h2>";
echo "<pre>";
echo "URL_ROOT: " . URL_ROOT . "\n";
echo "</pre>";

echo "<h2>Simulación del Fallback en PHP</h2>";
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$requestUri = strtok($requestUri, '?');

echo "<pre>";
echo "REQUEST_URI original: " . $requestUri . "\n";

if (preg_match('#/public/(.+)$#', $requestUri, $matches)) {
    echo "✅ Match encontrado: " . $matches[1] . "\n";
} else {
    echo "❌ No se encontró match para /public/...\n";
}

if (preg_match('#^/public/?$#', $requestUri)) {
    echo "✅ Es la raíz /public\n";
}
echo "</pre>";

echo "<h2>Pruebas de Acceso Directo</h2>";
echo "<p><a href='" . URL_ROOT . "/cart/info' target='_blank'>Probar: " . URL_ROOT . "/cart/info</a></p>";
echo "<p>Si funciona, deberías ver JSON con información del carrito.</p>";

echo "<h2>Prueba con parámetro test</h2>";
if (isset($_GET['test'])) {
    $_GET['url'] = $_GET['test'];
    echo "<p>URL simulada: " . htmlspecialchars($_GET['test']) . "</p>";
    echo "<p>Parseado: " . print_r(explode('/', filter_var(rtrim($_GET['test'], '/'), FILTER_SANITIZE_URL)), true) . "</p>";
} else {
    echo "<p>Accede con: <code>?test=cart/info</code> para simular una ruta</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar las pruebas por seguridad.</p>";

