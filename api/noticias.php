<?php
/**
 * API de Noticias
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
            handleGetNoticias($pdo, $path);
            break;
        case 'POST':
            handlePostNoticia($pdo, $input);
            break;
        case 'PUT':
            handlePutNoticia($pdo, $path, $input);
            break;
        case 'DELETE':
            handleDeleteNoticia($pdo, $path);
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
 * Manejar GET - Obtener noticias
 */
function handleGetNoticias($pdo, $path) {
    $params = $_GET;
    $limit = intval($params['limit'] ?? 10);
    $offset = intval($params['offset'] ?? 0);
    $categoria = $params['categoria'] ?? '';
    $destacada = $params['destacada'] ?? '';
    $search = $params['search'] ?? '';

    // Construir consulta
    $sql = "SELECT n.*, u.nombre as autor_nombre 
            FROM noticias n 
            LEFT JOIN usuarios u ON n.autor_id = u.id 
            WHERE n.activa = 1";
    
    $params_array = [];

    if ($categoria) {
        $sql .= " AND n.categoria = :categoria";
        $params_array['categoria'] = $categoria;
    }

    if ($destacada === 'true') {
        $sql .= " AND n.destacada = 1";
    }

    if ($search) {
        $sql .= " AND (n.titulo LIKE :search OR n.contenido LIKE :search)";
        $params_array['search'] = "%$search%";
    }

    $sql .= " ORDER BY n.fecha_publicacion DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    
    foreach ($params_array as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $noticias = $stmt->fetchAll();

    // Obtener total para paginación
    $countSql = "SELECT COUNT(*) as total FROM noticias n WHERE n.activa = 1";
    $countParams = [];
    
    if ($categoria) {
        $countSql .= " AND n.categoria = :categoria";
        $countParams['categoria'] = $categoria;
    }
    
    if ($destacada === 'true') {
        $countSql .= " AND n.destacada = 1";
    }
    
    if ($search) {
        $countSql .= " AND (n.titulo LIKE :search OR n.contenido LIKE :search)";
        $countParams['search'] = "%$search%";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue(":$key", $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];

    // Formatear fechas
    foreach ($noticias as &$noticia) {
        $noticia['fecha_publicacion'] = date('Y-m-d H:i:s', strtotime($noticia['fecha_publicacion']));
        $noticia['fecha_modificacion'] = date('Y-m-d H:i:s', strtotime($noticia['fecha_modificacion']));
    }

    handleSuccess([
        'noticias' => $noticias,
        'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Manejar POST - Crear noticia
 */
function handlePostNoticia($pdo, $input) {
    // Validar datos de entrada
    $required = ['titulo', 'contenido'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            handleError("El campo $field es obligatorio", 400);
        }
    }

    // Sanitizar datos
    $titulo = sanitizeInput($input['titulo']);
    $resumen = sanitizeInput($input['resumen'] ?? '');
    $contenido = sanitizeInput($input['contenido']);
    $imagen_url = sanitizeInput($input['imagen_url'] ?? '');
    $destacada = intval($input['destacada'] ?? 0);
    $autor_id = intval($input['autor_id'] ?? 1);

    // Insertar noticia
    $sql = "INSERT INTO noticias (titulo, resumen, contenido, imagen_url, autor_id, destacada) 
            VALUES (:titulo, :resumen, :contenido, :imagen_url, :autor_id, :destacada)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':resumen', $resumen);
    $stmt->bindValue(':contenido', $contenido);
    $stmt->bindValue(':imagen_url', $imagen_url);
    $stmt->bindValue(':autor_id', $autor_id);
    $stmt->bindValue(':destacada', $destacada);
    
    if ($stmt->execute()) {
        $noticiaId = $pdo->lastInsertId();
        
        // Obtener la noticia creada
        $sql = "SELECT n.*, u.nombre as autor_nombre 
                FROM noticias n 
                LEFT JOIN usuarios u ON n.autor_id = u.id 
                WHERE n.id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $noticiaId);
        $stmt->execute();
        $noticia = $stmt->fetch();
        
        writeLog('INFO', "Noticia creada: ID $noticiaId");
        handleSuccess($noticia, 'Noticia creada exitosamente');
    } else {
        handleError('Error al crear la noticia', 500);
    }
}

/**
 * Manejar PUT - Actualizar noticia
 */
function handlePutNoticia($pdo, $path, $input) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de noticia no válido', 400);
    }

    // Verificar que la noticia existe
    $sql = "SELECT id FROM noticias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Noticia no encontrada', 404);
    }

    // Construir consulta de actualización
    $fields = [];
    $params = [':id' => $id];

    if (isset($input['titulo'])) {
        $fields[] = 'titulo = :titulo';
        $params[':titulo'] = sanitizeInput($input['titulo']);
    }

    if (isset($input['resumen'])) {
        $fields[] = 'resumen = :resumen';
        $params[':resumen'] = sanitizeInput($input['resumen']);
    }

    if (isset($input['contenido'])) {
        $fields[] = 'contenido = :contenido';
        $params[':contenido'] = sanitizeInput($input['contenido']);
    }

    if (isset($input['imagen_url'])) {
        $fields[] = 'imagen_url = :imagen_url';
        $params[':imagen_url'] = sanitizeInput($input['imagen_url']);
    }

    if (isset($input['destacada'])) {
        $fields[] = 'destacada = :destacada';
        $params[':destacada'] = intval($input['destacada']);
    }

    if (isset($input['activa'])) {
        $fields[] = 'activa = :activa';
        $params[':activa'] = intval($input['activa']);
    }

    if (empty($fields)) {
        handleError('No hay campos para actualizar', 400);
    }

    $sql = "UPDATE noticias SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        writeLog('INFO', "Noticia actualizada: ID $id");
        handleSuccess(null, 'Noticia actualizada exitosamente');
    } else {
        handleError('Error al actualizar la noticia', 500);
    }
}

/**
 * Manejar DELETE - Eliminar noticia
 */
function handleDeleteNoticia($pdo, $path) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de noticia no válido', 400);
    }

    // Verificar que la noticia existe
    $sql = "SELECT id FROM noticias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Noticia no encontrada', 404);
    }

    // Eliminar noticia (soft delete)
    $sql = "UPDATE noticias SET activa = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    if ($stmt->execute()) {
        writeLog('INFO', "Noticia eliminada: ID $id");
        handleSuccess(null, 'Noticia eliminada exitosamente');
    } else {
        handleError('Error al eliminar la noticia', 500);
    }
}

/**
 * Obtener noticia por ID
 */
function getNoticiaById($pdo, $id) {
    $sql = "SELECT n.*, u.nombre as autor_nombre 
            FROM noticias n 
            LEFT JOIN usuarios u ON n.autor_id = u.id 
            WHERE n.id = :id AND n.activa = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Obtener noticias destacadas
 */
function getNoticiasDestacadas($pdo, $limit = 3) {
    $sql = "SELECT n.*, u.nombre as autor_nombre 
            FROM noticias n 
            LEFT JOIN usuarios u ON n.autor_id = u.id 
            WHERE n.activa = 1 AND n.destacada = 1 
            ORDER BY n.fecha_publicacion DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener noticias recientes
 */
function getNoticiasRecientes($pdo, $limit = 5) {
    $sql = "SELECT n.*, u.nombre as autor_nombre 
            FROM noticias n 
            LEFT JOIN usuarios u ON n.autor_id = u.id 
            WHERE n.activa = 1 
            ORDER BY n.fecha_publicacion DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Buscar noticias
 */
function searchNoticias($pdo, $query, $limit = 10) {
    $sql = "SELECT n.*, u.nombre as autor_nombre 
            FROM noticias n 
            LEFT JOIN usuarios u ON n.autor_id = u.id 
            WHERE n.activa = 1 
            AND (n.titulo LIKE :query OR n.contenido LIKE :query OR n.resumen LIKE :query)
            ORDER BY n.fecha_publicacion DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', "%$query%");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Incrementar vistas de noticia
 */
function incrementarVistas($pdo, $id) {
    $sql = "UPDATE noticias SET vistas = vistas + 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

/**
 * Obtener estadísticas de noticias
 */
function getNoticiasStats($pdo) {
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN activa = 1 THEN 1 END) as activas,
                COUNT(CASE WHEN destacada = 1 THEN 1 END) as destacadas,
                COUNT(CASE WHEN DATE(fecha_publicacion) = CURDATE() THEN 1 END) as hoy,
                COUNT(CASE WHEN DATE(fecha_publicacion) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as esta_semana
            FROM noticias";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch();
}
?>