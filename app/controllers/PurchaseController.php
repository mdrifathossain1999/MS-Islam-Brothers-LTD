<?php
class PurchaseController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $stmt = $this->db->query("
            SELECT p.*, s.supplier_name 
            FROM purchases p 
            LEFT JOIN suppliers s ON p.supplier_id = s.id 
            ORDER BY p.purchase_date DESC
        ");
        $purchases = $stmt->fetchAll();

        $suppliers = $this->db->query("SELECT * FROM suppliers WHERE status = 'active'")->fetchAll();

        $this->view('purchase/index', [
            'purchases' => $purchases,
            'suppliers' => $suppliers
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $purchase_date = $_POST['purchase_date'] ?? date('Y-m-d');
            $supplier_id = $_POST['supplier_id'] ?? null;
            $total_amount = $_POST['total_amount'] ?? 0;
            $paid_amount = $_POST['paid_amount'] ?? 0;
            $notes = $_POST['notes'] ?? '';
            
            $invoice_no = 'PUR-' . date('Ymd') . rand(1000, 9999);
            $due_amount = $total_amount - $paid_amount;
            
            $stmt = $this->db->prepare("
                INSERT INTO purchases (purchase_date, invoice_no, supplier_id, total_amount, paid_amount, due_amount, status, notes, created_by)
                VALUES (?, ?, ?, ?, ?, ?, 'received', ?, ?)
            ");
            $stmt->execute([$purchase_date, $invoice_no, $supplier_id, $total_amount, $paid_amount, $due_amount, $notes, $_SESSION['user_id']]);
            
            $this->success('Purchase created successfully!');
            $this->redirect('purchase/index');
        }
        
        $suppliers = $this->db->query("SELECT * FROM suppliers WHERE status = 'active'")->fetchAll();
        $products = $this->db->query("SELECT * FROM products WHERE status = 'active'")->fetchAll();
        
        $this->view('purchase/create', [
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function returnPurchase() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO purchase_returns (purchase_id, return_date, product_id, quantity, amount, reason, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['purchase_id'],
                $_POST['return_date'] ?? date('Y-m-d'),
                $_POST['product_id'],
                $_POST['quantity'],
                $_POST['amount'],
                $_POST['reason'] ?? '',
                $_SESSION['user_id']
            ]);
            
            $updateStock = $this->db->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $updateStock->execute([$_POST['quantity'], $_POST['product_id']]);
            
            $this->success('Purchase return recorded!');
            $this->redirect('purchase/return');
        }
        
        $purchases = $this->db->query("SELECT * FROM purchases ORDER BY purchase_date DESC")->fetchAll();
        $products = $this->db->query("SELECT * FROM products")->fetchAll();
        
        $this->view('purchase/return', [
            'purchases' => $purchases,
            'products' => $products
        ]);
    }
}