<?php
class SizeType {
    private $db;
    private $table = 'size_types';
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (size_type_name, size_type_options) VALUES (?, ?)");
        return $stmt->execute([$data['size_type_name'], $data['size_type_options']]);
    }
    
    public function delete($sizeTypeName) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE size_type_name = ?");
        return $stmt->execute([$sizeTypeName]);
    }
    
    public function exists($sizeTypeName) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE size_type_name = ?");
        $stmt->execute([$sizeTypeName]);
        return $stmt->fetchColumn() > 0;
    }
}