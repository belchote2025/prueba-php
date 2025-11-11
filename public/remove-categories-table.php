<?php
/**
 * Script para eliminar la tabla categories de la base de datos
 * Accede desde: http://localhost/prueba-php/public/remove-categories-table.php
 */

// Cargar configuración
require_once dirname(__DIR__) . '/src/config/config.php';
require_once dirname(__DIR__) . '/src/models/Database.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Tabla Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0"><i class="fas fa-trash-alt me-2"></i>Eliminar Tabla Categories</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $db = new Database();
                            
                            // Verificar si la tabla existe
                            $db->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                       WHERE table_schema = DATABASE() AND table_name = 'categories'");
                            $result = $db->single();
                            $tableExists = $result->count > 0;
                            
                            if ($tableExists) {
                                echo '<div class="alert alert-info">';
                                echo '<i class="fas fa-info-circle me-2"></i><strong>La tabla "categories" existe.</strong>';
                                echo '</div>';
                                
                                // Eliminar la tabla
                                $db->query("DROP TABLE IF EXISTS categories");
                                $db->execute();
                                
                                echo '<div class="alert alert-success">';
                                echo '<i class="fas fa-check-circle me-2"></i><strong>¡Éxito!</strong> La tabla "categories" ha sido eliminada.';
                                echo '</div>';
                                
                                // Verificar eliminación
                                $db->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                           WHERE table_schema = DATABASE() AND table_name = 'categories'");
                                $verifyResult = $db->single();
                                
                                if ($verifyResult->count == 0) {
                                    echo '<div class="alert alert-success mt-3">';
                                    echo '<strong>✓ Verificación:</strong> La tabla ha sido eliminada correctamente.';
                                    echo '</div>';
                                }
                                
                            } else {
                                echo '<div class="alert alert-info">';
                                echo '<i class="fas fa-info-circle me-2"></i><strong>La tabla "categories" no existe.</strong>';
                                echo '<p class="mb-0 mt-2">No hay nada que eliminar.</p>';
                                echo '</div>';
                            }
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="fas fa-times-circle me-2"></i><strong>Error:</strong> ' . htmlspecialchars($e->getMessage());
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="mt-4">
                            <a href="<?php echo URL_ROOT; ?>/admin" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
