<?php
// Función helper para obtener color de categoría
function getCategoryColor($category) {
    $colors = [
        'Fiestas' => 'danger',
        'Cultura' => 'success',
        'Eventos' => 'warning',
        'Historia' => 'info',
        'Actualidad' => 'secondary',
        'Logros' => 'primary'
    ];
    
    return $colors[$category] ?? 'secondary';
}

// Función para obtener resumen del contenido
function getExcerpt($content, $length = 150) {
    $text = strip_tags($content);
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . '...';
    }
    return $text;
}

// Función para obtener imagen por defecto
function getDefaultImage() {
    return URL_ROOT . '/assets/images/placeholder-blog.jpg';
}
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-5 mb-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.9) 0%, rgba(139, 0, 0, 0.9) 100%); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3 text-white">Blog</h1>
        <p class="lead text-white">Noticias, artículos y novedades de la Filá Mariscales</p>
    </div>
</section>

<style>
/* Responsive Blog */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem 0 !important;
        margin-top: 70px;
    }
    
    .hero-section h1 {
        font-size: 1.75rem !important;
    }
    
    .hero-section .lead {
        font-size: 1rem !important;
    }
    
    .card-img-top[style*="height: 400px"] {
        height: 250px !important;
    }
    
    .card-img-top[style*="height: 250px"] {
        height: 200px !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .card-body.p-4 {
        padding: 1.5rem 1rem !important;
    }
}

@media (max-width: 576px) {
    .hero-section h1 {
        font-size: 1.5rem !important;
    }
    
    .card-img-top[style*="height: 400px"],
    .card-img-top[style*="height: 250px"] {
        height: 180px !important;
    }
    
    .card-body.p-4 {
        padding: 1rem !important;
    }
    
    .col-6.col-md-4.col-lg-2 {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <?php if (!empty($data['featured_news']) && $data['current_page'] == 1): ?>
        <!-- Featured Post -->
        <?php 
        $featured = $data['featured_news'];
        $featuredId = is_object($featured) ? $featured->id : $featured['id'];
        $featuredTitulo = is_object($featured) ? $featured->titulo : $featured['titulo'];
        $featuredContenido = is_object($featured) ? $featured->contenido : $featured['contenido'];
        $featuredImagen = is_object($featured) ? ($featured->imagen_portada ?? $featured->imagen_url ?? null) : ($featured['imagen_portada'] ?? $featured['imagen_url'] ?? null);
        $featuredFecha = is_object($featured) ? $featured->fecha_publicacion : $featured['fecha_publicacion'];
        $featuredAutor = is_object($featured) ? ($featured->autor_nombre ?? 'Admin') : ($featured['autor_nombre'] ?? 'Admin');
        ?>
        <div class="row mb-5">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm mb-5">
                    <?php if ($featuredImagen): ?>
                        <img src="<?php echo URL_ROOT; ?>/serve-image.php?path=uploads/news/<?php echo urlencode($featuredImagen); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($featuredTitulo); ?>" 
                             style="height: 400px; object-fit: cover;"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px; display: none;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php else: ?>
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-danger me-2">Destacado</span>
                            <small class="text-muted"><?php echo formatDate($featuredFecha, 'blog'); ?></small>
                        </div>
                        <h2 class="card-title fw-bold"><?php echo htmlspecialchars($featuredTitulo); ?></h2>
                        <p class="card-text lead"><?php echo getExcerpt($featuredContenido, 200); ?></p>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center me-3">
                                <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-danger"></i>
                                </div>
                                <div>
                                    <p class="mb-0 small fw-bold"><?php echo htmlspecialchars($featuredAutor); ?></p>
                                    <p class="mb-0 small text-muted">Autor</p>
                                </div>
                            </div>
                            <a href="<?php echo URL_ROOT; ?>/blog/post/<?php echo $featuredId; ?>" class="btn btn-outline-danger ms-auto">Leer más</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Blog Grid -->
        <?php if (!empty($data['news'])): ?>
        <div class="row g-4 mb-5">
            <?php 
            $startIndex = ($data['current_page'] == 1 && !empty($data['featured_news'])) ? 1 : 0;
            foreach (array_slice($data['news'], $startIndex) as $index => $newsItem): 
                $postId = is_object($newsItem) ? $newsItem->id : $newsItem['id'];
                $postTitulo = is_object($newsItem) ? $newsItem->titulo : $newsItem['titulo'];
                $postContenido = is_object($newsItem) ? $newsItem->contenido : $newsItem['contenido'];
                $postImagen = is_object($newsItem) ? ($newsItem->imagen_portada ?? $newsItem->imagen_url ?? null) : ($newsItem['imagen_portada'] ?? $newsItem['imagen_url'] ?? null);
                $postFecha = is_object($newsItem) ? $newsItem->fecha_publicacion : $newsItem['fecha_publicacion'];
                $postAutor = is_object($newsItem) ? ($newsItem->autor_nombre ?? 'Admin') : ($newsItem['autor_nombre'] ?? 'Admin');
                $postCategoria = 'Actualidad'; // Por defecto, se puede mejorar después
            ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        <?php if ($postImagen): ?>
                            <img src="<?php echo URL_ROOT; ?>/serve-image.php?path=uploads/news/<?php echo urlencode($postImagen); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($postTitulo); ?>"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 250px; display: none;">
                                <i class="bi bi-image text-danger" style="font-size: 3rem;"></i>
                            </div>
                        <?php else: ?>
                            <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="bi bi-image text-danger" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <span class="badge bg-<?php echo getCategoryColor($postCategoria); ?>"><?php echo $postCategoria; ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <small class="text-muted me-3">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo formatDate($postFecha, 'blog'); ?>
                            </small>
                        </div>
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($postTitulo); ?></h5>
                        <p class="card-text text-muted"><?php echo getExcerpt($postContenido, 120); ?></p>
                        <a href="<?php echo URL_ROOT; ?>/blog/post/<?php echo $postId; ?>" class="btn btn-link p-0 text-danger">
                            Leer más <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- No hay posts -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 text-center py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-3 mb-3">No hay publicaciones disponibles</h3>
                        <p class="text-muted mb-4">Aún no se han publicado artículos en el blog. Vuelve pronto para estar al día con las últimas novedades de la Filá Mariscales.</p>
                        <a href="<?php echo URL_ROOT; ?>/" class="btn btn-danger">
                            <i class="bi bi-house me-2"></i>
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <?php if ($data['total_pages'] > 1): ?>
        <nav aria-label="Page navigation" class="mb-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $data['current_page'] == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo URL_ROOT; ?>/blog<?php echo $data['current_page'] > 1 ? '?page=' . ($data['current_page'] - 1) : ''; ?>" 
                       <?php echo $data['current_page'] == 1 ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                        Anterior
                    </a>
                </li>
                <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                    <?php if ($i == 1 || $i == $data['total_pages'] || ($i >= $data['current_page'] - 2 && $i <= $data['current_page'] + 2)): ?>
                        <li class="page-item <?php echo $i == $data['current_page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo URL_ROOT; ?>/blog?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php elseif ($i == $data['current_page'] - 3 || $i == $data['current_page'] + 3): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item <?php echo $data['current_page'] == $data['total_pages'] ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo URL_ROOT; ?>/blog?page=<?php echo $data['current_page'] + 1; ?>"
                       <?php echo $data['current_page'] == $data['total_pages'] ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                        Siguiente
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <!-- Newsletter Section -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card border-0 bg-light">
                    <div class="card-body p-4 text-center">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                                <h3 class="h4 fw-bold mb-3">¿Quieres estar al día?</h3>
                                <p class="mb-0">Suscríbete a nuestro boletín y recibe las últimas noticias directamente en tu correo.</p>
                            </div>
                            <div class="col-12 col-lg-6">
                                <form action="<?php echo URL_ROOT; ?>/newsletter/subscribe" method="POST" class="row g-2">
                                    <div class="col-12">
                                        <input type="email" name="email" class="form-control" placeholder="Tu correo electrónico" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-danger w-100">Suscribirme</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(139, 0, 0, 0.1) 100%); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Explora por categorías</h2>
            <p class="lead text-muted">Descubre contenido organizado por temas</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($data['categories'] as $category): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="<?php echo URL_ROOT; ?>/blog?categoria=<?php echo urlencode($category['name']); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 text-center p-4 hover-shadow">
                        <div class="bg-danger bg-opacity-25 text-danger rounded-circle p-3 mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="bi <?php echo $category['icon']; ?> fs-3"></i>
                        </div>
                        <h5 class="mb-0"><?php echo $category['name']; ?></h5>
                        <small class="text-muted"><?php echo $category['count']; ?> publicaciones</small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
