<?php
class Sale {
    private $db;
    private $table = 'sales';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($limit = 50) {
        $limit = intval($limit);
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                ORDER BY s.created_at DESC 
                LIMIT $limit";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByInvoice($invoiceNumber) {
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.invoice_number = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$invoiceNumber]);
        return $stmt->fetch();
    }

    public function getByDate($date) {
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.sale_date = ? 
                ORDER BY s.sale_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function getByDateRange($startDate, $endDate) {
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.sale_date BETWEEN ? AND ? 
                ORDER BY s.sale_date DESC, s.sale_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getTodaySales() {
        $today = date('Y-m-d');
        $sql = "SELECT s.*, c.customer_name, u.full_name as cashier_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.sale_date = ? AND s.status = 'completed'
                ORDER BY s.sale_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$today]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (
                    invoice_number, customer_id, user_id, subtotal, discount_amount, 
                    tax_amount, total_amount, payment_method, mobile_type, paid_amount, change_amount,
                    sale_date, sale_time, status, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['invoice_number'],
            $data['customer_id'] ?? null,
            $data['user_id'],
            $data['subtotal'],
            $data['discount_amount'] ?? 0,
            $data['tax_amount'] ?? 0,
            $data['total_amount'],
            $data['payment_method'] ?? 'cash',
            $data['mobile_type'] ?? null,
            $data['paid_amount'],
            $data['change_amount'] ?? 0,
            $data['sale_date'],
            $data['sale_time'],
            $data['status'] ?? 'completed',
            $data['notes'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['customer_id', 'subtotal', 'discount_amount', 'tax_amount', 'total_amount', 'payment_method', 'paid_amount', 'change_amount', 'status', 'notes'];
        
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

    public function cancel($id) {
        $sql = "UPDATE {$this->table} SET status = 'cancelled' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function generateInvoiceNumber() {
        $date = date('Ymd');
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE invoice_number LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["INV-$date-%"]);
        $result = $stmt->fetch();
        $count = $result['count'] + 1;
        return "INV-$date-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getTodayTotal() {
        $today = date('Y-m-d');
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table} WHERE sale_date = ? AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$today]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getMonthlyTotal($year, $month) {
        $sql = "SELECT SUM(total_amount) as total FROM {$this->table} 
                WHERE YEAR(sale_date) = ? AND MONTH(sale_date) = ? AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year, $month]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getTotalSales() {
        $sql = "SELECT SUM(total_amount) as total, COUNT(*) as count FROM {$this->table} WHERE status = 'completed'";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    public function getDailySalesData($days = 30) {
        $sql = "SELECT sale_date, SUM(total_amount) as total, SUM(paid_amount) as paid 
                FROM {$this->table} 
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) AND status = 'completed'
                GROUP BY sale_date 
                ORDER BY sale_date ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    public function getHourlySalesData() {
        $today = date('Y-m-d');
        $sql = "SELECT 
                    HOUR(sale_time) as hour,
                    SUM(total_amount) as total,
                    SUM(paid_amount) as paid,
                    SUM(total_amount - paid_amount) as due
                FROM {$this->table} 
                WHERE sale_date = ? AND status = 'completed'
                GROUP BY HOUR(sale_time)
                ORDER BY hour ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$today]);
        return $stmt->fetchAll();
    }

    public function getRecentSales($limit = 10) {
        $sql = "SELECT s.*, c.customer_name 
                FROM {$this->table} s 
                LEFT JOIN customers c ON s.customer_id = c.id 
                WHERE s.status = 'completed' 
                ORDER BY s.created_at DESC 
                LIMIT " . intval($limit);
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
