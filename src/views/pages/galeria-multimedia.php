<?php
// Obtener videos de $data
$videos = $data['videos'] ?? [];
?>

<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.8) 50%, rgba(220, 20, 60, 0.1) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold text-gradient mb-4">
                    <i class="bi bi-play-circle me-3"></i>Galería Multimedia
                </h1>
                <p class="lead mb-5">Revive los mejores momentos de la Filá Mariscales en acción</p>
                <div class="gallery-stats d-flex justify-content-center gap-4 mb-5">
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0"><?php echo $data['video_count'] ?? count($videos); ?></h3>
                        <small class="text-muted">Videos</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0">6</h3>
                        <small class="text-muted">Meses</small>
                    </div>
                    <div class="stat-item">
                        <h3 class="text-gradient mb-0">39</h3>
                        <small class="text-muted">Años</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Videos Section -->
<section class="videos-section py-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.05) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Videos Header -->
                <div class="videos-header text-center mb-5">
                    <h2 class="display-5 fw-bold text-gradient mb-3">
                        <i class="bi bi-play-circle me-3"></i>Videos de Actuaciones
                    </h2>
                    <p class="lead mb-4">Descubre la tradición y el honor de la Filá Mariscales</p>
                </div>

                <!-- Videos Grid -->
                <div class="videos-grid">
                    <div class="row">
                        <?php if (!empty($videos)): ?>
                            <?php foreach ($videos as $video): 
                                $videoObj = is_object($video) ? $video : (object)$video;
                                $videoId = $videoObj->id ?? 0;
                                $videoTitulo = $videoObj->titulo ?? 'Sin título';
                                $videoDescripcion = $videoObj->descripcion ?? '';
                                $videoUrl = $videoObj->url_video ?? '';
                                $videoThumbnail = $videoObj->url_thumbnail ?? '';
                                $videoTipo = $videoObj->tipo ?? 'youtube';
                                $videoFecha = $videoObj->fecha_subida ?? date('Y-m-d');
                                $videoDuracion = $videoObj->duracion ?? 0;
                                
                                // Formatear duración
                                $duracionFormato = '';
                                if ($videoDuracion > 0) {
                                    $minutos = floor($videoDuracion / 60);
                                    $segundos = $videoDuracion % 60;
                                    $duracionFormato = sprintf('%02d:%02d', $minutos, $segundos);
                                }
                                
                                // Determinar URL del video según tipo
                                $videoSrc = '';
                                if ($videoTipo === 'youtube') {
                                    $videoModel = new Video();
                                    $youtubeId = $videoModel->extractYouTubeId($videoUrl);
                                    if ($youtubeId) {
                                        $videoSrc = 'https://www.youtube.com/embed/' . $youtubeId;
                                        if (!$videoThumbnail) {
                                            $videoThumbnail = 'https://img.youtube.com/vi/' . $youtubeId . '/maxresdefault.jpg';
                                        }
                                    }
                                } elseif ($videoTipo === 'vimeo') {
                                    $videoModel = new Video();
                                    $vimeoId = $videoModel->extractVimeoId($videoUrl);
                                    if ($vimeoId) {
                                        $videoSrc = 'https://player.vimeo.com/video/' . $vimeoId;
                                    }
                                } else {
                                    $videoSrc = $videoUrl;
                                }
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="video-card">
                                    <div class="video-thumbnail">
                                        <div class="video-overlay">
                                            <button class="play-btn" data-video-id="<?php echo $videoId; ?>" data-video-src="<?php echo htmlspecialchars($videoSrc); ?>" data-video-tipo="<?php echo $videoTipo; ?>">
                                                <i class="bi bi-play-fill"></i>
                                            </button>
                                        </div>
                                        <?php if ($videoThumbnail): ?>
                                            <img src="<?php echo htmlspecialchars($videoThumbnail); ?>" alt="<?php echo htmlspecialchars($videoTitulo); ?>" 
                                                 onerror="this.onerror=null; this.src='<?php echo URL_ROOT; ?>/assets/images/default-video.jpg';">
                                        <?php else: ?>
                                            <div class="bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="bi bi-play-circle text-danger" style="font-size: 3rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="video-info">
                                        <h4 class="video-title"><?php echo htmlspecialchars($videoTitulo); ?></h4>
                                        <?php if ($videoDescripcion): ?>
                                            <p class="video-description"><?php echo htmlspecialchars(substr($videoDescripcion, 0, 100)) . (strlen($videoDescripcion) > 100 ? '...' : ''); ?></p>
                                        <?php endif; ?>
                                        <div class="video-meta">
                                            <span class="video-date"><i class="bi bi-calendar me-1"></i><?php echo formatDate($videoFecha, 'blog'); ?></span>
                                            <?php if ($duracionFormato): ?>
                                                <span class="video-duration"><i class="bi bi-clock me-1"></i><?php echo $duracionFormato; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-play-circle text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3">Aún no hay videos disponibles</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div class="video-modal" id="videoModal">
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal-content">
        <button class="modal-close" id="modalClose">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="video-container">
            <div id="videoPlayer"></div>
        </div>
        <div class="video-details">
            <h3 id="modalTitle"></h3>
            <p id="modalDescription"></p>
        </div>
    </div>
</div>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(220, 20, 60, 0.05) 100%);
    border-bottom: 3px solid var(--primary);
}

.text-gradient {
    background: linear-gradient(135deg, var(--primary) 0%, #8B0000 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.gallery-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-item h3 {
    font-family: 'Cinzel', serif;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-item small {
    font-family: 'Crimson Text', serif;
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Videos Section Styles */
.videos-section {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.05) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.05) 100%);
}

/* Particle Background */
.videos-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 20%, rgba(220, 20, 60, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 80% 80%, rgba(220, 20, 60, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 40% 60%, rgba(220, 20, 60, 0.05) 1px, transparent 1px);
    background-size: 100px 100px, 150px 150px, 200px 200px;
    animation: particleFloat 20s ease-in-out infinite;
    pointer-events: none;
}

@keyframes particleFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-10px) rotate(1deg); }
    50% { transform: translateY(-5px) rotate(-1deg); }
    75% { transform: translateY(-15px) rotate(0.5deg); }
}

.videos-header {
    position: relative;
    z-index: 2;
}

.videos-grid {
    position: relative;
    z-index: 2;
}

/* Video Cards */
.video-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(220, 20, 60, 0.1);
    animation: cardSlideIn 0.6s ease-out;
    animation-fill-mode: both;
}

.video-card:nth-child(1) { animation-delay: 0.1s; }
.video-card:nth-child(2) { animation-delay: 0.2s; }
.video-card:nth-child(3) { animation-delay: 0.3s; }
.video-card:nth-child(4) { animation-delay: 0.4s; }
.video-card:nth-child(5) { animation-delay: 0.5s; }
.video-card:nth-child(6) { animation-delay: 0.6s; }

@keyframes cardSlideIn {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.video-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(220, 20, 60, 0.2);
    border-color: var(--primary);
}

/* Video Thumbnail */
.video-thumbnail {
    position: relative;
    overflow: hidden;
    height: 200px;
    background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
}

.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.video-card:hover .video-thumbnail img {
    transform: scale(1.1);
}

/* Video Overlay */
.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(220, 20, 60, 0.8) 0%, rgba(139, 0, 0, 0.8) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.video-card:hover .video-overlay {
    opacity: 1;
}

/* Play Button */
.play-btn {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--primary);
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.play-btn:hover {
    transform: scale(1.1);
    background: white;
    box-shadow: 0 8px 25px rgba(220, 20, 60, 0.3);
}

.play-btn i {
    margin-left: 4px; /* Ajuste visual para centrar el icono */
}

/* Video Info */
.video-info {
    padding: 1.5rem;
}

.video-title {
    font-family: 'Cinzel', serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.video-description {
    font-family: 'Crimson Text', serif;
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.video-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: #888;
}

.video-date,
.video-duration {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Video Modal */
.video-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.video-modal.active {
    display: flex;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 20px;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(0, 0, 0, 0.7);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(220, 20, 60, 0.8);
    transform: scale(1.1);
}

.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    background: #000;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-details {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(248, 249, 250, 0.9) 0%, rgba(233, 236, 239, 0.9) 100%);
}

.video-details h3 {
    font-family: 'Cinzel', serif;
    color: var(--primary);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.video-details p {
    font-family: 'Crimson Text', serif;
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* Responsive Design for Videos */
@media (max-width: 768px) {
    .video-modal {
        padding: 1rem;
    }
    
    .modal-content {
        max-height: 95vh;
    }
    
    .video-container {
        padding-bottom: 60%;
    }
    
    .video-details {
        padding: 1rem;
    }
    
    .video-details h3 {
        font-size: 1.2rem;
    }
    
    .play-btn {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .video-thumbnail {
        height: 150px;
    }
    
    .gallery-stats {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .video-card {
        margin-bottom: 1rem;
    }
    
    .video-info {
        padding: 1rem;
    }
    
    .video-title {
        font-size: 1rem;
    }
    
    .video-description {
        font-size: 0.8rem;
    }
    
    .video-meta {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== FUNCIONALIDAD DE VIDEOS =====
    
    // Elementos del modal de video
    const videoModal = document.getElementById('videoModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');
    
    // Función para abrir modal con video
    function openVideoModal(videoSrc, videoTipo, videoTitulo, videoDescripcion) {
        const videoPlayer = document.getElementById('videoPlayer');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        
        // Limpiar contenido anterior
        videoPlayer.innerHTML = '';
        
        // Crear iframe según el tipo
        if (videoTipo === 'youtube' || videoTipo === 'vimeo') {
            const iframe = document.createElement('iframe');
            iframe.src = videoSrc;
            iframe.width = '100%';
            iframe.height = '500';
            iframe.frameBorder = '0';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            iframe.style.borderRadius = '10px';
            videoPlayer.appendChild(iframe);
        } else {
            // Video local
            const video = document.createElement('video');
            video.src = videoSrc;
            video.controls = true;
            video.style.width = '100%';
            video.style.borderRadius = '10px';
            videoPlayer.appendChild(video);
        }
        
        modalTitle.textContent = videoTitulo || 'Video';
        modalDescription.textContent = videoDescripcion || '';
        
        videoModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Event listeners para los botones de play
    document.addEventListener('click', function(e) {
        if (e.target.closest('.play-btn')) {
            const playBtn = e.target.closest('.play-btn');
            const videoSrc = playBtn.getAttribute('data-video-src');
            const videoTipo = playBtn.getAttribute('data-video-tipo') || 'local';
            const videoCard = playBtn.closest('.video-card');
            const videoTitulo = videoCard ? videoCard.querySelector('.video-title')?.textContent : '';
            const videoDescripcion = videoCard ? videoCard.querySelector('.video-description')?.textContent : '';
            
            if (videoSrc) {
                openVideoModal(videoSrc, videoTipo, videoTitulo, videoDescripcion);
            }
        }
    });
    
    // Event listeners para cerrar el modal
    modalClose.addEventListener('click', closeVideoModal);
    modalOverlay.addEventListener('click', closeVideoModal);
    
    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal.classList.contains('active')) {
            closeVideoModal();
        }
    });
    
    // Efectos de partículas adicionales
    function createFloatingParticles() {
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'floating-particles';
        particlesContainer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        `;
        
        // Crear partículas flotantes
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: rgba(220, 20, 60, 0.3);
                border-radius: 50%;
                animation: floatParticle ${5 + Math.random() * 10}s ease-in-out infinite;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation-delay: ${Math.random() * 5}s;
            `;
            particlesContainer.appendChild(particle);
        }
        
        // Añadir estilos de animación
        const style = document.createElement('style');
        style.textContent = `
            @keyframes floatParticle {
                0%, 100% { 
                    transform: translateY(0px) translateX(0px) scale(1);
                    opacity: 0.3;
                }
                25% { 
                    transform: translateY(-20px) translateX(10px) scale(1.2);
                    opacity: 0.6;
                }
                50% { 
                    transform: translateY(-10px) translateX(-15px) scale(0.8);
                    opacity: 0.4;
                }
                75% { 
                    transform: translateY(-30px) translateX(5px) scale(1.1);
                    opacity: 0.7;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Añadir al contenedor de videos
        const videosSection = document.querySelector('.videos-section');
        if (videosSection) {
            videosSection.appendChild(particlesContainer);
        }
    }
    
    // Inicializar partículas flotantes
    createFloatingParticles();
    
    console.log('Galería multimedia inicializada');
});
</script>
