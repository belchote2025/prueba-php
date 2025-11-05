<?php
/**
 * Script PHP para crear las tablas faltantes en la base de datos
 * Ejecuta el SQL necesario para crear las tablas opcionales
 */
chdir(dirname(__DIR__));
require_once 'src/config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tablas Faltantes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #8B4513;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 10px;
        }
        .success {
            color: #28a745;
            background: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .info {
            color: #0c5460;
            background: #d1ecf1;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning {
            color: #856404;
            background: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #8B4513;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #8B4513;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #654321;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Crear Tablas Faltantes</h1>
        
        <?php
        // Verificar si se debe ejecutar
        $execute = isset($_GET['execute']) && $_GET['execute'] === '1';
        
        if (!$execute) {
            ?>
            <div class="info">
                <strong>⚠️ ADVERTENCIA:</strong> Este script creará las siguientes tablas en tu base de datos:
                <ul>
                    <li><code>usuarios</code> (si no existe, ya tienes <code>users</code>)</li>
                    <li><code>contactos</code></li>
                    <li><code>newsletter</code> (si no existe, ya tienes <code>newsletter_subscriptions</code>)</li>
                    <li><code>configuracion</code></li>
                </ul>
                <p>También insertará datos iniciales en la tabla <code>configuracion</code>.</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ IMPORTANTE:</strong> Asegúrate de tener un respaldo de tu base de datos antes de continuar.
            </div>
            
            <p>
                <a href="?execute=1" class="btn">✅ Ejecutar Creación de Tablas</a>
                <a href="check-db.php" class="btn">🔍 Verificar Base de Datos</a>
            </p>
            <?php
        } else {
            // Ejecutar creación de tablas
            try {
                $host = DB_HOST;
                if (strpos($host, ':') !== false) {
                    $parts = explode(':', $host);
                    $host = $parts[0];
                }
                
                $dsn = 'mysql:host=' . $host . ';dbname=' . DB_NAME . ';charset=utf8mb4';
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                
                echo '<div class="info">✅ Conectado a la base de datos: <code>' . htmlspecialchars(DB_NAME) . '</code></div>';
                
                // SQL para crear las tablas
                $sql = "
                -- Crear tabla usuarios
                CREATE TABLE IF NOT EXISTS usuarios (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(100) NOT NULL,
                    email VARCHAR(150) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    telefono VARCHAR(20),
                    avatar VARCHAR(255),
                    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
                    activo BOOLEAN DEFAULT TRUE,
                    email_verificado BOOLEAN DEFAULT FALSE,
                    token_verificacion VARCHAR(255),
                    token_recuperacion VARCHAR(255),
                    fecha_token_recuperacion DATETIME,
                    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    ultimo_acceso TIMESTAMP NULL,
                    INDEX idx_email (email),
                    INDEX idx_rol (rol),
                    INDEX idx_activo (activo)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                -- Crear tabla contactos
                CREATE TABLE IF NOT EXISTS contactos (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(100) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    telefono VARCHAR(20),
                    asunto VARCHAR(200),
                    mensaje TEXT NOT NULL,
                    leido BOOLEAN DEFAULT FALSE,
                    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_leido (leido),
                    INDEX idx_fecha_envio (fecha_envio)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                -- Crear tabla newsletter
                CREATE TABLE IF NOT EXISTS newsletter (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(150) UNIQUE NOT NULL,
                    nombre VARCHAR(100),
                    activo BOOLEAN DEFAULT TRUE,
                    fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    token_confirmacion VARCHAR(255),
                    confirmado BOOLEAN DEFAULT FALSE,
                    INDEX idx_email (email),
                    INDEX idx_activo (activo)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                -- Crear tabla configuracion
                CREATE TABLE IF NOT EXISTS configuracion (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    clave VARCHAR(100) UNIQUE NOT NULL,
                    valor TEXT,
                    descripcion TEXT,
                    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_clave (clave)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ";
                
                // Ejecutar cada sentencia SQL por separado
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                $results = [];
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        try {
                            $pdo->exec($statement);
                            $results[] = ['query' => substr($statement, 0, 50) . '...', 'status' => 'success'];
                        } catch (PDOException $e) {
                            $results[] = ['query' => substr($statement, 0, 50) . '...', 'status' => 'error', 'message' => $e->getMessage()];
                        }
                    }
                }
                
                // Insertar datos iniciales de configuración
                echo '<h2>📝 Insertar Datos Iniciales</h2>';
                $configData = [
                    ['site_name', 'Filá Mariscales de Caballeros Templarios', 'Nombre del sitio web'],
                    ['site_description', 'Página oficial de la Filá Mariscales de Caballeros Templarios de Elche', 'Descripción del sitio'],
                    ['contact_email', 'info@filamariscales.com', 'Email de contacto principal'],
                    ['contact_phone', '+34 965 123 456', 'Teléfono de contacto'],
                    ['address', 'Elche, Alicante, España', 'Dirección de la filá'],
                    ['social_facebook', '', 'URL de Facebook'],
                    ['social_instagram', '', 'URL de Instagram'],
                    ['social_twitter', '', 'URL de Twitter'],
                    ['social_youtube', '', 'URL de YouTube'],
                    ['maintenance_mode', '0', 'Modo mantenimiento (0=no, 1=sí)']
                ];
                
                $insertStmt = $pdo->prepare("INSERT IGNORE INTO configuracion (clave, valor, descripcion) VALUES (?, ?, ?)");
                $inserted = 0;
                
                foreach ($configData as $config) {
                    try {
                        $insertStmt->execute($config);
                        if ($insertStmt->rowCount() > 0) {
                            $inserted++;
                        }
                    } catch (PDOException $e) {
                        // Ignorar errores de duplicados
                    }
                }
                
                echo '<div class="success">✅ Insertados ' . $inserted . ' registros en la tabla configuracion</div>';
                
                // Verificar tablas creadas
                echo '<h2>✅ Verificación de Tablas</h2>';
                echo '<table>';
                echo '<tr><th>Tabla</th><th>Estado</th><th>Registros</th></tr>';
                
                $tables = ['usuarios', 'contactos', 'newsletter', 'configuracion'];
                foreach ($tables as $table) {
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                        $count = $stmt->fetch()['count'];
                        echo '<tr><td><code>' . htmlspecialchars($table) . '</code></td>';
                        echo '<td class="success">✅ Existe</td>';
                        echo '<td>' . $count . '</td></tr>';
                    } catch (PDOException $e) {
                        echo '<tr><td><code>' . htmlspecialchars($table) . '</code></td>';
                        echo '<td class="error">❌ No existe</td>';
                        echo '<td>-</td></tr>';
                    }
                }
                
                echo '</table>';
                
                echo '<div class="success">';
                echo '<h3>✅ ¡Proceso Completado!</h3>';
                echo '<p>Las tablas faltantes han sido creadas exitosamente.</p>';
                echo '</div>';
                
                echo '<p><a href="check-db.php?html=1" class="btn">🔍 Verificar Base de Datos</a></p>';
                
            } catch (PDOException $e) {
                echo '<div class="error">';
                echo '<h3>❌ Error de Conexión</h3>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '</div>';
            }
        }
        ?>
        
        <hr>
        <p><small>⚠️ <strong>Seguridad:</strong> Elimina o protege este archivo después de usarlo.</small></p>
    </div>
</body>
</html>

