<?php

class Product {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Obtener todos los productos
     */
    public function getAllProducts() {
        $this->db->query('SELECT p.*, 
                         COALESCE(p.categoria, "Sin categoría") as categoria_nombre 
                         FROM productos p 
                         ORDER BY p.id DESC');
        
        return $this->db->resultSet();
    }
    
    /**
     * Crear un nuevo producto
     */
    public function createProduct($data) {
        $this->db->query('INSERT INTO productos (nombre, descripcion, precio, stock, categoria, activo, fecha_creacion) 
                         VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :activo, NOW())');
        
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        $this->db->bind(':precio', $data['precio']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':categoria', $data['categoria'] ?? $data['categoria_id'] ?? 'Sin categoría');
        $this->db->bind(':activo', $data['activo']);
        
        return $this->db->execute();
    }
    
    /**
     * Obtener un producto por ID
     */
    public function getProductById($id) {
        $this->db->query('SELECT p.*, 
                         COALESCE(p.categoria, "Sin categoría") as categoria_nombre 
                         FROM productos p 
                         WHERE p.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Actualizar un producto
     */
    public function updateProduct($id, $data) {
        $this->db->query('UPDATE productos 
                         SET nombre = :nombre, 
                             descripcion = :descripcion, 
                             precio = :precio, 
                             stock = :stock, 
                             categoria = :categoria, 
                             activo = :activo,
                             fecha_actualizacion = NOW()
                         WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        $this->db->bind(':precio', $data['precio']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':categoria', $data['categoria'] ?? $data['categoria_id'] ?? 'Sin categoría');
        $this->db->bind(':activo', $data['activo']);
        
        return $this->db->execute();
    }
    
    /**
     * Eliminar un producto
     */
    public function deleteProduct($id) {
        $this->db->query('DELETE FROM productos WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Obtener categorías (array hardcodeado)
     */
    public function getCategories() {
        return [
            'ropa' => 'Ropa',
            'accesorios' => 'Accesorios',
            'banderas' => 'Banderas',
            'merchandising' => 'Merchandising',
            'otros' => 'Otros'
        ];
    }
    
    /**
     * Contar productos
     */
    public function countProducts() {
        $this->db->query('SELECT COUNT(*) as total FROM productos');
        $result = $this->db->single();
        return $result->total ?? 0;
    }
}
