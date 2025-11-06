<?php
/**
 * Servicio de Optimización de Imágenes
 * Filá Mariscales
 */

class ImageOptimizer {
    /**
     * Optimizar imagen
     */
    public static function optimize(string $sourcePath, string $destinationPath = null, int $maxWidth = 1920, int $quality = 85): ?string {
        if (!file_exists($sourcePath)) {
            return null;
        }
        
        $destinationPath = $destinationPath ?? $sourcePath;
        
        // Obtener información de la imagen
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return null;
        }
        
        $mimeType = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Crear imagen desde archivo
        $image = self::createImageFromFile($sourcePath, $mimeType);
        if ($image === null) {
            return null;
        }
        
        // Redimensionar si es necesario
        if ($width > $maxWidth) {
            $newHeight = (int)($height * ($maxWidth / $width));
            $newImage = imagecreatetruecolor($maxWidth, $newHeight);
            
            // Preservar transparencia para PNG
            if ($mimeType === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $maxWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            $image = $newImage;
        }
        
        // Guardar imagen optimizada
        $result = self::saveImage($image, $destinationPath, $mimeType, $quality);
        
        // Liberar memoria
        imagedestroy($image);
        
        return $result ? $destinationPath : null;
    }
    
    /**
     * Crear thumbnail
     */
    public static function createThumbnail(string $sourcePath, string $destinationPath, int $maxWidth = 300, int $maxHeight = 300, int $quality = 85): ?string {
        if (!file_exists($sourcePath)) {
            return null;
        }
        
        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            return null;
        }
        
        $mimeType = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Calcular nuevas dimensiones manteniendo proporción
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
        
        // Crear imagen desde archivo
        $image = self::createImageFromFile($sourcePath, $mimeType);
        if ($image === null) {
            return null;
        }
        
        // Crear thumbnail
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Guardar thumbnail
        $result = self::saveImage($thumbnail, $destinationPath, $mimeType, $quality);
        
        // Liberar memoria
        imagedestroy($image);
        imagedestroy($thumbnail);
        
        return $result ? $destinationPath : null;
    }
    
    /**
     * Crear imagen desde archivo
     */
    private static function createImageFromFile(string $path, string $mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                return null;
        }
    }
    
    /**
     * Guardar imagen
     */
    private static function saveImage($image, string $path, string $mimeType, int $quality): bool {
        // Crear directorio si no existe
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        switch ($mimeType) {
            case 'image/jpeg':
                return imagejpeg($image, $path, $quality);
            case 'image/png':
                // PNG usa calidad 0-9, convertir de 0-100
                $pngQuality = (int)(9 - ($quality / 100) * 9);
                return imagepng($image, $path, $pngQuality);
            case 'image/gif':
                return imagegif($image, $path);
            default:
                return false;
        }
    }
    
    /**
     * Validar que es una imagen real
     */
    public static function validateImageFile(array $file): bool {
        // Verificar errores de subida
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Verificar MIME type real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }
        
        // Verificar que sea realmente una imagen
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return false;
        }
        
        // Verificar extensión
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        return in_array($extension, $allowedExtensions);
    }
    
    /**
     * Obtener información de imagen
     */
    public static function getImageInfo(string $path): ?array {
        $info = getimagesize($path);
        if ($info === false) {
            return null;
        }
        
        return [
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $info['mime'],
            'size' => filesize($path)
        ];
    }
}

