<?php
/**
 * Script para ejecutar el SQL de personalización
 * Accede desde: http://localhost/prueba-php/public/ejecutar-sql-personalizacion.php
 */

require_once dirname(__DIR__) . '/src/config/config.php';
require_once dirname(__DIR__) . '/src/models/Database.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejecutar SQL - Personalización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-database me-2"></i>Ejecutar SQL - Personalización</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            $db = new Database();
                            
                            // Leer el archivo SQL
                            $sqlFile = dirname(__DIR__) . '/database/personalizacion-schema.sql';
                            if (!file_exists($sqlFile)) {
                                throw new Exception('El archivo SQL no existe: ' . $sqlFile);
                            }
                            
                            $sql = file_get_contents($sqlFile);
                            
                            // Ejecutar el SQL línea por línea
                            $lines = explode("\n", $sql);
                            $currentStatement = '';
                            $executed = 0;
                            $errors = [];
                            
                            foreach ($lines as $line) {
                                $line = trim($line);
                                
                                // Ignorar líneas vacías y comentarios
                                if (empty($line) || strpos($line, '--') === 0) {
                                    continue;
                                }
                                
                                // Añadir línea al statement actual
                                $currentStatement .= $line . ' ';
                                
                                // Si la línea termina con ;, ejecutar el statement
                                if (substr($line, -1) === ';') {
                                    $currentStatement = trim($currentStatement);
                                    if (!empty($currentStatement)) {
                                        try {
                                            // Remover el punto y coma final
                                            $currentStatement = rtrim($currentStatement, ';');
                                            $db->query($currentStatement);
                                            $db->execute();
                                            $executed++;
                                        } catch (Exception $e) {
                                            // Ignorar errores de "ya existe"
                                            $errorMsg = $e->getMessage();
                                            if (strpos($errorMsg, 'already exists') === false && 
                                                strpos($errorMsg, 'Duplicate') === false &&
                                                strpos($errorMsg, 'Table') === false) {
                                                $errors[] = $errorMsg . ' (Statement: ' . substr($currentStatement, 0, 100) . '...)';
                                            }
                                        }
                                        $currentStatement = '';
                                    }
                                }
                            }
                            
                            // Ejecutar cualquier statement pendiente
                            if (!empty(trim($currentStatement))) {
                                try {
                                    $currentStatement = rtrim(trim($currentStatement), ';');
                                    $db->query($currentStatement);
                                    $db->execute();
                                    $executed++;
                                } catch (Exception $e) {
                                    $errorMsg = $e->getMessage();
                                    if (strpos($errorMsg, 'already exists') === false && 
                                        strpos($errorMsg, 'Duplicate') === false) {
                                        $errors[] = $errorMsg;
                                    }
                                }
                            }
                            
                            echo '<div class="alert alert-success">';
                            echo '<i class="fas fa-check-circle me-2"></i><strong>¡Éxito!</strong> Se ejecutaron ' . $executed . ' sentencias SQL.';
                            echo '</div>';
                            
                            if (!empty($errors)) {
                                echo '<div class="alert alert-warning mt-3">';
                                echo '<strong>Advertencias:</strong><ul>';
                                foreach ($errors as $error) {
                                    echo '<li>' . htmlspecialchars($error) . '</li>';
                                }
                                echo '</ul></div>';
                            }
                            
                            // Verificar que la tabla existe
                            $db->query("SHOW TABLES LIKE 'personalizacion'");
                            $result = $db->single();
                            
                            if ($result) {
                                echo '<div class="alert alert-info mt-3">';
                                echo '<strong>Verificación:</strong> La tabla "personalizacion" existe y está lista para usar.';
                                echo '</div>';
                                
                                // Mostrar registros
                                $db->query("SELECT COUNT(*) as total FROM personalizacion");
                                $count = $db->single();
                                echo '<div class="alert alert-success mt-3">';
                                echo '<strong>Registros:</strong> ' . ($count->total ?? 0) . ' personalizaciones guardadas.';
                                echo '</div>';
                            }
                            
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">';
                            echo '<i class="fas fa-times-circle me-2"></i><strong>Error:</strong> ' . htmlspecialchars($e->getMessage());
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="mt-4">
                            <a href="<?php echo URL_ROOT; ?>/admin/personalizacion" class="btn btn-primary">
                                <i class="fas fa-palette me-2"></i>Ir a Personalización
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

