<?php

class Video {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obtener todos los videos (para admin - muestra todos, activos e inactivos)
    public function getAllVideos($page = 1, $perPage = 12, $categoria = null, $onlyActive = false) {
        $offset = ($page - 1) * $perPage;
        
        $sql = 'SELECT v.*, e.titulo as evento_titulo 
                FROM videos v 
                LEFT JOIN eventos e ON v.evento_id = e.id';
        
        $whereConditions = [];
        if ($onlyActive) {
            $whereConditions[] = 'v.activo = 1';
        }
        if ($categoria) {
            $whereConditions[] = 'v.categoria = :categoria';
        }
        
        if (!empty($whereConditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereConditions);
        }
        
        $sql .= ' ORDER BY v.fecha_subida DESC LIMIT :perPage OFFSET :offset';
        
        $this->db->query($sql);
        
        if ($categoria) {
            $this->db->bind(':categoria', $categoria);
        }
        
        // Usar PDO::PARAM_INT para los parámetros numéricos
        $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Obtener todos los videos activos (para público)
    public function getActiveVideos($page = 1, $perPage = 12, $categoria = null) {
        return $this->getAllVideos($page, $perPage, $categoria, true);
    }
    
    // Obtener video por ID
    public function getVideoById($id) {
        $this->db->query('SELECT v.*, e.titulo as evento_titulo 
                         FROM videos v 
                         LEFT JOIN eventos e ON v.evento_id = e.id 
                         WHERE v.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Crear video
    public function createVideo($data) {
        try {
            $sql = 'INSERT INTO videos 
                    (titulo, descripcion, url_video, url_thumbnail, tipo, categoria, evento_id, duracion, activo) 
                    VALUES (:titulo, :descripcion, :url_video, :url_thumbnail, :tipo, :categoria, :evento_id, :duracion, :activo)';
            
            $this->db->query($sql);
            
            $this->db->bind(':titulo', $data['titulo']);
            $this->db->bind(':descripcion', !empty($data['descripcion']) ? $data['descripcion'] : null);
            $this->db->bind(':url_video', $data['url_video']);
            $this->db->bind(':url_thumbnail', !empty($data['url_thumbnail']) ? $data['url_thumbnail'] : null);
            $this->db->bind(':tipo', $data['tipo'] ?? 'youtube');
            $this->db->bind(':categoria', !empty($data['categoria']) ? $data['categoria'] : null);
            $this->db->bind(':evento_id', !empty($data['evento_id']) ? intval($data['evento_id']) : null);
            $this->db->bind(':duracion', !empty($data['duracion']) ? intval($data['duracion']) : null);
            $this->db->bind(':activo', isset($data['activo']) ? (bool)$data['activo'] : true);
            
            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Error al crear video: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar video
    public function updateVideo($id, $data) {
        $this->db->query('UPDATE videos SET 
                         titulo = :titulo, 
                         descripcion = :descripcion, 
                         url_video = :url_video, 
                         url_thumbnail = :url_thumbnail, 
                         tipo = :tipo, 
                         categoria = :categoria, 
                         evento_id = :evento_id, 
                         duracion = :duracion, 
                         activo = :activo 
                         WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? null);
        $this->db->bind(':url_video', $data['url_video']);
        $this->db->bind(':url_thumbnail', $data['url_thumbnail'] ?? null);
        $this->db->bind(':tipo', $data['tipo'] ?? 'youtube');
        $this->db->bind(':categoria', $data['categoria'] ?? null);
        $this->db->bind(':evento_id', $data['evento_id'] ?? null);
        $this->db->bind(':duracion', $data['duracion'] ?? null);
        $this->db->bind(':activo', $data['activo'] ?? true);
        
        return $this->db->execute();
    }
    
    // Eliminar video
    public function deleteVideo($id) {
        $this->db->query('DELETE FROM videos WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Incrementar vistas
    public function incrementViews($id) {
        $this->db->query('UPDATE videos SET vistas = vistas + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Obtener videos por categoría
    public function getVideosByCategory($categoria) {
        $this->db->query('SELECT * FROM videos WHERE categoria = :categoria AND activo = 1 ORDER BY fecha_subida DESC');
        $this->db->bind(':categoria', $categoria);
        
        return $this->db->resultSet();
    }
    
    // Obtener videos por evento
    public function getVideosByEvent($eventoId) {
        $this->db->query('SELECT * FROM videos WHERE evento_id = :evento_id AND activo = 1 ORDER BY fecha_subida DESC');
        $this->db->bind(':evento_id', $eventoId);
        
        return $this->db->resultSet();
    }
    
    // Extraer ID de YouTube
    public function extractYouTubeId($url) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
    
    // Extraer ID de Vimeo
    public function extractVimeoId($url) {
        preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
}

