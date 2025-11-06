<?php
/**
 * Sistema de Caché Básico
 * Filá Mariscales
 */

class CacheHelper {
    private static $cacheDir;
    private static $defaultTTL = 3600; // 1 hora por defecto
    
    /**
     * Inicializar sistema de caché
     */
    public static function init() {
        self::$cacheDir = dirname(dirname(__DIR__)) . '/cache/';
        
        // Crear directorio de caché si no existe
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
    }
    
    /**
     * Obtener valor del caché
     */
    public static function get(string $key) {
        $file = self::getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $data = unserialize(file_get_contents($file));
        
        // Verificar si ha expirado
        if (time() > $data['expires']) {
            self::delete($key);
            return null;
        }
        
        return $data['value'];
    }
    
    /**
     * Guardar valor en caché
     */
    public static function set(string $key, $value, int $ttl = null): bool {
        $ttl = $ttl ?? self::$defaultTTL;
        $file = self::getCacheFile($key);
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        return file_put_contents($file, serialize($data)) !== false;
    }
    
    /**
     * Eliminar del caché
     */
    public static function delete(string $key): bool {
        $file = self::getCacheFile($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return true;
    }
    
    /**
     * Limpiar todo el caché
     */
    public static function clear(): bool {
        $files = glob(self::$cacheDir . '*.cache');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }
    
    /**
     * Verificar si existe en caché
     */
    public static function has(string $key): bool {
        $file = self::getCacheFile($key);
        
        if (!file_exists($file)) {
            return false;
        }
        
        $data = unserialize(file_get_contents($file));
        
        // Verificar si ha expirado
        if (time() > $data['expires']) {
            self::delete($key);
            return false;
        }
        
        return true;
    }
    
    /**
     * Obtener o calcular (patrón remember)
     */
    public static function remember(string $key, callable $callback, int $ttl = null) {
        $value = self::get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        self::set($key, $value, $ttl);
        
        return $value;
    }
    
    /**
     * Obtener archivo de caché
     */
    private static function getCacheFile(string $key): string {
        $safeKey = md5($key);
        return self::$cacheDir . $safeKey . '.cache';
    }
    
    /**
     * Limpiar caché expirado
     */
    public static function cleanExpired(): int {
        $files = glob(self::$cacheDir . '*.cache');
        $cleaned = 0;
        
        foreach ($files as $file) {
            $data = unserialize(file_get_contents($file));
            
            if (time() > $data['expires']) {
                unlink($file);
                $cleaned++;
            }
        }
        
        return $cleaned;
    }
}

// Inicializar al cargar
CacheHelper::init();

