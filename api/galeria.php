<?php
/**
 * API de Galería
 * Filá Mariscales Web - Versión 2.0.0
 */

require_once 'config/database.php';

// Configurar headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Conectar a la base de datos
    $config = getDatabaseConfig();
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        $config['options']
    );

    $method = $_SERVER['REQUEST_METHOD'];
    $path = $_SERVER['PATH_INFO'] ?? '';
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            handleGetGaleria($pdo, $path);
            break;
        case 'POST':
            handlePostImagen($pdo, $input);
            break;
        case 'PUT':
            handlePutImagen($pdo, $path, $input);
            break;
        case 'DELETE':
            handleDeleteImagen($pdo, $path);
            break;
        default:
            handleError('Método no permitido', 405);
    }

} catch (PDOException $e) {
    writeLog('ERROR', 'Error de base de datos: ' . $e->getMessage());
    handleError('Error de conexión a la base de datos', 500);
} catch (Exception $e) {
    writeLog('ERROR', 'Error general: ' . $e->getMessage());
    handleError('Error interno del servidor', 500);
}

/**
 * Manejar GET - Obtener galería
 */
function handleGetGaleria($pdo, $path) {
    $params = $_GET;
    $limit = intval($params['limit'] ?? 12);
    $offset = intval($params['offset'] ?? 0);
    $categoria = $params['categoria'] ?? '';
    $search = $params['search'] ?? '';
    $orden = $params['orden'] ?? 'orden'; // orden, fecha, titulo

    // Construir consulta
    $sql = "SELECT * FROM galeria WHERE activa = 1";
    $params_array = [];

    if ($categoria) {
        $sql .= " AND categoria = :categoria";
        $params_array['categoria'] = $categoria;
    }

    if ($search) {
        $sql .= " AND (titulo LIKE :search OR descripcion LIKE :search)";
        $params_array['search'] = "%$search%";
    }

    // Ordenar
    switch ($orden) {
        case 'fecha':
            $sql .= " ORDER BY fecha_subida DESC";
            break;
        case 'titulo':
            $sql .= " ORDER BY titulo ASC";
            break;
        default:
            $sql .= " ORDER BY orden ASC, fecha_subida DESC";
    }

    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    
    foreach ($params_array as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $imagenes = $stmt->fetchAll();

    // Obtener total para paginación
    $countSql = "SELECT COUNT(*) as total FROM galeria WHERE activa = 1";
    $countParams = [];
    
    if ($categoria) {
        $countSql .= " AND categoria = :categoria";
        $countParams['categoria'] = $categoria;
    }
    
    if ($search) {
        $countSql .= " AND (titulo LIKE :search OR descripcion LIKE :search)";
        $countParams['search'] = "%$search%";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue(":$key", $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];

    // Formatear fechas y agregar información adicional
    foreach ($imagenes as &$imagen) {
        $imagen['fecha_subida'] = date('Y-m-d H:i:s', strtotime($imagen['fecha_subida']));
        $imagen['fecha_formateada'] = date('d/m/Y', strtotime($imagen['fecha_subida']));
        $imagen['url_completa'] = $imagen['imagen_url'];
        $imagen['thumb_completa'] = $imagen['thumb_url'] ?: $imagen['imagen_url'];
    }

    handleSuccess([
        'imagenes' => $imagenes,
        'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Manejar POST - Crear imagen
 */
function handlePostImagen($pdo, $input) {
    // Validar datos de entrada
    $required = ['imagen_url'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            handleError("El campo $field es obligatorio", 400);
        }
    }

    // Sanitizar datos
    $titulo = sanitizeInput($input['titulo'] ?? '');
    $descripcion = sanitizeInput($input['descripcion'] ?? '');
    $imagen_url = sanitizeInput($input['imagen_url']);
    $thumb_url = sanitizeInput($input['thumb_url'] ?? '');
    $categoria = sanitizeInput($input['categoria'] ?? 'general');
    $orden = intval($input['orden'] ?? 0);

    // Insertar imagen
    $sql = "INSERT INTO galeria (titulo, descripcion, imagen_url, thumb_url, categoria, orden) 
            VALUES (:titulo, :descripcion, :imagen_url, :thumb_url, :categoria, :orden)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':imagen_url', $imagen_url);
    $stmt->bindValue(':thumb_url', $thumb_url);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':orden', $orden);
    
    if ($stmt->execute()) {
        $imagenId = $pdo->lastInsertId();
        
        // Obtener la imagen creada
        $sql = "SELECT * FROM galeria WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $imagenId);
        $stmt->execute();
        $imagen = $stmt->fetch();
        
        writeLog('INFO', "Imagen creada: ID $imagenId");
        handleSuccess($imagen, 'Imagen agregada exitosamente');
    } else {
        handleError('Error al agregar la imagen', 500);
    }
}

/**
 * Manejar PUT - Actualizar imagen
 */
function handlePutImagen($pdo, $path, $input) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de imagen no válido', 400);
    }

    // Verificar que la imagen existe
    $sql = "SELECT id FROM galeria WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Imagen no encontrada', 404);
    }

    // Construir consulta de actualización
    $fields = [];
    $params = [':id' => $id];

    if (isset($input['titulo'])) {
        $fields[] = 'titulo = :titulo';
        $params[':titulo'] = sanitizeInput($input['titulo']);
    }

    if (isset($input['descripcion'])) {
        $fields[] = 'descripcion = :descripcion';
        $params[':descripcion'] = sanitizeInput($input['descripcion']);
    }

    if (isset($input['imagen_url'])) {
        $fields[] = 'imagen_url = :imagen_url';
        $params[':imagen_url'] = sanitizeInput($input['imagen_url']);
    }

    if (isset($input['thumb_url'])) {
        $fields[] = 'thumb_url = :thumb_url';
        $params[':thumb_url'] = sanitizeInput($input['thumb_url']);
    }

    if (isset($input['categoria'])) {
        $fields[] = 'categoria = :categoria';
        $params[':categoria'] = sanitizeInput($input['categoria']);
    }

    if (isset($input['orden'])) {
        $fields[] = 'orden = :orden';
        $params[':orden'] = intval($input['orden']);
    }

    if (isset($input['activa'])) {
        $fields[] = 'activa = :activa';
        $params[':activa'] = intval($input['activa']);
    }

    if (empty($fields)) {
        handleError('No hay campos para actualizar', 400);
    }

    $sql = "UPDATE galeria SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        writeLog('INFO', "Imagen actualizada: ID $id");
        handleSuccess(null, 'Imagen actualizada exitosamente');
    } else {
        handleError('Error al actualizar la imagen', 500);
    }
}

/**
 * Manejar DELETE - Eliminar imagen
 */
function handleDeleteImagen($pdo, $path) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de imagen no válido', 400);
    }

    // Verificar que la imagen existe
    $sql = "SELECT id, imagen_url, thumb_url FROM galeria WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $imagen = $stmt->fetch();
    
    if (!$imagen) {
        handleError('Imagen no encontrada', 404);
    }

    // Eliminar imagen (soft delete)
    $sql = "UPDATE galeria SET activa = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    if ($stmt->execute()) {
        // Opcional: eliminar archivos físicos
        // deleteImageFiles($imagen['imagen_url'], $imagen['thumb_url']);
        
        writeLog('INFO', "Imagen eliminada: ID $id");
        handleSuccess(null, 'Imagen eliminada exitosamente');
    } else {
        handleError('Error al eliminar la imagen', 500);
    }
}

/**
 * Obtener imagen por ID
 */
function getImagenById($pdo, $id) {
    $sql = "SELECT * FROM galeria WHERE id = :id AND activa = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Obtener imágenes por categoría
 */
function getImagenesPorCategoria($pdo, $categoria, $limit = 12) {
    $sql = "SELECT * FROM galeria 
            WHERE activa = 1 AND categoria = :categoria 
            ORDER BY orden ASC, fecha_subida DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener categorías disponibles
 */
function getCategorias($pdo) {
    $sql = "SELECT DISTINCT categoria, COUNT(*) as total 
            FROM galeria 
            WHERE activa = 1 
            GROUP BY categoria 
            ORDER BY categoria ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener imágenes recientes
 */
function getImagenesRecientes($pdo, $limit = 6) {
    $sql = "SELECT * FROM galeria 
            WHERE activa = 1 
            ORDER BY fecha_subida DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener imágenes destacadas
 */
function getImagenesDestacadas($pdo, $limit = 8) {
    $sql = "SELECT * FROM galeria 
            WHERE activa = 1 
            ORDER BY orden ASC, fecha_subida DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Buscar imágenes
 */
function searchImagenes($pdo, $query, $limit = 12) {
    $sql = "SELECT * FROM galeria 
            WHERE activa = 1 
            AND (titulo LIKE :query OR descripcion LIKE :query OR categoria LIKE :query)
            ORDER BY fecha_subida DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', "%$query%");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener estadísticas de galería
 */
function getGaleriaStats($pdo) {
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN activa = 1 THEN 1 END) as activas,
                COUNT(DISTINCT categoria) as categorias,
                COUNT(CASE WHEN DATE(fecha_subida) = CURDATE() THEN 1 END) as hoy,
                COUNT(CASE WHEN DATE(fecha_subida) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as esta_semana
            FROM galeria";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Actualizar orden de imágenes
 */
function updateOrdenImagenes($pdo, $ordenes) {
    $pdo->beginTransaction();
    
    try {
        foreach ($ordenes as $id => $orden) {
            $sql = "UPDATE galeria SET orden = :orden WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':orden', intval($orden));
            $stmt->bindValue(':id', intval($id));
            $stmt->execute();
        }
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Eliminar archivos de imagen
 */
function deleteImageFiles($imagenUrl, $thumbUrl = '') {
    $uploadPath = UPLOAD_PATH . 'gallery/';
    
    if ($imagenUrl && file_exists($uploadPath . basename($imagenUrl))) {
        unlink($uploadPath . basename($imagenUrl));
    }
    
    if ($thumbUrl && file_exists($uploadPath . basename($thumbUrl))) {
        unlink($uploadPath . basename($thumbUrl));
    }
}

/**
 * Generar thumbnail
 */
function generateThumbnail($sourcePath, $destPath, $width = 300, $height = 300) {
    if (!extension_loaded('gd')) {
        return false;
    }
    
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    
    // Crear imagen desde archivo
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    if (!$sourceImage) {
        return false;
    }
    
    // Calcular dimensiones manteniendo proporción
    $ratio = min($width / $sourceWidth, $height / $sourceHeight);
    $newWidth = intval($sourceWidth * $ratio);
    $newHeight = intval($sourceHeight * $ratio);
    
    // Crear thumbnail
    $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preservar transparencia para PNG
    if ($mimeType === 'image/png') {
        imagealphablending($thumbImage, false);
        imagesavealpha($thumbImage, true);
    }
    
    // Redimensionar
    imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
    
    // Guardar thumbnail
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($thumbImage, $destPath, 85);
            break;
        case 'image/png':
            $result = imagepng($thumbImage, $destPath, 8);
            break;
        case 'image/gif':
            $result = imagegif($thumbImage, $destPath);
            break;
    }
    
    // Limpiar memoria
    imagedestroy($sourceImage);
    imagedestroy($thumbImage);
    
    return $result;
}
?>