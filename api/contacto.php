<?php
/**
 * API de Contacto
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
            handleGetContactos($pdo, $path);
            break;
        case 'POST':
            handlePostContacto($pdo, $input);
            break;
        case 'PUT':
            handlePutContacto($pdo, $path, $input);
            break;
        case 'DELETE':
            handleDeleteContacto($pdo, $path);
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
 * Manejar GET - Obtener contactos
 */
function handleGetContactos($pdo, $path) {
    $params = $_GET;
    $limit = intval($params['limit'] ?? 20);
    $offset = intval($params['offset'] ?? 0);
    $leido = $params['leido'] ?? '';
    $search = $params['search'] ?? '';
    $fecha_desde = $params['fecha_desde'] ?? '';
    $fecha_hasta = $params['fecha_hasta'] ?? '';

    // Construir consulta
    $sql = "SELECT * FROM contactos WHERE 1=1";
    $params_array = [];

    if ($leido !== '') {
        $sql .= " AND leido = :leido";
        $params_array['leido'] = intval($leido);
    }

    if ($fecha_desde) {
        $sql .= " AND DATE(fecha_envio) >= :fecha_desde";
        $params_array['fecha_desde'] = $fecha_desde;
    }

    if ($fecha_hasta) {
        $sql .= " AND DATE(fecha_envio) <= :fecha_hasta";
        $params_array['fecha_hasta'] = $fecha_hasta;
    }

    if ($search) {
        $sql .= " AND (nombre LIKE :search OR email LIKE :search OR asunto LIKE :search OR mensaje LIKE :search)";
        $params_array['search'] = "%$search%";
    }

    $sql .= " ORDER BY fecha_envio DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    
    foreach ($params_array as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $contactos = $stmt->fetchAll();

    // Obtener total para paginación
    $countSql = "SELECT COUNT(*) as total FROM contactos WHERE 1=1";
    $countParams = [];
    
    if ($leido !== '') {
        $countSql .= " AND leido = :leido";
        $countParams['leido'] = intval($leido);
    }
    
    if ($fecha_desde) {
        $countSql .= " AND DATE(fecha_envio) >= :fecha_desde";
        $countParams['fecha_desde'] = $fecha_desde;
    }
    
    if ($fecha_hasta) {
        $countSql .= " AND DATE(fecha_envio) <= :fecha_hasta";
        $countParams['fecha_hasta'] = $fecha_hasta;
    }
    
    if ($search) {
        $countSql .= " AND (nombre LIKE :search OR email LIKE :search OR asunto LIKE :search OR mensaje LIKE :search)";
        $countParams['search'] = "%$search%";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue(":$key", $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];

    // Formatear fechas
    foreach ($contactos as &$contacto) {
        $contacto['fecha_envio'] = date('Y-m-d H:i:s', strtotime($contacto['fecha_envio']));
        $contacto['fecha_formateada'] = date('d/m/Y H:i', strtotime($contacto['fecha_envio']));
        $contacto['mensaje_preview'] = substr($contacto['mensaje'], 0, 100) . (strlen($contacto['mensaje']) > 100 ? '...' : '');
    }

    handleSuccess([
        'contactos' => $contactos,
        'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Manejar POST - Crear contacto
 */
function handlePostContacto($pdo, $input) {
    // Validar datos de entrada
    $required = ['nombre', 'email', 'mensaje'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            handleError("El campo $field es obligatorio", 400);
        }
    }

    // Sanitizar datos
    $nombre = sanitizeInput($input['nombre']);
    $email = sanitizeInput($input['email']);
    $telefono = sanitizeInput($input['telefono'] ?? '');
    $asunto = sanitizeInput($input['asunto'] ?? '');
    $mensaje = sanitizeInput($input['mensaje']);

    // Validar email
    if (!validateEmail($email)) {
        handleError('Email no válido', 400);
    }

    // Validar longitud del mensaje
    if (strlen($mensaje) < 10) {
        handleError('El mensaje debe tener al menos 10 caracteres', 400);
    }

    // Verificar rate limiting por IP
    $ip = getClientIP();
    if (!checkContactRateLimit($pdo, $ip)) {
        handleError('Demasiados mensajes enviados. Inténtalo más tarde.', 429);
    }

    // Insertar contacto
    $sql = "INSERT INTO contactos (nombre, email, telefono, asunto, mensaje) 
            VALUES (:nombre, :email, :telefono, :asunto, :mensaje)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':telefono', $telefono);
    $stmt->bindValue(':asunto', $asunto);
    $stmt->bindValue(':mensaje', $mensaje);
    
    if ($stmt->execute()) {
        $contactoId = $pdo->lastInsertId();
        
        // Enviar email de notificación (opcional)
        if (SMTP_USERNAME && SMTP_PASSWORD) {
            sendContactNotification($nombre, $email, $asunto, $mensaje);
        }
        
        // Guardar en log
        writeLog('INFO', "Mensaje de contacto recibido: ID $contactoId", [
            'nombre' => $nombre,
            'email' => $email,
            'ip' => $ip
        ]);
        
        handleSuccess(['id' => $contactoId], 'Mensaje enviado correctamente. Te responderemos pronto.');
    } else {
        handleError('Error al enviar el mensaje', 500);
    }
}

/**
 * Manejar PUT - Actualizar contacto
 */
function handlePutContacto($pdo, $path, $input) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de contacto no válido', 400);
    }

    // Verificar que el contacto existe
    $sql = "SELECT id FROM contactos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Contacto no encontrado', 404);
    }

    // Construir consulta de actualización
    $fields = [];
    $params = [':id' => $id];

    if (isset($input['leido'])) {
        $fields[] = 'leido = :leido';
        $params[':leido'] = intval($input['leido']);
    }

    if (isset($input['nombre'])) {
        $fields[] = 'nombre = :nombre';
        $params[':nombre'] = sanitizeInput($input['nombre']);
    }

    if (isset($input['email'])) {
        $fields[] = 'email = :email';
        $params[':email'] = sanitizeInput($input['email']);
    }

    if (isset($input['telefono'])) {
        $fields[] = 'telefono = :telefono';
        $params[':telefono'] = sanitizeInput($input['telefono']);
    }

    if (isset($input['asunto'])) {
        $fields[] = 'asunto = :asunto';
        $params[':asunto'] = sanitizeInput($input['asunto']);
    }

    if (isset($input['mensaje'])) {
        $fields[] = 'mensaje = :mensaje';
        $params[':mensaje'] = sanitizeInput($input['mensaje']);
    }

    if (empty($fields)) {
        handleError('No hay campos para actualizar', 400);
    }

    $sql = "UPDATE contactos SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        writeLog('INFO', "Contacto actualizado: ID $id");
        handleSuccess(null, 'Contacto actualizado exitosamente');
    } else {
        handleError('Error al actualizar el contacto', 500);
    }
}

/**
 * Manejar DELETE - Eliminar contacto
 */
function handleDeleteContacto($pdo, $path) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de contacto no válido', 400);
    }

    // Verificar que el contacto existe
    $sql = "SELECT id FROM contactos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Contacto no encontrado', 404);
    }

    // Eliminar contacto
    $sql = "DELETE FROM contactos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    if ($stmt->execute()) {
        writeLog('INFO', "Contacto eliminado: ID $id");
        handleSuccess(null, 'Contacto eliminado exitosamente');
    } else {
        handleError('Error al eliminar el contacto', 500);
    }
}

/**
 * Obtener contacto por ID
 */
function getContactoById($pdo, $id) {
    $sql = "SELECT * FROM contactos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Marcar contacto como leído
 */
function marcarComoLeido($pdo, $id) {
    $sql = "UPDATE contactos SET leido = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    return $stmt->execute();
}

/**
 * Marcar contacto como no leído
 */
function marcarComoNoLeido($pdo, $id) {
    $sql = "UPDATE contactos SET leido = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    return $stmt->execute();
}

/**
 * Obtener contactos no leídos
 */
function getContactosNoLeidos($pdo, $limit = 10) {
    $sql = "SELECT * FROM contactos 
            WHERE leido = 0 
            ORDER BY fecha_envio DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener estadísticas de contactos
 */
function getContactosStats($pdo) {
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN leido = 0 THEN 1 END) as no_leidos,
                COUNT(CASE WHEN leido = 1 THEN 1 END) as leidos,
                COUNT(CASE WHEN DATE(fecha_envio) = CURDATE() THEN 1 END) as hoy,
                COUNT(CASE WHEN DATE(fecha_envio) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as esta_semana,
                COUNT(CASE WHEN DATE(fecha_envio) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as este_mes
            FROM contactos";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Verificar rate limiting para contactos
 */
function checkContactRateLimit($pdo, $ip) {
    $sql = "SELECT COUNT(*) as count 
            FROM contactos 
            WHERE DATE(fecha_envio) = CURDATE() 
            AND ip = :ip";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ip', $ip);
    $stmt->execute();
    
    $result = $stmt->fetch();
    
    // Máximo 5 mensajes por día por IP
    return $result['count'] < 5;
}

/**
 * Enviar notificación de contacto por email
 */
function sendContactNotification($nombre, $email, $asunto, $mensaje) {
    if (!SMTP_USERNAME || !SMTP_PASSWORD) {
        return false;
    }
    
    $to = SMTP_FROM_EMAIL;
    $subject = "Nuevo mensaje de contacto - $asunto";
    $body = "
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> $nombre</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Asunto:</strong> $asunto</p>
    <p><strong>Mensaje:</strong></p>
    <p>$mensaje</p>
    <hr>
    <p><small>Enviado desde el formulario de contacto de " . APP_NAME . "</small></p>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_EMAIL . '>',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    return mail($to, $subject, $body, implode("\r\n", $headers));
}

/**
 * Enviar respuesta automática
 */
function sendAutoReply($email, $nombre) {
    if (!SMTP_USERNAME || !SMTP_PASSWORD) {
        return false;
    }
    
    $subject = "Confirmación de recepción - " . APP_NAME;
    $body = "
    <h2>¡Gracias por contactarnos!</h2>
    <p>Hola $nombre,</p>
    <p>Hemos recibido tu mensaje y te responderemos lo antes posible.</p>
    <p>Gracias por tu interés en " . APP_NAME . ".</p>
    <hr>
    <p><small>Este es un mensaje automático. Por favor, no respondas a este email.</small></p>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_EMAIL . '>',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    return mail($email, $subject, $body, implode("\r\n", $headers));
}

/**
 * Buscar contactos
 */
function searchContactos($pdo, $query, $limit = 20) {
    $sql = "SELECT * FROM contactos 
            WHERE (nombre LIKE :query OR email LIKE :query OR asunto LIKE :query OR mensaje LIKE :query)
            ORDER BY fecha_envio DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', "%$query%");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener contactos por fecha
 */
function getContactosPorFecha($pdo, $fecha) {
    $sql = "SELECT * FROM contactos 
            WHERE DATE(fecha_envio) = :fecha 
            ORDER BY fecha_envio DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':fecha', $fecha);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Exportar contactos a CSV
 */
function exportContactosCSV($pdo, $fecha_desde = null, $fecha_hasta = null) {
    $sql = "SELECT * FROM contactos WHERE 1=1";
    $params = [];
    
    if ($fecha_desde) {
        $sql .= " AND DATE(fecha_envio) >= :fecha_desde";
        $params[':fecha_desde'] = $fecha_desde;
    }
    
    if ($fecha_hasta) {
        $sql .= " AND DATE(fecha_envio) <= :fecha_hasta";
        $params[':fecha_hasta'] = $fecha_hasta;
    }
    
    $sql .= " ORDER BY fecha_envio DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $contactos = $stmt->fetchAll();
    
    $csv = "ID,Nombre,Email,Telefono,Asunto,Mensaje,Leido,Fecha\n";
    
    foreach ($contactos as $contacto) {
        $csv .= sprintf(
            "%d,%s,%s,%s,%s,%s,%s,%s\n",
            $contacto['id'],
            '"' . str_replace('"', '""', $contacto['nombre']) . '"',
            $contacto['email'],
            $contacto['telefono'],
            '"' . str_replace('"', '""', $contacto['asunto']) . '"',
            '"' . str_replace('"', '""', $contacto['mensaje']) . '"',
            $contacto['leido'] ? 'Sí' : 'No',
            $contacto['fecha_envio']
        );
    }
    
    return $csv;
}
?>