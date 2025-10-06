<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/helpers/FlipbookHelper.php';

// Verificar parámetros
if (!isset($_GET['flipbook']) || !isset($_GET['page'])) {
    http_response_code(400);
    echo "Parámetros requeridos: flipbook y page";
    exit;
}

$flipbookName = $_GET['flipbook'];
$pageNumber = (int)$_GET['page'];

// Validar número de página
if ($pageNumber < 1) {
    http_response_code(400);
    echo "Número de página inválido";
    exit;
}

try {
    $flipbookHelper = new FlipbookHelper();
    
    // Verificar que el flipbook existe
    $metadata = $flipbookHelper->getFlipbookMetadata($flipbookName);
    if (!$metadata) {
        http_response_code(404);
        echo "Flipbook no encontrado";
        exit;
    }
    
    // Verificar que la página existe
    if ($pageNumber > $metadata['total_pages']) {
        http_response_code(404);
        echo "Página no encontrada";
        exit;
    }
    
    // Cargar el contenido de la página
    $pageFile = __DIR__ . '/../uploads/flipbooks/' . $flipbookName . '/pages/page_' . $pageNumber . '.html';
    
    if (file_exists($pageFile)) {
        echo file_get_contents($pageFile);
    } else {
        // Si no existe el archivo, generar contenido por defecto
        echo generateDefaultPageContent($pageNumber, $flipbookName);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo "Error interno del servidor";
    error_log("Error en serve-flipbook-page.php: " . $e->getMessage());
}

function generateDefaultPageContent($pageNumber, $flipbookName) {
    $content = '';
    
    switch ($pageNumber) {
        case 1:
            $content = '
            <div class="page-content">
                <div class="page-header">
                    <h1>Filá Mariscales</h1>
                    <h2>Caballeros Templarios</h2>
                </div>
                <div class="page-body">
                    <div class="templar-symbol">⚔️</div>
                    <p class="motto">Tradición, Honor y Hermandad</p>
                    <div class="page-info">
                        <p>Fundada en 1985</p>
                        <p>Elche, Alicante</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 2:
            $content = '
            <div class="page-content">
                <h2>Nuestra Historia</h2>
                <div class="page-body">
                    <p>La Filá Mariscales de Caballeros Templarios nació en 1985 con el propósito de mantener viva la tradición templaria y el honor caballeresco en las fiestas de Elche.</p>
                    <div class="highlight-box">
                        <h3>Misión</h3>
                        <p>Preservar y difundir los valores templarios a través de la cultura y las tradiciones locales.</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 3:
            $content = '
            <div class="page-content">
                <h2>Tradiciones</h2>
                <div class="page-body">
                    <div class="tradition-item">
                        <h3>Desfiles de Moros y Cristianos</h3>
                        <p>Participación activa en las fiestas patronales con trajes históricos y ceremonias tradicionales.</p>
                    </div>
                    <div class="tradition-item">
                        <h3>Ceremonias de Investidura</h3>
                        <p>Rituales solemnes para nuevos miembros de la filá.</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 4:
            $content = '
            <div class="page-content">
                <h2>Actividades</h2>
                <div class="page-body">
                    <div class="activity-item">
                        <h3>Ensayos Semanales</h3>
                        <p>Preparación de desfiles y ceremonias cada viernes.</p>
                    </div>
                    <div class="activity-item">
                        <h3>Eventos Culturales</h3>
                        <p>Organización de conferencias y exposiciones sobre historia templaria.</p>
                    </div>
                    <div class="activity-item">
                        <h3>Actividades Sociales</h3>
                        <p>Comidas de hermandad y eventos comunitarios.</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 5:
            $content = '
            <div class="page-content">
                <h2>Servicios</h2>
                <div class="page-body">
                    <div class="service-item">
                        <h3>Alquiler de Trajes</h3>
                        <p>Trajes históricos para eventos y celebraciones.</p>
                    </div>
                    <div class="service-item">
                        <h3>Asesoramiento Histórico</h3>
                        <p>Consultoría sobre tradiciones y ceremonias templarias.</p>
                    </div>
                    <div class="service-item">
                        <h3>Organización de Eventos</h3>
                        <p>Planificación y ejecución de ceremonias especiales.</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 6:
            $content = '
            <div class="page-content">
                <h2>Información de Contacto</h2>
                <div class="page-body">
                    <div class="contact-info">
                        <h3>Dirección</h3>
                        <p>Calle Templarios, 123<br>03201 Elche, Alicante</p>
                    </div>
                    <div class="contact-info">
                        <h3>Teléfono</h3>
                        <p>965 123 456</p>
                    </div>
                    <div class="contact-info">
                        <h3>Email</h3>
                        <p>info@filamariscales.com</p>
                    </div>
                </div>
            </div>';
            break;
            
        case 7:
            $content = '
            <div class="page-content">
                <h2>Galería de Fotos</h2>
                <div class="page-body">
                    <div class="photo-grid">
                        <div class="photo-placeholder">📸</div>
                        <div class="photo-placeholder">📸</div>
                        <div class="photo-placeholder">📸</div>
                        <div class="photo-placeholder">📸</div>
                    </div>
                    <p class="photo-note">Momentos especiales de la Filá Mariscales</p>
                </div>
            </div>';
            break;
            
        case 8:
            $content = '
            <div class="page-content">
                <div class="page-footer">
                    <h2>¡Únete a Nosotros!</h2>
                    <div class="page-body">
                        <p>Si compartes nuestros valores y quieres formar parte de esta gran familia templaria, no dudes en contactarnos.</p>
                        <div class="join-info">
                            <h3>Requisitos</h3>
                            <ul>
                                <li>Mayor de 18 años</li>
                                <li>Interés por la historia y tradiciones</li>
                                <li>Compromiso con los valores templarios</li>
                            </ul>
                        </div>
                        <div class="footer-motto">
                            <p><strong>"Honor, Lealtad y Tradición"</strong></p>
                            <p>Filá Mariscales de Caballeros Templarios</p>
                        </div>
                    </div>
                </div>
            </div>';
            break;
            
        default:
            $content = '
            <div class="page-content">
                <h2>Página ' . $pageNumber . '</h2>
                <div class="page-body">
                    <p>Contenido de la página ' . $pageNumber . '</p>
                </div>
            </div>';
    }
    
    return $content;
}
?>
