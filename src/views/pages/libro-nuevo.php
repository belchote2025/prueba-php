<?php
require_once __DIR__ . '/../../../src/config/config.php';
require_once __DIR__ . '/../../../src/helpers/FlipbookHelper.php';

// Inicializar el helper de flipbook
$flipbookHelper = new FlipbookHelper();

// Obtener flipbooks disponibles
$flipbooks = $flipbookHelper->getFlipbooks();

// Si no hay flipbooks, crear uno por defecto
if (empty($flipbooks)) {
    $result = $flipbookHelper->convertPdfToFlipbook('', 'filamariscales_default');
    if ($result['success']) {
        $flipbooks = $flipbookHelper->getFlipbooks();
    }
}

// Usar el primer flipbook disponible
$currentFlipbook = !empty($flipbooks) ? $flipbooks[0] : null;

// Configurar datos para el layout
$data = [
    'title' => 'Libro Interactivo',
    'description' => 'Descubre la historia y tradiciones de la Filá Mariscales de Caballeros Templarios'
];

// Iniciar el buffer de salida
ob_start();
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.1) 100%);
        padding: 4rem 0 2rem 0;
        margin-top: 80px; /* Espacio para navbar fijo */
    }

    .text-gradient {
        background: linear-gradient(45deg, #dc143c, #8b0000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-family: 'Cinzel', serif;
        font-weight: 700;
    }

    .flipbook-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin: 2rem auto;
        max-width: 1200px;
    }

    .heyzine-flipbook {
        text-align: center;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="text-gradient">
                    <i class="bi bi-book me-3"></i>Libro Interactivo
                </h1>
                <p class="lead">Descubre la historia y tradiciones de la Filá Mariscales de Caballeros Templarios</p>
            </div>
        </div>
    </div>
</section>

<!-- Flipbook Container -->
<div class="flipbook-container">
    <!-- Flipbook de Heyzine embebido -->
    <div class="heyzine-flipbook">
        <iframe 
            allowfullscreen="allowfullscreen" 
            allow="clipboard-write" 
            scrolling="no" 
            class="fp-iframe" 
            src="https://heyzine.com/flip-book/fcf3fbe7c1.html" 
            style="border: 1px solid lightgray; width: 100%; height: 800px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        </iframe>
    </div>
</div>

<?php
// Obtener el contenido del buffer
$content = ob_get_clean();

// Incluir el layout principal
include __DIR__ . '/../layouts/main.php';
?>

