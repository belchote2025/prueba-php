<?php
/**
 * Archivo de prueba para verificar el routing
 * Accede a: https://tudominio.com/public/test-route.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Prueba de Routing</h1>";

echo "<h2>Variables del Servidor</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NO DEFINIDO') . "\n";
echo "\$_GET['url']: " . (isset($_GET['url']) ? $_GET['url'] : 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>Pruebas de Acceso Directo</h2>";
echo "<p><a href='" . URL_ROOT . "/cart/info' target='_blank'>Probar: " . URL_ROOT . "/cart/info</a></p>";
echo "<p><a href='" . URL_ROOT . "/order/wishlist/info' target='_blank'>Probar: " . URL_ROOT . "/order/wishlist/info</a></p>";

echo "<h2>Prueba con Fetch desde JavaScript</h2>";
echo "<button onclick='testFetch()'>Probar Fetch Cart</button>";
echo "<button onclick='testFetchWishlist()'>Probar Fetch Wishlist</button>";
echo "<div id='result'></div>";

echo "<script>";
echo "function testFetch() {";
echo "    var url = '" . URL_ROOT . "/cart/info';";
echo "    console.log('Testing fetch to:', url);";
echo "    fetch(url)";
echo "        .then(response => {";
echo "            console.log('Response status:', response.status);";
echo "            console.log('Response headers:', response.headers);";
echo "            return response.text();";
echo "        })";
echo "        .then(text => {";
echo "            console.log('Response text:', text);";
echo "            document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';";
echo "        })";
echo "        .catch(error => {";
echo "            console.error('Error:', error);";
echo "            document.getElementById('result').innerHTML = '<p style=\"color:red;\">Error: ' + error + '</p>';";
echo "        });";
echo "}";
echo "function testFetchWishlist() {";
echo "    var url = '" . URL_ROOT . "/order/wishlist/info';";
echo "    console.log('Testing fetch to:', url);";
echo "    fetch(url)";
echo "        .then(response => {";
echo "            console.log('Response status:', response.status);";
echo "            return response.text();";
echo "        })";
echo "        .then(text => {";
echo "            console.log('Response text:', text);";
echo "            document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';";
echo "        })";
echo "        .catch(error => {";
echo "            console.error('Error:', error);";
echo "            document.getElementById('result').innerHTML = '<p style=\"color:red;\">Error: ' + error + '</p>';";
echo "        });";
echo "}";
echo "</script>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (test-route.php) después de completar las pruebas por seguridad.</p>";

