<?php
class CustomerType {
    private $db;
    private $table = 'customer_types';

    public function __construct($db) {
        $this->db = $db;
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        try {
            $this->db->query("SELECT 1 FROM {$this->table} LIMIT 1");
        } catch (Exception $e) {
            $this->createTable();
        }
    }

    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT PRIMARY KEY AUTO_INCREMENT,
            type_name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(255),
            discount_percent DECIMAL(5,2) DEFAULT 0.00,
            display_order INT DEFAULT 0,
            is_active ENUM('yes', 'no') DEFAULT 'yes',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);

        $check = $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
        if ($check == 0) {
            $this->insertDefaults();
        }
    }

    private function insertDefaults() {
        $defaults = [
            ['Retailer', 'Regular retail customers', 0.00, 1],
            ['Dealer', 'Wholesale dealers with discount', 5.00, 2],
            ['Wholesaler', 'Bulk buyers with special rates', 10.00, 3],
            ['VIP', 'VIP customers with extra benefits', 15.00, 4]
        ];
        
        foreach ($defaults as $type) {
            $stmt = $this->db->prepare("INSERT IGNORE INTO {$this->table} (type_name, description, discount_percent, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute($type);
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY display_order ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getActive() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 'yes' ORDER BY display_order ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE type_name = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (type_name, description, discount_percent, display_order, is_active) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['type_name'],
            $data['description'] ?? '',
            $data['discount_percent'] ?? 0.00,
            $data['display_order'] ?? 0,
            $data['is_active'] ?? 'yes'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                type_name = ?, description = ?, discount_percent = ?, 
                display_order = ?, is_active = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['type_name'],
            $data['description'] ?? '',
            $data['discount_percent'] ?? 0.00,
            $data['display_order'] ?? 0,
            $data['is_active'] ?? 'yes',
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id) {
        $type = $this->getById($id);
        if ($type) {
            $newStatus = $type['is_active'] === 'yes' ? 'no' : 'yes';
            $sql = "UPDATE {$this->table} SET is_active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$newStatus, $id]);
        }
        return false;
    }

    public function exists($name) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE type_name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAllForSelect() {
        $types = $this->getActive();
        $result = [];
        foreach ($types as $type) {
            $result[$type['type_name']] = $type['type_name'];
        }
        return $result;
    }
}
