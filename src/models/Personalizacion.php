<?php

require_once __DIR__ . '/Database.php';

class Personalizacion {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Obtener todas las personalizaciones
     */
    public function getAll() {
        $this->db->query('SELECT * FROM personalizacion WHERE activo = 1 ORDER BY tipo, nombre');
        return $this->db->resultSet();
    }
    
    /**
     * Obtener personalizaciones por tipo
     */
    public function getByType($tipo) {
        $this->db->query('SELECT * FROM personalizacion WHERE tipo = :tipo AND activo = 1 ORDER BY nombre');
        $this->db->bind(':tipo', $tipo);
        return $this->db->resultSet();
    }
    
    /**
     * Obtener una personalización específica
     */
    public function get($tipo, $nombre) {
        $this->db->query('SELECT * FROM personalizacion WHERE tipo = :tipo AND nombre = :nombre AND activo = 1');
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':nombre', $nombre);
        return $this->db->single();
    }
    
    /**
     * Guardar o actualizar una personalización
     */
    public function save($tipo, $nombre, $valor, $descripcion = '') {
        // Verificar si existe
        $existente = $this->get($tipo, $nombre);
        
        if ($existente) {
            // Actualizar
            $this->db->query('UPDATE personalizacion SET valor = :valor, descripcion = :descripcion WHERE tipo = :tipo AND nombre = :nombre');
            $this->db->bind(':valor', $valor);
            $this->db->bind(':descripcion', $descripcion);
            $this->db->bind(':tipo', $tipo);
            $this->db->bind(':nombre', $nombre);
            return $this->db->execute();
        } else {
            // Insertar
            $this->db->query('INSERT INTO personalizacion (tipo, nombre, valor, descripcion) VALUES (:tipo, :nombre, :valor, :descripcion)');
            $this->db->bind(':tipo', $tipo);
            $this->db->bind(':nombre', $nombre);
            $this->db->bind(':valor', $valor);
            $this->db->bind(':descripcion', $descripcion);
            return $this->db->execute();
        }
    }
    
    /**
     * Guardar múltiples personalizaciones
     */
    public function saveMultiple($personalizaciones) {
        $success = true;
        foreach ($personalizaciones as $item) {
            $result = $this->save(
                $item['tipo'],
                $item['nombre'],
                $item['valor'],
                $item['descripcion'] ?? ''
            );
            if (!$result) {
                $success = false;
            }
        }
        return $success;
    }
    
    /**
     * Obtener estilos CSS generados
     */
    public function getStylesCSS() {
        $personalizaciones = $this->getAll();
        $css = ":root {\n";
        
        // Valores por defecto
        $colors = [];
        $fonts = [];
        $animations = [];
        $general = [];
        
        foreach ($personalizaciones as $item) {
            $itemObj = is_object($item) ? $item : (object)$item;
            $tipo = $itemObj->tipo ?? '';
            $nombre = $itemObj->nombre ?? '';
            $valor = $itemObj->valor ?? '';
            $activo = $itemObj->activo ?? true;
            
            if (!$activo) continue;
            
            if ($tipo === 'color') {
                $colors[$nombre] = $valor;
                $css .= "    --{$nombre}: {$valor};\n";
            } elseif ($tipo === 'fuente') {
                $fonts[$nombre] = $valor;
                $css .= "    --{$nombre}: {$valor};\n";
            } elseif ($tipo === 'animacion') {
                $animations[$nombre] = $valor;
                $css .= "    --{$nombre}: {$valor};\n";
            } elseif ($tipo === 'general') {
                $general[$nombre] = $valor;
                $css .= "    --{$nombre}: {$valor};\n";
            }
        }
        
        $css .= "}\n\n";
        
        // Aplicar fuente al body
        if (isset($fonts['font-family'])) {
            $css .= "body {\n";
            $css .= "    font-family: {$fonts['font-family']} !important;\n";
            if (isset($fonts['font-size-base'])) {
                $css .= "    font-size: {$fonts['font-size-base']} !important;\n";
            }
            $css .= "}\n\n";
        }
        
        // Aplicar colores a elementos principales
        if (isset($colors['primary-color'])) {
            $css .= "/* Aplicar color principal */\n";
            $css .= ".btn-primary, .bg-primary, .text-primary, .navbar-brand, .nav-link.active {\n";
            $css .= "    color: {$colors['primary-color']} !important;\n";
            $css .= "}\n\n";
            $css .= ".btn-primary, .bg-primary {\n";
            $css .= "    background-color: {$colors['primary-color']} !important;\n";
            $css .= "    border-color: {$colors['primary-color']} !important;\n";
            $css .= "}\n\n";
        }
        
        if (isset($colors['secondary-color'])) {
            $css .= "/* Aplicar color secundario */\n";
            $css .= ".btn-secondary, .bg-secondary, .text-secondary {\n";
            $css .= "    color: {$colors['secondary-color']} !important;\n";
            $css .= "}\n\n";
            $css .= ".btn-secondary, .bg-secondary {\n";
            $css .= "    background-color: {$colors['secondary-color']} !important;\n";
            $css .= "    border-color: {$colors['secondary-color']} !important;\n";
            $css .= "}\n\n";
        }
        
        // Aplicar border-radius
        if (isset($general['border-radius'])) {
            $css .= "/* Aplicar border-radius */\n";
            $css .= ".card, .btn, .form-control, .navbar, .dropdown-menu {\n";
            $css .= "    border-radius: {$general['border-radius']} !important;\n";
            $css .= "}\n\n";
        }
        
        // Aplicar velocidad de transiciones
        if (isset($animations['transition-speed'])) {
            $css .= "/* Aplicar velocidad de transiciones */\n";
            $css .= "* {\n";
            $css .= "    transition-duration: {$animations['transition-speed']} !important;\n";
            $css .= "}\n\n";
        }
        
        // Aplicar animaciones
        if (isset($animations['card-animation'])) {
            $animation = $animations['card-animation'];
            if ($animation === 'fadeIn') {
                $css .= "@keyframes fadeIn {\n";
                $css .= "    from { opacity: 0; transform: translateY(20px); }\n";
                $css .= "    to { opacity: 1; transform: translateY(0); }\n";
                $css .= "}\n";
                $css .= ".card { animation: fadeIn 0.5s ease-in-out; }\n\n";
            } elseif ($animation === 'slideIn') {
                $css .= "@keyframes slideIn {\n";
                $css .= "    from { transform: translateX(-100%); }\n";
                $css .= "    to { transform: translateX(0); }\n";
                $css .= "}\n";
                $css .= ".card { animation: slideIn 0.5s ease-in-out; }\n\n";
            }
        }
        
        if (isset($animations['button-animation'])) {
            $animation = $animations['button-animation'];
            if ($animation === 'pulse') {
                $css .= "@keyframes pulse {\n";
                $css .= "    0%, 100% { transform: scale(1); }\n";
                $css .= "    50% { transform: scale(1.05); }\n";
                $css .= "}\n";
                $css .= ".btn:hover { animation: pulse 0.3s ease-in-out; }\n\n";
            }
        }
        
        return $css;
    }
    
    /**
     * Resetear a valores por defecto
     */
    public function resetDefaults() {
        $defaults = [
            ['tipo' => 'color', 'nombre' => 'primary-color', 'valor' => '#8B0000', 'descripcion' => 'Color principal'],
            ['tipo' => 'color', 'nombre' => 'secondary-color', 'valor' => '#DC143C', 'descripcion' => 'Color secundario'],
            ['tipo' => 'color', 'nombre' => 'success-color', 'valor' => '#228B22', 'descripcion' => 'Color de éxito'],
            ['tipo' => 'color', 'nombre' => 'info-color', 'valor' => '#4682B4', 'descripcion' => 'Color informativo'],
            ['tipo' => 'color', 'nombre' => 'warning-color', 'valor' => '#FF4500', 'descripcion' => 'Color de advertencia'],
            ['tipo' => 'color', 'nombre' => 'danger-color', 'valor' => '#8B0000', 'descripcion' => 'Color de peligro'],
            ['tipo' => 'fuente', 'nombre' => 'font-family', 'valor' => 'Segoe UI, Tahoma, Geneva, Verdana, sans-serif', 'descripcion' => 'Familia de fuente principal'],
            ['tipo' => 'fuente', 'nombre' => 'font-size-base', 'valor' => '1rem', 'descripcion' => 'Tamaño de fuente base'],
            ['tipo' => 'animacion', 'nombre' => 'card-animation', 'valor' => 'fadeIn', 'descripcion' => 'Animación para cards'],
            ['tipo' => 'animacion', 'nombre' => 'button-animation', 'valor' => 'pulse', 'descripcion' => 'Animación para botones'],
            ['tipo' => 'animacion', 'nombre' => 'transition-speed', 'valor' => '0.3s', 'descripcion' => 'Velocidad de transiciones'],
            ['tipo' => 'general', 'nombre' => 'border-radius', 'valor' => '8px', 'descripcion' => 'Radio de bordes'],
            ['tipo' => 'general', 'nombre' => 'shadow-intensity', 'valor' => 'medium', 'descripcion' => 'Intensidad de sombras'],
        ];
        
        return $this->saveMultiple($defaults);
    }
}

