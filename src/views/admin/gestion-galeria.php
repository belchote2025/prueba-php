<?php
// Verificar autenticación de administrador
if (!isAdminLoggedIn()) {
    header('Location: /prueba-php/public/admin/login');
    exit;
}

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'upload_video':
                handleVideoUpload();
                break;
            case 'update_video':
                handleVideoUpdate();
                break;
            case 'delete_video':
                handleVideoDelete();
                break;
        }
    }
}

// Función para manejar la subida de videos
function handleVideoUpload() {
    if (!isset($_FILES['video_file']) || !isset($_FILES['thumbnail'])) {
        $_SESSION['error'] = 'Debe seleccionar un video y una miniatura';
        return;
    }
    
    $videoFile = $_FILES['video_file'];
    $thumbnailFile = $_FILES['thumbnail'];
    
    // Validar archivo de video
    $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    if (!in_array($videoFile['type'], $allowedVideoTypes)) {
        $_SESSION['error'] = 'Tipo de archivo de video no permitido';
        return;
    }
    
    // Validar archivo de miniatura
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($thumbnailFile['type'], $allowedImageTypes)) {
        $_SESSION['error'] = 'Tipo de archivo de miniatura no permitido';
        return;
    }
    
    // Crear directorios si no existen
    $videoDir = 'uploads/videos/';
    $thumbnailDir = 'uploads/videos/thumbnails/';
    
    if (!is_dir($videoDir)) {
        mkdir($videoDir, 0755, true);
    }
    if (!is_dir($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }
    
    // Generar nombres únicos
    $videoExtension = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
    $thumbnailExtension = pathinfo($thumbnailFile['name'], PATHINFO_EXTENSION);
    
    $videoName = 'video_' . time() . '_' . uniqid() . '.' . $videoExtension;
    $thumbnailName = 'thumb_' . time() . '_' . uniqid() . '.' . $thumbnailExtension;
    
    $videoPath = $videoDir . $videoName;
    $thumbnailPath = $thumbnailDir . $thumbnailName;
    
    // Mover archivos
    if (move_uploaded_file($videoFile['tmp_name'], $videoPath) && 
        move_uploaded_file($thumbnailFile['tmp_name'], $thumbnailPath)) {
        
        // Guardar información en base de datos
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $duration = $_POST['duration'] ?? '00:00';
        
        // Aquí deberías guardar en la base de datos
        // Por ahora, guardamos en un archivo JSON
        $videosFile = 'uploads/videos/videos.json';
        $videos = [];
        
        if (file_exists($videosFile)) {
            $videos = json_decode(file_get_contents($videosFile), true) ?: [];
        }
        
        $newVideo = [
            'id' => uniqid(),
            'title' => $title,
            'description' => $description,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'date' => $date,
            'duration' => $duration,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $videos[] = $newVideo;
        file_put_contents($videosFile, json_encode($videos, JSON_PRETTY_PRINT));
        
        $_SESSION['success'] = 'Video subido correctamente';
    } else {
        $_SESSION['error'] = 'Error al subir los archivos';
    }
}

// Función para manejar la actualización de videos
function handleVideoUpdate() {
    $videoId = $_POST['video_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $duration = $_POST['duration'] ?? '';
    
    $videosFile = 'uploads/videos/videos.json';
    if (file_exists($videosFile)) {
        $videos = json_decode(file_get_contents($videosFile), true) ?: [];
        
        foreach ($videos as &$video) {
            if ($video['id'] === $videoId) {
                $video['title'] = $title;
                $video['description'] = $description;
                $video['date'] = $date;
                $video['duration'] = $duration;
                $video['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }
        
        file_put_contents($videosFile, json_encode($videos, JSON_PRETTY_PRINT));
        $_SESSION['success'] = 'Video actualizado correctamente';
    }
}

// Función para manejar la eliminación de videos
function handleVideoDelete() {
    $videoId = $_POST['video_id'] ?? '';
    
    $videosFile = 'uploads/videos/videos.json';
    if (file_exists($videosFile)) {
        $videos = json_decode(file_get_contents($videosFile), true) ?: [];
        
        foreach ($videos as $index => $video) {
            if ($video['id'] === $videoId) {
                // Eliminar archivos físicos
                if (file_exists($video['video_path'])) {
                    unlink($video['video_path']);
                }
                if (file_exists($video['thumbnail_path'])) {
                    unlink($video['thumbnail_path']);
                }
                
                // Eliminar del array
                unset($videos[$index]);
                $videos = array_values($videos); // Reindexar array
                break;
            }
        }
        
        file_put_contents($videosFile, json_encode($videos, JSON_PRETTY_PRINT));
        $_SESSION['success'] = 'Video eliminado correctamente';
    }
}

// Cargar videos existentes
$videos = [];
$videosFile = 'uploads/videos/videos.json';
if (file_exists($videosFile)) {
    $videos = json_decode(file_get_contents($videosFile), true) ?: [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Galería - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .admin-header {
            background: linear-gradient(135deg, var(--primary) 0%, #8B0000 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .video-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .video-thumbnail {
            height: 150px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(220, 20, 60, 0.05);
        }
        
        .upload-area.dragover {
            border-color: var(--primary);
            background: rgba(220, 20, 60, 0.1);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-images me-2"></i>Gestión de Galería</h1>
                    <p class="mb-0">Administra carrusel, galería general y videos de la Filá Mariscales</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/prueba-php/public/admin" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i>Volver al Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Navegación por pestañas -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="galleryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="carrusel-tab" data-bs-toggle="tab" data-bs-target="#carrusel" type="button" role="tab">
                            <i class="bi bi-collection-play me-2"></i>Carrusel
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="galeria-tab" data-bs-toggle="tab" data-bs-target="#galeria" type="button" role="tab">
                            <i class="bi bi-images me-2"></i>Galería General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab">
                            <i class="bi bi-play-circle me-2"></i>Videos
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="galleryTabContent">
            <!-- Pestaña Carrusel -->
            <div class="tab-pane fade show active" id="carrusel" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-12">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCarruselModal">
                            <i class="bi bi-plus-circle me-2"></i>Subir Imagen al Carrusel
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-collection-play display-1 text-muted"></i>
                            <h3 class="text-muted mt-3">Gestión de Carrusel</h3>
                            <p class="text-muted">Aquí podrás gestionar las imágenes del carrusel principal</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña Galería General -->
            <div class="tab-pane fade" id="galeria" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-12">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadGaleriaModal">
                            <i class="bi bi-plus-circle me-2"></i>Subir Imagen a la Galería
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-images display-1 text-muted"></i>
                            <h3 class="text-muted mt-3">Gestión de Galería General</h3>
                            <p class="text-muted">Aquí podrás gestionar las imágenes de la galería general</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña Videos -->
            <div class="tab-pane fade" id="videos" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-12">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-plus-circle me-2"></i>Subir Nuevo Video
                        </button>
                    </div>
                </div>

        <!-- Grid de videos -->
        <div class="row">
            <?php if (empty($videos)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-play-circle display-1 text-muted"></i>
                        <h3 class="text-muted mt-3">No hay videos subidos</h3>
                        <p class="text-muted">Sube tu primer video para comenzar</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($videos as $video): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="video-card">
                            <div class="video-thumbnail">
                                <img src="<?php echo htmlspecialchars($video['thumbnail_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($video['title']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($video['title']); ?></h5>
                                <p class="card-text text-muted small">
                                    <?php echo htmlspecialchars(substr($video['description'], 0, 100)); ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i><?php echo $video['date']; ?>
                                        <i class="bi bi-clock ms-2 me-1"></i><?php echo $video['duration']; ?>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                onclick="editVideo('<?php echo $video['id']; ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" 
                                                onclick="deleteVideo('<?php echo $video['id']; ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para subir video -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Subir Nuevo Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="upload_video">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título del Video</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Fecha del Evento</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duración (mm:ss)</label>
                                    <input type="text" class="form-control" id="duration" name="duration" 
                                           placeholder="15:30" pattern="[0-9]{1,2}:[0-9]{2}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="video_file" class="form-label">Archivo de Video</label>
                                    <input type="file" class="form-control" id="video_file" name="video_file" 
                                           accept="video/*" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Miniatura del Video</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" 
                                   accept="image/*" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i>Subir Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar video -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_video">
                        <input type="hidden" name="video_id" id="edit_video_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_title" class="form-label">Título del Video</label>
                                    <input type="text" class="form-control" id="edit_title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_date" class="form-label">Fecha del Evento</label>
                                    <input type="date" class="form-control" id="edit_date" name="date" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_duration" class="form-label">Duración (mm:ss)</label>
                            <input type="text" class="form-control" id="edit_duration" name="duration" 
                                   placeholder="15:30" pattern="[0-9]{1,2}:[0-9]{2}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para editar video
        function editVideo(videoId) {
            // Aquí deberías cargar los datos del video desde el servidor
            // Por simplicidad, usamos datos estáticos
            document.getElementById('edit_video_id').value = videoId;
            
            // Mostrar modal
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
        
        // Función para eliminar video
        function deleteVideo(videoId) {
            if (confirm('¿Estás seguro de que quieres eliminar este video?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_video">
                    <input type="hidden" name="video_id" value="${videoId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Establecer fecha actual por defecto
        document.getElementById('date').value = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
