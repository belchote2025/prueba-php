<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /prueba-php/public/admin');
    exit;
}

require_once '../../src/helpers/FlipbookHelper.php';

$flipbookHelper = new FlipbookHelper();
$message = '';
$error = '';

// Procesar formulario de nuevo flipbook
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_flipbook') {
        $flipbookName = trim($_POST['flipbook_name']);
        
        if (empty($flipbookName)) {
            $error = 'El nombre del flipbook es requerido';
        } else {
            // Crear flipbook
            $result = $flipbookHelper->convertPdfToFlipbook('', $flipbookName);
            
            if ($result['success']) {
                $message = 'Flipbook creado exitosamente';
            } else {
                $error = 'Error al crear flipbook: ' . $result['error'];
            }
        }
    } elseif ($_POST['action'] === 'delete_flipbook') {
        $flipbookName = $_POST['flipbook_name'];
        
        if ($flipbookHelper->deleteFlipbook($flipbookName)) {
            $message = 'Flipbook eliminado exitosamente';
        } else {
            $error = 'Error al eliminar flipbook';
        }
    }
}

// Obtener lista de flipbooks
$flipbooks = $flipbookHelper->getFlipbooks();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Flipbooks - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #dc143c 0%, #8B0000 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .flipbook-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .flipbook-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .flipbook-info h5 {
            color: #dc143c;
            font-weight: 600;
        }

        .flipbook-stats {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #dc143c, #d4af37);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #d4af37, #dc143c);
        }

        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #c82333, #dc3545);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="bi bi-book me-2"></i>Gestión de Flipbooks</h1>
                    <p class="mb-0">Administra los libros interactivos de la Filá Mariscales</p>
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
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Crear Nuevo Flipbook -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Crear Nuevo Flipbook</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="create_flipbook">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="flipbook_name" class="form-label">Nombre del Flipbook</label>
                                        <input type="text" class="form-control" id="flipbook_name" name="flipbook_name" 
                                               placeholder="Ej: Historia de la Filá 2024" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Crear Flipbook
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Flipbooks -->
        <div class="row">
            <div class="col-12">
                <h3><i class="bi bi-collection me-2"></i>Flipbooks Disponibles</h3>
                
                <?php if (empty($flipbooks)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        No hay flipbooks disponibles. ¡Crea el primero!
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($flipbooks as $flipbook): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="flipbook-card">
                                    <div class="flipbook-info">
                                        <h5><?php echo htmlspecialchars($flipbook['name']); ?></h5>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-calendar me-1"></i>
                                            Creado: <?php echo date('d/m/Y', strtotime($flipbook['created_at'])); ?>
                                        </p>
                                        
                                        <div class="flipbook-stats">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="fw-bold text-primary"><?php echo $flipbook['total_pages']; ?></div>
                                                    <small class="text-muted">Páginas</small>
                                                </div>
                                                <div class="col-6">
                                                    <div class="fw-bold text-success"><?php echo ucfirst($flipbook['status']); ?></div>
                                                    <small class="text-muted">Estado</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="/prueba-php/public/libro" class="btn btn-primary btn-sm me-2" target="_blank">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </a>
                                            
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="deleteFlipbook('<?php echo htmlspecialchars($flipbook['name']); ?>')">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres eliminar este flipbook?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="" id="deleteForm" style="display: inline;">
                        <input type="hidden" name="action" value="delete_flipbook">
                        <input type="hidden" name="flipbook_name" id="deleteFlipbookName">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteFlipbook(flipbookName) {
            document.getElementById('deleteFlipbookName').value = flipbookName;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>
