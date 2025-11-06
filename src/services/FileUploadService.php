<?php
/**
 * Servicio Unificado de Subida de Archivos
 * Filá Mariscales
 */

require_once __DIR__ . '/ImageOptimizer.php';

class FileUploadService {
    // Constantes de configuración
    const MAX_FILE_SIZE = 52428800; // 50MB
    const MAX_IMAGE_SIZE = 10485760; // 10MB
    const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const ALLOWED_DOCUMENT_TYPES = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
    /**
     * Subir imagen con validación y optimización
     */
    public static function uploadImage(array $file, string $destinationDir, string $prefix = 'img', bool $optimize = true, bool $createThumbnail = false): array {
        $result = [
            'success' => false,
            'message' => '',
            'filename' => null,
            'thumbnail' => null,
            'path' => null
        ];
        
        // Validar archivo
        if (!ImageOptimizer::validateImageFile($file)) {
            $result['message'] = 'Archivo no válido o no es una imagen.';
            return $result;
        }
        
        // Validar tamaño
        if ($file['size'] > self::MAX_IMAGE_SIZE) {
            $result['message'] = 'La imagen es demasiado grande. Máximo 10MB.';
            return $result;
        }
        
        // Crear directorio si no existe
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                $result['message'] = 'No se pudo crear el directorio de destino.';
                return $result;
            }
        }
        
        // Verificar permisos
        if (!is_writable($destinationDir)) {
            $result['message'] = 'El directorio no tiene permisos de escritura.';
            return $result;
        }
        
        // Generar nombre único
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . uniqid() . '_' . time() . '.' . $extension;
        $destinationPath = rtrim($destinationDir, '/') . '/' . $filename;
        
        // Mover archivo temporal
        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            $result['message'] = 'Error al guardar el archivo.';
            return $result;
        }
        
        // Optimizar imagen si se solicita
        if ($optimize) {
            ImageOptimizer::optimize($destinationPath, $destinationPath, 1920, 85);
        }
        
        // Crear thumbnail si se solicita
        if ($createThumbnail) {
            $thumbnailPath = rtrim($destinationDir, '/') . '/thumb_' . $filename;
            ImageOptimizer::createThumbnail($destinationPath, $thumbnailPath, 300, 300, 85);
            $result['thumbnail'] = basename($thumbnailPath);
        }
        
        $result['success'] = true;
        $result['message'] = 'Imagen subida correctamente.';
        $result['filename'] = $filename;
        $result['path'] = $destinationPath;
        
        return $result;
    }
    
    /**
     * Subir múltiples imágenes
     */
    public static function uploadMultipleImages(array $files, string $destinationDir, string $prefix = 'img', bool $optimize = true): array {
        $results = [];
        
        foreach ($files['tmp_name'] as $key => $tmpName) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $tmpName,
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ];
                
                $results[] = self::uploadImage($file, $destinationDir, $prefix, $optimize);
            }
        }
        
        return $results;
    }
    
    /**
     * Subir documento
     */
    public static function uploadDocument(array $file, string $destinationDir, string $prefix = 'doc'): array {
        $result = [
            'success' => false,
            'message' => '',
            'filename' => null,
            'path' => null
        ];
        
        // Validar errores
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['message'] = 'Error al subir el archivo.';
            return $result;
        }
        
        // Validar tipo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, self::ALLOWED_DOCUMENT_TYPES)) {
            $result['message'] = 'Tipo de archivo no permitido.';
            return $result;
        }
        
        // Validar tamaño
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $result['message'] = 'El archivo es demasiado grande. Máximo 50MB.';
            return $result;
        }
        
        // Crear directorio
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        
        // Generar nombre único
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . uniqid() . '_' . time() . '.' . $extension;
        $destinationPath = rtrim($destinationDir, '/') . '/' . $filename;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            $result['success'] = true;
            $result['message'] = 'Documento subido correctamente.';
            $result['filename'] = $filename;
            $result['path'] = $destinationPath;
        } else {
            $result['message'] = 'Error al guardar el archivo.';
        }
        
        return $result;
    }
    
    /**
     * Eliminar archivo
     */
    public static function deleteFile(string $filePath): bool {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }
    
    /**
     * Eliminar archivo y su thumbnail
     */
    public static function deleteImageWithThumbnail(string $filePath): bool {
        $deleted = self::deleteFile($filePath);
        
        // Intentar eliminar thumbnail
        $dir = dirname($filePath);
        $filename = basename($filePath);
        $thumbnailPath = $dir . '/thumb_' . $filename;
        
        if (file_exists($thumbnailPath)) {
            self::deleteFile($thumbnailPath);
        }
        
        return $deleted;
    }
}

