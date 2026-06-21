<?php
class Payment {
    private $db;
    private $table = 'payments';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getBySaleId($saleId) {
        $sql = "SELECT * FROM {$this->table} WHERE sale_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saleId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (sale_id, payment_method, amount, transaction_id, notes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['sale_id'],
            $data['payment_method'],
            $data['amount'],
            $data['transaction_id'] ?? null,
            $data['notes'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function delete($saleId) {
        $sql = "DELETE FROM {$this->table} WHERE sale_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$saleId]);
    }
}
