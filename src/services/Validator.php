<?php
/**
 * Servicio de Validación Centralizado
 * Filá Mariscales
 */

class Validator {
    private static $errors = [];
    
    /**
     * Validar datos según reglas
     */
    public static function validate(array $data, array $rules): bool {
        self::$errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;
            
            foreach ($rulesArray as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value) && $value !== '0') {
                            self::$errors[$field][] = "El campo {$field} es obligatorio.";
                        }
                        break;
                    
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            self::$errors[$field][] = "El campo {$field} debe ser un email válido.";
                        }
                        break;
                    
                    case 'min':
                        if (!empty($value) && strlen($value) < (int)$ruleValue) {
                            self::$errors[$field][] = "El campo {$field} debe tener al menos {$ruleValue} caracteres.";
                        }
                        break;
                    
                    case 'max':
                        if (!empty($value) && strlen($value) > (int)$ruleValue) {
                            self::$errors[$field][] = "El campo {$field} no puede tener más de {$ruleValue} caracteres.";
                        }
                        break;
                    
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            self::$errors[$field][] = "El campo {$field} debe ser un número.";
                        }
                        break;
                    
                    case 'integer':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                            self::$errors[$field][] = "El campo {$field} debe ser un número entero.";
                        }
                        break;
                    
                    case 'float':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
                            self::$errors[$field][] = "El campo {$field} debe ser un número decimal.";
                        }
                        break;
                    
                    case 'url':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                            self::$errors[$field][] = "El campo {$field} debe ser una URL válida.";
                        }
                        break;
                    
                    case 'in':
                        $allowedValues = explode(',', $ruleValue);
                        if (!empty($value) && !in_array($value, $allowedValues)) {
                            self::$errors[$field][] = "El campo {$field} debe ser uno de: " . implode(', ', $allowedValues);
                        }
                        break;
                    
                    case 'regex':
                        if (!empty($value) && !preg_match($ruleValue, $value)) {
                            self::$errors[$field][] = "El campo {$field} no cumple con el formato requerido.";
                        }
                        break;
                }
            }
        }
        
        return empty(self::$errors);
    }
    
    /**
     * Obtener errores de validación
     */
    public static function getErrors(): array {
        return self::$errors;
    }
    
    /**
     * Obtener primer error de un campo
     */
    public static function getFirstError(string $field): ?string {
        return self::$errors[$field][0] ?? null;
    }
    
    /**
     * Validar producto
     */
    public static function validateProduct(array $data): bool {
        $rules = [
            'nombre' => 'required|min:3|max:255',
            'descripcion' => 'max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'integer'
        ];
        
        return self::validate($data, $rules);
    }
    
    /**
     * Validar usuario
     */
    public static function validateUser(array $data): bool {
        $rules = [
            'nombre' => 'required|min:2|max:100',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ];
        
        return self::validate($data, $rules);
    }
    
    /**
     * Validar evento
     */
    public static function validateEvent(array $data): bool {
        $rules = [
            'titulo' => 'required|min:3|max:255',
            'descripcion' => 'max:2000',
            'fecha' => 'required',
            'lugar' => 'max:255'
        ];
        
        return self::validate($data, $rules);
    }
}

