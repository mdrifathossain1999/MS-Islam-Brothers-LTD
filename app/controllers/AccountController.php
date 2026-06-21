<?php
class AccountController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $today = date('Y-m-d');
        $totalCash = 0;
        $todayIncome = 0;
        $todayExpense = 0;
        $todaySales = 0;

        $salesStmt = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE DATE(sale_date) = '$today' AND status != 'cancelled'");
        $todaySalesData = $salesStmt->fetch();
        $todaySales = floatval($todaySalesData['total']);

        $paidStmt = $this->db->query("SELECT COALESCE(SUM(paid_amount), 0) as total FROM sales WHERE DATE(sale_date) = '$today' AND status != 'cancelled'");
        $todayPaidData = $paidStmt->fetch();
        $todayIncome = floatval($todayPaidData['total']);

        $expenseStmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE DATE(expense_date) = '$today'");
        $todayExpenseData = $expenseStmt->fetch();
        $todayExpense = floatval($todayExpenseData['total']);

        $accounts = $this->db->query("SELECT * FROM accounts WHERE is_active = 'yes' ORDER BY account_name")->fetchAll();

        $txnStmt = $this->db->query("
            SELECT t.*, a.account_name 
            FROM transactions t 
            LEFT JOIN accounts a ON t.account_id = a.id 
            ORDER BY t.transaction_date DESC, t.id DESC LIMIT 20
        ");
        $transactions = $txnStmt->fetchAll();

        $totalCash = $todayIncome - $todayExpense;

        $this->view('account/index', [
            'totalCash' => $totalCash,
            'todayIncome' => $todayIncome,
            'todayExpense' => $todayExpense,
            'todaySales' => $todaySales,
            'accounts' => $accounts,
            'transactions' => $transactions
        ]);
    }

    public function edit($id = null) {
        if (!$id) {
            $this->error('Invalid account!');
            $this->redirect('account/index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                UPDATE accounts SET account_name = ?, account_number = ?, account_type = ?, is_default = ? WHERE id = ?
            ");
            $stmt->execute([
                $_POST['account_name'],
                $_POST['account_number'] ?? '',
                $_POST['account_type'] ?? 'cash',
                $_POST['is_default'] ?? 'no',
                $id
            ]);
            
            $this->success('Account updated successfully!');
            $this->redirect('account/index');
        }

        $account = $this->db->query("SELECT * FROM accounts WHERE id = $id")->fetch();
        if (!$account) {
            $this->error('Account not found!');
            $this->redirect('account/index');
        }

        $this->view('account/edit', ['account' => $account]);
    }

    public function delete($id = null) {
        if (!$id) {
            $this->error('Invalid account!');
            $this->redirect('account/index');
        }

        $stmt = $this->db->prepare("UPDATE accounts SET is_active = 'no' WHERE id = ?");
        $stmt->execute([$id]);

        $this->success('Account deleted successfully!');
        $this->redirect('account/index');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO accounts (account_name, account_number, account_type, opening_balance, current_balance, is_default)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['account_name'],
                $_POST['account_number'] ?? '',
                $_POST['account_type'] ?? 'cash',
                $_POST['opening_balance'] ?? 0,
                $_POST['opening_balance'] ?? 0,
                $_POST['is_default'] ?? 'no'
            ]);
            
            $this->success('Account created successfully!');
            $this->redirect('account/index');
        }

        $this->view('account/create');
    }
    
    public function deposit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO transactions (account_id, type, amount, description, transaction_date, created_by)
                VALUES (?, 'deposit', ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['account_id'],
                $_POST['amount'],
                $_POST['description'] ?? 'Deposit',
                $_POST['transaction_date'] ?? date('Y-m-d'),
                $_SESSION['user_id']
            ]);
            
            // Update account balance
            $update = $this->db->prepare("UPDATE accounts SET current_balance = current_balance + ? WHERE id = ?");
            $update->execute([$_POST['amount'], $_POST['account_id']]);
            
            $this->success('Deposit successful!');
            $this->redirect('account/index');
        }
        
        $accounts = $this->db->query("SELECT * FROM accounts WHERE is_active = 'yes' ORDER BY account_name")->fetchAll();
        $this->view('account/deposit', ['accounts' => $accounts]);
    }
    
    public function withdraw() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO transactions (account_id, type, amount, description, transaction_date, created_by)
                VALUES (?, 'withdraw', ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['account_id'],
                $_POST['amount'],
                $_POST['description'] ?? 'Withdraw',
                $_POST['transaction_date'] ?? date('Y-m-d'),
                $_SESSION['user_id']
            ]);
            
            // Update account balance
            $update = $this->db->prepare("UPDATE accounts SET current_balance = current_balance - ? WHERE id = ?");
            $update->execute([$_POST['amount'], $_POST['account_id']]);
            
            $this->success('Withdraw successful!');
            $this->redirect('account/index');
        }
        
        $accounts = $this->db->query("SELECT * FROM accounts WHERE is_active = 'yes' ORDER BY account_name")->fetchAll();
        $this->view('account/withdraw', ['accounts' => $accounts]);
    }
}