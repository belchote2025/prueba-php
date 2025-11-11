<?php
/**
 * Script para eliminar la tabla categories (versión local)
 * Accede desde: http://localhost/prueba-php/public/remove-categories-table-local.php
 */

// Configuración local de XAMPP (ajusta si es necesario)
$host = 'localhost';
$dbname = 'fila_mariscales_web'; // Ajusta el nombre de tu base de datos
$user = 'root';
$pass = ''; // Contraseña vacía por defecto en XAMPP

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
                            // Conexión directa a MySQL
                            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            echo '<div class="alert alert-success">';
                            echo '<i class="fas fa-check-circle me-2"></i><strong>Conexión exitosa</strong> a la base de datos: ' . htmlspecialchars($dbname);
                            echo '</div>';
                            
                            // Verificar si la tabla existe
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                                WHERE table_schema = '$dbname' AND table_name = 'categories'");
                            $result = $stmt->fetch(PDO::FETCH_OBJ);
                            $tableExists = $result->count > 0;
                            
                            if ($tableExists) {
                                echo '<div class="alert alert-info">';
                                echo '<i class="fas fa-info-circle me-2"></i><strong>La tabla "categories" existe.</strong>';
                                echo '</div>';
                                
                                // Contar registros si los hay
                                try {
                                    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
                                    $countResult = $countStmt->fetch(PDO::FETCH_OBJ);
                                    if ($countResult->count > 0) {
                                        echo '<div class="alert alert-warning">';
                                        echo '<i class="fas fa-exclamation-triangle me-2"></i>La tabla contiene ' . $countResult->count . ' registro(s).';
                                        echo '</div>';
                                    }
                                } catch (Exception $e) {
                                    // Ignorar si no se puede contar
                                }
                                
                                // Eliminar la tabla
                                $pdo->exec("DROP TABLE IF EXISTS categories");
                                
                                echo '<div class="alert alert-success">';
                                echo '<i class="fas fa-check-circle me-2"></i><strong>¡Éxito!</strong> La tabla "categories" ha sido eliminada.';
                                echo '</div>';
                                
                                // Verificar eliminación
                                $verifyStmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables 
                                                         WHERE table_schema = '$dbname' AND table_name = 'categories'");
                                $verifyResult = $verifyStmt->fetch(PDO::FETCH_OBJ);
                                
                                if ($verifyResult->count == 0) {
                                    echo '<div class="alert alert-success mt-3">';
                                    echo '<strong>✓ Verificación:</strong> La tabla ha sido eliminada correctamente de la base de datos.';
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-warning mt-3">';
                                    echo '<strong>⚠ Advertencia:</strong> La tabla aún existe. Verifica permisos.';
                                    echo '</div>';
                                }
                                
                            } else {
                                echo '<div class="alert alert-info">';
                                echo '<i class="fas fa-info-circle me-2"></i><strong>La tabla "categories" no existe en la base de datos.</strong>';
                                echo '<p class="mb-0 mt-2">No hay nada que eliminar.</p>';
                                echo '</div>';
                            }
                            
                        } catch (PDOException $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="fas fa-times-circle me-2"></i><strong>Error de conexión:</strong> ' . htmlspecialchars($e->getMessage());
                            echo '<p class="mt-2"><small>Verifica que:</small></p>';
                            echo '<ul class="small">';
                            echo '<li>XAMPP esté corriendo</li>';
                            echo '<li>El nombre de la base de datos sea correcto</li>';
                            echo '<li>Las credenciales sean correctas (root sin contraseña por defecto)</li>';
                            echo '</ul>';
                            echo '</div>';
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="fas fa-times-circle me-2"></i><strong>Error:</strong> ' . htmlspecialchars($e->getMessage());
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="mt-4">
                            <a href="http://localhost/prueba-php/public/admin" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Admin
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <strong>Nota:</strong> Si este script no funciona, ejecuta este SQL directamente en phpMyAdmin:<br>
                    <code>DROP TABLE IF EXISTS categories;</code>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

