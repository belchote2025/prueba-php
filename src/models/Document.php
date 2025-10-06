<?php

class Document {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Crear tabla de documentos si no existe
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS documentos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descripcion TEXT,
            categoria VARCHAR(100) NOT NULL,
            archivo_nombre VARCHAR(255) NOT NULL,
            archivo_ruta VARCHAR(500) NOT NULL,
            archivo_tipo VARCHAR(100) NOT NULL,
            archivo_tamaño INT NOT NULL,
            usuario_id INT NOT NULL,
            fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            activo TINYINT(1) DEFAULT 1,
            descargas INT DEFAULT 0,
            FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        
        $this->db->query($sql);
        return $this->db->execute();
    }
    
    // Obtener todos los documentos activos
    public function getAllDocuments($page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.activo = 1 
                         ORDER BY d.fecha_subida DESC 
                         LIMIT :perPage OFFSET :offset');
        
        $this->db->bind(':perPage', $perPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    // Obtener documentos por categoría
    public function getDocumentsByCategory($category, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.activo = 1 AND d.categoria = :category 
                         ORDER BY d.fecha_subida DESC 
                         LIMIT :perPage OFFSET :offset');
        
        $this->db->bind(':category', $category);
        $this->db->bind(':perPage', $perPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    // Buscar documentos
    public function searchDocuments($searchTerm, $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.activo = 1 AND (d.titulo LIKE :search OR d.descripcion LIKE :search) 
                         ORDER BY d.fecha_subida DESC 
                         LIMIT :perPage OFFSET :offset');
        
        $this->db->bind(':search', '%' . $searchTerm . '%');
        $this->db->bind(':perPage', $perPage);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    // Obtener un documento por ID
    public function getDocumentById($id) {
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.id = :id AND d.activo = 1');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Crear nuevo documento
    public function createDocument($data) {
        $this->db->query('INSERT INTO documentos (titulo, descripcion, categoria, archivo_nombre, archivo_ruta, archivo_tipo, archivo_tamaño, usuario_id) 
                         VALUES (:titulo, :descripcion, :categoria, :archivo_nombre, :archivo_ruta, :archivo_tipo, :archivo_tamaño, :usuario_id)');
        
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':descripcion', $data['descripcion']);
        $this->db->bind(':categoria', $data['categoria']);
        $this->db->bind(':archivo_nombre', $data['archivo_nombre']);
        $this->db->bind(':archivo_ruta', $data['archivo_ruta']);
        $this->db->bind(':archivo_tipo', $data['archivo_tipo']);
        $this->db->bind(':archivo_tamaño', $data['archivo_tamaño']);
        $this->db->bind(':usuario_id', $data['usuario_id']);
        
        return $this->db->execute();
    }
    
    // Actualizar documento
    public function updateDocument($id, $data) {
        $this->db->query('UPDATE documentos 
                         SET titulo = :titulo, descripcion = :descripcion, categoria = :categoria 
                         WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':descripcion', $data['descripcion']);
        $this->db->bind(':categoria', $data['categoria']);
        
        return $this->db->execute();
    }
    
    // Eliminar documento (soft delete)
    public function deleteDocument($id) {
        $this->db->query('UPDATE documentos SET activo = 0 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Incrementar contador de descargas
    public function incrementDownloads($id) {
        $this->db->query('UPDATE documentos SET descargas = descargas + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Obtener estadísticas de documentos
    public function getDocumentStats() {
        $this->db->query('SELECT 
                            COUNT(*) as total_documentos,
                            SUM(descargas) as total_descargas,
                            COUNT(CASE WHEN categoria = "documentos-oficiales" THEN 1 END) as documentos_oficiales,
                            COUNT(CASE WHEN categoria = "formularios" THEN 1 END) as formularios,
                            COUNT(CASE WHEN categoria = "publicaciones" THEN 1 END) as publicaciones,
                            COUNT(CASE WHEN categoria = "multimedia" THEN 1 END) as multimedia,
                            COUNT(CASE WHEN categoria = "otros" THEN 1 END) as otros
                         FROM documentos 
                         WHERE activo = 1');
        
        return $this->db->single();
    }
    
    // Obtener documentos más descargados
    public function getMostDownloaded($limit = 5) {
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.activo = 1 
                         ORDER BY d.descargas DESC 
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    // Obtener documentos recientes
    public function getRecentDocuments($limit = 5) {
        $this->db->query('SELECT d.*, u.nombre, u.apellidos 
                         FROM documentos d 
                         LEFT JOIN users u ON d.usuario_id = u.id 
                         WHERE d.activo = 1 
                         ORDER BY d.fecha_subida DESC 
                         LIMIT :limit');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    // Contar total de documentos
    public function countDocuments($category = null, $search = null) {
        if ($category && $search) {
            $this->db->query('SELECT COUNT(*) as total 
                             FROM documentos 
                             WHERE activo = 1 AND categoria = :category AND (titulo LIKE :search OR descripcion LIKE :search)');
            $this->db->bind(':category', $category);
            $this->db->bind(':search', '%' . $search . '%');
        } elseif ($category) {
            $this->db->query('SELECT COUNT(*) as total 
                             FROM documentos 
                             WHERE activo = 1 AND categoria = :category');
            $this->db->bind(':category', $category);
        } elseif ($search) {
            $this->db->query('SELECT COUNT(*) as total 
                             FROM documentos 
                             WHERE activo = 1 AND (titulo LIKE :search OR descripcion LIKE :search)');
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query('SELECT COUNT(*) as total FROM documentos WHERE activo = 1');
        }
        
        $result = $this->db->single();
        return $result->total;
    }
    
    // Obtener categorías disponibles
    public function getCategories() {
        return [
            'documentos-oficiales' => 'Documentos Oficiales',
            'formularios' => 'Formularios',
            'publicaciones' => 'Publicaciones',
            'multimedia' => 'Multimedia',
            'otros' => 'Otros'
        ];
    }
    
    // Validar tipo de archivo
    public function validateFileType($fileType) {
        $allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpeg',
            'image/png',
            'image/gif',
            'audio/mpeg',
            'audio/wav',
            'video/mp4',
            'video/avi'
        ];
        
        return in_array($fileType, $allowedTypes);
    }
    
    // Obtener extensión de archivo por tipo MIME
    public function getFileExtension($mimeType) {
        $extensions = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
            'video/mp4' => 'mp4',
            'video/avi' => 'avi'
        ];
        
        return $extensions[$mimeType] ?? 'bin';
    }
}
