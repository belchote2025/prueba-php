<?php
/**
 * Script para insertar videos de ejemplo en la base de datos
 * Ejecutar una vez para crear videos de ejemplo que pueden ser modificados después
 */

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/models/Database.php';
require_once __DIR__ . '/../src/models/Video.php';

try {
    $db = new Database();
    $videoModel = new Video();
    
    // Verificar si ya hay videos
    $existingVideos = $videoModel->getAllVideos(1, 1, null, null);
    if (!empty($existingVideos)) {
        echo "<h2>Ya existen videos en la base de datos.</h2>";
        echo "<p>Total de videos encontrados: " . count($existingVideos) . "</p>";
        echo "<p>Si quieres insertar videos de ejemplo, elimina primero los existentes o modifícalos desde el panel de administración.</p>";
        echo "<p><a href='" . URL_ROOT . "/admin/videos' class='btn btn-primary'>Ir a Gestión de Videos</a></p>";
        echo "<p><a href='" . URL_ROOT . "/galeria-multimedia' class='btn btn-secondary'>Ver Galería Multimedia</a></p>";
        exit;
    }
    
    // Videos de ejemplo
    $videosEjemplo = [
        [
            'titulo' => 'Desfile Principal 2024 - Filá Mariscales',
            'descripcion' => 'Video del desfile principal de las Fiestas de Moros y Cristianos 2024. La Filá Mariscales en todo su esplendor.',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Ejemplo - reemplazar con URL real
            'url_thumbnail' => '',
            'tipo' => 'youtube',
            'categoria' => 'desfiles',
            'evento_id' => null,
            'duracion' => 180,
            'activo' => 1
        ],
        [
            'titulo' => 'Entrada de Bandas 2024',
            'descripcion' => 'Nuestras bandas de música en la tradicional entrada de bandas durante las fiestas.',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Ejemplo - reemplazar con URL real
            'url_thumbnail' => '',
            'tipo' => 'youtube',
            'categoria' => 'bandas',
            'evento_id' => null,
            'duracion' => 240,
            'activo' => 1
        ],
        [
            'titulo' => 'Historia de la Filá Mariscales',
            'descripcion' => 'Documental sobre la historia y tradición de la Filá Mariscales de Caballeros Templarios.',
            'url_video' => 'https://vimeo.com/123456789', // Ejemplo - reemplazar con URL real
            'url_thumbnail' => '',
            'tipo' => 'vimeo',
            'categoria' => 'historia',
            'evento_id' => null,
            'duracion' => 600,
            'activo' => 1
        ],
        [
            'titulo' => 'Cena Anual de Hermandad 2024',
            'descripcion' => 'Momentos especiales de nuestra cena anual de hermandad donde compartimos tradición y camaradería.',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Ejemplo - reemplazar con URL real
            'url_thumbnail' => '',
            'tipo' => 'youtube',
            'categoria' => 'eventos',
            'evento_id' => null,
            'duracion' => 300,
            'activo' => 1
        ],
        [
            'titulo' => 'Procesión de Semana Santa',
            'descripcion' => 'Participación de la Filá Mariscales en la procesión de Semana Santa.',
            'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Ejemplo - reemplazar con URL real
            'url_thumbnail' => '',
            'tipo' => 'youtube',
            'categoria' => 'procesiones',
            'evento_id' => null,
            'duracion' => 420,
            'activo' => 1
        ]
    ];
    
    $insertados = 0;
    $errores = [];
    
    foreach ($videosEjemplo as $videoData) {
        try {
            $videoId = $videoModel->createVideo($videoData);
            if ($videoId) {
                $insertados++;
                echo "<p style='color: green;'>✓ Video insertado: " . htmlspecialchars($videoData['titulo']) . " (ID: $videoId)</p>";
            } else {
                $errores[] = $videoData['titulo'];
                echo "<p style='color: red;'>✗ Error al insertar: " . htmlspecialchars($videoData['titulo']) . "</p>";
            }
        } catch (Exception $e) {
            $errores[] = $videoData['titulo'] . ' - ' . $e->getMessage();
            echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($videoData['titulo']) . " - " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>Resumen:</h3>";
    echo "<p><strong>Videos insertados:</strong> $insertados de " . count($videosEjemplo) . "</p>";
    
    if (!empty($errores)) {
        echo "<p><strong>Errores:</strong></p><ul>";
        foreach ($errores as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
    
    echo "<hr>";
    echo "<div class='alert alert-info'>";
    echo "<h4>Nota Importante:</h4>";
    echo "<p>Estos son videos de ejemplo con URLs de prueba (Rick Astley - Never Gonna Give You Up).</p>";
    echo "<p><strong>Debes editarlos desde el panel de administración y reemplazar las URLs con las reales de tus videos.</strong></p>";
    echo "</div>";
    echo "<div class='mt-3'>";
    echo "<a href='" . URL_ROOT . "/admin/videos' class='btn btn-primary me-2'>Ir a Gestión de Videos</a>";
    echo "<a href='" . URL_ROOT . "/galeria-multimedia' class='btn btn-success'>Ver Galería Multimedia</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

