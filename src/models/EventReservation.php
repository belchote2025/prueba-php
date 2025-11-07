<?php

class EventReservation {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Crear reserva
    public function createReservation($data) {
        $this->db->query('INSERT INTO reservas_eventos 
                         (evento_id, usuario_id, nombre, email, telefono, num_personas, estado, notas) 
                         VALUES (:evento_id, :usuario_id, :nombre, :email, :telefono, :num_personas, :estado, :notas)');
        
        $this->db->bind(':evento_id', $data['evento_id']);
        $this->db->bind(':usuario_id', $data['usuario_id'] ?? null);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':telefono', $data['telefono'] ?? null);
        $this->db->bind(':num_personas', $data['num_personas'] ?? 1);
        $this->db->bind(':estado', $data['estado'] ?? 'pendiente');
        $this->db->bind(':notas', $data['notas'] ?? null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // Obtener reserva por ID
    public function getReservationById($id) {
        $this->db->query('SELECT r.*, e.titulo as evento_titulo, e.fecha as evento_fecha, e.hora as evento_hora, e.lugar as evento_lugar 
                         FROM reservas_eventos r 
                         JOIN eventos e ON r.evento_id = e.id 
                         WHERE r.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Obtener reserva por código
    public function getReservationByCode($codigo) {
        $this->db->query('SELECT r.*, e.titulo as evento_titulo, e.fecha as evento_fecha, e.hora as evento_hora, e.lugar as evento_lugar 
                         FROM reservas_eventos r 
                         JOIN eventos e ON r.evento_id = e.id 
                         WHERE r.codigo_reserva = :codigo');
        $this->db->bind(':codigo', $codigo);
        
        return $this->db->single();
    }
    
    // Obtener reservas de un evento
    public function getReservationsByEventId($eventoId) {
        $this->db->query('SELECT r.*, u.nombre as usuario_nombre 
                         FROM reservas_eventos r 
                         LEFT JOIN usuarios u ON r.usuario_id = u.id 
                         WHERE r.evento_id = :evento_id 
                         ORDER BY r.fecha_reserva DESC');
        $this->db->bind(':evento_id', $eventoId);
        
        return $this->db->resultSet();
    }
    
    // Obtener reservas de un usuario
    public function getReservationsByUserId($usuarioId) {
        $this->db->query('SELECT r.*, e.titulo as evento_titulo, e.fecha as evento_fecha, e.hora as evento_hora, e.lugar as evento_lugar 
                         FROM reservas_eventos r 
                         JOIN eventos e ON r.evento_id = e.id 
                         WHERE r.usuario_id = :usuario_id 
                         ORDER BY r.fecha_reserva DESC');
        $this->db->bind(':usuario_id', $usuarioId);
        
        return $this->db->resultSet();
    }
    
    // Contar reservas confirmadas de un evento
    public function countConfirmedReservations($eventoId) {
        $this->db->query('SELECT SUM(num_personas) as total 
                         FROM reservas_eventos 
                         WHERE evento_id = :evento_id AND estado = "confirmada"');
        $this->db->bind(':evento_id', $eventoId);
        
        $result = $this->db->single();
        return $result->total ?? 0;
    }
    
    // Actualizar estado de reserva
    public function updateReservationStatus($id, $estado) {
        $this->db->query('UPDATE reservas_eventos SET estado = :estado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $estado);
        
        return $this->db->execute();
    }
    
    // Cancelar reserva
    public function cancelReservation($id) {
        return $this->updateReservationStatus($id, 'cancelada');
    }
    
    // Verificar disponibilidad
    public function checkAvailability($eventoId, $numPersonas) {
        // Obtener capacidad del evento
        $this->db->query('SELECT capacidad FROM eventos WHERE id = :id');
        $this->db->bind(':id', $eventoId);
        $evento = $this->db->single();
        
        if (!$evento || !$evento->capacidad) {
            return true; // Sin límite de capacidad
        }
        
        $ocupadas = $this->countConfirmedReservations($eventoId);
        $disponibles = $evento->capacidad - $ocupadas;
        
        return $disponibles >= $numPersonas;
    }
}

