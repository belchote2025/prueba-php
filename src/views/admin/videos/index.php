<?php
// Verificar autenticación
if (!isAdminLoggedIn()) {
    header('Location: ' . URL_ROOT . '/admin/login');
    exit;
}

$videos = $data['videos'] ?? [];
$currentPage = $data['current_page'] ?? 1;
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="bi bi-play-circle me-2"></i>
            Gestión de Videos
        </h2>
        <a href="<?php echo URL_ROOT; ?>/admin/videos/nuevo" class="btn btn-danger">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Video
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($videos)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Thumbnail</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Categoría</th>
                                <th>Vistas</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($videos as $video): 
                                $videoObj = is_object($video) ? $video : (object)$video;
                                $videoId = $videoObj->id ?? 0;
                                $videoTitulo = $videoObj->titulo ?? 'Sin título';
                                $videoThumbnail = $videoObj->url_thumbnail ?? '';
                                $videoTipo = $videoObj->tipo ?? 'youtube';
                                $videoCategoria = $videoObj->categoria ?? '-';
                                $videoVistas = $videoObj->vistas ?? 0;
                                $videoActivo = $videoObj->activo ?? false;
                                $videoFecha = $videoObj->fecha_subida ?? date('Y-m-d');
                                
                                // Generar thumbnail si es YouTube
                                if ($videoTipo === 'youtube' && !$videoThumbnail) {
                                    $videoModel = new Video();
                                    $youtubeId = $videoModel->extractYouTubeId($videoObj->url_video ?? '');
                                    if ($youtubeId) {
                                        $videoThumbnail = 'https://img.youtube.com/vi/' . $youtubeId . '/mqdefault.jpg';
                                    }
                                }
                            ?>
                            <tr>
                                <td><?php echo $videoId; ?></td>
                                <td>
                                    <?php if ($videoThumbnail): ?>
                                        <img src="<?php echo htmlspecialchars($videoThumbnail); ?>" 
                                             alt="Thumbnail" 
                                             style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;"
                                             onerror="this.onerror=null; this.src='<?php echo URL_ROOT; ?>/assets/images/default-video.jpg';">
                                    <?php else: ?>
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 80px; height: 60px; border-radius: 4px;">
                                            <i class="bi bi-play-circle text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($videoTitulo); ?></strong>
                                    <?php if ($videoObj->descripcion ?? ''): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars(substr($videoObj->descripcion, 0, 50)) . '...'; ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $videoTipo === 'youtube' ? 'danger' : ($videoTipo === 'vimeo' ? 'info' : 'secondary'); ?>">
                                        <?php echo strtoupper($videoTipo); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($videoCategoria); ?></td>
                                <td>
                                    <i class="bi bi-eye me-1"></i>
                                    <?php echo number_format($videoVistas); ?>
                                </td>
                                <td>
                                    <?php if ($videoActivo): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo formatDate($videoFecha, 'blog'); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo URL_ROOT; ?>/admin/videos/editar/<?php echo $videoId; ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" 
                                              action="<?php echo URL_ROOT; ?>/admin/videos/eliminar/<?php echo $videoId; ?>" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este video?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-play-circle text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 mb-2">No hay videos registrados</h4>
                    <p class="text-muted mb-4">Comienza agregando tu primer video al sistema</p>
                    <a href="<?php echo URL_ROOT; ?>/admin/videos/nuevo" class="btn btn-danger btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>
                        Crear Primer Video
                    </a>
                    <div class="mt-4">
                        <small class="text-muted">
                            Puedes agregar videos de YouTube, Vimeo o videos locales
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

