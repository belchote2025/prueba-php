<?php

class Comment {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Obtener comentarios de una noticia
    public function getCommentsByNewsId($noticiaId, $aprobados = true) {
        $sql = 'SELECT c.*, u.nombre as usuario_nombre, u.avatar as usuario_avatar 
                FROM comentarios c 
                LEFT JOIN usuarios u ON c.usuario_id = u.id 
                WHERE c.noticia_id = :noticia_id';
        
        if ($aprobados) {
            $sql .= ' AND c.aprobado = 1';
        }
        
        $sql .= ' ORDER BY c.fecha_creacion ASC';
        
        $this->db->query($sql);
        $this->db->bind(':noticia_id', $noticiaId);
        
        return $this->db->resultSet();
    }
    
    // Obtener comentarios con respuestas anidadas
    public function getCommentsWithReplies($noticiaId, $aprobados = true) {
        $comments = $this->getCommentsByNewsId($noticiaId, $aprobados);
        
        // Organizar comentarios por padre
        $organized = [];
        foreach ($comments as $comment) {
            if ($comment->comentario_padre_id === null) {
                $comment->respuestas = [];
                $organized[$comment->id] = $comment;
            }
        }
        
        // Agregar respuestas a sus padres
        foreach ($comments as $comment) {
            if ($comment->comentario_padre_id !== null && isset($organized[$comment->comentario_padre_id])) {
                $organized[$comment->comentario_padre_id]->respuestas[] = $comment;
            }
        }
        
        return array_values($organized);
    }
    
    // Crear comentario
    public function createComment($data) {
        $this->db->query('INSERT INTO comentarios (noticia_id, usuario_id, nombre, email, comentario, comentario_padre_id, aprobado) 
                         VALUES (:noticia_id, :usuario_id, :nombre, :email, :comentario, :comentario_padre_id, :aprobado)');
        
        $this->db->bind(':noticia_id', $data['noticia_id']);
        $this->db->bind(':usuario_id', $data['usuario_id'] ?? null);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':comentario', $data['comentario']);
        $this->db->bind(':comentario_padre_id', $data['comentario_padre_id'] ?? null);
        $this->db->bind(':aprobado', $data['aprobado'] ?? false);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // Aprobar/desaprobar comentario
    public function toggleApproval($id, $aprobado) {
        $this->db->query('UPDATE comentarios SET aprobado = :aprobado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':aprobado', $aprobado);
        
        return $this->db->execute();
    }
    
    // Eliminar comentario
    public function deleteComment($id) {
        $this->db->query('DELETE FROM comentarios WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Obtener comentario por ID
    public function getCommentById($id) {
        $this->db->query('SELECT c.*, u.nombre as usuario_nombre, u.avatar as usuario_avatar 
                         FROM comentarios c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Contar comentarios por noticia
    public function countCommentsByNewsId($noticiaId, $aprobados = true) {
        $sql = 'SELECT COUNT(*) as total FROM comentarios WHERE noticia_id = :noticia_id';
        if ($aprobados) {
            $sql .= ' AND aprobado = 1';
        }
        
        $this->db->query($sql);
        $this->db->bind(':noticia_id', $noticiaId);
        
        $result = $this->db->single();
        return $result->total ?? 0;
    }
    
    // Obtener comentarios pendientes de aprobación
    public function getPendingComments($limit = 50) {
        $this->db->query('SELECT c.*, n.titulo as noticia_titulo, u.nombre as usuario_nombre 
                         FROM comentarios c 
                         LEFT JOIN noticias n ON c.noticia_id = n.id 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.aprobado = 0 
                         ORDER BY c.fecha_creacion DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
}

