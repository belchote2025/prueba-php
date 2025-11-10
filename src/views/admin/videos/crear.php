<?php
// Habilitar visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que las constantes estén definidas
if (!defined('URL_ROOT')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/src/config/config.php';
}

// Verificar autenticación
if (!function_exists('isAdminLoggedIn')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/src/config/admin_credentials.php';
}

// Asegurar que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isAdminLoggedIn()) {
    if (!headers_sent()) {
        header('Location: ' . URL_ROOT . '/admin/login');
    } else {
        echo '<script>window.location.href = "' . URL_ROOT . '/admin/login";</script>';
    }
    exit;
}

// Verificar que $data esté definido
if (!isset($data) && isset($GLOBALS['data'])) {
    $data = $GLOBALS['data'];
} elseif (!isset($data)) {
    $data = [];
}

$eventos = $data['eventos'] ?? [];
$errors = isset($_SESSION['error_message']) ? [$_SESSION['error_message']] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Video - Panel de Administración</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?php echo URL_ROOT; ?>/assets/css/admin.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URL_ROOT; ?>/admin/dashboard">
                <i class="fas fa-shield-alt me-2"></i><span class="d-none d-sm-inline">Panel de Administración</span><span class="d-sm-none">Admin</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/dashboard">Dashboard</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/usuarios">Usuarios</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/eventos">Eventos</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/noticias">Blog</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/galeria">Galería</a>
                    <a class="nav-link active" href="<?php echo URL_ROOT; ?>/admin/videos">Videos</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/documentos">Documentos</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/visitas">Analíticas</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/logout">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <h2 class="h3 mb-3 mb-md-0">
                    <i class="fas fa-video me-2"></i>
                    Nuevo Video
                </h2>
                <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-outline-secondary w-100 w-md-auto">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver
                </a>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="<?php echo URL_ROOT; ?>/admin/videos/guardar" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       placeholder="Ej: Desfile Principal 2024" required>
                                <small class="form-text text-muted">Título descriptivo del video</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                          placeholder="Descripción del video..."></textarea>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo de Video <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipo" name="tipo" required onchange="toggleVideoInput()">
                                    <option value="youtube">YouTube</option>
                                    <option value="vimeo">Vimeo</option>
                                    <option value="local">Video Local</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="general">General</option>
                                    <option value="desfiles">Desfiles</option>
                                    <option value="eventos">Eventos</option>
                                    <option value="bandas">Bandas</option>
                                    <option value="procesiones">Procesiones</option>
                                    <option value="historia">Historia</option>
                                    <option value="entrevistas">Entrevistas</option>
                                </select>
                            </div>
                            
                            <!-- URL de Video (para YouTube, Vimeo, Otro) -->
                            <div class="col-12 mb-3" id="url-video-container">
                                <label for="url_video" class="form-label">URL del Video <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="url_video" name="url_video" 
                                       placeholder="https://www.youtube.com/watch?v=... o https://vimeo.com/...">
                                <small class="form-text text-muted">
                                    <span id="url-hint">Pega la URL completa del video de YouTube o Vimeo</span>
                                </small>
                            </div>
                            
                            <!-- Archivo de Video (para Local) -->
                            <div class="col-12 mb-3" id="file-video-container" style="display: none;">
                                <label for="video_file" class="form-label">Archivo de Video <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="video_file" name="video_file" 
                                       accept="video/mp4,video/webm,video/ogg,video/quicktime">
                                <small class="form-text text-muted">Formatos permitidos: MP4, WebM, OGG, QuickTime (máx. 100MB)</small>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="url_thumbnail" class="form-label">URL de Thumbnail (opcional)</label>
                                <input type="url" class="form-control" id="url_thumbnail" name="url_thumbnail" 
                                       placeholder="https://...">
                                <small class="form-text text-muted">URL de imagen personalizada para el thumbnail</small>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="thumbnail_file" class="form-label">Subir Thumbnail (opcional)</label>
                                <input type="file" class="form-control" id="thumbnail_file" name="thumbnail_file" 
                                       accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="form-text text-muted">Formatos: JPG, PNG, GIF, WebP</small>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="evento_id" class="form-label">Evento Relacionado (opcional)</label>
                                <select class="form-select" id="evento_id" name="evento_id">
                                    <option value="">Ninguno</option>
                                    <?php foreach ($eventos as $evento): 
                                        $eventoObj = is_object($evento) ? $evento : (object)$evento;
                                    ?>
                                        <option value="<?php echo $eventoObj->id ?? 0; ?>">
                                            <?php echo htmlspecialchars(($eventoObj->titulo ?? '') . ' - ' . ($eventoObj->fecha ?? '')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="duracion" class="form-label">Duración (segundos)</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" 
                                       min="0" placeholder="Ej: 180 (3 minutos)">
                                <small class="form-text text-muted">Duración en segundos (opcional)</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                    <label class="form-check-label" for="activo">
                                        Video activo (visible en la galería)
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Guardar Video
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleVideoInput() {
    const tipo = document.getElementById('tipo').value;
    const urlContainer = document.getElementById('url-video-container');
    const fileContainer = document.getElementById('file-video-container');
    const urlInput = document.getElementById('url_video');
    const fileInput = document.getElementById('video_file');
    const urlHint = document.getElementById('url-hint');
    
    if (tipo === 'local') {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        urlInput.removeAttribute('required');
        fileInput.setAttribute('required', 'required');
        urlHint.textContent = 'Selecciona un archivo de video desde tu ordenador';
    } else {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        urlInput.setAttribute('required', 'required');
        fileInput.removeAttribute('required');
        
        if (tipo === 'youtube') {
            urlHint.textContent = 'Pega la URL completa del video de YouTube (ej: https://www.youtube.com/watch?v=...)';
        } else if (tipo === 'vimeo') {
            urlHint.textContent = 'Pega la URL completa del video de Vimeo (ej: https://vimeo.com/...)';
        } else {
            urlHint.textContent = 'Pega la URL completa del video';
        }
    }
}

// Ejemplos de videos (pueden ser modificados)
document.addEventListener('DOMContentLoaded', function() {
    // Ejemplo de YouTube
    const ejemploYouTube = document.createElement('div');
    ejemploYouTube.className = 'alert alert-info mt-3';
    ejemploYouTube.innerHTML = `
        <strong><i class="fas fa-info-circle me-2"></i>Ejemplo de URL de YouTube:</strong><br>
        <code>https://www.youtube.com/watch?v=dQw4w9WgXcQ</code><br>
        <small>O también: <code>https://youtu.be/dQw4w9WgXcQ</code></small>
    `;
    document.getElementById('url-video-container').appendChild(ejemploYouTube);
});
</script>

<style>
/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .form-control,
    .form-select {
        font-size: 16px; /* Evita zoom en iOS */
    }
}
</style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

