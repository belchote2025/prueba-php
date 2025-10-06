<?php
/**
 * API de Productos
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
            handleGetProductos($pdo, $path);
            break;
        case 'POST':
            handlePostProducto($pdo, $input);
            break;
        case 'PUT':
            handlePutProducto($pdo, $path, $input);
            break;
        case 'DELETE':
            handleDeleteProducto($pdo, $path);
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
 * Manejar GET - Obtener productos
 */
function handleGetProductos($pdo, $path) {
    $params = $_GET;
    $limit = intval($params['limit'] ?? 12);
    $offset = intval($params['offset'] ?? 0);
    $categoria = $params['categoria'] ?? '';
    $search = $params['search'] ?? '';
    $orden = $params['orden'] ?? 'nombre'; // nombre, precio, fecha, destacado
    $destacado = $params['destacado'] ?? '';
    $precio_min = $params['precio_min'] ?? '';
    $precio_max = $params['precio_max'] ?? '';

    // Construir consulta
    $sql = "SELECT * FROM productos WHERE activo = 1";
    $params_array = [];

    if ($categoria) {
        $sql .= " AND categoria = :categoria";
        $params_array['categoria'] = $categoria;
    }

    if ($destacado === 'true') {
        $sql .= " AND destacado = 1";
    }

    if ($precio_min) {
        $sql .= " AND precio >= :precio_min";
        $params_array['precio_min'] = floatval($precio_min);
    }

    if ($precio_max) {
        $sql .= " AND precio <= :precio_max";
        $params_array['precio_max'] = floatval($precio_max);
    }

    if ($search) {
        $sql .= " AND (nombre LIKE :search OR descripcion LIKE :search)";
        $params_array['search'] = "%$search%";
    }

    // Ordenar
    switch ($orden) {
        case 'precio_asc':
            $sql .= " ORDER BY precio ASC";
            break;
        case 'precio_desc':
            $sql .= " ORDER BY precio DESC";
            break;
        case 'fecha':
            $sql .= " ORDER BY fecha_creacion DESC";
            break;
        case 'destacado':
            $sql .= " ORDER BY destacado DESC, nombre ASC";
            break;
        default:
            $sql .= " ORDER BY nombre ASC";
    }

    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    
    foreach ($params_array as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $productos = $stmt->fetchAll();

    // Obtener total para paginación
    $countSql = "SELECT COUNT(*) as total FROM productos WHERE activo = 1";
    $countParams = [];
    
    if ($categoria) {
        $countSql .= " AND categoria = :categoria";
        $countParams['categoria'] = $categoria;
    }
    
    if ($destacado === 'true') {
        $countSql .= " AND destacado = 1";
    }
    
    if ($precio_min) {
        $countSql .= " AND precio >= :precio_min";
        $countParams['precio_min'] = floatval($precio_min);
    }
    
    if ($precio_max) {
        $countSql .= " AND precio <= :precio_max";
        $countParams['precio_max'] = floatval($precio_max);
    }
    
    if ($search) {
        $countSql .= " AND (nombre LIKE :search OR descripcion LIKE :search)";
        $countParams['search'] = "%$search%";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue(":$key", $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];

    // Formatear precios y agregar información adicional
    foreach ($productos as &$producto) {
        $producto['precio_formateado'] = number_format($producto['precio'], 2) . '€';
        $producto['precio_oferta_formateado'] = $producto['precio_oferta'] ? number_format($producto['precio_oferta'], 2) . '€' : null;
        $producto['tiene_oferta'] = $producto['precio_oferta'] > 0;
        $producto['descuento_porcentaje'] = $producto['precio_oferta'] > 0 ? 
            round((($producto['precio'] - $producto['precio_oferta']) / $producto['precio']) * 100) : 0;
        $producto['stock_disponible'] = $producto['stock'] > 0;
        $producto['fecha_creacion'] = date('Y-m-d H:i:s', strtotime($producto['fecha_creacion']));
        $producto['fecha_modificacion'] = date('Y-m-d H:i:s', strtotime($producto['fecha_modificacion']));
    }

    handleSuccess([
        'productos' => $productos,
        'pagination' => [
            'total' => intval($total),
            'limit' => $limit,
            'offset' => $offset,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

/**
 * Manejar POST - Crear producto
 */
function handlePostProducto($pdo, $input) {
    // Validar datos de entrada
    $required = ['nombre', 'precio'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            handleError("El campo $field es obligatorio", 400);
        }
    }

    // Sanitizar datos
    $nombre = sanitizeInput($input['nombre']);
    $descripcion = sanitizeInput($input['descripcion'] ?? '');
    $precio = floatval($input['precio']);
    $precio_oferta = floatval($input['precio_oferta'] ?? 0);
    $imagen_url = sanitizeInput($input['imagen_url'] ?? '');
    $categoria = sanitizeInput($input['categoria'] ?? 'general');
    $stock = intval($input['stock'] ?? 0);
    $destacado = intval($input['destacado'] ?? 0);

    // Validar precio
    if ($precio <= 0) {
        handleError('El precio debe ser mayor a 0', 400);
    }

    if ($precio_oferta > 0 && $precio_oferta >= $precio) {
        handleError('El precio de oferta debe ser menor al precio normal', 400);
    }

    // Insertar producto
    $sql = "INSERT INTO productos (nombre, descripcion, precio, precio_oferta, imagen_url, categoria, stock, destacado) 
            VALUES (:nombre, :descripcion, :precio, :precio_oferta, :imagen_url, :categoria, :stock, :destacado)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $nombre);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':precio', $precio);
    $stmt->bindValue(':precio_oferta', $precio_oferta);
    $stmt->bindValue(':imagen_url', $imagen_url);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':stock', $stock);
    $stmt->bindValue(':destacado', $destacado);
    
    if ($stmt->execute()) {
        $productoId = $pdo->lastInsertId();
        
        // Obtener el producto creado
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $productoId);
        $stmt->execute();
        $producto = $stmt->fetch();
        
        writeLog('INFO', "Producto creado: ID $productoId");
        handleSuccess($producto, 'Producto creado exitosamente');
    } else {
        handleError('Error al crear el producto', 500);
    }
}

/**
 * Manejar PUT - Actualizar producto
 */
function handlePutProducto($pdo, $path, $input) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de producto no válido', 400);
    }

    // Verificar que el producto existe
    $sql = "SELECT id FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Producto no encontrado', 404);
    }

    // Construir consulta de actualización
    $fields = [];
    $params = [':id' => $id];

    if (isset($input['nombre'])) {
        $fields[] = 'nombre = :nombre';
        $params[':nombre'] = sanitizeInput($input['nombre']);
    }

    if (isset($input['descripcion'])) {
        $fields[] = 'descripcion = :descripcion';
        $params[':descripcion'] = sanitizeInput($input['descripcion']);
    }

    if (isset($input['precio'])) {
        $fields[] = 'precio = :precio';
        $params[':precio'] = floatval($input['precio']);
    }

    if (isset($input['precio_oferta'])) {
        $fields[] = 'precio_oferta = :precio_oferta';
        $params[':precio_oferta'] = floatval($input['precio_oferta']);
    }

    if (isset($input['imagen_url'])) {
        $fields[] = 'imagen_url = :imagen_url';
        $params[':imagen_url'] = sanitizeInput($input['imagen_url']);
    }

    if (isset($input['categoria'])) {
        $fields[] = 'categoria = :categoria';
        $params[':categoria'] = sanitizeInput($input['categoria']);
    }

    if (isset($input['stock'])) {
        $fields[] = 'stock = :stock';
        $params[':stock'] = intval($input['stock']);
    }

    if (isset($input['destacado'])) {
        $fields[] = 'destacado = :destacado';
        $params[':destacado'] = intval($input['destacado']);
    }

    if (isset($input['activo'])) {
        $fields[] = 'activo = :activo';
        $params[':activo'] = intval($input['activo']);
    }

    if (empty($fields)) {
        handleError('No hay campos para actualizar', 400);
    }

    $sql = "UPDATE productos SET " . implode(', ', $fields) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        writeLog('INFO', "Producto actualizado: ID $id");
        handleSuccess(null, 'Producto actualizado exitosamente');
    } else {
        handleError('Error al actualizar el producto', 500);
    }
}

/**
 * Manejar DELETE - Eliminar producto
 */
function handleDeleteProducto($pdo, $path) {
    $id = intval(trim($path, '/'));
    
    if (!$id) {
        handleError('ID de producto no válido', 400);
    }

    // Verificar que el producto existe
    $sql = "SELECT id FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        handleError('Producto no encontrado', 404);
    }

    // Eliminar producto (soft delete)
    $sql = "UPDATE productos SET activo = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    
    if ($stmt->execute()) {
        writeLog('INFO', "Producto eliminado: ID $id");
        handleSuccess(null, 'Producto eliminado exitosamente');
    } else {
        handleError('Error al eliminar el producto', 500);
    }
}

/**
 * Obtener producto por ID
 */
function getProductoById($pdo, $id) {
    $sql = "SELECT * FROM productos WHERE id = :id AND activo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Obtener productos por categoría
 */
function getProductosPorCategoria($pdo, $categoria, $limit = 12) {
    $sql = "SELECT * FROM productos 
            WHERE activo = 1 AND categoria = :categoria 
            ORDER BY destacado DESC, nombre ASC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener productos destacados
 */
function getProductosDestacados($pdo, $limit = 8) {
    $sql = "SELECT * FROM productos 
            WHERE activo = 1 AND destacado = 1 
            ORDER BY fecha_creacion DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener productos en oferta
 */
function getProductosEnOferta($pdo, $limit = 8) {
    $sql = "SELECT * FROM productos 
            WHERE activo = 1 AND precio_oferta > 0 
            ORDER BY (precio - precio_oferta) DESC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener categorías disponibles
 */
function getCategorias($pdo) {
    $sql = "SELECT DISTINCT categoria, COUNT(*) as total 
            FROM productos 
            WHERE activo = 1 
            GROUP BY categoria 
            ORDER BY categoria ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Buscar productos
 */
function searchProductos($pdo, $query, $limit = 12) {
    $sql = "SELECT * FROM productos 
            WHERE activo = 1 
            AND (nombre LIKE :query OR descripcion LIKE :query OR categoria LIKE :query)
            ORDER BY destacado DESC, nombre ASC 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', "%$query%");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener productos relacionados
 */
function getProductosRelacionados($pdo, $productoId, $categoria, $limit = 4) {
    $sql = "SELECT * FROM productos 
            WHERE activo = 1 AND categoria = :categoria AND id != :id 
            ORDER BY destacado DESC, RAND() 
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':id', $productoId);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Obtener estadísticas de productos
 */
function getProductosStats($pdo) {
    $sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN activo = 1 THEN 1 END) as activos,
                COUNT(CASE WHEN destacado = 1 THEN 1 END) as destacados,
                COUNT(CASE WHEN precio_oferta > 0 THEN 1 END) as en_oferta,
                COUNT(CASE WHEN stock > 0 THEN 1 END) as con_stock,
                COUNT(CASE WHEN stock = 0 THEN 1 END) as sin_stock,
                COUNT(DISTINCT categoria) as categorias,
                AVG(precio) as precio_promedio,
                MIN(precio) as precio_minimo,
                MAX(precio) as precio_maximo
            FROM productos";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Actualizar stock de producto
 */
function updateStock($pdo, $productoId, $cantidad) {
    $sql = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cantidad', $cantidad);
    $stmt->bindValue(':id', $productoId);
    
    return $stmt->execute();
}

/**
 * Verificar disponibilidad de producto
 */
function verificarDisponibilidad($pdo, $productoId, $cantidad) {
    $sql = "SELECT stock FROM productos WHERE id = :id AND activo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $productoId);
    $stmt->execute();
    
    $producto = $stmt->fetch();
    
    if (!$producto) {
        return false;
    }
    
    return $producto['stock'] >= $cantidad;
}

/**
 * Obtener productos más vendidos
 */
function getProductosMasVendidos($pdo, $limit = 10) {
    $sql = "SELECT p.*, SUM(pd.cantidad) as total_vendido
            FROM productos p
            INNER JOIN pedido_detalles pd ON p.id = pd.producto_id
            INNER JOIN pedidos pe ON pd.pedido_id = pe.id
            WHERE p.activo = 1 AND pe.estado IN ('entregado', 'enviado')
            GROUP BY p.id
            ORDER BY total_vendido DESC
            LIMIT :limit";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}
?>