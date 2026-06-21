<?php
class Invoice {
    private $db;
    private $table = 'invoices';
    private $items_table = 'invoice_items';
    private $payments_table = 'invoice_payments';

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $database = new Database();
            $this->db = $database->getConnection();
        }
    }

    public function getAll($limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT i.*, u.full_name as cashier_name, c.customer_name as customer
            FROM {$this->table} i
            LEFT JOIN users u ON i.user_id = u.id
            LEFT JOIN customers c ON i.customer_id = c.id
            ORDER BY i.id DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT i.*, u.full_name as cashier_name
            FROM {$this->table} i
            LEFT JOIN users u ON i.user_id = u.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByInvoiceNumber($invoiceNumber) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE invoice_number = ?");
        $stmt->execute([$invoiceNumber]);
        return $stmt->fetch();
    }

    public function getByCustomer($customerId) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE customer_id = ? 
            ORDER BY id DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public function getByDateRange($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE invoice_date BETWEEN ? AND ?
            ORDER BY invoice_date DESC, id DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getTodayInvoices() {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE invoice_date = ?
            ORDER BY id DESC
        ");
        $stmt->execute([$today]);
        return $stmt->fetchAll();
    }

    public function getDueInvoices($days = 0) {
        $today = date('Y-m-d');
        if ($days > 0) {
            $date = date('Y-m-d', strtotime("+$days days"));
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE due_date <= ? AND payment_status IN ('unpaid','partial')
                ORDER BY due_date ASC
            ");
            $stmt->execute([$date]);
        } else {
            $stmt = $this->db->prepare("
                SELECT * FROM {$this->table} 
                WHERE due_date < ? AND payment_status IN ('unpaid','partial')
                ORDER BY due_date ASC
            ");
            $stmt->execute([$today]);
        }
        return $stmt->fetchAll();
    }

    public function getUnpaidInvoices() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE payment_status IN ('unpaid','partial')
            ORDER BY due_date ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $invoiceNumber = $this->generateInvoiceNumber($data['invoice_prefix'] ?? 'INV');
        
        $sql = "INSERT INTO {$this->table} (
            invoice_number, invoice_prefix, invoice_date, due_date, customer_id,
            customer_name, customer_phone, customer_address, sales_id, user_id,
            cashier_name, subtotal, discount_amount, tax_amount, shipping_cost, total_amount,
            paid_amount, due_amount, change_amount, payment_status, payment_method,
            mobile_type, transaction_id, invoice_type, status, notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $invoiceNumber, $data['invoice_prefix'] ?? 'INV', $data['invoice_date'], $data['due_date'],
            $data['customer_id'], $data['customer_name'], $data['customer_phone'], $data['customer_address'],
            $data['sales_id'], $data['user_id'], $data['cashier_name'], $data['subtotal'],
            $data['discount_amount'] ?? 0, $data['tax_amount'] ?? 0, $data['shipping_cost'] ?? 0,
            $data['total_amount'], $data['paid_amount'], $data['due_amount'], $data['change_amount'],
            $data['payment_status'], $data['payment_method'], $data['mobile_type'],
            $data['transaction_id'], $data['invoice_type'], $data['status'], $data['notes']
        ]);
        
        $invoiceId = $this->db->lastInsertId();
        $this->updateInvoiceNumber($data['invoice_prefix'] ?? 'INV');
        return $invoiceId;
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function updatePaymentStatus($id, $paidAmount, $dueAmount, $status) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET paid_amount = ?, due_amount = ?, payment_status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$paidAmount, $dueAmount, $status, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = 'void' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addItem($invoiceId, $item) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->items_table} (
                invoice_id, product_id, barcode, item_name, quantity, unit, unit_price, total_price
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $invoiceId, $item['product_id'], $item['barcode'], $item['item_name'],
            $item['quantity'], $item['unit'], $item['unit_price'], $item['total_price']
        ]);
    }

    public function getItems($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->items_table} WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    public function addPayment($paymentData) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->payments_table} (
                invoice_id, payment_date, payment_time, amount, payment_method,
                mobile_type, transaction_id, notes, received_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $paymentData['invoice_id'], $paymentData['payment_date'], $paymentData['payment_time'],
            $paymentData['amount'], $paymentData['payment_method'], $paymentData['mobile_type'],
            $paymentData['transaction_id'], $paymentData['notes'], $paymentData['received_by']
        ]);
        
        if ($result) {
            $this->updateInvoicePayments($paymentData['invoice_id']);
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getPayments($invoiceId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.full_name as received_by_name
            FROM {$this->payments_table} p
            LEFT JOIN users u ON p.received_by = u.id
            WHERE p.invoice_id = ? AND p.is_deleted = 'no'
            ORDER BY p.payment_date DESC, p.id DESC
        ");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    private function updateInvoicePayments($invoiceId) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) as total_paid 
            FROM {$this->payments_table} 
            WHERE invoice_id = ? AND is_deleted = 'no'
        ");
        $stmt->execute([$invoiceId]);
        $result = $stmt->fetch();
        
        $invoice = $this->getById($invoiceId);
        $paidAmount = floatval($result['total_paid']);
        $totalAmount = floatval($invoice['total_amount']);
        $dueAmount = max(0, $totalAmount - $paidAmount);
        
        if ($dueAmount <= 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }
        
        $this->updatePaymentStatus($invoiceId, $paidAmount, $dueAmount, $status);
    }

    public function getSummary() {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_invoices,
                SUM(total_amount) as total_amount,
                SUM(paid_amount) as total_paid,
                SUM(due_amount) as total_due
            FROM {$this->table}
            WHERE status != 'void'
        ");
        return $stmt->fetch();
    }

    public function getTodaySummary() {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_invoices,
                COALESCE(SUM(total_amount), 0) as total_amount,
                COALESCE(SUM(paid_amount), 0) as total_paid,
                COALESCE(SUM(due_amount), 0) as total_due
            FROM {$this->table}
            WHERE invoice_date = ? AND status != 'void'
        ");
        $stmt->execute([$today]);
        return $stmt->fetch();
    }

    public function search($keyword) {
        $keyword = "%{$keyword}%";
        $stmt = $this->db->prepare("
            SELECT i.*, c.customer_name
            FROM {$this->table} i
            LEFT JOIN customers c ON i.customer_id = c.id
            WHERE i.invoice_number LIKE ? OR i.customer_name LIKE ?
            ORDER BY i.id DESC
            LIMIT 50
        ");
        $stmt->execute([$keyword, $keyword]);
        return $stmt->fetchAll();
    }

    private function generateInvoiceNumber($prefix) {
        $year = date('Y');
        $stmt = $this->db->prepare("
            SELECT current_number, padding FROM invoice_numbering 
            WHERE prefix = ? AND fiscal_year = ? AND is_active = 'yes'
        ");
        $stmt->execute([$prefix, $year]);
        $result = $stmt->fetch();
        
        if ($result) {
            $number = str_pad($result['current_number'], $result['padding'], '0', STR_PAD_LEFT);
            return $prefix . '-' . $year . '-' . $number;
        }
        
        return $prefix . '-' . $year . '-' . str_pad(1, 4, '0', STR_PAD_LEFT);
    }

    private function updateInvoiceNumber($prefix) {
        $year = date('Y');
        $stmt = $this->db->prepare("
            UPDATE invoice_numbering 
            SET current_number = current_number + 1 
            WHERE prefix = ? AND fiscal_year = ?
        ");
        $stmt->execute([$prefix, $year]);
    }

    public function getTemplates() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates WHERE is_active = 'yes' ORDER BY is_default DESC, id ASC");
        return $stmt->fetchAll();
    }

    public function getDefaultTemplate() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates WHERE is_default = 'yes' AND is_active = 'yes' LIMIT 1");
        return $stmt->fetch();
    }

    public function ensureDefaultNumbering() {
        try {
            $check = $this->db->query("SELECT COUNT(*) as cnt FROM invoice_numbering");
            $result = $check->fetch();
            
            if ($result['cnt'] == 0) {
                $this->db->exec("INSERT INTO `invoice_numbering` (`prefix`, `starting_number`, `current_number`, `padding`, `fiscal_year`, `is_active`) VALUES ('INV', 1, 1, 4, '2026', 'yes'), ('RET', 1, 1, 4, '2026', 'yes')");
            }
        } catch (Exception $e) {
            // Table may not exist yet
        }
    }

    public function logActivity($invoiceId, $activityType, $description, $userId = null, $oldValue = null, $newValue = null) {
        $stmt = $this->db->prepare("
            INSERT INTO invoice_activities (invoice_id, user_id, activity_type, description, old_value, new_value, ip_address)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        return $stmt->execute([$invoiceId, $userId, $activityType, $description, $oldValue, $newValue, $ip]);
    }

    public function getActivities($invoiceId) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.full_name as user_name
            FROM invoice_activities a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.invoice_id = ?
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }
}