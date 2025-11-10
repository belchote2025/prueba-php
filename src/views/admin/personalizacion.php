<?php
// Verificar que las constantes estén definidas
if (!defined('URL_ROOT')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/src/config/config.php';
}

// Verificar autenticación
if (!function_exists('isAdminLoggedIn')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/src/config/admin_credentials.php';
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isAdminLoggedIn()) {
    if (!headers_sent()) {
        header('Location: ' . URL_ROOT . '/admin/login');
    } else {
        echo '<script>window.location.href = "' . URL_ROOT . '/admin/login";</script>';
    }
    exit;
}

// Verificar que $data esté definido
if (!isset($data) && isset($GLOBALS['data'])) {
    $data = $GLOBALS['data'];
} elseif (!isset($data)) {
    $data = [];
}

$colores = $data['colores'] ?? [];
$fuentes = $data['fuentes'] ?? [];
$animaciones = $data['animaciones'] ?? [];
$generales = $data['generales'] ?? [];

// Si no hay datos, mostrar mensaje informativo
$hasData = !empty($colores) || !empty($fuentes) || !empty($animaciones) || !empty($generales);

$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalización - Panel de Administración</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?php echo URL_ROOT; ?>/assets/css/admin.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link href="<?php echo URL_ROOT; ?>/assets/css/theme.css" rel="stylesheet">
    <!-- Personalización dinámica -->
    <link href="<?php echo URL_ROOT; ?>/aplicar-personalizacion.php" rel="stylesheet">
    
    <style>
        .color-picker-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .color-preview {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .color-preview:hover {
            transform: scale(1.1);
        }
        
        .preview-section {
            position: sticky;
            top: 20px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            box-shadow: var(--shadow-md);
        }
        
        .preview-card {
            margin-bottom: var(--spacing-md);
        }
        
        .font-select {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URL_ROOT; ?>/admin/dashboard">
                <i class="fas fa-shield-alt me-2"></i>Panel de Administración
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/dashboard">Dashboard</a>
                    <a class="nav-link active" href="<?php echo URL_ROOT; ?>/admin/personalizacion">Personalización</a>
                    <a class="nav-link" href="<?php echo URL_ROOT; ?>/admin/logout">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-palette me-2"></i>Personalización del Sitio</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($successMessage): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!$hasData): ?>
                            <div class="alert alert-warning mb-4">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i>No hay personalizaciones configuradas</h5>
                                <p class="mb-2">Parece que la tabla de personalización no existe o está vacía. Puedes usar los valores por defecto o ejecutar el SQL para crear la tabla.</p>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo URL_ROOT; ?>/ejecutar-sql-personalizacion.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-database me-2"></i>Ejecutar SQL de Personalización
                                    </a>
                                    <a href="<?php echo URL_ROOT; ?>/debug-personalizacion.php" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-bug me-2"></i>Ejecutar Diagnóstico
                                    </a>
                                </div>
                                <p class="mt-3 mb-0"><small><strong>Nota:</strong> Puedes usar el formulario de abajo con valores por defecto. Al guardar, se crearán los registros automáticamente.</small></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo URL_ROOT; ?>/admin/guardar-personalizacion" id="personalizacionForm">
                            <!-- Colores -->
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-fill-drip me-2"></i>Colores</h5>
                                <div class="row">
                                    <?php 
                                    $colorMap = [
                                        'primary-color' => 'Color Principal',
                                        'secondary-color' => 'Color Secundario',
                                        'success-color' => 'Color de Éxito',
                                        'info-color' => 'Color Informativo',
                                        'warning-color' => 'Color de Advertencia',
                                        'danger-color' => 'Color de Peligro'
                                    ];
                                    
                                    foreach ($colorMap as $key => $label):
                                        $color = null;
                                        foreach ($colores as $c) {
                                            $cObj = is_object($c) ? $c : (object)$c;
                                            if (($cObj->nombre ?? '') === $key) {
                                                $color = $cObj;
                                                break;
                                            }
                                        }
                                        $valor = $color ? (is_object($color) ? $color->valor : $color['valor']) : '#8B0000';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?php echo $label; ?></label>
                                        <div class="color-picker-wrapper">
                                            <input type="color" 
                                                   class="form-control form-control-color" 
                                                   name="colores[<?php echo $key; ?>]" 
                                                   value="<?php echo htmlspecialchars($valor); ?>"
                                                   onchange="updateColorPreview('<?php echo $key; ?>', this.value)">
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="color-<?php echo $key; ?>" 
                                                   name="colores[<?php echo $key; ?>]" 
                                                   value="<?php echo htmlspecialchars($valor); ?>"
                                                   onchange="updateColorPreview('<?php echo $key; ?>', this.value)">
                                            <div class="color-preview" 
                                                 id="preview-<?php echo $key; ?>" 
                                                 style="background-color: <?php echo htmlspecialchars($valor); ?>"></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Fuentes -->
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-font me-2"></i>Fuentes</h5>
                                <div class="row">
                                    <?php 
                                    $fontFamilies = [
                                        'Segoe UI, Tahoma, Geneva, Verdana, sans-serif' => 'Segoe UI (Moderno)',
                                        'Arial, Helvetica, sans-serif' => 'Arial (Clásico)',
                                        'Georgia, serif' => 'Georgia (Elegante)',
                                        'Times New Roman, serif' => 'Times New Roman (Tradicional)',
                                        'Courier New, monospace' => 'Courier New (Monospace)',
                                        'Verdana, Geneva, sans-serif' => 'Verdana (Legible)',
                                        'Trebuchet MS, sans-serif' => 'Trebuchet MS (Moderno)',
                                        'Impact, Charcoal, sans-serif' => 'Impact (Bold)'
                                    ];
                                    
                                    $fontFamily = null;
                                    foreach ($fuentes as $f) {
                                        $fObj = is_object($f) ? $f : (object)$f;
                                        if (($fObj->nombre ?? '') === 'font-family') {
                                            $fontFamily = $fObj;
                                            break;
                                        }
                                    }
                                    $fontValue = $fontFamily ? (is_object($fontFamily) ? $fontFamily->valor : $fontFamily['valor']) : 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Familia de Fuente</label>
                                        <select class="form-select font-select" name="fuentes[font-family]" onchange="updateFontPreview(this.value)">
                                            <?php foreach ($fontFamilies as $font => $label): ?>
                                                <option value="<?php echo htmlspecialchars($font); ?>" <?php echo $fontValue === $font ? 'selected' : ''; ?>>
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php 
                                    $fontSize = null;
                                    if (!empty($fuentes)) {
                                        foreach ($fuentes as $f) {
                                            $fObj = is_object($f) ? $f : (object)$f;
                                            if (($fObj->nombre ?? '') === 'font-size-base') {
                                                $fontSize = $fObj;
                                                break;
                                            }
                                        }
                                    }
                                    $fontSizeValue = $fontSize ? (is_object($fontSize) ? $fontSize->valor : $fontSize['valor']) : '1rem';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tamaño de Fuente Base</label>
                                        <select class="form-select" name="fuentes[font-size-base]">
                                            <option value="0.875rem" <?php echo $fontSizeValue === '0.875rem' ? 'selected' : ''; ?>>Pequeño (0.875rem)</option>
                                            <option value="1rem" <?php echo $fontSizeValue === '1rem' ? 'selected' : ''; ?>>Normal (1rem)</option>
                                            <option value="1.125rem" <?php echo $fontSizeValue === '1.125rem' ? 'selected' : ''; ?>>Grande (1.125rem)</option>
                                            <option value="1.25rem" <?php echo $fontSizeValue === '1.25rem' ? 'selected' : ''; ?>>Muy Grande (1.25rem)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Animaciones -->
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-magic me-2"></i>Animaciones</h5>
                                <div class="row">
                                    <?php 
                                    $animations = [
                                        'fadeIn' => 'Fade In (Desvanecer)',
                                        'slideIn' => 'Slide In (Deslizar)',
                                        'pulse' => 'Pulse (Pulso)',
                                        'bounce' => 'Bounce (Rebote)',
                                        'none' => 'Sin Animación'
                                    ];
                                    
                                    $cardAnim = null;
                                    if (!empty($animaciones)) {
                                        foreach ($animaciones as $a) {
                                            $aObj = is_object($a) ? $a : (object)$a;
                                            if (($aObj->nombre ?? '') === 'card-animation') {
                                                $cardAnim = $aObj;
                                                break;
                                            }
                                        }
                                    }
                                    $cardAnimValue = $cardAnim ? (is_object($cardAnim) ? $cardAnim->valor : $cardAnim['valor']) : 'fadeIn';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Animación para Cards</label>
                                        <select class="form-select" name="animaciones[card-animation]">
                                            <?php foreach ($animations as $anim => $label): ?>
                                                <option value="<?php echo $anim; ?>" <?php echo $cardAnimValue === $anim ? 'selected' : ''; ?>>
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php 
                                    $buttonAnim = null;
                                    if (!empty($animaciones)) {
                                        foreach ($animaciones as $a) {
                                            $aObj = is_object($a) ? $a : (object)$a;
                                            if (($aObj->nombre ?? '') === 'button-animation') {
                                                $buttonAnim = $aObj;
                                                break;
                                            }
                                        }
                                    }
                                    $buttonAnimValue = $buttonAnim ? (is_object($buttonAnim) ? $buttonAnim->valor : $buttonAnim['valor']) : 'pulse';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Animación para Botones</label>
                                        <select class="form-select" name="animaciones[button-animation]">
                                            <?php foreach ($animations as $anim => $label): ?>
                                                <option value="<?php echo $anim; ?>" <?php echo $buttonAnimValue === $anim ? 'selected' : ''; ?>>
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php 
                                    $transitionSpeed = null;
                                    if (!empty($animaciones)) {
                                        foreach ($animaciones as $a) {
                                            $aObj = is_object($a) ? $a : (object)$a;
                                            if (($aObj->nombre ?? '') === 'transition-speed') {
                                                $transitionSpeed = $aObj;
                                                break;
                                            }
                                        }
                                    }
                                    $transitionSpeedValue = $transitionSpeed ? (is_object($transitionSpeed) ? $transitionSpeed->valor : $transitionSpeed['valor']) : '0.3s';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Velocidad de Transiciones</label>
                                        <select class="form-select" name="animaciones[transition-speed]">
                                            <option value="0.15s" <?php echo $transitionSpeedValue === '0.15s' ? 'selected' : ''; ?>>Rápido (0.15s)</option>
                                            <option value="0.3s" <?php echo $transitionSpeedValue === '0.3s' ? 'selected' : ''; ?>>Normal (0.3s)</option>
                                            <option value="0.5s" <?php echo $transitionSpeedValue === '0.5s' ? 'selected' : ''; ?>>Lento (0.5s)</option>
                                            <option value="1s" <?php echo $transitionSpeedValue === '1s' ? 'selected' : ''; ?>>Muy Lento (1s)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Generales -->
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-cog me-2"></i>Configuración General</h5>
                                <div class="row">
                                    <?php 
                                    $borderRadius = null;
                                    if (!empty($generales)) {
                                        foreach ($generales as $g) {
                                            $gObj = is_object($g) ? $g : (object)$g;
                                            if (($gObj->nombre ?? '') === 'border-radius') {
                                                $borderRadius = $gObj;
                                                break;
                                            }
                                        }
                                    }
                                    $borderRadiusValue = $borderRadius ? (is_object($borderRadius) ? $borderRadius->valor : $borderRadius['valor']) : '8px';
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Radio de Bordes</label>
                                        <select class="form-select" name="generales[border-radius]">
                                            <option value="0" <?php echo $borderRadiusValue === '0' ? 'selected' : ''; ?>>Sin Bordes (0)</option>
                                            <option value="4px" <?php echo $borderRadiusValue === '4px' ? 'selected' : ''; ?>>Pequeño (4px)</option>
                                            <option value="8px" <?php echo $borderRadiusValue === '8px' ? 'selected' : ''; ?>>Normal (8px)</option>
                                            <option value="12px" <?php echo $borderRadiusValue === '12px' ? 'selected' : ''; ?>>Grande (12px)</option>
                                            <option value="20px" <?php echo $borderRadiusValue === '20px' ? 'selected' : ''; ?>>Muy Grande (20px)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetearPersonalizacion()">
                                    <i class="fas fa-undo me-2"></i>Resetear a Valores por Defecto
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Vista Previa -->
            <div class="col-lg-4">
                <div class="preview-section">
                    <h5 class="mb-3"><i class="fas fa-eye me-2"></i>Vista Previa</h5>
                    
                    <div class="preview-card card">
                        <div class="card-header">
                            <h6 class="mb-0">Card de Ejemplo</h6>
                        </div>
                        <div class="card-body">
                            <p>Este es un ejemplo de cómo se verá tu diseño con los cambios aplicados.</p>
                            <button class="btn btn-primary btn-sm">Botón de Ejemplo</button>
                        </div>
                    </div>
                    
                    <div class="preview-card">
                        <h6>Texto de Ejemplo</h6>
                        <p id="preview-text">Este texto muestra la fuente seleccionada.</p>
                    </div>
                    
                    <div class="preview-card">
                        <h6>Colores</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary">Principal</span>
                            <span class="badge bg-secondary">Secundario</span>
                            <span class="badge bg-success">Éxito</span>
                            <span class="badge bg-info">Info</span>
                            <span class="badge bg-warning">Advertencia</span>
                            <span class="badge bg-danger">Peligro</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Theme JS -->
    <script src="<?php echo URL_ROOT; ?>/assets/js/theme.js"></script>
    
    <script>
        function updateColorPreview(key, value) {
            // Actualizar ambos inputs
            document.querySelectorAll(`input[name="colores[${key}]"]`).forEach(input => {
                input.value = value;
            });
            
            // Actualizar preview
            const preview = document.getElementById(`preview-${key}`);
            if (preview) {
                preview.style.backgroundColor = value;
            }
            
            // Actualizar CSS en tiempo real
            document.documentElement.style.setProperty(`--${key}`, value);
            
            // Actualizar badges en preview
            if (key === 'primary-color') {
                document.querySelectorAll('.badge.bg-primary').forEach(badge => {
                    badge.style.backgroundColor = value;
                });
            }
        }
        
        function updateFontPreview(value) {
            document.getElementById('preview-text').style.fontFamily = value;
            document.body.style.fontFamily = value;
        }
        
        function resetearPersonalizacion() {
            if (confirm('¿Estás seguro de que quieres resetear todos los valores a los predeterminados?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo URL_ROOT; ?>/admin/resetear-personalizacion';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Sincronizar inputs de color
        document.querySelectorAll('input[type="color"]').forEach(colorInput => {
            colorInput.addEventListener('input', function() {
                const name = this.name.match(/\[(.*?)\]/)[1];
                updateColorPreview(name, this.value);
            });
        });
        
        // Aplicar estilos iniciales
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($colores as $color): 
                $cObj = is_object($color) ? $color : (object)$color;
                $nombre = $cObj->nombre ?? '';
                $valor = $cObj->valor ?? '';
                if ($nombre && $valor):
            ?>
            document.documentElement.style.setProperty('--<?php echo $nombre; ?>', '<?php echo htmlspecialchars($valor); ?>');
            <?php 
                endif;
            endforeach; 
            ?>
        });
    </script>
</body>
</html>

