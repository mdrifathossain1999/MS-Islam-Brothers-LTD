<?php
class SummaryCard {
    private $db;
    private $table = 'summary_cards';

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
            card_key VARCHAR(50) UNIQUE NOT NULL,
            card_title VARCHAR(100) NOT NULL,
            card_type ENUM('users', 'products', 'customers', 'low_stock', 'custom') DEFAULT 'custom',
            icon_class VARCHAR(50) DEFAULT 'bi bi-collection',
            color_class VARCHAR(50) DEFAULT 'blue',
            custom_query TEXT,
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
            ['total_users', 'Total Users', 'users', 'bi bi-people', 'purple', 1],
            ['total_products', 'Total Products', 'products', 'bi bi-box-seam', 'green', 2],
            ['total_customers', 'Total Customers', 'customers', 'bi bi-person-hearts', 'orange', 3],
            ['total_due', 'Total Due', 'total_due', 'bi bi-cash-stack', 'red', 4],
            ['total_sell', 'Total Sales', 'total_sell', 'bi bi-cart-check', 'blue', 5],
            ['total_profit', 'Total Profit', 'total_profit', 'bi bi-graph-up-arrow', 'green', 6],
            ['low_stock', 'Low Stock Items', 'low_stock', 'bi bi-exclamation-triangle', 'orange', 7]
        ];
        
        foreach ($defaults as $card) {
            $stmt = $this->db->prepare("INSERT IGNORE INTO {$this->table} (card_key, card_title, card_type, icon_class, color_class, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute($card);
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

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (card_key, card_title, card_type, icon_class, color_class, custom_query, display_order, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['card_key'],
            $data['card_title'],
            $data['card_type'] ?? 'custom',
            $data['icon_class'] ?? 'bi bi-collection',
            $data['color_class'] ?? 'blue',
            $data['custom_query'] ?? null,
            $data['display_order'] ?? 0,
            $data['is_active'] ?? 'yes'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                card_title = ?, card_type = ?, icon_class = ?, color_class = ?, 
                custom_query = ?, display_order = ?, is_active = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['card_title'],
            $data['card_type'] ?? 'custom',
            $data['icon_class'] ?? 'bi bi-collection',
            $data['color_class'] ?? 'blue',
            $data['custom_query'] ?? null,
            $data['display_order'] ?? 0,
            $data['is_active'] ?? 'yes',
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND card_type = 'custom'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id) {
        $card = $this->getById($id);
        if ($card) {
            $newStatus = $card['is_active'] === 'yes' ? 'no' : 'yes';
            $sql = "UPDATE {$this->table} SET is_active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$newStatus, $id]);
        }
        return false;
    }

    public function getCardValue($card) {
        switch ($card['card_type']) {
            case 'users':
                $sql = "SELECT COUNT(*) as count FROM users WHERE status = 'active'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return $result['count'] ?? 0;
                
            case 'products':
                $sql = "SELECT COUNT(*) as count FROM products WHERE status = 'active'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return $result['count'] ?? 0;
                
            case 'customers':
                $sql = "SELECT COUNT(*) as count FROM customers WHERE status = 'active'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return $result['count'] ?? 0;
                
            case 'total_due':
                $sql = "SELECT COALESCE(SUM(total_amount - paid_amount), 0) as count FROM customers WHERE status = 'active'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return floatval($result['count'] ?? 0);
                
            case 'total_sell':
                $sql = "SELECT COALESCE(SUM(total_amount), 0) as count FROM sales WHERE status = 'completed'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return floatval($result['count'] ?? 0);
                
            case 'total_profit':
                $sql = "SELECT COALESCE(SUM(s.subtotal - (p.cost_price * si.quantity)), 0) as count 
                        FROM sales s 
                        LEFT JOIN sale_items si ON s.id = si.sale_id 
                        LEFT JOIN products p ON si.product_id = p.id 
                        WHERE s.status = 'completed'";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return floatval($result['count'] ?? 0);
                
            case 'low_stock':
                $sql = "SELECT COUNT(*) as count FROM products WHERE status = 'active' AND stock_quantity <= low_stock_threshold";
                $stmt = $this->db->query($sql);
                $result = $stmt->fetch();
                return $result['count'] ?? 0;
                
            case 'custom':
                if (!empty($card['custom_query'])) {
                    try {
                        $stmt = $this->db->query($card['custom_query']);
                        $result = $stmt->fetch();
                        return $result['count'] ?? 0;
                    } catch (Exception $e) {
                        return 0;
                    }
                }
                return 0;
            default:
                return 0;
        }
    }
}
