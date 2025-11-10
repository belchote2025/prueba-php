<?php
ob_start(); // Start output buffering
?>

<!-- Hero Section with Carousel -->
<div class="hero particles" style="position: relative; min-height: 100vh; display: flex; align-items: center; overflow: hidden;">
    
    <!-- Carrusel de Fotos -->
    <div id="heroCarousel" class="carousel slide" style="position: absolute; width: 100%; height: 100%; z-index: 1; background: rgba(0,0,0,0.3);" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner" style="height: 100%;">
            
            <?php if (!empty($carousel_images)): ?>
                <?php foreach ($carousel_images as $index => $image): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="height: 100vh;">
                        <div class="carousel-image-container" style="width: 100%; height: 100%; background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('<?php echo $image['url']; ?>') center/cover; position: absolute; top: 0; left: 0;"></div>
                        <div class="carousel-caption d-none d-md-block scroll-reveal">
                            <h2 class="animate-fadeInDown text-shimmer" style="font-size: 3rem; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);"><?php echo htmlspecialchars($image['name']); ?></h2>
                            <p class="animate-fadeInUp" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Filá Mariscales</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback si no hay imágenes -->
                <div class="carousel-item active" style="height: 100vh;">
                    <div class="carousel-image-container" style="width: 100%; height: 100%; background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover; position: absolute; top: 0; left: 0;"></div>
                    <div class="carousel-caption d-none d-md-block">
                        <h2 style="font-size: 3rem; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Caballeros Templarios</h2>
                        <p style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Tradición y Honor</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Controles del Carrusel -->
        <button class="carousel-control-prev carousel-control-custom" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next carousel-control-custom" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
        
        <!-- Indicadores del Carrusel -->
        <?php if (!empty($carousel_images)): ?>
            <div class="carousel-indicators" style="bottom: 30px; z-index: 10;">
                <?php foreach ($carousel_images as $index => $image): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                            class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                            aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                            aria-label="Slide <?php echo $index + 1; ?>" 
                            style="width: 12px; height: 12px; border-radius: 50%; background-color: <?php echo $index === 0 ? '#FFFFFF' : 'rgba(255, 255, 255, 0.5)'; ?>; border: 2px solid #FFFFFF; margin: 0 5px;"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Contenido del Hero -->
    <div class="container hero-content" style="position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11 col-12 text-center">
                <h1 class="hero-title">Bienvenidos a la Filá Mariscales</h1>
                <p class="hero-subtitle">Caballeros Templarios de Elche</p>
                <div class="hero-buttons">
                    <a href="<?php echo URL_ROOT; ?>/historia" class="btn-hero btn-hero-primary">
                        <i class="bi bi-shield-fill me-2"></i>Conócenos
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/calendario" class="btn-hero btn-hero-secondary">
                        <i class="bi bi-calendar-event me-2"></i>Próximos Eventos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="py-7 about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
                <h2 class="text-gradient mb-4">Sobre la Filá Mariscales</h2>
                <p class="lead mb-4">Somos una de las filaes más tradicionales y respetadas de las Fiestas de Moros y Cristianos de Elche, fundada en 1985.</p>
                <p class="mb-4">Nuestra filá representa a los Caballeros Templarios, una de las órdenes militares más importantes de la Edad Media. Con más de 35 años de historia, hemos mantenido viva la tradición y el espíritu de las fiestas.</p>
                <div class="d-flex gap-3 flex-wrap stats-container">
                    <div class="text-center stat-item">
                        <h3 class="text-gradient mb-0">150+</h3>
                        <p class="small text-muted">Miembros</p>
                    </div>
                    <div class="text-center stat-item">
                        <h3 class="text-gradient mb-0">35</h3>
                        <p class="small text-muted">Años de Historia</p>
                    </div>
                    <div class="text-center stat-item">
                        <h3 class="text-gradient mb-0">25+</h3>
                        <p class="small text-muted">Eventos Anuales</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="position-relative about-image">
                <div style="width: 100%; height: 400px; background: linear-gradient(135deg, rgba(139, 0, 0, 0.8) 0%, rgba(220, 20, 60, 0.8) 100%); border-radius: 0.5rem; position: relative; overflow: hidden;" class="about-image-container">
                    <img src="<?php echo URL_ROOT; ?>/assets/images/fila-mariscales-event.jpg" 
                         alt="Filá Mariscales - Evento y Comunidad" 
                         class="img-fluid rounded-3 shadow-lg"
                         style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"
                         onerror="this.onerror=null; this.src='<?php echo URL_ROOT; ?>/assets/images/backgrounds/knight-templar-background.jpg';"
                         onload="this.parentElement.style.background='none';">
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="py-7 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="text-gradient mb-5">Próximos Eventos</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($upcoming_events as $event): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card hover-lift h-100">
                    <?php if (!empty($event['image'])): ?>
                        <img src="<?php echo $event['image']; ?>" class="card-img-top" alt="<?php echo $event['title']; ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-gradient-dark d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #8B4513 0%, #654321 100%);">
                            <div class="text-center text-white">
                                <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                                <p class="mt-2 mb-0"><?php echo htmlspecialchars($event['title']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $event['title']; ?></h5>
                        <p class="card-text"><?php echo $event['description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i><?php echo $event['date']; ?>
                            </small>
                            <span class="badge bg-<?php echo $event['status'] === 'Confirmado' ? 'success' : 'warning'; ?>"><?php echo $event['status']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-7">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="text-gradient mb-5">Galería de Fotos</h2>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($gallery as $item): ?>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item position-relative hover-scale">
                    <div class="gallery-image-container">
                        <img src="<?php echo $item['thumb']; ?>" class="gallery-image" alt="<?php echo $item['alt']; ?>">
                    </div>
                    <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0 transition-all rounded-3 d-flex align-items-center justify-content-center">
                        <div class="text-white text-center">
                            <h5><?php echo $item['caption']; ?></h5>
                            <a href="<?php echo $item['full']; ?>" class="btn btn-light btn-sm" target="_blank">
                                <i class="bi bi-zoom-in me-1"></i>Ver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
ob_end_flush(); // End output buffering
?>

<style>
/* Estilos para la galería de fotos uniforme */
.gallery-item {
    height: 100%;
}

.gallery-image-container {
    width: 100%;
    height: 300px; /* Altura fija para todas las imágenes */
    overflow: hidden;
    border-radius: 15px;
    position: relative;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Mantiene la proporción y cubre todo el contenedor */
    object-position: center; /* Centra la imagen */
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.gallery-overlay {
    border-radius: 15px;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .gallery-image-container {
        height: 250px;
    }
}

@media (max-width: 576px) {
    .gallery-image-container {
        height: 200px;
    }
}

/* Estilos para el carrusel uniforme */
.carousel-image-container {
    width: 100%;
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: absolute;
    top: 0;
    left: 0;
    transition: all 0.5s ease;
}

.carousel-item {
    height: 100vh;
    position: relative;
}

.carousel-item.active .carousel-image-container {
    transform: scale(1);
}

.carousel-item:not(.active) .carousel-image-container {
    transform: scale(1.05);
}

/* Hero Content Styles */
.hero-content {
    padding: 2rem 1rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
    color: white;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.5rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    color: white;
    margin-bottom: 2rem;
}

.hero-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-hero {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(240, 240, 240, 0.15) 100%);
    color: #FFFFFF;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    white-space: nowrap;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(240, 240, 240, 0.25) 100%);
    border-color: rgba(255, 255, 255, 0.5);
    color: #FFFFFF;
}

/* Carousel Controls */
.carousel-control-custom {
    width: 50px;
    height: 50px;
    background: rgba(139, 0, 0, 0.5);
    border: 2px solid #FFFFFF;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.carousel-control-custom:hover {
    opacity: 1;
    background: rgba(139, 0, 0, 0.7);
}

/* About Section */
.about-section {
    padding: 4rem 0;
}

.stats-container {
    justify-content: center;
}

.stat-item {
    flex: 1;
    min-width: 100px;
}

.about-image-container {
    height: 400px;
    width: 100%;
    object-fit: cover;
}

/* Responsive para el carrusel y hero */
@media (max-width: 991.98px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
    }
    
    .btn-hero {
        padding: 10px 25px;
        font-size: 0.95rem;
    }
    
    .carousel-control-custom {
        width: 45px;
        height: 45px;
    }
    
    .about-image-container {
        height: 350px;
    }
}

@media (max-width: 768px) {
    .hero {
        min-height: 70vh;
    }
    
    .hero-content {
        padding: 1.5rem 1rem;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    
    .btn-hero {
        padding: 10px 20px;
        font-size: 0.9rem;
        width: 100%;
        max-width: 250px;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .carousel-image-container {
        height: 100vh;
    }
    
    .carousel-control-custom {
        width: 40px;
        height: 40px;
    }
    
    .carousel-caption h2 {
        font-size: 2rem !important;
    }
    
    .carousel-caption p {
        font-size: 1.2rem !important;
    }
    
    .about-section {
        padding: 3rem 0;
    }
    
    .about-image-container {
        height: 300px;
    }
    
    
    .stats-container {
        gap: 1.5rem;
    }
    
    .stat-item {
        min-width: 80px;
    }
}

@media (max-width: 576px) {
    .hero {
        min-height: 60vh;
    }
    
    .hero-content {
        padding: 1rem 0.75rem;
    }
    
    .hero-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: 1.25rem;
    }
    
    .btn-hero {
        padding: 8px 18px;
        font-size: 0.85rem;
        width: 100%;
        max-width: 220px;
    }
    
    .carousel-control-custom {
        width: 35px;
        height: 35px;
    }
    
    .carousel-caption h2 {
        font-size: 1.5rem !important;
    }
    
    .carousel-caption p {
        font-size: 1rem !important;
    }
    
    .about-section {
        padding: 2rem 0;
    }
    
    .about-image-container {
        height: 250px;
    }
    
    
    .stats-container {
        gap: 1rem;
    }
    
    .stat-item h3 {
        font-size: 1.5rem;
    }
    
    .stat-item p {
        font-size: 0.8rem;
    }
}
</style>


