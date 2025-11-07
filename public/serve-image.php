<?php
// Script para servir imágenes de forma segura
$imagePath = $_GET['path'] ?? '';

// Directorios permitidos
$allowedDirs = [
    'uploads/carousel/',
    'uploads/gallery/', 
    'uploads/eventos/',
    'uploads/news/',
    'assets/images/'
];

// Verificar que la ruta esté en un directorio permitido
$isAllowed = false;
foreach ($allowedDirs as $dir) {
    if (strpos($imagePath, $dir) === 0) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed || empty($imagePath)) {
    header('HTTP/1.0 403 Forbidden');
    echo 'Acceso denegado';
    exit;
}

// Construir la ruta completa - intentar múltiples ubicaciones posibles
$rootDir = dirname(__DIR__);
$fullPath = null;

// Intentar diferentes rutas posibles
$possiblePaths = [
    $rootDir . '/' . $imagePath,  // Ruta desde raíz del proyecto
    __DIR__ . '/../' . $imagePath, // Ruta relativa desde public
    __DIR__ . '/' . $imagePath,    // Ruta dentro de public (por si están ahí)
];

foreach ($possiblePaths as $path) {
    // Normalizar la ruta (eliminar .. y .)
    $normalizedPath = realpath($path);
    if ($normalizedPath !== false && file_exists($normalizedPath)) {
        // Verificar que la ruta normalizada esté dentro de un directorio permitido
        $isValid = false;
        foreach ($allowedDirs as $dir) {
            $allowedPath = realpath($rootDir . '/' . $dir);
            if ($allowedPath !== false && strpos($normalizedPath, $allowedPath) === 0) {
                $isValid = true;
                break;
            }
        }
        if ($isValid) {
            $fullPath = $normalizedPath;
            break;
        }
    }
}

// Verificar que el archivo existe
if ($fullPath === null || !file_exists($fullPath)) {
    // Log para debugging
    error_log("serve-image.php: Archivo no encontrado");
    error_log("serve-image.php: imagePath recibido: " . $imagePath);
    error_log("serve-image.php: rootDir: " . $rootDir);
    foreach ($possiblePaths as $idx => $path) {
        error_log("serve-image.php: Ruta intentada " . ($idx + 1) . ": " . $path . " (existe: " . (file_exists($path) ? 'sí' : 'no') . ")");
    }
    
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: text/plain');
    echo 'Imagen no encontrada: ' . htmlspecialchars($imagePath);
    exit;
}

// Obtener la extensión del archivo
$extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

// Mapear extensiones a tipos MIME
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp'
];

// Verificar que la extensión sea válida
if (!isset($mimeTypes[$extension])) {
    header('HTTP/1.0 400 Bad Request');
    echo 'Tipo de archivo no permitido';
    exit;
}

// Obtener información del archivo
$fileSize = filesize($fullPath);
$lastModified = filemtime($fullPath);

// Configurar headers
header('Content-Type: ' . $mimeTypes[$extension]);
header('Content-Length: ' . $fileSize);
header('Cache-Control: max-age=31536000, public');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Servir el archivo
readfile($fullPath);
exit;
?>
