<?php
class Customer {
    private $db;
    private $table = 'customers';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($status = null) {
        if ($status) {
            $sql = "SELECT * FROM {$this->table} WHERE status = ? ORDER BY customer_name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $sql = "SELECT * FROM {$this->table} ORDER BY customer_name ASC";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }

    public function getActive() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY customer_name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByPhone($phone) {
        $sql = "SELECT * FROM {$this->table} WHERE phone = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public function search($term) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE customer_name LIKE ? OR phone LIKE ? OR address LIKE ?
                ORDER BY customer_name ASC";
        $stmt = $this->db->prepare($sql);
        $term = "%{$term}%";
        $stmt->execute([$term, $term, $term]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        // Check for duplicate phone
        if (!empty($data['phone'])) {
            $existing = $this->getByPhone($data['phone']);
            if ($existing) {
                return $existing['id'];
            }
        }
        
        $sql = "INSERT INTO {$this->table} (customer_name, phone, customer_type, email, address, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['customer_name'],
            $data['phone'] ?? null,
            $data['customer_type'] ?? 'Retailer',
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['status'] ?? 'active'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        $allowed = ['customer_name', 'phone', 'email', 'address', 'status', 'total_purchases', 'loyalty_points'];
        
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

    public function updatePurchaseStats($customerId, $amount) {
        $sql = "UPDATE {$this->table} 
                SET total_purchases = total_purchases + ?, 
                    loyalty_points = loyalty_points + ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $points = floor($amount);
        $stmt->execute([$amount, $points, $customerId]);
        return true;
    }

    // Add payment to customer's due
    public function addPayment($customerId, $amount, $method = 'cash', $transactionId = null, $notes = null) {
        // Update customer paid_amount
        $sql = "UPDATE {$this->table} 
                SET paid_amount = paid_amount + ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$amount, $customerId]);
        
        // Save payment history
        $userId = $_SESSION['user_id'] ?? 1;
        $historySql = "INSERT INTO payment_history (customer_id, amount, payment_method, transaction_id, notes, user_id) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $historyStmt = $this->db->prepare($historySql);
        return $historyStmt->execute([$customerId, $amount, $method, $transactionId, $notes, $userId]);
    }
    
    // Get payment history for a customer
    public function getPaymentHistory($customerId, $limit = 50) {
        $sql = "SELECT ph.*, u.full_name as user_name 
                FROM payment_history ph 
                LEFT JOIN users u ON ph.user_id = u.id 
                WHERE ph.customer_id = ? 
                ORDER BY ph.created_at DESC 
                LIMIT " . intval($limit);
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    // Update total amount (when new sale is made on credit)
    public function addToTotal($customerId, $amount) {
        $sql = "UPDATE {$this->table} 
                SET total_amount = total_amount + ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$amount, $customerId]);
    }

    // Calculate due amount
    public function getDueAmount($customerId) {
        $customer = $this->getById($customerId);
        if ($customer) {
            return $customer['total_amount'] - $customer['paid_amount'];
        }
        return 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getTotalCustomers() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    // Get total due from all customers (sum of total_amount - paid_amount)
    public function getTotalDue() {
        $sql = "SELECT SUM(total_amount - paid_amount) as total_due FROM {$this->table} WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return floatval($result['total_due'] ?? 0);
    }

    // Get this month's due from sales
    public function getThisMonthDue() {
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
        $sql = "SELECT SUM(s.total_amount - s.paid_amount) as month_due 
                FROM sales s 
                WHERE s.status = 'completed' 
                AND s.customer_id IS NOT NULL 
                AND s.sale_date BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$firstDay, $lastDay]);
        $result = $stmt->fetch();
        return floatval($result['month_due'] ?? 0);
    }

    // Get or create Walk-in Customer
    public function getWalkinCustomer() {
        $walkin = $this->getByPhone('WALKIN');
        if (!$walkin) {
            $data = [
                'customer_name' => 'Walk-in Customer',
                'phone' => 'WALKIN',
                'email' => '',
                'address' => 'Local/Walk-in Sales',
                'customer_type' => 'Retailer',
                'status' => 'active'
            ];
            $id = $this->create($data);
            return $this->getById($id);
        }
        return $walkin;
    }
}
