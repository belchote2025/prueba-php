<?php

class FlipbookHelper {
    
    private $uploadsDir;
    private $flipbooksDir;
    
    public function __construct() {
        $this->uploadsDir = __DIR__ . '/../../uploads/';
        $this->flipbooksDir = $this->uploadsDir . 'flipbooks/';
        
        // Crear directorio si no existe
        if (!file_exists($this->flipbooksDir)) {
            mkdir($this->flipbooksDir, 0755, true);
        }
    }
    
    /**
     * Convierte un PDF a páginas de flipbook
     */
    public function convertPdfToFlipbook($pdfFile, $flipbookName) {
        try {
            // Crear directorio para este flipbook
            $flipbookDir = $this->flipbooksDir . $flipbookName . '/';
            if (!file_exists($flipbookDir)) {
                mkdir($flipbookDir, 0755, true);
            }
            
            // Verificar si el archivo PDF existe
            if (!file_exists($pdfFile)) {
                throw new Exception("El archivo PDF no existe: " . $pdfFile);
            }
            
            // Para esta implementación, vamos a crear páginas simuladas
            // En una implementación real, usarías una librería como Imagick o similar
            $pages = $this->createSimulatedPages($pdfFile, $flipbookDir, $flipbookName);
            
            // Guardar metadatos del flipbook
            $metadata = [
                'name' => $flipbookName,
                'created_at' => date('Y-m-d H:i:s'),
                'total_pages' => $pages,
                'pdf_file' => basename($pdfFile),
                'status' => 'ready'
            ];
            
            file_put_contents($flipbookDir . 'metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            
            return [
                'success' => true,
                'pages' => $pages,
                'directory' => $flipbookDir,
                'metadata' => $metadata
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Crea páginas simuladas para el flipbook
     * En una implementación real, esto convertiría el PDF a imágenes
     */
    private function createSimulatedPages($pdfFile, $flipbookDir, $flipbookName) {
        $pagesDir = $flipbookDir . 'pages/';
        if (!file_exists($pagesDir)) {
            mkdir($pagesDir, 0755, true);
        }
        
        // Simular 8 páginas para el flipbook
        $totalPages = 8;
        
        for ($i = 1; $i <= $totalPages; $i++) {
            $pageContent = $this->generatePageContent($i, $flipbookName);
            file_put_contents($pagesDir . "page_$i.html", $pageContent);
        }
        
        return $totalPages;
    }
    
    /**
     * Genera el contenido HTML para una página del flipbook
     */
    private function generatePageContent($pageNumber, $flipbookName) {
        $content = '';
        
        switch ($pageNumber) {
            case 1:
                $content = $this->getPage1Content();
                break;
            case 2:
                $content = $this->getPage2Content();
                break;
            case 3:
                $content = $this->getPage3Content();
                break;
            case 4:
                $content = $this->getPage4Content();
                break;
            case 5:
                $content = $this->getPage5Content();
                break;
            case 6:
                $content = $this->getPage6Content();
                break;
            case 7:
                $content = $this->getPage7Content();
                break;
            case 8:
                $content = $this->getPage8Content();
                break;
            default:
                $content = $this->getDefaultPageContent($pageNumber);
        }
        
        return $content;
    }
    
    private function getPage1Content() {
        return '
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
    }
    
    private function getPage2Content() {
        return '
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
    }
    
    private function getPage3Content() {
        return '
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
    }
    
    private function getPage4Content() {
        return '
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
    }
    
    private function getPage5Content() {
        return '
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
    }
    
    private function getPage6Content() {
        return '
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
    }
    
    private function getPage7Content() {
        return '
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
    }
    
    private function getPage8Content() {
        return '
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
    }
    
    private function getDefaultPageContent($pageNumber) {
        return '
        <div class="page-content">
            <h2>Página ' . $pageNumber . '</h2>
            <div class="page-body">
                <p>Contenido de la página ' . $pageNumber . '</p>
            </div>
        </div>';
    }
    
    /**
     * Obtiene la lista de flipbooks disponibles
     */
    public function getFlipbooks() {
        $flipbooks = [];
        
        if (is_dir($this->flipbooksDir)) {
            $directories = scandir($this->flipbooksDir);
            
            foreach ($directories as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($this->flipbooksDir . $dir)) {
                    $metadataFile = $this->flipbooksDir . $dir . '/metadata.json';
                    
                    if (file_exists($metadataFile)) {
                        $metadata = json_decode(file_get_contents($metadataFile), true);
                        $flipbooks[] = $metadata;
                    }
                }
            }
        }
        
        return $flipbooks;
    }
    
    /**
     * Obtiene los metadatos de un flipbook específico
     */
    public function getFlipbookMetadata($flipbookName) {
        $metadataFile = $this->flipbooksDir . $flipbookName . '/metadata.json';
        
        if (file_exists($metadataFile)) {
            return json_decode(file_get_contents($metadataFile), true);
        }
        
        return null;
    }
    
    /**
     * Elimina un flipbook
     */
    public function deleteFlipbook($flipbookName) {
        $flipbookDir = $this->flipbooksDir . $flipbookName . '/';
        
        if (is_dir($flipbookDir)) {
            $this->deleteDirectory($flipbookDir);
            return true;
        }
        
        return false;
    }
    
    /**
     * Elimina un directorio recursivamente
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
}
