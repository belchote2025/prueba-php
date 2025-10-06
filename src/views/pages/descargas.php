<?php
require_once __DIR__ . '/../../../src/config/config.php';

// Configurar datos para el layout
$data = [
    'title' => 'Descargas',
    'description' => 'Documentos y archivos para descargar'
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

    .document-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .file-icon {
        font-size: 2.5rem;
    }

    .category-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .search-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .no-documents {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .no-documents i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .download-btn {
        background: linear-gradient(45deg, #dc143c, #8b0000);
        border: none;
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 20, 60, 0.4);
        color: white;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="text-gradient">
                    <i class="bi bi-download me-3"></i>Zona de Descargas
                </h1>
                <p class="lead">Documentos y archivos para socios y simpatizantes de la Filá Mariscales</p>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-5">
    <div class="container">
        <div class="search-section">
            <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-danger"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Buscar documentos...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Todas las categorías</option>
                        <?php if (isset($data['categories'])): ?>
                            <?php foreach ($data['categories'] as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Documents Grid -->
        <div class="row g-4" id="documentsContainer">
            <?php if (!empty($data['documents'])): ?>
                <?php foreach ($data['documents'] as $document): ?>
                    <div class="col-md-6 col-lg-4 document-item" data-category="<?php echo $document->categoria; ?>">
                        <div class="card document-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-light p-3 rounded me-3">
                                        <?php
                                        $icon = 'bi-file-earmark';
                                        $color = 'text-secondary';
                                        
                                        if (strpos($document->archivo_tipo, 'pdf') !== false) {
                                            $icon = 'bi-file-earmark-pdf';
                                            $color = 'text-danger';
                                        } elseif (strpos($document->archivo_tipo, 'word') !== false || strpos($document->archivo_tipo, 'document') !== false) {
                                            $icon = 'bi-file-earmark-word';
                                            $color = 'text-primary';
                                        } elseif (strpos($document->archivo_tipo, 'excel') !== false || strpos($document->archivo_tipo, 'spreadsheet') !== false) {
                                            $icon = 'bi-file-earmark-excel';
                                            $color = 'text-success';
                                        } elseif (strpos($document->archivo_tipo, 'image') !== false) {
                                            $icon = 'bi-file-earmark-image';
                                            $color = 'text-info';
                                        } elseif (strpos($document->archivo_tipo, 'audio') !== false) {
                                            $icon = 'bi-file-earmark-music';
                                            $color = 'text-warning';
                                        }
                                        ?>
                                        <i class="<?php echo $icon; ?> <?php echo $color; ?> file-icon"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($document->titulo); ?></h5>
                                        <p class="text-muted small mb-2"><?php echo date('d/m/Y', strtotime($document->fecha_subida)); ?></p>
                                        <span class="badge bg-light text-dark category-badge">
                                            <?php echo htmlspecialchars($data['categories'][$document->categoria] ?? $document->categoria); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($document->descripcion): ?>
                                    <p class="card-text small text-muted mb-3"><?php echo htmlspecialchars($document->descripcion); ?></p>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="small text-muted">
                                        <?php echo strtoupper(pathinfo($document->archivo_nombre, PATHINFO_EXTENSION)); ?> • 
                                        <?php echo number_format($document->archivo_tamaño / 1024, 1); ?> KB
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-download me-1"></i><?php echo $document->descargas; ?> descargas
                                    </small>
                                </div>
                                
                                <div class="text-center">
                                    <a href="/prueba-php/public/descargar/<?php echo $document->id; ?>" class="download-btn">
                                        <i class="bi bi-download me-1"></i>Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-documents">
                        <i class="bi bi-file-earmark-x"></i>
                        <h3>No hay documentos disponibles</h3>
                        <p>Próximamente se publicarán nuevos documentos y archivos.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const clearSearch = document.getElementById('clearSearch');
    const documentItems = document.querySelectorAll('.document-item');

    // Función para filtrar documentos
    function filterDocuments() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        documentItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            const description = item.querySelector('.card-text') ? item.querySelector('.card-text').textContent.toLowerCase() : '';
            const category = item.getAttribute('data-category');

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                item.style.animation = 'fadeIn 0.5s ease-in';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterDocuments);
    categoryFilter.addEventListener('change', filterDocuments);
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        filterDocuments();
    });

    // Animación fadeIn
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php
// Obtener el contenido del buffer
$content = ob_get_clean();

// Incluir el layout principal
include __DIR__ . '/../layouts/main.php';
?>