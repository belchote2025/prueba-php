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

if (!function_exists('isAdminLoggedIn')) {
    die('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Error</title>' .
        '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' .
        '</head><body><div class="container mt-5"><div class="alert alert-danger">' .
        '<h4>Error de Configuración</h4>' .
        '<p>La función isAdminLoggedIn no está disponible. Verifica que admin_credentials.php esté cargado correctamente.</p>' .
        '</div></div></body></html>');
}

// Asegurar que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isAdminLoggedIn()) {
    // Verificar si los headers ya se enviaron
    if (!headers_sent()) {
        header('Location: ' . URL_ROOT . '/admin/login');
        exit;
    } else {
        // Si los headers ya se enviaron, usar JavaScript para redirigir
        echo '<script>window.location.href = "' . URL_ROOT . '/admin/login";</script>';
        exit;
    }
}

// Verificar que $data esté definido (puede venir de extract() o $GLOBALS)
if (!isset($data) && isset($GLOBALS['data'])) {
    $data = $GLOBALS['data'];
} elseif (!isset($data)) {
    // Si aún no está definido, intentar obtenerlo de otra forma
    $data = [];
    error_log("Warning: \$data no está definido en admin/videos/index.php");
}

$videos = $data['videos'] ?? [];
$currentPage = $data['current_page'] ?? 1;
$totalPages = $data['total_pages'] ?? 1;
$totalVideos = $data['total_videos'] ?? 0;

// Debug: Log de datos recibidos
error_log("admin/videos/index.php - Videos recibidos: " . count($videos));
error_log("admin/videos/index.php - Total videos: " . $totalVideos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Videos - Panel de Administración</title>
    
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .navbar-brand { font-weight: bold; }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.875em;
            padding: 0.5em 0.75em;
        }
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        .video-thumbnail-preview {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        .stats-card {
            border-radius: 8px;
            color: white;
        }
        .stats-card h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }
        .stats-card h5 {
            margin-bottom: 0.5rem;
            opacity: 0.9;
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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="h3 mb-3 mb-md-0">
            <i class="fas fa-video me-2"></i>
            Gestión de Videos
        </h2>
        <a href="<?php echo URL_ROOT; ?>/admin/videos/nuevo" class="btn btn-danger w-100 w-md-auto shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>
            Nuevo Video
        </a>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-video me-2"></i>Total de Videos</h5>
                    <h2 class="mb-0"><?php echo $totalVideos; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-check-circle me-2"></i>Videos Activos</h5>
                    <h2 class="mb-0"><?php 
                        $activos = 0;
                        foreach ($videos as $v) {
                            if ((is_object($v) ? $v->activo : $v['activo'])) $activos++;
                        }
                        echo $activos;
                    ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-pause-circle me-2"></i>Videos Inactivos</h5>
                    <h2 class="mb-0"><?php echo $totalVideos - $activos; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Videos -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Videos</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($videos)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                $videoTipo = $videoObj->tipo ?? 'youtube';
                                $videoCategoria = $videoObj->categoria ?? 'general';
                                $videoVistas = $videoObj->vistas ?? 0;
                                $videoActivo = $videoObj->activo ?? 0;
                                $videoFecha = $videoObj->fecha_subida ?? date('Y-m-d');
                                
                                // Obtener thumbnail
                                $thumbnail = '';
                                try {
                                    // Primero intentar usar url_thumbnail si existe
                                    if (!empty($videoObj->url_thumbnail ?? '')) {
                                        $thumbnail = $videoObj->url_thumbnail;
                                    } elseif (($videoObj->tipo ?? '') === 'youtube') {
                                        // Intentar generar thumbnail de YouTube
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoObj->url_video ?? '', $matches);
                                        if (isset($matches[1])) {
                                            $thumbnail = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
                                        }
                                    } elseif (($videoObj->tipo ?? '') === 'vimeo') {
                                        // Para Vimeo, intentar obtener thumbnail (requiere API)
                                        $thumbnail = ''; // Por ahora vacío, se puede implementar después
                                    }
                                } catch (Exception $e) {
                                    $thumbnail = '';
                                }
                            ?>
                            <tr>
                                <td><?php echo $videoId; ?></td>
                                <td>
                                    <?php if ($thumbnail): ?>
                                        <img src="<?php echo htmlspecialchars($thumbnail); ?>" 
                                             alt="Thumbnail" 
                                             class="video-thumbnail-preview"
                                             onerror="this.onerror=null; this.src='<?php echo URL_ROOT; ?>/assets/images/default-avatar.png'; this.className='video-thumbnail-preview';">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center video-thumbnail-preview">
                                            <i class="fas fa-video"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($videoTitulo); ?></strong>
                                    <?php if ($videoObj->descripcion ?? ''): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars(substr($videoObj->descripcion, 0, 50)); ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $videoTipo === 'youtube' ? 'danger' : ($videoTipo === 'vimeo' ? 'info' : 'secondary'); ?>">
                                        <?php echo ucfirst($videoTipo); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($videoCategoria); ?></td>
                                <td><?php echo number_format($videoVistas); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $videoActivo ? 'success' : 'secondary'; ?>">
                                        <?php echo $videoActivo ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($videoFecha)); ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo URL_ROOT; ?>/admin/videos/editar/<?php echo $videoId; ?>" 
                                           class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo URL_ROOT; ?>/admin/videos/eliminar/<?php echo $videoId; ?>" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este video?');" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Paginación de videos">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo URL_ROOT; ?>/admin/videos/<?php echo $currentPage > 1 ? $currentPage - 1 : 1; ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $currentPage == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo URL_ROOT; ?>/admin/videos/<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo URL_ROOT; ?>/admin/videos/<?php echo $currentPage < $totalPages ? $currentPage + 1 : $totalPages; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-video fa-4x text-muted mb-3"></i>
                    </div>
                    <h4 class="text-muted mb-3">No hay videos registrados</h4>
                    <p class="text-muted mb-4">Comienza agregando tu primer video para mostrarlo en la galería multimedia</p>
                    <a href="<?php echo URL_ROOT; ?>/admin/videos/nuevo" class="btn btn-danger btn-lg shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Crear Primer Video
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Responsive para Videos */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
    }
    
    .table th {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .table th,
    .table td {
        padding: 0.375rem;
    }
    
    .table td img {
        width: 60px !important;
        height: 45px !important;
    }
}
</style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

