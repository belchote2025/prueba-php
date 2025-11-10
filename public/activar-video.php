<?php
/**
 * Script para activar el video si está inactivo
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
    <title>Activar Video</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Activar Video</h1>

<?php
try {
    $db = new Database();
    $videoModel = new Video();
    
    // Obtener todos los videos
    $db->query("SELECT * FROM videos ORDER BY id DESC");
    $videos = $db->resultSet();
    
    if (empty($videos)) {
        echo '<div class="alert alert-warning">';
        echo '<h4>No hay videos en la base de datos</h4>';
        echo '<p><a href="' . URL_ROOT . '/public/insertar-videos-ejemplo.php" class="btn btn-primary">Insertar Videos de Ejemplo</a></p>';
        echo '</div>';
        echo '</div></body></html>';
        exit;
    }
    
    // Verificar estado de los videos
    $videosInactivos = [];
    foreach ($videos as $v) {
        $vObj = is_object($v) ? $v : (object)$v;
        if (($vObj->activo ?? 0) == 0) {
            $videosInactivos[] = $vObj;
        }
    }
    
    // Si hay un parámetro GET para activar
    if (isset($_GET['activar']) && is_numeric($_GET['activar'])) {
        $videoId = (int)$_GET['activar'];
        
        // Verificar que el video existe
        $db->query("SELECT * FROM videos WHERE id = :id");
        $db->bind(':id', $videoId);
        $video = $db->single();
        
        if ($video) {
            // Activar el video
            $db->query("UPDATE videos SET activo = 1 WHERE id = :id");
            $db->bind(':id', $videoId);
            $db->execute();
            
            echo '<div class="alert alert-success">';
            echo '<h4>✅ Video activado correctamente</h4>';
            echo '<p>El video "' . htmlspecialchars($video->titulo ?? 'Sin título') . '" ha sido activado y ahora debería aparecer en la galería.</p>';
            echo '<p><a href="' . URL_ROOT . '/galeria-multimedia" class="btn btn-success">Ver Galería Multimedia</a></p>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">';
            echo '<h4>❌ Error</h4>';
            echo '<p>No se encontró el video con ID ' . $videoId . '</p>';
            echo '</div>';
        }
    } else {
        // Mostrar lista de videos y su estado
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h2>Estado de los Videos</h2>';
        
        if (!empty($videosInactivos)) {
            echo '<div class="alert alert-warning">';
            echo '<h4>⚠️ Se encontraron ' . count($videosInactivos) . ' video(s) inactivo(s)</h4>';
            echo '<p>Los videos inactivos no se muestran en la galería pública.</p>';
            echo '</div>';
            
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Título</th><th>Estado</th><th>Acción</th></tr></thead>';
            echo '<tbody>';
            foreach ($videosInactivos as $v) {
                echo '<tr>';
                echo '<td>' . ($v->id ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($v->titulo ?? 'Sin título') . '</td>';
                echo '<td><span class="badge bg-danger">Inactivo</span></td>';
                echo '<td><a href="?activar=' . ($v->id ?? 0) . '" class="btn btn-sm btn-success">Activar</a></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-success">';
            echo '<h4>✅ Todos los videos están activos</h4>';
            echo '<p>Los videos deberían mostrarse en la galería.</p>';
            echo '<p><a href="' . URL_ROOT . '/galeria-multimedia" class="btn btn-success">Ver Galería Multimedia</a></p>';
            echo '</div>';
        }
        
        // Mostrar todos los videos
        echo '<h3 class="mt-4">Todos los Videos</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>ID</th><th>Título</th><th>Estado</th><th>Acción</th></tr></thead>';
        echo '<tbody>';
        foreach ($videos as $v) {
            $vObj = is_object($v) ? $v : (object)$v;
            $activo = ($vObj->activo ?? 0) == 1;
            echo '<tr>';
            echo '<td>' . ($vObj->id ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($vObj->titulo ?? 'Sin título') . '</td>';
            echo '<td>' . ($activo ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>') . '</td>';
            if (!$activo) {
                echo '<td><a href="?activar=' . ($vObj->id ?? 0) . '" class="btn btn-sm btn-success">Activar</a></td>';
            } else {
                echo '<td><span class="text-muted">Ya está activo</span></td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
        
        echo '</div></div>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">';
    echo '<h2>Error:</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
}
?>

        <div class="card mt-4">
            <div class="card-body">
                <h3>Otras Acciones</h3>
                <div class="d-grid gap-2">
                    <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-primary">Gestionar Videos (Panel Admin)</a>
                    <a href="<?php echo URL_ROOT; ?>/galeria-multimedia" class="btn btn-success">Ver Galería Multimedia</a>
                    <a href="<?php echo URL_ROOT; ?>/public/debug-video-especifico.php" class="btn btn-secondary">Ver Diagnóstico Completo</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

