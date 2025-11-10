<?php
/**
 * Script para ejecutar el SQL de añadir campo tags a la tabla videos
 * Accede desde: http://localhost/prueba-php/public/ejecutar-sql-tags.php
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
    <title>Ejecutar SQL - Añadir Campo Tags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-database me-2"></i>Ejecutar SQL - Añadir Campo Tags</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $db = new Database();
                            
                            // Verificar si el campo ya existe
                            $db->query("SHOW COLUMNS FROM videos LIKE 'tags'");
                            $result = $db->single();
                            
                            if ($result) {
                                echo '<div class="alert alert-info">';
                                echo '<i class="fas fa-info-circle me-2"></i><strong>El campo "tags" ya existe en la tabla "videos".</strong>';
                                echo '</div>';
                            } else {
                                // Ejecutar el ALTER TABLE
                                $db->query("ALTER TABLE videos ADD COLUMN tags VARCHAR(500) DEFAULT NULL AFTER categoria");
                                $db->execute();
                                
                                echo '<div class="alert alert-success">';
                                echo '<i class="fas fa-check-circle me-2"></i><strong>¡Éxito!</strong> El campo "tags" ha sido añadido correctamente a la tabla "videos".';
                                echo '</div>';
                                
                                // Verificar que se creó correctamente
                                $db->query("SHOW COLUMNS FROM videos LIKE 'tags'");
                                $result = $db->single();
                                if ($result) {
                                    echo '<div class="alert alert-success mt-3">';
                                    echo '<strong>Verificación:</strong> El campo existe y está listo para usar.';
                                    echo '</div>';
                                }
                            }
                            
                            // Mostrar estructura actual de la tabla
                            echo '<div class="mt-4">';
                            echo '<h5>Estructura actual de la tabla "videos":</h5>';
                            $db->query("SHOW COLUMNS FROM videos");
                            $columns = $db->resultSet();
                            
                            echo '<table class="table table-striped table-sm">';
                            echo '<thead><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($columns as $col) {
                                $colObj = is_object($col) ? $col : (object)$col;
                                $isTags = ($colObj->Field ?? '') === 'tags';
                                $rowClass = $isTags ? 'table-success' : '';
                                echo '<tr class="' . $rowClass . '">';
                                echo '<td><strong>' . htmlspecialchars($colObj->Field ?? '') . '</strong></td>';
                                echo '<td>' . htmlspecialchars($colObj->Type ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($colObj->Null ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($colObj->Key ?? '') . '</td>';
                                echo '<td>' . htmlspecialchars($colObj->Default ?? 'NULL') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                            echo '</div>';
                            
                        } catch (Exception $e) {
                            $errorMsg = $e->getMessage();
                            
                            // Verificar si el error es porque el campo ya existe
                            if (strpos($errorMsg, 'Duplicate column') !== false || 
                                strpos($errorMsg, 'already exists') !== false ||
                                strpos($errorMsg, 'Duplicate') !== false) {
                                echo '<div class="alert alert-warning">';
                                echo '<i class="fas fa-exclamation-triangle me-2"></i><strong>El campo "tags" ya existe en la tabla "videos".</strong>';
                                echo '<p class="mb-0 mt-2">No es necesario ejecutar el script nuevamente.</p>';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-danger">';
                                echo '<i class="fas fa-times-circle me-2"></i><strong>Error al ejecutar el SQL:</strong>';
                                echo '<p class="mb-0 mt-2">' . htmlspecialchars($errorMsg) . '</p>';
                                echo '</div>';
                            }
                        }
                        ?>
                        
                        <div class="mt-4">
                            <a href="<?php echo URL_ROOT; ?>/admin/videos" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Gestión de Videos
                            </a>
                            <a href="<?php echo URL_ROOT; ?>/admin/dashboard" class="btn btn-secondary">
                                <i class="fas fa-home me-2"></i>Ir al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</body>
</html>

