<?php
/**
 * Helper para CSRF Tokens
 * Filá Mariscales
 */

class CsrfHelper {
    /**
     * Generar y guardar token CSRF
     */
    public static function generateToken(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = SecurityHelper::generateCsrfToken();
            $_SESSION['csrf_token_time'] = time();
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Obtener token actual
     */
    public static function getToken(): string {
        return self::generateToken();
    }
    
    /**
     * Validar token CSRF
     */
    public static function validateToken(?string $token): bool {
        if (empty($token) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Verificar expiración (1 hora)
        if (isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > 3600) {
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generar campo hidden para formulario
     */
    public static function field(): string {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
    
    /**
     * Validar token desde POST
     */
    public static function validatePost(): bool {
        $token = $_POST['csrf_token'] ?? null;
        return self::validateToken($token);
    }
    
    /**
     * Validar token desde GET
     */
    public static function validateGet(): bool {
        $token = $_GET['csrf_token'] ?? null;
        return self::validateToken($token);
    }
    
    /**
     * Regenerar token (después de uso exitoso)
     */
    public static function regenerate(): void {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
        self::generateToken();
    }
}

