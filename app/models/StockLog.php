<?php
class StockLog {
    private $db;
    private $table = 'stock_logs';

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (product_id, previous_quantity, new_quantity, change_type, reference_type, reference_id, notes, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['product_id'],
            $data['previous_quantity'],
            $data['new_quantity'],
            $data['change_type'],
            $data['reference_type'] ?? null,
            $data['reference_id'] ?? null,
            $data['notes'] ?? null,
            $data['user_id']
        ]);
        return $this->db->lastInsertId();
    }

    public function getByProduct($productId) {
        $sql = "SELECT sl.*, u.username, u.full_name 
                FROM {$this->table} sl 
                LEFT JOIN users u ON sl.user_id = u.id 
                WHERE sl.product_id = ? 
                ORDER BY sl.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAll($limit = 100) {
        $sql = "SELECT sl.*, u.username, u.full_name, p.product_name 
                FROM {$this->table} sl 
                LEFT JOIN users u ON sl.user_id = u.id 
                LEFT JOIN products p ON sl.product_id = p.id 
                ORDER BY sl.created_at DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
