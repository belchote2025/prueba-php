<?php
if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() {
        if (class_exists('SecurityHelper')) {
            return SecurityHelper::generateCsrfToken();
        }
        return bin2hex(random_bytes(32));
    }
}

$formData = $data['form_data'] ?? [
    'titulo' => '',
    'descripcion' => '',
    'url_video' => '',
    'url_thumbnail' => '',
    'tipo' => 'youtube',
    'categoria' => '',
    'evento_id' => null,
    'duracion' => null,
    'activo' => true,
    'errors' => []
];

$eventos = $data['eventos'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Video - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 mb-0">
                        <i class="bi bi-play-circle me-2"></i>
                        Nuevo Video
                    </h2>
                    <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Volver
                    </a>
                </div>
                
                <?php if (!empty($formData['errors'])): ?>
                    <div class="alert alert-danger">
                        <h5>Errores encontrados:</h5>
                        <ul class="mb-0">
                            <?php foreach ($formData['errors'] as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? generateCsrfToken(); ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="titulo" class="form-label">Título *</label>
                                        <input type="text" class="form-control" id="titulo" name="titulo" 
                                               value="<?php echo htmlspecialchars($formData['titulo']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($formData['descripcion']); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tipo" class="form-label">Tipo de Video *</label>
                                        <select class="form-select" id="tipo" name="tipo" required>
                                            <option value="youtube" <?php echo $formData['tipo'] === 'youtube' ? 'selected' : ''; ?>>YouTube</option>
                                            <option value="vimeo" <?php echo $formData['tipo'] === 'vimeo' ? 'selected' : ''; ?>>Vimeo</option>
                                            <option value="local" <?php echo $formData['tipo'] === 'local' ? 'selected' : ''; ?>>Video Local</option>
                                            <option value="otro" <?php echo $formData['tipo'] === 'otro' ? 'selected' : ''; ?>>Otro</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="url_video" class="form-label">URL del Video *</label>
                                        <input type="url" class="form-control" id="url_video" name="url_video" 
                                               value="<?php echo htmlspecialchars($formData['url_video']); ?>" 
                                               placeholder="https://www.youtube.com/watch?v=..." required>
                                        <small class="form-text text-muted">
                                            Para YouTube: https://www.youtube.com/watch?v=... o https://youtu.be/...<br>
                                            Para Vimeo: https://vimeo.com/...
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="url_thumbnail" class="form-label">URL de Thumbnail (opcional)</label>
                                        <input type="url" class="form-control" id="url_thumbnail" name="url_thumbnail" 
                                               value="<?php echo htmlspecialchars($formData['url_thumbnail']); ?>"
                                               placeholder="https://...">
                                        <small class="form-text text-muted">Si se deja vacío, se generará automáticamente para YouTube</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="categoria" class="form-label">Categoría</label>
                                                <input type="text" class="form-control" id="categoria" name="categoria" 
                                                       value="<?php echo htmlspecialchars($formData['categoria']); ?>"
                                                       placeholder="Ej: Desfiles, Eventos, etc.">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="duracion" class="form-label">Duración (segundos)</label>
                                                <input type="number" class="form-control" id="duracion" name="duracion" 
                                                       value="<?php echo $formData['duracion'] ?? ''; ?>"
                                                       min="0" placeholder="Ej: 300">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="evento_id" class="form-label">Evento relacionado (opcional)</label>
                                        <select class="form-select" id="evento_id" name="evento_id">
                                            <option value="">Ninguno</option>
                                            <?php foreach ($eventos as $evento): 
                                                $eventoObj = is_object($evento) ? $evento : (object)$evento;
                                                $eventoId = $eventoObj->id ?? 0;
                                                $eventoTitulo = $eventoObj->titulo ?? '';
                                            ?>
                                                <option value="<?php echo $eventoId; ?>" 
                                                        <?php echo ($formData['evento_id'] ?? null) == $eventoId ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($eventoTitulo); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                                                   <?php echo ($formData['activo'] ?? true) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="activo">
                                                Video activo (visible en la galería)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Vista Previa</h5>
                                            <div id="videoPreview" class="text-center py-3">
                                                <i class="bi bi-play-circle text-muted" style="font-size: 3rem;"></i>
                                                <p class="text-muted small mt-2">Ingresa la URL del video para ver la vista previa</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-save me-2"></i>
                                    Guardar Video
                                </button>
                                <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-outline-secondary">
                                    <i class="bi bi-x me-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Vista previa de video
        const tipoSelect = document.getElementById('tipo');
        const urlInput = document.getElementById('url_video');
        const previewDiv = document.getElementById('videoPreview');
        
        function updatePreview() {
            const tipo = tipoSelect.value;
            const url = urlInput.value;
            
            if (!url) {
                previewDiv.innerHTML = '<i class="bi bi-play-circle text-muted" style="font-size: 3rem;"></i><p class="text-muted small mt-2">Ingresa la URL del video</p>';
                return;
            }
            
            if (tipo === 'youtube') {
                const youtubeId = extractYouTubeId(url);
                if (youtubeId) {
                    const thumbnail = `https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`;
                    previewDiv.innerHTML = `
                        <img src="${thumbnail}" alt="Preview" class="img-fluid rounded mb-2" style="max-height: 200px;">
                        <p class="small text-muted">YouTube Video</p>
                    `;
                } else {
                    previewDiv.innerHTML = '<p class="text-danger small">URL de YouTube inválida</p>';
                }
            } else if (tipo === 'vimeo') {
                const vimeoId = extractVimeoId(url);
                if (vimeoId) {
                    previewDiv.innerHTML = `
                        <div class="bg-secondary rounded p-3 mb-2">
                            <i class="bi bi-play-circle text-white" style="font-size: 2rem;"></i>
                        </div>
                        <p class="small text-muted">Vimeo Video ID: ${vimeoId}</p>
                    `;
                } else {
                    previewDiv.innerHTML = '<p class="text-danger small">URL de Vimeo inválida</p>';
                }
            } else {
                previewDiv.innerHTML = '<p class="text-muted small">Vista previa no disponible para este tipo</p>';
            }
        }
        
        function extractYouTubeId(url) {
            const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
            return match ? match[1] : null;
        }
        
        function extractVimeoId(url) {
            const match = url.match(/vimeo\.com\/(?:.*\/)?(\d+)/);
            return match ? match[1] : null;
        }
        
        tipoSelect.addEventListener('change', updatePreview);
        urlInput.addEventListener('input', updatePreview);
    </script>
</body>
</html>

