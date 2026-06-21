<?php

class Product {
    private $db;
    private $table = 'products';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($status = null) {
        if ($status) {
            $sql = "SELECT * FROM {$this->table} WHERE status = ? ORDER BY product_name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY product_name ASC";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }

    public function getActive() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY product_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByBarcode($barcode) {
        $sql = "SELECT * FROM {$this->table} WHERE barcode = ? AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$barcode]);
        return $stmt->fetch();
    }
    
    public function getByCategory($category) {
        $sql = "SELECT * FROM {$this->table} WHERE category = ? ORDER BY product_name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    public function search($term) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'active' 
                AND (product_name LIKE ? OR barcode LIKE ?) 
                ORDER BY product_name ASC 
                LIMIT 20";
        $stmt = $this->db->prepare($sql);
        $term = "%{$term}%";
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        // Check if barcode already exists (if provided)
        if (!empty($data['barcode'])) {
            $checkSql = "SELECT id FROM {$this->table} WHERE barcode = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$data['barcode']]);
            if ($checkStmt->fetch()) {
                throw new Exception("Barcode already exists");
            }
        }
        
        $sql = "INSERT INTO {$this->table} (barcode, product_name, category, description, cost_price, sell_price, stock_quantity, low_stock_threshold, unit, image, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['barcode'] ?? null,
            $data['product_name'],
            $data['category'] ?? null,
            $data['description'] ?? null,
            $data['cost_price'] ?? 0,
            $data['sell_price'],
            $data['stock_quantity'] ?? 0,
            $data['low_stock_threshold'] ?? 10,
            $data['unit'] ?? 'piece',
            $data['image'] ?? null,
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['barcode', 'product_name', 'category', 'description', 'cost_price', 'sell_price', 'stock_quantity', 'low_stock_threshold', 'unit', 'image', 'status'];
        
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateStock($id, $quantity, $type = 'add', $userId = null) {
        $product = $this->getById($id);
        if (!$product) return false;

        $newQuantity = $type === 'add' ? $product['stock_quantity'] + $quantity : $product['stock_quantity'] - $quantity;
        
        $sql = "UPDATE {$this->table} SET stock_quantity = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$newQuantity, $id]);

        if ($result && $userId) {
            require_once 'StockLog.php';
            $log = new StockLog($this->db);
            $log->create([
                'product_id' => $id,
                'previous_quantity' => $product['stock_quantity'],
                'new_quantity' => $newQuantity,
                'change_type' => $type,
                'user_id' => $userId,
                'notes' => "Stock $type via sale"
            ]);
        }

        return $result;
    }

    public function delete($id) {
        // First delete related records
        $this->db->exec("DELETE FROM stock_logs WHERE product_id = $id");
        $this->db->exec("DELETE FROM sale_items WHERE product_id = $id");
        
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE product_name = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function getLowStock($limit = null) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'active' AND stock_quantity <= low_stock_threshold 
                ORDER BY stock_quantity ASC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getCategories() {
        // Try to get from categories table first
        try {
            $sql = "SELECT c.id, c.category_name, c.size_type, c.size_options, 
                    (SELECT COUNT(*) FROM {$this->table} p WHERE p.category = c.category_name) as count 
                    FROM categories c ORDER BY c.category_name";
            $stmt = $this->db->query($sql);
            $categories = $stmt->fetchAll();
            if (!empty($categories)) {
                return $categories;
            }
        } catch (Exception $e) {}
        
        // Fallback to products table
        $sql = "SELECT category, COUNT(*) as count FROM {$this->table} WHERE category IS NOT NULL AND category != '' GROUP BY category ORDER BY category";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getUnits() {
        try {
            $sql = "SELECT * FROM units ORDER BY unit_name ASC";
            $stmt = $this->db->query($sql);
            $units = $stmt->fetchAll();
            if (empty($units)) {
                $this->initDefaultUnits();
                $stmt = $this->db->query($sql);
                $units = $stmt->fetchAll();
            }
            return $units;
        } catch (Exception $e) {
            $this->createUnitsTable();
            $this->initDefaultUnits();
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
    }
    
    private function createUnitsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS units (
            id INT PRIMARY KEY AUTO_INCREMENT,
            unit_name VARCHAR(50) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);
    }
    
    private function initDefaultUnits() {
        $defaultUnits = ['S', 'M', 'L', 'XL', 'XXL', 'OVER', 'SMALL', 'MEDIUM', 'LARGE', 'PIECE', 'KG', 'GRAM', 'LITER', 'ML', 'BOX', 'PACKET', 'DOZEN', 'BOTTLE'];
        foreach ($defaultUnits as $unit) {
            $sql = "INSERT IGNORE INTO units (unit_name) VALUES (?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$unit]);
        }
    }
    
    public function addCategory($category, $sizeType = '', $sizeOptions = '') {
        // Create categories table if not exists
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS `categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `category_name` varchar(100) NOT NULL,
                `size_type` varchar(50) DEFAULT '',
                `size_options` text DEFAULT '',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `category_name` (`category_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        } catch (Exception $e) {}
        
        // Check if category exists
        $check = $this->db->prepare("SELECT id FROM categories WHERE category_name = ?");
        $check->execute([$category]);
        if ($check->fetch()) {
            return false;
        }
        
        // Insert new category
        $stmt = $this->db->prepare("INSERT INTO categories (category_name, size_type, size_options) VALUES (?, ?, ?)");
        return $stmt->execute([$category, $sizeType, $sizeOptions]);
    }
    
    public function updateCategory($oldCategory, $newCategory, $sizeType = '', $sizeOptions = '') {
        $sql = "UPDATE categories SET category_name = ?, size_type = ?, size_options = ? WHERE category_name = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newCategory, $sizeType, $sizeOptions, $oldCategory]);
    }
    
    public function deleteCategory($category) {
        $sql = "UPDATE {$this->table} SET category = NULL WHERE category = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$category]);
    }
    
    public function addUnit($unitName) {
        $sql = "INSERT INTO units (unit_name) VALUES (?)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$unitName]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function updateUnit($id, $newName) {
        $sql = "UPDATE units SET unit_name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newName, $id]);
    }
    
    public function deleteUnit($id) {
        $sql = "DELETE FROM units WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
