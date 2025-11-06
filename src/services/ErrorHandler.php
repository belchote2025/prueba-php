<?php
/**
 * Manejo Centralizado de Errores
 * Filá Mariscales
 */

class ErrorHandler {
    private static $logFile;
    private static $isProduction;
    
    /**
     * Inicializar manejador de errores
     */
    public static function init() {
        self::$isProduction = self::isProduction();
        self::$logFile = dirname(dirname(__DIR__)) . '/logs/errors.log';
        
        // Crear directorio de logs si no existe
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Configurar manejador de errores
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    /**
     * Detectar si estamos en producción
     */
    private static function isProduction(): bool {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return (
            strpos($host, 'localhost') === false &&
            strpos($host, '127.0.0.1') === false &&
            strpos($host, '.local') === false
        );
    }
    
    /**
     * Manejar errores PHP
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        // No mostrar errores si están suprimidos con @
        if (error_reporting() === 0) {
            return false;
        }
        
        $error = [
            'type' => self::getErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log del error
        self::logError($error);
        
        // Mostrar error solo en desarrollo
        if (!self::$isProduction) {
            echo "<div style='background: #fee; border: 1px solid #fcc; padding: 10px; margin: 10px; border-radius: 5px;'>";
            echo "<strong>{$error['type']}:</strong> {$error['message']}<br>";
            echo "<small>File: {$error['file']} (Line: {$error['line']})</small>";
            echo "</div>";
        }
        
        return true;
    }
    
    /**
     * Manejar excepciones
     */
    public static function handleException($exception) {
        $error = [
            'type' => 'Exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log del error
        self::logError($error);
        
        // Mostrar error apropiado
        if (self::$isProduction) {
            self::showProductionError();
        } else {
            self::showDevelopmentError($error);
        }
    }
    
    /**
     * Manejar errores fatales
     */
    public static function handleShutdown() {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $errorData = [
                'type' => 'Fatal Error',
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            self::logError($errorData);
            
            if (self::$isProduction) {
                self::showProductionError();
            } else {
                self::showDevelopmentError($errorData);
            }
        }
    }
    
    /**
     * Obtener tipo de error legible
     */
    private static function getErrorType($errno): string {
        $types = [
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        ];
        
        // E_STRICT está deprecado en PHP 8 y fue eliminado
        // No lo incluimos para evitar warnings de deprecación
        // En PHP 8+, los errores E_STRICT se reportan como E_NOTICE
        
        return $types[$errno] ?? 'Unknown Error';
    }
    
    /**
     * Registrar error en log
     */
    private static function logError(array $error) {
        $logEntry = sprintf(
            "[%s] %s: %s in %s on line %d\n",
            $error['timestamp'],
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line']
        );
        
        if (isset($error['trace'])) {
            $logEntry .= "Stack trace:\n" . $error['trace'] . "\n";
        }
        
        $logEntry .= str_repeat('-', 80) . "\n";
        
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }
    
    /**
     * Mostrar error en producción
     */
    private static function showProductionError() {
        http_response_code(500);
        
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }
        
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del Servidor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #dc143c; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>⚠️ Error del Servidor</h1>
        <p>Lo sentimos, ha ocurrido un error. Por favor, inténtalo más tarde.</p>
        <p><a href="/">Volver al inicio</a></p>
    </div>
</body>
</html>';
        exit;
    }
    
    /**
     * Mostrar error en desarrollo
     */
    private static function showDevelopmentError(array $error) {
        echo "<div style='background: #fee; border: 2px solid #f00; padding: 20px; margin: 20px; border-radius: 8px; font-family: monospace;'>";
        echo "<h2 style='color: #c00; margin-top: 0;'>{$error['type']}</h2>";
        echo "<p><strong>Mensaje:</strong> {$error['message']}</p>";
        echo "<p><strong>Archivo:</strong> {$error['file']}</p>";
        echo "<p><strong>Línea:</strong> {$error['line']}</p>";
        
        if (isset($error['trace'])) {
            echo "<details><summary><strong>Stack Trace</strong></summary>";
            echo "<pre style='background: #fff; padding: 10px; overflow-x: auto;'>";
            echo htmlspecialchars($error['trace']);
            echo "</pre></details>";
        }
        
        echo "</div>";
    }
    
    /**
     * Registrar error manualmente
     */
    public static function log($message, array $context = []) {
        $error = [
            'type' => 'Manual Log',
            'message' => $message,
            'context' => $context,
            'file' => debug_backtrace()[0]['file'] ?? 'unknown',
            'line' => debug_backtrace()[0]['line'] ?? 0,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        self::logError($error);
    }
}

// Inicializar al cargar el archivo
ErrorHandler::init();

