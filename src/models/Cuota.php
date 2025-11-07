<?php

class Cuota {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Obtener todas las cuotas con información del usuario
     */
    public function getAllCuotas($filtroEstado = null, $filtroUsuario = null) {
        $sql = 'SELECT c.*, u.nombre as usuario_nombre, u.email as usuario_email 
                FROM cuotas c 
                LEFT JOIN usuarios u ON c.usuario_id = u.id 
                WHERE 1=1';
        
        $params = [];
        
        if ($filtroEstado) {
            $sql .= ' AND c.estado = :estado';
            $params[':estado'] = $filtroEstado;
        }
        
        if ($filtroUsuario) {
            $sql .= ' AND c.usuario_id = :usuario_id';
            $params[':usuario_id'] = $filtroUsuario;
        }
        
        $sql .= ' ORDER BY c.fecha_vencimiento DESC, c.fecha_creacion DESC';
        
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Obtener una cuota por ID
     */
    public function getCuotaById($id) {
        $this->db->query('SELECT c.*, u.nombre as usuario_nombre, u.email as usuario_email 
                          FROM cuotas c 
                          LEFT JOIN usuarios u ON c.usuario_id = u.id 
                          WHERE c.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Obtener cuotas pendientes
     */
    public function getCuotasPendientes() {
        $this->db->query('SELECT COUNT(*) as total FROM cuotas WHERE estado = "pendiente"');
        $result = $this->db->single();
        return $result ? (int)$result->total : 0;
    }

    /**
     * Obtener cuotas vencidas
     */
    public function getCuotasVencidas() {
        $this->db->query('SELECT COUNT(*) as total FROM cuotas 
                          WHERE estado = "pendiente" AND fecha_vencimiento < CURDATE()');
        $result = $this->db->single();
        return $result ? (int)$result->total : 0;
    }

    /**
     * Crear una nueva cuota
     */
    public function createCuota($data) {
        $sql = 'INSERT INTO cuotas 
                (usuario_id, año, mes, monto, estado, fecha_vencimiento, notas) 
                VALUES (:usuario_id, :año, :mes, :monto, :estado, :fecha_vencimiento, :notas)';
        
        $this->db->query($sql);
        $this->db->bind(':usuario_id', $data['usuario_id']);
        $this->db->bind(':año', $data['año']);
        $this->db->bind(':mes', !empty($data['mes']) ? $data['mes'] : null);
        $this->db->bind(':monto', $data['monto']);
        $this->db->bind(':estado', $data['estado'] ?? 'pendiente');
        $this->db->bind(':fecha_vencimiento', !empty($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : null);
        $this->db->bind(':notas', !empty($data['notas']) ? $data['notas'] : null);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualizar una cuota
     */
    public function updateCuota($id, $data) {
        $sql = 'UPDATE cuotas SET 
                usuario_id = :usuario_id,
                año = :año,
                mes = :mes,
                monto = :monto,
                estado = :estado,
                fecha_vencimiento = :fecha_vencimiento,
                fecha_pago = :fecha_pago,
                metodo_pago = :metodo_pago,
                referencia_pago = :referencia_pago,
                notas = :notas
                WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':usuario_id', $data['usuario_id']);
        $this->db->bind(':año', $data['año']);
        $this->db->bind(':mes', !empty($data['mes']) ? $data['mes'] : null);
        $this->db->bind(':monto', $data['monto']);
        $this->db->bind(':estado', $data['estado']);
        $this->db->bind(':fecha_vencimiento', !empty($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : null);
        $this->db->bind(':fecha_pago', !empty($data['fecha_pago']) ? $data['fecha_pago'] : null);
        $this->db->bind(':metodo_pago', !empty($data['metodo_pago']) ? $data['metodo_pago'] : null);
        $this->db->bind(':referencia_pago', !empty($data['referencia_pago']) ? $data['referencia_pago'] : null);
        $this->db->bind(':notas', !empty($data['notas']) ? $data['notas'] : null);
        
        return $this->db->execute();
    }

    /**
     * Marcar cuota como pagada
     */
    public function marcarComoPagada($id, $fechaPago = null, $metodoPago = null, $referenciaPago = null) {
        $sql = 'UPDATE cuotas SET 
                estado = "pagada",
                fecha_pago = :fecha_pago,
                metodo_pago = :metodo_pago,
                referencia_pago = :referencia_pago
                WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':fecha_pago', $fechaPago ?: date('Y-m-d'));
        $this->db->bind(':metodo_pago', $metodoPago);
        $this->db->bind(':referencia_pago', $referenciaPago);
        
        return $this->db->execute();
    }

    /**
     * Eliminar una cuota
     */
    public function deleteCuota($id) {
        $this->db->query('DELETE FROM cuotas WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Obtener cuotas por usuario
     */
    public function getCuotasByUsuario($usuarioId) {
        $this->db->query('SELECT * FROM cuotas WHERE usuario_id = :usuario_id ORDER BY fecha_vencimiento DESC');
        $this->db->bind(':usuario_id', $usuarioId);
        return $this->db->resultSet();
    }
}

