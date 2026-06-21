<?php
class ExpenseController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category_id'] ?? '';
        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        
        $sql = "SELECT e.*, ec.category_name, u.full_name 
                FROM expenses e 
                LEFT JOIN expense_categories ec ON e.category_id = ec.id 
                LEFT JOIN users u ON e.created_by = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (e.description LIKE ? OR e.reference_no LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($category_id) {
            $sql .= " AND e.category_id = ?";
            $params[] = $category_id;
        }
        if ($date_from) {
            $sql .= " AND e.expense_date >= ?";
            $params[] = $date_from;
        }
        if ($date_to) {
            $sql .= " AND e.expense_date <= ?";
            $params[] = $date_to;
        }
        
        $sql .= " ORDER BY e.expense_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $expenses = $stmt->fetchAll();
        
        $categories = $this->db->query("SELECT * FROM expense_categories WHERE is_active = 'yes'")->fetchAll();
        
        if (empty($categories)) {
            $this->db->exec("INSERT INTO expense_categories (category_name) VALUES ('Rent'), ('Utilities'), ('Salary'), ('Supplies'), ('Transportation'), ('Marketing'), ('Maintenance'), ('Miscellaneous')");
            $categories = $this->db->query("SELECT * FROM expense_categories WHERE is_active = 'yes'")->fetchAll();
        }
        
        $this->view('expense/index', [
            'expenses' => $expenses,
            'categories' => $categories
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO expenses (expense_date, category_id, amount, description, reference_no, created_by)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['expense_date'] ?? date('Y-m-d'),
                $_POST['category_id'],
                $_POST['amount'],
                $_POST['description'] ?? '',
                $_POST['reference_no'] ?? '',
                $_SESSION['user_id']
            ]);
            
            $this->success('Expense added successfully!');
            $this->redirect('expense/index');
        }
        
        $categories = $this->db->query("SELECT * FROM expense_categories WHERE is_active = 'yes'")->fetchAll();
        
        if (empty($categories)) {
            $this->db->exec("INSERT INTO expense_categories (category_name) VALUES ('Rent'), ('Utilities'), ('Salary'), ('Supplies'), ('Transportation'), ('Marketing'), ('Maintenance'), ('Miscellaneous')");
            $categories = $this->db->query("SELECT * FROM expense_categories WHERE is_active = 'yes'")->fetchAll();
        }
        
        $this->view('expense/create', [
            'categories' => $categories
        ]);
    }
}