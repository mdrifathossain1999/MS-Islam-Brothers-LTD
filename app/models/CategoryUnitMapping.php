<?php
class CategoryUnitMapping {
    private $db;
    private $table = 'category_unit_mapping';
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    private function tableExists() {
        try {
            $result = $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            return $result && $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getAll() {
        if (!$this->tableExists()) return [];
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY category ASC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getByCategory($category) {
        if (!$this->tableExists()) return null;
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE category = ?");
            $stmt->execute([$category]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function getUnitByCategory($category) {
        $mapping = $this->getByCategory($category);
        return $mapping ? $mapping['unit_name'] : null;
    }
    
    public function create($data) {
        if (!$this->tableExists()) return false;
        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (category, unit_name) VALUES (?, ?)");
            return $stmt->execute([$data['category'], $data['unit_name']]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function update($category, $unitName) {
        if (!$this->tableExists()) return false;
        try {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET unit_name = ? WHERE category = ?");
            return $stmt->execute([$unitName, $category]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function delete($category) {
        if (!$this->tableExists()) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE category = ?");
            return $stmt->execute([$category]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function exists($category) {
        if (!$this->tableExists()) return false;
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE category = ?");
            $stmt->execute([$category]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}