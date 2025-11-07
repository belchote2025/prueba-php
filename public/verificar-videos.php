<?php
// Script temporal para verificar la tabla videos
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/models/Database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Tabla Videos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Verificación de Tabla Videos</h1>
    
    <?php
    try {
        $db = new Database();
        $pdo = $db->getConnection();
        
        if (!$pdo) {
            echo '<p class="error">❌ No hay conexión a la base de datos</p>';
            exit;
        }
        
        echo '<p class="success">✅ Conexión a la base de datos establecida</p>';
        echo '<p class="info">Base de datos: ' . DB_NAME . '</p>';
        
        // Verificar si la tabla existe
        $stmt = $pdo->query("SHOW TABLES LIKE 'videos'");
        $tableExists = $stmt->rowCount() > 0;
        
        if ($tableExists) {
            echo '<p class="success">✅ La tabla "videos" existe</p>';
            
            // Mostrar estructura
            $stmt = $pdo->query("DESCRIBE videos");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<h2>Estructura de la tabla:</h2>';
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>';
            foreach ($columns as $col) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($col['Field']) . '</td>';
                echo '<td>' . htmlspecialchars($col['Type']) . '</td>';
                echo '<td>' . htmlspecialchars($col['Null']) . '</td>';
                echo '<td>' . htmlspecialchars($col['Key']) . '</td>';
                echo '<td>' . htmlspecialchars($col['Default'] ?? 'NULL') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM videos");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<p class="info">Total de videos: ' . $count['total'] . '</p>';
            
            // Mostrar videos si existen
            if ($count['total'] > 0) {
                $stmt = $pdo->query("SELECT * FROM videos ORDER BY fecha_subida DESC LIMIT 10");
                $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<h2>Últimos videos:</h2>';
                echo '<table border="1" cellpadding="5">';
                echo '<tr><th>ID</th><th>Título</th><th>Tipo</th><th>Activo</th><th>Fecha</th></tr>';
                foreach ($videos as $video) {
                    echo '<tr>';
                    echo '<td>' . $video['id'] . '</td>';
                    echo '<td>' . htmlspecialchars($video['titulo']) . '</td>';
                    echo '<td>' . htmlspecialchars($video['tipo']) . '</td>';
                    echo '<td>' . ($video['activo'] ? 'Sí' : 'No') . '</td>';
                    echo '<td>' . $video['fecha_subida'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            
        } else {
            echo '<p class="error">❌ La tabla "videos" NO existe</p>';
            echo '<p class="info">Necesitas ejecutar el script de instalación de nuevas funcionalidades.</p>';
            echo '<p>Ejecuta: <code>public/install-new-features.php</code> (si aún existe)</p>';
        }
        
    } catch (Exception $e) {
        echo '<p class="error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
    ?>
    
    <hr>
    <p><a href="<?php echo URL_ROOT; ?>/admin/videos">← Volver a Gestión de Videos</a></p>
</body>
</html>

