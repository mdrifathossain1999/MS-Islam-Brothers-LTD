<?php
class HeldSale {
    private $db;
    private $table = 'held_sales';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $sql = "SELECT h.*, c.customer_name, u.full_name as held_by 
                FROM {$this->table} h 
                LEFT JOIN customers c ON h.customer_id = c.id 
                LEFT JOIN users u ON h.user_id = u.id 
                ORDER BY h.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT h.*, c.customer_name, u.full_name as held_by 
                FROM {$this->table} h 
                LEFT JOIN customers c ON h.customer_id = c.id 
                LEFT JOIN users u ON h.user_id = u.id 
                WHERE h.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (
                    hold_name, customer_id, user_id, items_json, 
                    subtotal, vat_percent, vat_amount, discount, total,
                    sale_date, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['hold_name'] ?? 'Hold ' . date('H:i:s'),
            $data['customer_id'] ?? null,
            $data['user_id'],
            $data['items_json'],
            $data['subtotal'],
            $data['vat_percent'] ?? 0,
            $data['vat_amount'] ?? 0,
            $data['discount'] ?? 0,
            $data['total'],
            $data['sale_date'] ?? date('Y-m-d')
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['hold_name', 'customer_id', 'items_json', 'subtotal', 'vat_percent', 'vat_amount', 'discount', 'total'];
        
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

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteAll() {
        $sql = "DELETE FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->execute();
    }

    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}
