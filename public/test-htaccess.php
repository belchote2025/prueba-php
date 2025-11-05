<?php
/**
 * Archivo de prueba para verificar cómo Apache procesa las URLs
 * Accede a: https://tudominio.com/public/test-htaccess.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

echo "<h1>🔍 Diagnóstico de Apache .htaccess</h1>";

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

echo "<h2>Parámetros GET</h2>";
echo "<pre>";
echo "\$_GET: " . print_r($_GET, true) . "\n";
echo "\$_GET['url']: " . (isset($_GET['url']) ? $_GET['url'] : 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>URL_ROOT</h2>";
chdir(dirname(__DIR__));
require_once 'src/config/config.php';
echo "<pre>";
echo "URL_ROOT: " . URL_ROOT . "\n";
echo "</pre>";

echo "<h2>Pruebas de Rutas</h2>";
echo "<p><a href='" . URL_ROOT . "/cart/info' target='_blank'>Probar: " . URL_ROOT . "/cart/info</a></p>";
echo "<p>Si funciona, deberías ver JSON con información del carrito.</p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar las pruebas por seguridad.</p>";

