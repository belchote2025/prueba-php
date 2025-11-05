<?php
/**
 * Archivo de diagnóstico para ver qué está pasando con las redirecciones
 * Accede a: https://tudominio.com/public/test-redirect.php
 * IMPORTANTE: Elimina este archivo después de usarlo
 */

chdir(dirname(__DIR__));
require_once 'src/config/config.php';

echo "<h1>🔍 Diagnóstico de Redirecciones</h1>";

echo "<h2>Variables del Servidor</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NO DEFINIDO') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_REFERER: " . ($_SERVER['HTTP_REFERER'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "</pre>";

echo "<h2>URL_ROOT</h2>";
echo "<pre>";
echo "URL_ROOT: " . URL_ROOT . "\n";
echo "</pre>";

echo "<h2>Prueba de Enlaces</h2>";
echo "<p>Haz clic en estos enlaces y observa qué URL aparece en la barra de direcciones:</p>";
echo "<ul>";
echo "<li><a href='" . URL_ROOT . "/historia' target='_blank'>" . URL_ROOT . "/historia</a></li>";
echo "<li><a href='" . URL_ROOT . "/cart/info' target='_blank'>" . URL_ROOT . "/cart/info</a></li>";
echo "</ul>";

echo "<h2>Prueba de URLs Relativas</h2>";
echo "<p>Estos son enlaces relativos (sin URL_ROOT):</p>";
echo "<ul>";
echo "<li><a href='/public/historia' target='_blank'>/public/historia (relativa)</a></li>";
echo "<li><a href='/historia' target='_blank'>/historia (relativa sin /public)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";

