<?php
/**
 * Debug completo del .htaccess
 * Accede a: https://tudominio.com/public/test-htaccess-debug.php
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Debug Completo de .htaccess</h1>";

echo "<h2>Variables del Servidor</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>THE_REQUEST (si está disponible)</h2>";
if (isset($_SERVER['THE_REQUEST'])) {
    echo "<pre>" . htmlspecialchars($_SERVER['THE_REQUEST']) . "</pre>";
} else {
    echo "<p>THE_REQUEST no está disponible en \$_SERVER</p>";
}

echo "<h2>URL_ROOT</h2>";
echo "<pre>" . URL_ROOT . "</pre>";

echo "<h2>Prueba de Acceso Directo</h2>";
echo "<p>Haz clic en este enlace y observa qué URL aparece:</p>";
echo "<p><a href='" . URL_ROOT . "/historia' target='_blank' style='font-size: 18px;'>" . URL_ROOT . "/historia</a></p>";

echo "<h2>Prueba de URL Relativa</h2>";
echo "<p>Haz clic en este enlace relativa y observa qué pasa:</p>";
echo "<p><a href='/historia' target='_blank' style='font-size: 18px;'>/historia (relativa sin /public)</a></p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";

