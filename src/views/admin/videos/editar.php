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

$video = $data['video'] ?? null;
$eventos = $data['eventos'] ?? [];
$errors = isset($_SESSION['error_message']) ? [$_SESSION['error_message']] : [];

if (!$video) {
    if (!headers_sent()) {
        header('Location: ' . URL_ROOT . '/admin/videos');
    } else {
        echo '<script>window.location.href = "' . URL_ROOT . '/admin/videos";</script>';
    }
    exit;
}

$videoObj = is_object($video) ? $video : (object)$video;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Video - Panel de Administración</title>
    
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <!-- Admin CSS -->
        <link href="<?php echo URL_ROOT; ?>/assets/css/admin.css" rel="stylesheet">
        <!-- Theme CSS -->
        <link href="<?php echo URL_ROOT; ?>/assets/css/theme.css" rel="stylesheet">
    
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
                    <i class="fas fa-edit me-2"></i>
                    Editar Video
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
                    <form method="POST" action="<?php echo URL_ROOT; ?>/admin/videos/actualizar/<?php echo $videoObj->id; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?php echo htmlspecialchars($videoObj->titulo ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($videoObj->descripcion ?? ''); ?></textarea>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo de Video <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipo" name="tipo" required onchange="toggleVideoInput()">
                                    <option value="youtube" <?php echo ($videoObj->tipo ?? '') === 'youtube' ? 'selected' : ''; ?>>YouTube</option>
                                    <option value="vimeo" <?php echo ($videoObj->tipo ?? '') === 'vimeo' ? 'selected' : ''; ?>>Vimeo</option>
                                    <option value="local" <?php echo ($videoObj->tipo ?? '') === 'local' ? 'selected' : ''; ?>>Video Local</option>
                                    <option value="otro" <?php echo ($videoObj->tipo ?? '') === 'otro' ? 'selected' : ''; ?>>Otro</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="general" <?php echo ($videoObj->categoria ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="desfiles" <?php echo ($videoObj->categoria ?? '') === 'desfiles' ? 'selected' : ''; ?>>Desfiles</option>
                                    <option value="eventos" <?php echo ($videoObj->categoria ?? '') === 'eventos' ? 'selected' : ''; ?>>Eventos</option>
                                    <option value="bandas" <?php echo ($videoObj->categoria ?? '') === 'bandas' ? 'selected' : ''; ?>>Bandas</option>
                                    <option value="procesiones" <?php echo ($videoObj->categoria ?? '') === 'procesiones' ? 'selected' : ''; ?>>Procesiones</option>
                                    <option value="historia" <?php echo ($videoObj->categoria ?? '') === 'historia' ? 'selected' : ''; ?>>Historia</option>
                                    <option value="entrevistas" <?php echo ($videoObj->categoria ?? '') === 'entrevistas' ? 'selected' : ''; ?>>Entrevistas</option>
                                </select>
                            </div>
                            
                            <!-- URL de Video -->
                            <div class="col-12 mb-3" id="url-video-container">
                                <label for="url_video" class="form-label">URL del Video <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="url_video" name="url_video" 
                                       value="<?php echo htmlspecialchars($videoObj->url_video ?? ''); ?>" 
                                       required onblur="validateVideoUrl()">
                                <div id="url-validation" class="mt-2"></div>
                                <small class="form-text text-muted">
                                    <span id="url-hint">URL del video</span>
                                </small>
                            </div>
                            
                            <!-- Previsualización del Video -->
                            <div class="col-12 mb-3" id="video-preview-container" style="display: none;">
                                <label class="form-label">Previsualización</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div id="video-preview"></div>
                                        <div id="video-info" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Archivo de Video (para Local) -->
                            <div class="col-12 mb-3" id="file-video-container" style="display: <?php echo ($videoObj->tipo ?? '') === 'local' ? 'block' : 'none'; ?>;">
                                <label for="video_file" class="form-label">Nuevo Archivo de Video (opcional)</label>
                                <input type="file" class="form-control" id="video_file" name="video_file" 
                                       accept="video/mp4,video/webm,video/ogg,video/quicktime">
                                <small class="form-text text-muted">
                                    <?php if (($videoObj->tipo ?? '') === 'local'): ?>
                                        Video actual: <a href="<?php echo htmlspecialchars($videoObj->url_video ?? '#'); ?>" target="_blank">Ver video actual</a>
                                    <?php endif; ?>
                                    <br>Dejar vacío para mantener el video actual. Formatos: MP4, WebM, OGG, QuickTime
                                </small>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="url_thumbnail" class="form-label">URL de Thumbnail (opcional)</label>
                                <input type="url" class="form-control" id="url_thumbnail" name="url_thumbnail" 
                                       value="<?php echo htmlspecialchars($videoObj->url_thumbnail ?? ''); ?>">
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="thumbnail_file" class="form-label">Nuevo Thumbnail (opcional)</label>
                                <input type="file" class="form-control" id="thumbnail_file" name="thumbnail_file" 
                                       accept="image/jpeg,image/png,image/gif,image/webp">
                                <?php if ($videoObj->url_thumbnail ?? ''): ?>
                                    <small class="form-text text-muted">
                                        Thumbnail actual: <img src="<?php echo htmlspecialchars($videoObj->url_thumbnail); ?>" 
                                                              style="max-width: 100px; max-height: 60px; margin-top: 5px;">
                                    </small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="evento_id" class="form-label">Evento Relacionado (opcional)</label>
                                <select class="form-select" id="evento_id" name="evento_id">
                                    <option value="">Ninguno</option>
                                    <?php foreach ($eventos as $evento): 
                                        $eventoObj = is_object($evento) ? $evento : (object)$evento;
                                        $selected = ($videoObj->evento_id ?? 0) == ($eventoObj->id ?? 0) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $eventoObj->id ?? 0; ?>" <?php echo $selected; ?>>
                                            <?php echo htmlspecialchars(($eventoObj->titulo ?? '') . ' - ' . ($eventoObj->fecha ?? '')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="duracion" class="form-label">Duración (segundos)</label>
                                <input type="number" class="form-control" id="duracion" name="duracion" 
                                       value="<?php echo $videoObj->duracion ?? 0; ?>" min="0">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="tags" class="form-label">Etiquetas/Tags (opcional)</label>
                                <input type="text" class="form-control" id="tags" name="tags" 
                                       value="<?php echo htmlspecialchars($videoObj->tags ?? ''); ?>"
                                       placeholder="Ej: desfile, 2024, moros y cristianos (separadas por comas)">
                                <small class="form-text text-muted">Separa las etiquetas con comas. Ejemplo: desfile, 2024, moros y cristianos</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                           <?php echo ($videoObj->activo ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="activo">
                                        Video activo (visible en la galería)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="alert alert-info">
                                    <strong>Estadísticas:</strong><br>
                                    Vistas: <?php echo number_format($videoObj->vistas ?? 0); ?><br>
                                    Fecha de subida: <?php echo date('d/m/Y H:i', strtotime($videoObj->fecha_subida ?? 'now')); ?><br>
                                    Última modificación: <?php echo date('d/m/Y H:i', strtotime($videoObj->fecha_modificacion ?? 'now')); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Actualizar Video
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones de validación y extracción de IDs
function extractYouTubeId(url) {
    const patterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]+)/,
        /youtube\.com\/v\/([a-zA-Z0-9_-]+)/
    ];
    
    for (let pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
            return match[1];
        }
    }
    return null;
}

function extractVimeoId(url) {
    const patterns = [
        /vimeo\.com\/(\d+)/,
        /vimeo\.com\/video\/(\d+)/,
        /player\.vimeo\.com\/video\/(\d+)/
    ];
    
    for (let pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
            return match[1];
        }
    }
    return null;
}

function validateVideoUrl() {
    const tipo = document.getElementById('tipo').value;
    const urlInput = document.getElementById('url_video');
    const url = urlInput.value.trim();
    const validationDiv = document.getElementById('url-validation');
    const previewContainer = document.getElementById('video-preview-container');
    const previewDiv = document.getElementById('video-preview');
    const infoDiv = document.getElementById('video-info');
    
    // Limpiar validación anterior
    validationDiv.innerHTML = '';
    previewContainer.style.display = 'none';
    previewDiv.innerHTML = '';
    infoDiv.innerHTML = '';
    
    if (!url) {
        return;
    }
    
    let isValid = false;
    let videoId = null;
    let embedUrl = '';
    let thumbnailUrl = '';
    let message = '';
    
    if (tipo === 'youtube') {
        videoId = extractYouTubeId(url);
        if (videoId) {
            isValid = true;
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
            thumbnailUrl = `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`;
            message = `<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>URL de YouTube válida. ID: ${videoId}</div>`;
        } else {
            message = `<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>URL de YouTube no válida. Formato esperado: https://www.youtube.com/watch?v=VIDEO_ID o https://youtu.be/VIDEO_ID</div>`;
        }
    } else if (tipo === 'vimeo') {
        videoId = extractVimeoId(url);
        if (videoId) {
            isValid = true;
            embedUrl = `https://player.vimeo.com/video/${videoId}`;
            message = `<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>URL de Vimeo válida. ID: ${videoId}</div>`;
        } else {
            message = `<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>URL de Vimeo no válida. Formato esperado: https://vimeo.com/VIDEO_ID</div>`;
        }
    } else {
        // Para otros tipos, solo validar que sea una URL válida
        try {
            new URL(url);
            isValid = true;
            message = `<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>URL válida</div>`;
        } catch (e) {
            message = `<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>URL no válida</div>`;
        }
    }
    
    validationDiv.innerHTML = message;
    
    // Mostrar previsualización si es válida
    if (isValid && (tipo === 'youtube' || tipo === 'vimeo')) {
        previewContainer.style.display = 'block';
        previewDiv.innerHTML = `
            <div class="ratio ratio-16x9">
                <iframe src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        `;
        
        if (tipo === 'youtube' && thumbnailUrl) {
            infoDiv.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <img src="${thumbnailUrl}" alt="Thumbnail" style="max-width: 120px; border-radius: 4px;">
                    <div>
                        <small class="text-muted">Thumbnail generado automáticamente</small><br>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="setThumbnailUrl('${thumbnailUrl}')">
                            <i class="fas fa-magic me-1"></i>Usar este thumbnail
                        </button>
                    </div>
                </div>
            `;
        }
    }
}

function setThumbnailUrl(url) {
    document.getElementById('url_thumbnail').value = url;
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show mt-2';
    alert.innerHTML = '<i class="fas fa-check me-2"></i>Thumbnail establecido automáticamente<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.getElementById('url_thumbnail').parentElement.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}

function toggleVideoInput() {
    const tipo = document.getElementById('tipo').value;
    const urlContainer = document.getElementById('url-video-container');
    const fileContainer = document.getElementById('file-video-container');
    const urlInput = document.getElementById('url_video');
    const fileInput = document.getElementById('video_file');
    const urlHint = document.getElementById('url-hint');
    const previewContainer = document.getElementById('video-preview-container');
    
    // Ocultar previsualización al cambiar tipo
    previewContainer.style.display = 'none';
    
    if (tipo === 'local') {
        urlContainer.style.display = 'none';
        fileContainer.style.display = 'block';
        urlInput.removeAttribute('required');
        fileInput.removeAttribute('required'); // No requerido al editar
        urlHint.textContent = 'Sube un nuevo archivo de video o deja vacío para mantener el actual';
    } else {
        urlContainer.style.display = 'block';
        fileContainer.style.display = 'none';
        urlInput.setAttribute('required', 'required');
        fileInput.removeAttribute('required');
        
        if (tipo === 'youtube') {
            urlHint.textContent = 'URL del video de YouTube';
        } else if (tipo === 'vimeo') {
            urlHint.textContent = 'URL del video de Vimeo';
        } else {
            urlHint.textContent = 'URL del video';
        }
    }
    
    // Validar URL si ya hay una
    if (urlInput.value) {
        validateVideoUrl();
    }
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', function() {
    toggleVideoInput();
    
    // Validar URL al cambiar
    document.getElementById('url_video').addEventListener('input', function() {
        clearTimeout(this.validationTimeout);
        this.validationTimeout = setTimeout(validateVideoUrl, 500);
    });
    
    // Validar URL inicial si existe
    if (document.getElementById('url_video').value) {
        validateVideoUrl();
    }
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
        font-size: 16px;
    }
}
</style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Theme JS -->
    <script src="<?php echo URL_ROOT; ?>/assets/js/theme.js"></script>
</body>
</html>

