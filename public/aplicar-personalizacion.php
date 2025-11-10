<?php
/**
 * Script para aplicar las personalizaciones guardadas
 * Este archivo genera CSS dinámico basado en las preferencias guardadas
 */

require_once dirname(__DIR__) . '/src/config/config.php';
require_once dirname(__DIR__) . '/src/models/Database.php';
require_once dirname(__DIR__) . '/src/models/Personalizacion.php';

header('Content-Type: text/css; charset=UTF-8');
// Cache corto para que los cambios se reflejen rápidamente
header('Cache-Control: public, max-age=60');

try {
    $personalizacionModel = new Personalizacion();
    $css = $personalizacionModel->getStylesCSS();
    echo $css;
} catch (Exception $e) {
    // Si hay error, devolver CSS por defecto
    echo ":root {\n";
    echo "    --primary-color: #8B0000;\n";
    echo "    --secondary-color: #DC143C;\n";
    echo "}\n";
}

