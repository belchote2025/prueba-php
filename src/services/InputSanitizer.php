<?php
/**
 * Servicio de Sanitización de Inputs
 * Filá Mariscales
 */

class InputSanitizer {
    /**
     * Sanitizar string
     */
    public static function sanitizeString($value): string {
        if (!is_string($value)) {
            return '';
        }
        
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        return $value;
    }
    
    /**
     * Sanitizar array
     */
    public static function sanitizeArray(array $data): array {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            $key = self::sanitizeString($key);
            
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitizar POST data
     */
    public static function sanitizePost(): array {
        return self::sanitizeArray($_POST);
    }
    
    /**
     * Sanitizar GET data
     */
    public static function sanitizeGet(): array {
        return self::sanitizeArray($_GET);
    }
    
    /**
     * Sanitizar valor individual
     */
    public static function sanitize($value) {
        if (is_array($value)) {
            return self::sanitizeArray($value);
        }
        
        return self::sanitizeString($value);
    }
    
    /**
     * Limpiar para uso en SQL (solo para valores que no van en prepared statements)
     * NOTA: Siempre preferir prepared statements
     */
    public static function cleanForSQL($value): string {
        $value = self::sanitizeString($value);
        // Remover caracteres peligrosos
        $value = str_replace([';', '--', '/*', '*/', 'xp_', 'sp_'], '', $value);
        
        return $value;
    }
    
    /**
     * Sanitizar para HTML (permitir algunos tags)
     */
    public static function sanitizeHTML($value, $allowedTags = '<p><br><strong><em><ul><ol><li><a>'): string {
        $value = self::sanitizeString($value);
        // Usar strip_tags con tags permitidos
        $value = strip_tags($value, $allowedTags);
        
        return $value;
    }
    
    /**
     * Sanitizar email
     */
    public static function sanitizeEmail($email): string {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return $email;
    }
    
    /**
     * Sanitizar URL
     */
    public static function sanitizeURL($url): string {
        $url = filter_var(trim($url), FILTER_SANITIZE_URL);
        return $url;
    }
    
    /**
     * Sanitizar número entero
     */
    public static function sanitizeInt($value): ?int {
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return $value !== false ? (int)$value : null;
    }
    
    /**
     * Sanitizar número decimal
     */
    public static function sanitizeFloat($value): ?float {
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return $value !== false ? (float)$value : null;
    }
}

