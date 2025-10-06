<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /prueba-php/public/admin');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?> - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #dc143c, #8b0000);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .document-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .file-icon {
            font-size: 2rem;
        }
        
        .category-badge {
            font-size: 0.75rem;
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: #dc143c;
            background-color: #f8f9fa;
        }
        
        .upload-area.dragover {
            border-color: #dc143c;
            background-color: #fff5f5;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-file-earmark-text me-2"></i>Gestión de Documentos</h1>
                    <p class="mb-0">Administra los documentos disponibles para descarga</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="/prueba-php/public/admin" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i>Volver al Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulario de Subida -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-light">
                <h3 class="h5 mb-0">Subir Documento</h3>
            </div>
            <div class="card-body">
                <form id="uploadForm" action="/prueba-php/public/admin/documentos/subir" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="documentTitle" class="form-label">Título del Documento</label>
                            <input type="text" class="form-control" id="documentTitle" name="documentTitle" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="documentCategory" class="form-label">Categoría</label>
                            <select class="form-select" id="documentCategory" name="documentCategory" required>
                                <option value="">Selecciona una categoría</option>
                                <?php if (isset($data['categories'])): ?>
                                    <?php foreach ($data['categories'] as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Archivo</label>
                        <div class="upload-area" id="uploadArea">
                            <i class="bi bi-cloud-upload display-4 text-muted mb-3"></i>
                            <p class="mb-2">Arrastra y suelta tu archivo aquí o haz clic para seleccionar</p>
                            <input type="file" class="form-control d-none" id="documentFile" name="documentFile" required>
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('documentFile').click()">
                                <i class="bi bi-folder2-open me-1"></i>Seleccionar Archivo
                            </button>
                            <div class="form-text mt-2">Formatos aceptados: PDF, DOCX, XLSX, JPG, PNG, MP3 (máx. 20MB)</div>
                        </div>
                        <div id="fileInfo" class="mt-2" style="display: none;">
                            <div class="alert alert-info">
                                <i class="bi bi-file-earmark me-2"></i>
                                <span id="fileName"></span> (<span id="fileSize"></span>)
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="documentDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="documentDescription" name="documentDescription" rows="3" placeholder="Descripción opcional del documento"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Subir Documento
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de Documentos -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0">Documentos Subidos</h3>
                <span class="badge bg-primary"><?php echo $data['totalDocuments'] ?? 0; ?> documentos</span>
            </div>
            <div class="card-body">
                <?php if (!empty($data['documents'])): ?>
                    <div class="row g-4">
                        <?php foreach ($data['documents'] as $document): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card document-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-light p-2 rounded me-3">
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
                                                <h6 class="card-title mb-1"><?php echo htmlspecialchars($document->titulo); ?></h6>
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
                                            <small class="text-muted">
                                                <?php echo strtoupper(pathinfo($document->archivo_nombre, PATHINFO_EXTENSION)); ?> • 
                                                <?php echo number_format($document->archivo_tamaño / 1024, 1); ?> KB
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-download me-1"></i><?php echo $document->descargas; ?> descargas
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="/prueba-php/public/admin/documentos/<?php echo $document->id; ?>/editar" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil me-1"></i>Editar
                                            </a>
                                            <form method="POST" action="/prueba-php/public/admin/documentos/<?php echo $document->id; ?>/eliminar" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este documento?')">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Paginación -->
                    <?php if (isset($data['totalPages']) && $data['totalPages'] > 1): ?>
                        <nav aria-label="Paginación de documentos" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                    <li class="page-item <?php echo $i == $data['currentPage'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">No hay documentos subidos</h4>
                        <p class="text-muted">Sube tu primer documento usando el formulario de arriba.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('documentFile');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        uploadArea.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            uploadArea.classList.add('dragover');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('dragover');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                showFileInfo(files[0]);
            }
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                showFileInfo(e.target.files[0]);
            }
        });

        function showFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.style.display = 'block';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('documentFile');
            const titleInput = document.getElementById('documentTitle');
            const categoryInput = document.getElementById('documentCategory');

            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Por favor selecciona un archivo');
                return;
            }

            if (!titleInput.value.trim()) {
                e.preventDefault();
                alert('Por favor ingresa un título para el documento');
                return;
            }

            if (!categoryInput.value) {
                e.preventDefault();
                alert('Por favor selecciona una categoría');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Subiendo...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
