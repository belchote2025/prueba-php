<?php
// Datos de los videos (simulados - en producción vendrían de la base de datos)
$videos = [
    [
        'id' => 'video1',
        'title' => 'Desfile de Moros y Cristianos 2023',
        'description' => 'Participación completa de la Filá Mariscales en las fiestas patronales de Elche. Un espectáculo lleno de tradición, honor y hermandad.',
        'thumbnail' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Agosto 2023',
        'duration' => '15:30',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4'
    ],
    [
        'id' => 'video2',
        'title' => 'Ensayo General 2023',
        'description' => 'Preparación y ensayo previo a las fiestas principales. Los caballeros templarios perfeccionan cada movimiento y coreografía.',
        'thumbnail' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Julio 2023',
        'duration' => '8:45',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4'
    ],
    [
        'id' => 'video3',
        'title' => 'Ceremonia de Iniciación',
        'description' => 'Ritual de bienvenida para nuevos caballeros templarios. Una ceremonia llena de simbolismo y tradición medieval.',
        'thumbnail' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Junio 2023',
        'duration' => '12:20',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_5mb.mp4'
    ],
    [
        'id' => 'video4',
        'title' => 'Hermanamiento con Filá Templaria',
        'description' => 'Intercambio cultural con otra filá templaria de Valencia. Un momento de unión entre hermandades templarias.',
        'thumbnail' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Mayo 2023',
        'duration' => '22:15',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4'
    ],
    [
        'id' => 'video5',
        'title' => 'Conferencia Historia Templaria',
        'description' => 'Charla educativa sobre la historia de los caballeros templarios. Conocimiento y tradición se unen en esta conferencia.',
        'thumbnail' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Abril 2023',
        'duration' => '45:30',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4'
    ],
    [
        'id' => 'video6',
        'title' => 'Comida de Hermandad',
        'description' => 'Momento de convivencia entre todos los miembros de la filá. La hermandad se fortalece en estos encuentros.',
        'thumbnail' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
        'date' => 'Marzo 2023',
        'duration' => '18:45',
        'src' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_5mb.mp4'
    ]
];
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
                        <h3 class="text-gradient mb-0"><?php echo count($videos); ?></h3>
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
                        <?php foreach ($videos as $video): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="video-card">
                                <div class="video-thumbnail">
                                    <div class="video-overlay">
                                        <button class="play-btn" data-video="<?php echo $video['id']; ?>">
                                            <i class="bi bi-play-fill"></i>
                                        </button>
                                    </div>
                                    <img src="<?php echo $video['thumbnail']; ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                                </div>
                                <div class="video-info">
                                    <h4 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h4>
                                    <p class="video-description"><?php echo htmlspecialchars($video['description']); ?></p>
                                    <div class="video-meta">
                                        <span class="video-date"><i class="bi bi-calendar me-1"></i><?php echo $video['date']; ?></span>
                                        <span class="video-duration"><i class="bi bi-clock me-1"></i><?php echo $video['duration']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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
            <video id="modalVideo" controls>
                <source src="" type="video/mp4">
                Tu navegador no soporta el elemento video.
            </video>
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
    
    // Datos de los videos
    const videosData = {
        <?php foreach ($videos as $video): ?>
        '<?php echo $video['id']; ?>': {
            title: '<?php echo addslashes($video['title']); ?>',
            description: '<?php echo addslashes($video['description']); ?>',
            src: '<?php echo $video['src']; ?>'
        },
        <?php endforeach; ?>
    };
    
    // Elementos del modal de video
    const videoModal = document.getElementById('videoModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');
    const modalVideo = document.getElementById('modalVideo');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    
    // Función para abrir el modal de video
    function openVideoModal(videoId) {
        const videoData = videosData[videoId];
        if (!videoData) return;
        
        // Actualizar contenido del modal
        modalTitle.textContent = videoData.title;
        modalDescription.textContent = videoData.description;
        modalVideo.src = videoData.src;
        
        // Mostrar modal
        videoModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Reproducir video automáticamente
        modalVideo.play().catch(e => {
            console.log('Autoplay no permitido:', e);
        });
    }
    
    // Función para cerrar el modal de video
    function closeVideoModal() {
        videoModal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Pausar video
        modalVideo.pause();
        modalVideo.currentTime = 0;
    }
    
    // Event listeners para los botones de play
    document.addEventListener('click', function(e) {
        if (e.target.closest('.play-btn')) {
            const playBtn = e.target.closest('.play-btn');
            const videoId = playBtn.getAttribute('data-video');
            openVideoModal(videoId);
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
