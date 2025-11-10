<?php

class Video {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get all videos with optional filters
    public function getAllVideos($page = 1, $perPage = 20, $categoria = null, $activo = true) {
        // Asegurar que $page sea un entero
        $page = (int)$page;
        if ($page < 1) $page = 1;
        
        $perPage = (int)$perPage;
        if ($perPage < 1) $perPage = 20;
        
        $offset = ($page - 1) * $perPage;
        
        $sql = 'SELECT * FROM videos WHERE 1=1';
        $params = [];
        
        if ($activo !== null) {
            $sql .= ' AND activo = :activo';
            $params[':activo'] = $activo ? 1 : 0;
        }
        
        if ($categoria) {
            $sql .= ' AND categoria = :categoria';
            $params[':categoria'] = $categoria;
        }
        
        $sql .= ' ORDER BY fecha_subida DESC LIMIT :perPage OFFSET :offset';
        
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        $this->db->bind(':perPage', (int)$perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Get total count of videos
    public function getTotalVideos($categoria = null, $activo = true) {
        $sql = 'SELECT COUNT(*) as total FROM videos WHERE 1=1';
        $params = [];
        
        if ($activo !== null) {
            $sql .= ' AND activo = :activo';
            $params[':activo'] = $activo ? 1 : 0;
        }
        
        if ($categoria) {
            $sql .= ' AND categoria = :categoria';
            $params[':categoria'] = $categoria;
        }
        
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        $result = $this->db->single();
        return $result ? (int)$result->total : 0;
    }
    
    // Get video by ID
    public function getVideoById($id) {
        $this->db->query('SELECT * FROM videos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Create new video
    public function createVideo($data) {
        // Generar thumbnail automáticamente si no se proporciona y es YouTube
        if (empty($data['url_thumbnail']) && ($data['tipo'] ?? '') === 'youtube') {
            $youtubeId = self::extractYouTubeId($data['url_video'] ?? '');
            if ($youtubeId) {
                $data['url_thumbnail'] = 'https://img.youtube.com/vi/' . $youtubeId . '/maxresdefault.jpg';
            }
        }
        
        $this->db->query('INSERT INTO videos (titulo, descripcion, url_video, url_thumbnail, tipo, categoria, tags, evento_id, duracion, activo) 
                         VALUES (:titulo, :descripcion, :url_video, :url_thumbnail, :tipo, :categoria, :tags, :evento_id, :duracion, :activo)');
        
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        $this->db->bind(':url_video', $data['url_video']);
        $this->db->bind(':url_thumbnail', $data['url_thumbnail'] ?? '');
        $this->db->bind(':tipo', $data['tipo'] ?? 'youtube');
        $this->db->bind(':categoria', $data['categoria'] ?? 'general');
        $this->db->bind(':tags', $data['tags'] ?? '');
        $this->db->bind(':evento_id', $data['evento_id'] ?? null);
        $this->db->bind(':duracion', $data['duracion'] ?? 0);
        $this->db->bind(':activo', $data['activo'] ?? 1);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // Update video
    public function updateVideo($id, $data) {
        // Generar thumbnail automáticamente si no se proporciona y es YouTube
        if (empty($data['url_thumbnail']) && ($data['tipo'] ?? '') === 'youtube') {
            $youtubeId = self::extractYouTubeId($data['url_video'] ?? '');
            if ($youtubeId) {
                $data['url_thumbnail'] = 'https://img.youtube.com/vi/' . $youtubeId . '/maxresdefault.jpg';
            }
        }
        
        $this->db->query('UPDATE videos SET 
                         titulo = :titulo, 
                         descripcion = :descripcion, 
                         url_video = :url_video, 
                         url_thumbnail = :url_thumbnail, 
                         tipo = :tipo, 
                         categoria = :categoria, 
                         tags = :tags,
                         evento_id = :evento_id, 
                         duracion = :duracion, 
                         activo = :activo 
                         WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        $this->db->bind(':url_video', $data['url_video']);
        $this->db->bind(':url_thumbnail', $data['url_thumbnail'] ?? '');
        $this->db->bind(':tipo', $data['tipo'] ?? 'youtube');
        $this->db->bind(':categoria', $data['categoria'] ?? 'general');
        $this->db->bind(':tags', $data['tags'] ?? '');
        $this->db->bind(':evento_id', $data['evento_id'] ?? null);
        $this->db->bind(':duracion', $data['duracion'] ?? 0);
        $this->db->bind(':activo', $data['activo'] ?? 1);
        
        return $this->db->execute();
    }
    
    // Delete video
    public function deleteVideo($id) {
        $this->db->query('DELETE FROM videos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // Extract YouTube ID from URL
    public static function extractYouTubeId($url) {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }
    
    // Extract Vimeo ID from URL
    public static function extractVimeoId($url) {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }
    
    // Get video embed URL
    public function getEmbedUrl($video) {
        $tipo = is_object($video) ? $video->tipo : $video['tipo'];
        $url = is_object($video) ? $video->url_video : $video['url_video'];
        
        if ($tipo === 'youtube') {
            $id = self::extractYouTubeId($url);
            return $id ? 'https://www.youtube.com/embed/' . $id : '';
        } elseif ($tipo === 'vimeo') {
            $id = self::extractVimeoId($url);
            return $id ? 'https://player.vimeo.com/video/' . $id : '';
        } else {
            return $url;
        }
    }
    
    // Get video thumbnail
    public function getThumbnail($video) {
        $thumbnail = is_object($video) ? $video->url_thumbnail : ($video['url_thumbnail'] ?? '');
        
        if ($thumbnail) {
            return $thumbnail;
        }
        
        $tipo = is_object($video) ? $video->tipo : $video['tipo'];
        $url = is_object($video) ? $video->url_video : $video['url_video'];
        
        if ($tipo === 'youtube') {
            $id = self::extractYouTubeId($url);
            return $id ? 'https://img.youtube.com/vi/' . $id . '/maxresdefault.jpg' : '';
        }
        
        return '';
    }
    
    // Increment view count
    public function incrementViews($id) {
        $this->db->query('UPDATE videos SET vistas = vistas + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // Get videos by category
    public function getVideosByCategory($categoria) {
        $this->db->query('SELECT * FROM videos WHERE categoria = :categoria AND activo = 1 ORDER BY fecha_subida DESC');
        $this->db->bind(':categoria', $categoria);
        return $this->db->resultSet();
    }
}

