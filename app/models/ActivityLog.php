<?php
class ActivityLog {
    private $db;
    private $table = 'activity_logs';

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
            user_id INT DEFAULT NULL,
            action VARCHAR(50) NOT NULL,
            module VARCHAR(50) NOT NULL,
            description TEXT,
            reference_type VARCHAR(50) DEFAULT NULL,
            reference_id INT DEFAULT NULL,
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);
    }

    public function log($userId, $action, $module, $description = null, $referenceType = null, $referenceId = null) {
        $sql = "INSERT INTO {$this->table} (user_id, action, module, description, reference_type, reference_id, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $userId,
            $action,
            $module,
            $description,
            $referenceType,
            $referenceId,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function getAll($limit = 100, $offset = 0, $filters = []) {
        $sql = "SELECT al.id, al.user_id, al.action, al.module, al.description, al.reference_type, al.reference_id, al.ip_address, al.user_agent, al.created_at, u.username, u.full_name 
                FROM {$this->table} al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND al.user_id = ?";
            $params[] = $filters['user_id'];
        }
        if (!empty($filters['module'])) {
            $sql .= " AND al.module = ?";
            $params[] = $filters['module'];
        }
        if (!empty($filters['action'])) {
            $sql .= " AND al.action = ?";
            $params[] = $filters['action'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(al.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(al.created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        // UNION with stock_logs
        $unionSql = " UNION ALL 
                SELECT sl.id, sl.user_id, sl.change_type as action, 'stock' as module, 
                CONCAT('Stock: ', p.product_name, ' - ', sl.notes, ' (', sl.previous_quantity, ' → ', sl.new_quantity, ')') as description,
                'product' as reference_type, sl.product_id as reference_id, NULL as ip_address, NULL as user_agent, sl.created_at,
                u.username, u.full_name
                FROM stock_logs sl 
                LEFT JOIN users u ON sl.user_id = u.id
                LEFT JOIN products p ON sl.product_id = p.id
                WHERE 1=1";
        
        if (!empty($filters['user_id'])) {
            $unionSql .= " AND sl.user_id = ?";
            $params[] = $filters['user_id'];
        }
        if (!empty($filters['module']) && $filters['module'] === 'stock') {
            // Keep this filter for stock only
        }
        if (!empty($filters['date_from'])) {
            $unionSql .= " AND DATE(sl.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $unionSql .= " AND DATE(sl.created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        // Apply module filter for combined result
        $moduleFilter = "";
        if (!empty($filters['module'])) {
            if ($filters['module'] === 'stock') {
                $moduleFilter = " AND module = 'stock'";
            } elseif ($filters['module'] !== 'stock') {
                $moduleFilter = " AND module = '" . $filters['module'] . "'";
            }
        }

        // If filtering by stock module only, don't include activity_logs
        if (!empty($filters['module']) && $filters['module'] === 'stock') {
            $finalSql = "SELECT sl.id, sl.user_id, sl.change_type as action, 'stock' as module, 
                    CONCAT('Stock: ', p.product_name, ' - ', sl.notes, ' (', sl.previous_quantity, ' → ', sl.new_quantity, ')') as description,
                    'product' as reference_type, sl.product_id as reference_id, NULL as ip_address, NULL as user_agent, sl.created_at,
                    u.username, u.full_name
                    FROM stock_logs sl 
                    LEFT JOIN users u ON sl.user_id = u.id
                    LEFT JOIN products p ON sl.product_id = p.id
                    WHERE 1=1";
            
            if (!empty($filters['user_id'])) {
                $finalSql .= " AND sl.user_id = ?";
            }
            if (!empty($filters['date_from'])) {
                $finalSql .= " AND DATE(sl.created_at) >= ?";
            }
            if (!empty($filters['date_to'])) {
                $finalSql .= " AND DATE(sl.created_at) <= ?";
            }
            
            $finalSql .= " ORDER BY sl.created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        } else {
            $finalSql = "SELECT * FROM (" . $sql . $unionSql . ") as combined WHERE 1=1" . $moduleFilter . " ORDER BY created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($finalSql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalCount($filters = []) {
        $params = [];
        
        // Count from activity_logs
        $sql1 = "SELECT COUNT(*) FROM {$this->table} al WHERE 1=1";
        if (!empty($filters['user_id'])) {
            $sql1 .= " AND al.user_id = ?";
            $params[] = $filters['user_id'];
        }
        if (!empty($filters['module']) && $filters['module'] !== 'stock') {
            $sql1 .= " AND al.module = ?";
            $params[] = $filters['module'];
        }
        if (!empty($filters['action'])) {
            $sql1 .= " AND al.action = ?";
            $params[] = $filters['action'];
        }
        if (!empty($filters['date_from'])) {
            $sql1 .= " AND DATE(al.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql1 .= " AND DATE(al.created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        
        // Count from stock_logs
        $sql2 = "SELECT COUNT(*) FROM stock_logs sl WHERE 1=1";
        if (!empty($filters['user_id'])) {
            $sql2 .= " AND sl.user_id = ?";
        }
        if (!empty($filters['date_from'])) {
            $sql2 .= " AND DATE(sl.created_at) >= ?";
        }
        if (!empty($filters['date_to'])) {
            $sql2 .= " AND DATE(sl.created_at) <= ?";
        }
        
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute($params);
        $count1 = $stmt1->fetchColumn();
        
        if (!empty($filters['module']) && $filters['module'] === 'stock') {
            return $count1;
        }
        
        $params2 = [];
        if (!empty($filters['user_id'])) {
            $params2[] = $filters['user_id'];
        }
        if (!empty($filters['date_from'])) {
            $params2[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $params2[] = $filters['date_to'];
        }
        
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute($params2);
        $count2 = $stmt2->fetchColumn();
        
        return $count1 + $count2;
    }

    public function getModules() {
        $modules = [];
        
        $stmt1 = $this->db->query("SELECT DISTINCT module FROM {$this->table}");
        $modules1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);
        
        $modules = array_merge($modules1, ['stock']);
        $modules = array_unique($modules);
        sort($modules);
        
        return $modules;
    }

    public function getActions() {
        $actions = [];
        
        $stmt1 = $this->db->query("SELECT DISTINCT action FROM {$this->table}");
        $actions1 = $stmt1->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt2 = $this->db->query("SELECT DISTINCT change_type as action FROM stock_logs");
        $actions2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        
        $actions = array_merge($actions1, $actions2);
        $actions = array_unique($actions);
        sort($actions);
        
        return $actions;
    }

    public function getRecent($limit = 10) {
        return $this->getAll($limit);
    }

    public function clearOld($days = 30) {
        // Clear activity_logs
        $sql1 = "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute([$days]);
        
        // Clear old stock_logs (keep 90 days)
        $sql2 = "DELETE FROM stock_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)";
        $this->db->exec($sql2);
        
        return true;
    }
}
