<?php
class SaleReturnController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO sale_returns (sale_id, return_date, product_id, quantity, amount, reason, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['sale_id'],
                $_POST['return_date'] ?? date('Y-m-d'),
                $_POST['product_id'],
                $_POST['quantity'],
                $_POST['amount'],
                $_POST['reason'] ?? '',
                $_SESSION['user_id']
            ]);
            
            $updateStock = $this->db->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?");
            $updateStock->execute([$_POST['quantity'], $_POST['product_id']]);
            
            $this->success('Sale return recorded!');
            $this->redirect('sale/return');
        }
        
        $sales = $this->db->query("SELECT s.*, c.customer_name FROM sales s LEFT JOIN customers c ON s.customer_id = c.id ORDER BY s.sale_date DESC")->fetchAll();
        $products = $this->db->query("SELECT * FROM products")->fetchAll();
        
        $this->view('sale/return', [
            'sales' => $sales,
            'products' => $products
        ]);
    }
}