<?php
/**
 * Script de diagnóstico para verificar por qué no se muestran videos en la galería
 */

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/models/Database.php';
require_once __DIR__ . '/../src/models/Video.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Videos en Galería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; }
        .card { margin-bottom: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">🔍 Verificación de Videos en Galería</h1>

<?php
try {
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h2>1. Verificación de Conexión a Base de Datos</h2>';
    
    $db = new Database();
    echo '<p class="success">✅ Conexión a la base de datos establecida</p>';
    
    // Verificar si existe la tabla videos
    $db->query("SHOW TABLES LIKE 'videos'");
    $tableExists = $db->single();
    
    if ($tableExists) {
        echo '<p class="success">✅ La tabla "videos" existe</p>';
    } else {
        echo '<p class="error">❌ La tabla "videos" NO existe</p>';
        echo '</div></div></div></body></html>';
        exit;
    }
    echo '</div></div>';
    
    // Verificar videos directamente en la BD
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h2>2. Videos en la Base de Datos</h2>';
    
    $db->query("SELECT COUNT(*) as total FROM videos");
    $total = $db->single();
    echo '<p><strong>Total de videos en la BD:</strong> ' . $total->total . '</p>';
    
    $db->query("SELECT COUNT(*) as total FROM videos WHERE activo = 1");
    $activos = $db->single();
    echo '<p><strong>Videos activos:</strong> ' . $activos->total . '</p>';
    
    $db->query("SELECT COUNT(*) as total FROM videos WHERE activo = 0");
    $inactivos = $db->single();
    echo '<p><strong>Videos inactivos:</strong> ' . $inactivos->total . '</p>';
    
    // Obtener todos los videos
    $db->query("SELECT * FROM videos ORDER BY fecha_subida DESC LIMIT 10");
    $videosBD = $db->resultSet();
    
    if (!empty($videosBD)) {
        echo '<h3>Últimos 10 videos:</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>ID</th><th>Título</th><th>Tipo</th><th>Activo</th><th>Fecha</th></tr></thead>';
        echo '<tbody>';
        foreach ($videosBD as $v) {
            $vObj = is_object($v) ? $v : (object)$v;
            echo '<tr>';
            echo '<td>' . ($vObj->id ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($vObj->titulo ?? 'Sin título') . '</td>';
            echo '<td>' . htmlspecialchars($vObj->tipo ?? 'N/A') . '</td>';
            echo '<td>' . (($vObj->activo ?? 0) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>') . '</td>';
            echo '<td>' . ($vObj->fecha_subida ?? 'N/A') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="warning">⚠️ No hay videos en la base de datos</p>';
        echo '<p><a href="' . URL_ROOT . '/public/insertar-videos-ejemplo.php" class="btn btn-primary">Insertar Videos de Ejemplo</a></p>';
    }
    echo '</div></div>';
    
    // Probar el modelo Video
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h2>3. Prueba del Modelo Video</h2>';
    
    if (!class_exists('Video')) {
        echo '<p class="error">❌ La clase Video no existe</p>';
    } else {
        echo '<p class="success">✅ La clase Video existe</p>';
        
        try {
            $videoModel = new Video();
            echo '<p class="success">✅ Instancia de Video creada</p>';
            
            // Probar getAllVideos con activo = true
            $videosActivos = $videoModel->getAllVideos(1, 50, null, true);
            echo '<p><strong>getAllVideos(1, 50, null, true):</strong> ' . count($videosActivos) . ' videos</p>';
            
            // Probar getAllVideos con activo = null (todos)
            $todosVideos = $videoModel->getAllVideos(1, 50, null, null);
            echo '<p><strong>getAllVideos(1, 50, null, null):</strong> ' . count($todosVideos) . ' videos</p>';
            
            // Probar getTotalVideos
            $totalActivos = $videoModel->getTotalVideos(null, true);
            echo '<p><strong>getTotalVideos(null, true):</strong> ' . $totalActivos . '</p>';
            
            if (!empty($videosActivos)) {
                echo '<h3>Videos activos obtenidos por el modelo:</h3>';
                echo '<ul>';
                foreach ($videosActivos as $v) {
                    $vObj = is_object($v) ? $v : (object)$v;
                    echo '<li>' . htmlspecialchars($vObj->titulo ?? 'Sin título') . ' (ID: ' . ($vObj->id ?? 'N/A') . ', Activo: ' . (($vObj->activo ?? 0) ? 'Sí' : 'No') . ')</li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="warning">⚠️ El modelo no devuelve videos activos</p>';
                if (!empty($todosVideos)) {
                    echo '<p class="warning">⚠️ Pero hay ' . count($todosVideos) . ' videos en total (algunos pueden estar inactivos)</p>';
                }
            }
        } catch (Exception $e) {
            echo '<p class="error">❌ Error al usar el modelo: ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
    }
    echo '</div></div>';
    
    // Simular lo que hace Pages->galeriaMultimedia()
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h2>4. Simulación de Pages->galeriaMultimedia()</h2>';
    
    try {
        if (class_exists('Video')) {
            $videoModel = new Video();
            $videos = $videoModel->getAllVideos(1, 50, null, true);
            $videoCount = $videoModel->getTotalVideos(null, true);
            
            echo '<p><strong>Videos obtenidos:</strong> ' . count($videos) . '</p>';
            echo '<p><strong>Total de videos activos:</strong> ' . $videoCount . '</p>';
            
            if (empty($videos)) {
                echo '<div class="alert alert-warning">';
                echo '<h4>⚠️ No se encontraron videos activos</h4>';
                echo '<p>Esto significa que la página de galería mostrará el mensaje "Aún no hay videos disponibles".</p>';
                echo '<p><strong>Posibles causas:</strong></p>';
                echo '<ul>';
                echo '<li>No hay videos en la base de datos</li>';
                echo '<li>Todos los videos están marcados como inactivos (activo = 0)</li>';
                echo '<li>Hay un problema con la consulta SQL</li>';
                echo '</ul>';
                echo '<p><a href="' . URL_ROOT . '/public/insertar-videos-ejemplo.php" class="btn btn-primary">Insertar Videos de Ejemplo</a></p>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-success">';
                echo '<h4>✅ Los videos deberían mostrarse en la galería</h4>';
                echo '<p>Si aún no se muestran, puede haber un problema en la vista.</p>';
                echo '<p><a href="' . URL_ROOT . '/galeria-multimedia" class="btn btn-success">Ver Galería Multimedia</a></p>';
                echo '</div>';
            }
        } else {
            echo '<p class="error">❌ La clase Video no está disponible</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
    echo '</div></div>';
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">';
    echo '<h2>Error General:</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}
?>

        <div class="card">
            <div class="card-body">
                <h2>5. Acciones Recomendadas</h2>
                <ul>
                    <li><a href="<?php echo URL_ROOT; ?>/public/insertar-videos-ejemplo.php">Insertar videos de ejemplo</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/admin/videos">Gestionar videos desde el panel de administración</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/galeria-multimedia">Ver la galería multimedia</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

