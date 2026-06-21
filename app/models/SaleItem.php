<?php
class SaleItem {
    private $db;
    private $table = 'sale_items';

    public function __construct($db = null) {
        if ($db === null) {
            $database = new Database();
            $this->db = $database->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function getBySaleId($saleId) {
        if (!$this->db) {
            return [];
        }
        $sql = "SELECT si.*, p.product_name, p.barcode 
                FROM {$this->table} si 
                LEFT JOIN products p ON si.product_id = p.id 
                WHERE si.sale_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saleId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        // Verify product exists
        $checkSql = "SELECT id FROM products WHERE id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([$data['product_id']]);
        if (!$checkStmt->fetch()) {
            return false; // Product doesn't exist, skip this item
        }
        
        $sql = "INSERT INTO {$this->table} (
                    sale_id, product_id, barcode, product_name, quantity, unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['sale_id'],
            $data['product_id'],
            $data['barcode'] ?? null,
            $data['product_name'],
            $data['quantity'],
            $data['unit_price'],
            $data['total_price']
        ]);
        return $this->db->lastInsertId();
    }

    public function createMultiple($saleId, $items) {
        foreach ($items as $item) {
            $this->create([
                'sale_id' => $saleId,
                'product_id' => $item['product_id'],
                'barcode' => $item['barcode'] ?? null,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price']
            ]);
        }
        return true;
    }

    public function delete($saleId) {
        $sql = "DELETE FROM {$this->table} WHERE sale_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$saleId]);
    }

    public function getProductSalesReport($startDate, $endDate) {
        $sql = "SELECT si.product_id, si.product_name, si.barcode,
                       SUM(si.quantity) as total_quantity, 
                       SUM(si.total_price) as total_sales
                FROM {$this->table} si
                JOIN sales s ON si.sale_id = s.id
                WHERE s.sale_date BETWEEN ? AND ? AND s.status = 'completed'
                GROUP BY si.product_id
                ORDER BY total_sales DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
}
