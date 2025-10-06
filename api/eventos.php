<?php
/**
 * API de Eventos
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
            handleGetEventos($pdo, $path);
            break;
        case 'POST':
            handlePostEvento($pdo, $input);
            break;
        case 'PUT':
            handlePutEvento($pdo, $path, $input);
            break;
        case 'DELETE':
            handleDeleteEvento($pdo, $path);
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
 * Manejar GET - Obtener eventos
 */
function handleGetEventos($pdo, $path) {
    $params = $_GET;
    $limit = intval($params['limit'] ?? 10);
    $offset = intval($params['offset'] ?? 0);
    $tipo = $params['tipo'] ?? '';
    $fecha_desde = $params['fecha_desde'] ?? '';
    $fecha_hasta = $params['fecha_hasta'] ?? '';
    $proximos = $params['proximos'] ?? '';
    $search = $params['search'] ?? '';

    // Construir consulta
    $sql = "SELECT * FROM eventos WHERE activo = 1";
    $params_array = [];

    if ($tipo) {
        $sql .= " AND tipo = :tipo";
        $params_array['tipo'] = $tipo;
    }

    if ($fecha_desde) {
        $sql .= " AND fecha >= :fecha_desde";
        $params_array['fecha_desde'] = $fecha_desde;
    }

    if ($fecha_hasta) {
        $sql .= " AND fecha <= :fecha_hasta";
        $params_array['fecha_hasta'] = $fecha_hasta;
    }

    if ($proximos === 'true') {
        $sql .= " AND fecha >= CURDATE()";
    }

    if ($search) {
        $sql .= " AND (titulo LIKE :search OR descripcion LIKE :search OR lugar LIKE :search)";
        $params_array['search'] = "%$search%";
    }

    $sql .= " ORDER BY fecha ASC, hora ASC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    
    foreach ($params_array as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $eventos = $stmt->fetchAll();

    // Obtener total para paginación
    $countSql = "SELECT COUNT(*) as total FROM eventos WHERE activo = 1";
    $countParams = [];
    
    if ($tipo) {
        $countSql .= " AND tipo = :tipo";
        $countParams['tipo'] = $tipo;
    }
    
    if ($fecha_desde) {
        $countSql .= " AND fecha >= :fecha_desde";
        $countParams['fecha_desde'] = $fecha_desde;
    }
    
    if ($fecha_hasta) {
        $countSql .= " AND fecha <= :fecha_hasta";
        $countParams['fecha_hasta'] = $fecha_hasta;
    }
    
    if ($proximos === 'true') {
        $countSql .= " AND fecha >= CURDATE()";
    }
    
    if ($search) {
        $countSql .= " AND (titulo LIKE :search OR descripcion LIKE :search OR lugar LIKE :search)";
        $countParams['search'] = "%$search%";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue(":$key", $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];

    // Formatear fechas y agregar información adicional
    foreach ($eventos as &$evento) {
        $evento['fecha_formateada'] = date('d/m/Y', strtotime($evento['fecha']));
        $evento['hora_formateada'] = $evento['hora'] ? date('H:i', strtotime($evento['hora'])) : '';
        $evento['fecha_completa'] = date('Y-m-d H:i:s', strtotime($evento['fecha'] . ' ' . $evento['hora']));
        $evento['es_pasado'] = strtotime($evento['fecha']) < time();
        $evento['es_hoy'] = date('Y-m-d', strtotime($evento['fecha'])) === date('Y-m-d');
        $evento['es_proximo'] = strtotime($evento['fecha']) > time() && strtotime($evento['fecha']) <= strtotime('+7 days');
    }

    handleSuccess([
        'eventos' => $eventos,
        'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Manejar POST - Crear evento
 */
function handlePostEvento($pdo, $input) {
    // Validar datos de entrada
    $required = ['titulo', 'fecha'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            handleError("El campo $field es obligatorio", 400);
        }
    }

    // Sanitizar datos
    $titulo = sanitizeInput($input['titulo']);
    $descripcion = sanitizeInput($input['descripcion'] ?? '');
    $fecha = sanitizeInput($input['fecha']);
    $hora = sanitizeInput($input['hora'] ?? '');
    $lugar = sanitizeInput($input['lugar'] ?? '');
    $tipo = sanitizeInput($input['tipo'] ?? 'otro');
    $imagen_url = sanitizeInput($input['imagen_url'] ?? '');
    $precio = floatval($input['precio'] ?? 0);
    $capacidad = intval($input['capacidad'] ?? 0);
    $inscripciones_abiertas = intval($input['inscripciones_abiertas'] ?? 0);

    // Validar fecha
    if (!strtotime($fecha)) {
        handleError('Fecha no válida', 400);
    }

    // Validar tipo
    $tipos_validos = ['ensayo', 'presentacion', 'desfile', 'cena', 'reunion', 'otro'];
    if (!in_array($tipo, $tipos_validos)) {
        handleError('Tipo de evento no válido', 400);
    }

    // Insertar evento
    $sql = "INSERT INTO eventos (titulo, descripcion, fecha, hora, lugar, tipo, imagen_url, precio, capacidad, inscripciones_abiertas) 
            VALUES (:titulo, :descripcion, :fecha, :hora, :lugar, :tipo, :imagen_url, :precio, :capacidad, :inscripciones_abiertas)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':fecha', $fecha);
    $stmt->bindValue(':hora', $hora);
    $stmt->bindValue(':lugar', $lugar);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':imagen_url', $imagen_url);
    $stmt->bindValue(':precio', $precio);
    $stmt->bindValue(':capacidad', $capacidad);
    $stmt->bindValue(':inscripciones_abiertas', $inscripciones_abiertas);
    
    if ($stmt->execute()) {
        $eventoId = $pdo->lastInsertId();
        
        // Obtener el evento creado
        $sql = "SELECT * FROM eventos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $eventoId);
        $stmt->execute();
        $evento = $stmt->fetch();
        
        writeLog('INFO', "Evento creado: ID $eventoId");
        handleSuccess($evento, 'Evento creado exitosamente');
    } else {
        handleError('Error al crear el evento', 500);
    }
}

/**
 * Manejar PUT - Actualizar evento
 */
function handlePutEvento($pdo, $path, $input) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de evento no válido', 400);
    }

    // Verificar que el evento existe
    $sql = "SELECT id FROM eventos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Evento no encontrado', 404);
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

    if (isset($input['fecha'])) {
        $fields[] = 'fecha = :fecha';
        $params[':fecha'] = sanitizeInput($input['fecha']);
    }

    if (isset($input['hora'])) {
        $fields[] = 'hora = :hora';
        $params[':hora'] = sanitizeInput($input['hora']);
    }

    if (isset($input['lugar'])) {
        $fields[] = 'lugar = :lugar';
        $params[':lugar'] = sanitizeInput($input['lugar']);
    }

    if (isset($input['tipo'])) {
        $fields[] = 'tipo = :tipo';
        $params[':tipo'] = sanitizeInput($input['tipo']);
    }

    if (isset($input['imagen_url'])) {
        $fields[] = 'imagen_url = :imagen_url';
        $params[':imagen_url'] = sanitizeInput($input['imagen_url']);
    }

    if (isset($input['precio'])) {
        $fields[] = 'precio = :precio';
        $params[':precio'] = floatval($input['precio']);
    }

    if (isset($input['capacidad'])) {
        $fields[] = 'capacidad = :capacidad';
        $params[':capacidad'] = intval($input['capacidad']);
    }

    if (isset($input['inscripciones_abiertas'])) {
        $fields[] = 'inscripciones_abiertas = :inscripciones_abiertas';
        $params[':inscripciones_abiertas'] = intval($input['inscripciones_abiertas']);
    }

    if (isset($input['activo'])) {
        $fields[] = 'activo = :activo';
        $params[':activo'] = intval($input['activo']);
    }

    if (empty($fields)) {
        handleError('No hay campos para actualizar', 400);
    }

    $sql = "UPDATE eventos SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        writeLog('INFO', "Evento actualizado: ID $id");
        handleSuccess(null, 'Evento actualizado exitosamente');
    } else {
        handleError('Error al actualizar el evento', 500);
    }
}

/**
 * Manejar DELETE - Eliminar evento
 */
function handleDeleteEvento($pdo, $path) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de evento no válido', 400);
    }

    // Verificar que el evento existe
    $sql = "SELECT id FROM eventos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Evento no encontrado', 404);
    }

    // Eliminar evento (soft delete)
    $sql = "UPDATE eventos SET activo = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    if ($stmt->execute()) {
        writeLog('INFO', "Evento eliminado: ID $id");
        handleSuccess(null, 'Evento eliminado exitosamente');
    } else {
        handleError('Error al eliminar el evento', 500);
    }
}

/**
 * Obtener evento por ID
 */
function getEventoById($pdo, $id) {
    $sql = "SELECT * FROM eventos WHERE id = :id AND activo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Obtener eventos próximos
 */
function getEventosProximos($pdo, $limit = 5) {
    $sql = "SELECT * FROM eventos 
            WHERE activo = 1 AND fecha >= CURDATE() 
            ORDER BY fecha ASC, hora ASC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener eventos de hoy
 */
function getEventosHoy($pdo) {
    $sql = "SELECT * FROM eventos 
            WHERE activo = 1 AND DATE(fecha) = CURDATE() 
            ORDER BY hora ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener eventos por tipo
 */
function getEventosPorTipo($pdo, $tipo, $limit = 10) {
    $sql = "SELECT * FROM eventos 
            WHERE activo = 1 AND tipo = :tipo 
            ORDER BY fecha ASC, hora ASC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener eventos por mes
 */
function getEventosPorMes($pdo, $mes, $año) {
    $sql = "SELECT * FROM eventos 
            WHERE activo = 1 
            AND MONTH(fecha) = :mes 
            AND YEAR(fecha) = :año 
            ORDER BY fecha ASC, hora ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindValue(':año', $año, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener estadísticas de eventos
 */
function getEventosStats($pdo) {
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN activo = 1 THEN 1 END) as activos,
                COUNT(CASE WHEN fecha >= CURDATE() THEN 1 END) as proximos,
                COUNT(CASE WHEN DATE(fecha) = CURDATE() THEN 1 END) as hoy,
                COUNT(CASE WHEN fecha >= CURDATE() AND fecha <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as esta_semana,
                COUNT(CASE WHEN tipo = 'ensayo' THEN 1 END) as ensayos,
                COUNT(CASE WHEN tipo = 'presentacion' THEN 1 END) as presentaciones,
                COUNT(CASE WHEN tipo = 'desfile' THEN 1 END) as desfiles,
                COUNT(CASE WHEN tipo = 'cena' THEN 1 END) as cenas
            FROM eventos";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Obtener calendario de eventos
 */
function getCalendarioEventos($pdo, $mes, $año) {
    $sql = "SELECT 
                DATE(fecha) as fecha,
                COUNT(*) as total_eventos,
                GROUP_CONCAT(titulo SEPARATOR '|') as titulos,
                GROUP_CONCAT(tipo SEPARATOR '|') as tipos,
                GROUP_CONCAT(hora SEPARATOR '|') as horas
            FROM eventos 
            WHERE activo = 1 
            AND MONTH(fecha) = :mes 
            AND YEAR(fecha) = :año 
            GROUP BY DATE(fecha)
            ORDER BY fecha ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindValue(':año', $año, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}
?>