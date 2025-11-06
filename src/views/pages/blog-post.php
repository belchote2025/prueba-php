<?php $content = '\n';
ob_start(); // Start output buffering

// Obtener datos del post
$post = $data['post'];
$postId = is_object($post) ? $post->id : $post['id'];
$postTitulo = is_object($post) ? $post->titulo : $post['titulo'];
$postContenido = is_object($post) ? $post->contenido : $post['contenido'];
$postImagen = is_object($post) ? ($post->imagen_portada ?? $post->imagen_url ?? null) : ($post['imagen_portada'] ?? $post['imagen_url'] ?? null);
$postFecha = is_object($post) ? $post->fecha_publicacion : $post['fecha_publicacion'];
$postAutor = is_object($post) ? ($post->autor_nombre ?? 'Admin') : ($post['autor_nombre'] ?? 'Admin');
$postAutorApellidos = is_object($post) ? ($post->autor_apellidos ?? '') : ($post['autor_apellidos'] ?? '');
$autorCompleto = trim($postAutor . ' ' . $postAutorApellidos);
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-5 mb-5" style="background: rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/" class="text-white-50">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/blog" class="text-white-50">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($postTitulo); ?></li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-3"><?php echo htmlspecialchars($postTitulo); ?></h1>
        <p class="lead">
            <i class="bi bi-calendar3 me-2"></i>
            <?php echo formatDate($postFecha, 'blog'); ?>
            <span class="mx-3">|</span>
            <i class="bi bi-person me-2"></i>
            <?php echo htmlspecialchars($autorCompleto); ?>
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Post Content -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm mb-4">
                    <?php if ($postImagen): ?>
                        <img src="<?php echo URL_ROOT; ?>/uploads/news/<?php echo htmlspecialchars($postImagen); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($postTitulo); ?>"
                             style="max-height: 500px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4">
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($postContenido)); ?>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Post Meta -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person text-danger fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($autorCompleto); ?></p>
                                    <small class="text-muted">Autor</small>
                                </div>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Publicado el <?php echo formatDate($postFecha); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </article>
                
                <!-- Share Buttons -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Compartir este artículo</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-facebook me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($postTitulo); ?>" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($postTitulo . ' - ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" 
                               class="btn btn-outline-success btn-sm">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Enlace copiado al portapapeles');" 
                                    class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-clipboard me-1"></i> Copiar enlace
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Posts -->
                <?php if (!empty($data['related_posts'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">Artículos relacionados</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($data['related_posts'] as $related): 
                            $relatedId = is_object($related) ? $related->id : $related['id'];
                            $relatedTitulo = is_object($related) ? $related->titulo : $related['titulo'];
                            $relatedImagen = is_object($related) ? ($related->imagen_portada ?? $related->imagen_url ?? null) : ($related['imagen_portada'] ?? $related['imagen_url'] ?? null);
                            $relatedFecha = is_object($related) ? $related->fecha_publicacion : $related['fecha_publicacion'];
                        ?>
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <?php if ($relatedImagen): ?>
                                <img src="<?php echo URL_ROOT; ?>/uploads/news/<?php echo htmlspecialchars($relatedImagen); ?>" 
                                     class="rounded me-3" 
                                     alt="<?php echo htmlspecialchars($relatedTitulo); ?>"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-danger bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-danger"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    <a href="<?php echo URL_ROOT; ?>/blog/post/<?php echo $relatedId; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($relatedTitulo); ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo formatDate($relatedFecha); ?>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Back to Blog -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <a href="<?php echo URL_ROOT; ?>/blog" class="btn btn-danger w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver al blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
?>

