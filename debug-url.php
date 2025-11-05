<?php
/**
 * Archivo de depuración - Verificar URL_ROOT
 * Accede a: https://tudominio.com/public/debug-url.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

// Cargar configuración
require_once '../src/config/config.php';

echo "<h1>🔍 Depuración de URLs</h1>";
echo "<h2>Variables del Servidor</h2>";
echo "<pre>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'NO DEFINIDO') . "\n";
echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>URL_ROOT Detectado</h2>";
echo "<p><strong>URL_ROOT:</strong> <code>" . URL_ROOT . "</code></p>";

echo "<h2>Pruebas de Rutas</h2>";
echo "<p>Ruta de carrito: <code>" . URL_ROOT . "/cart/info</code></p>";
echo "<p>Ruta de wishlist: <code>" . URL_ROOT . "/order/wishlist/info</code></p>";

echo "<h2>Prueba de Fetch</h2>";
echo "<script>";
echo "console.log('URL_ROOT desde PHP:', '" . URL_ROOT . "');";
echo "console.log('Ruta carrito:', '" . URL_ROOT . "/cart/info');";
echo "console.log('Ruta wishlist:', '" . URL_ROOT . "/order/wishlist/info');";
echo "</script>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (debug-url.php) después de completar las pruebas por seguridad.</p>";

