<?php
class CustomerController extends Controller {
    public function index() {
        $this->requireLogin();

        $customerModel = $this->model('Customer');
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $customers = $customerModel->search($search);
        } else {
            $customers = $customerModel->getAll();
        }

        // Calculate totals
        $totalCustomers = count($customers);
        $totalDue = 0;
        $totalPaid = 0;
        
        foreach ($customers as $customer) {
            $totalDue += floatval($customer['total_amount']) - floatval($customer['paid_amount']);
            $totalPaid += floatval($customer['paid_amount']);
        }
        
        $totalAmount = $totalDue + $totalPaid;

        $this->view('customers/index', [
            'customers' => $customers,
            'total_customers' => $totalCustomers,
            'total_due' => $totalDue,
            'total_paid' => $totalPaid,
            'total_amount' => $totalAmount
        ]);
    }

    public function create() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerModel = $this->model('Customer');
            
            $data = [
                'customer_name' => $_POST['customer_name'],
                'phone' => $_POST['phone'] ?? null,
                'email' => $_POST['email'] ?? null,
                'address' => $_POST['address'] ?? null,
                'customer_type' => $_POST['customer_type'] ?? 'Retailer',
                'status' => $_POST['status'] ?? 'active'
            ];

            $customerId = $customerModel->create($data);
            
            if ($customerId) {
                $this->logActivity('create', 'customer', 'Created customer: ' . $_POST['customer_name'], 'customer', $customerId);
                echo json_encode(['success' => true, 'message' => 'Customer added successfully', 'customer_id' => $customerId]);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error adding customer']);
                exit();
            }
            exit();
        }

        $this->view('customers/create');
    }

    public function edit($id = null) {
        $this->requireLogin();

        if (!$id) {
            $this->redirect('customer/index');
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);

        if (!$customer) {
            die('Customer not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'customer_name' => $_POST['customer_name'],
                'phone' => $_POST['phone'] ?? null,
                'email' => $_POST['email'] ?? null,
                'address' => $_POST['address'] ?? null,
                'status' => $_POST['status'] ?? 'active'
            ];

            $customerModel->update($id, $data);
            $this->logActivity('update', 'customer', 'Updated customer: ' . $_POST['customer_name'], 'customer', $id);
            $this->success('Customer updated successfully!');
            $this->redirect('customer/index');
        }

        $this->view('customers/edit', ['customer' => $customer]);
    }

    public function delete($id = null) {
        $this->requireAdmin();

        if ($id) {
            $customerModel = $this->model('Customer');
            $customer = $customerModel->getById($id);
            $customerModel->delete($id);
            $this->logActivity('delete', 'customer', 'Deleted customer: ' . ($customer['customer_name'] ?? 'ID: ' . $id), 'customer', $id);
            $this->success('Customer deleted successfully!');
        }

        $this->redirect('customer/index');
    }

    public function search() {
        header('Content-Type: application/json');
        
        $term = $_GET['term'] ?? '';
        $customerModel = $this->model('Customer');
        $customers = $customerModel->search($term);
        
        echo json_encode($customers);
        exit();
    }

    public function history($id = null) {
        $this->requireLogin();

        if (!$id) {
            $this->redirect('customer/index');
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);

        if (!$customer) {
            die('Customer not found');
        }

        $sql = "SELECT s.*, u.full_name as cashier_name 
                FROM sales s 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.customer_id = ? 
                ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $sales = $stmt->fetchAll();

        $this->view('customers/history', ['customer' => $customer, 'sales' => $sales]);
    }

    // Handle payment receive from customer
    public function payment($id = null) {
        $this->requireLogin();

        if (!$id) {
            $this->redirect('customer/index');
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);

        if (!$customer) {
            die('Customer not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment_amount = floatval($_POST['payment_amount']);
            
            if ($payment_amount > 0) {
                $customerModel->addPayment($id, $payment_amount);
                $_SESSION['success_message'] = 'Payment received successfully!';
            }
            
            $this->redirect('customer/edit/' . $id);
        }

        $this->redirect('customer/edit/' . $id);
    }

    // Quick add customer from POS
    public function quickAdd() {
        header('Content-Type: application/json');
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerModel = $this->model('Customer');
            
            $rawInput = file_get_contents('php://input');
            $postData = json_decode($rawInput, true);
            
            // Debug - log received data
            error_log('quickAdd received: ' . $rawInput);
            
            $name = $postData['customer_name'] ?? '';
            $phone = $postData['phone'] ?? '';
            $email = $postData['email'] ?? '';
            $address = $postData['address'] ?? '';
            $type = $postData['customer_type'] ?? 'Retailer';
            
            // Debug - log parsed values
            error_log("name=$name, phone=$phone");
            
            if (empty($name) || empty($phone)) {
                echo json_encode(['success' => false, 'message' => 'Name and phone are required', 'debug' => ['name' => $name, 'phone' => $phone]]);
                exit();
            }
            
            // Check if phone already exists
            $existing = $customerModel->getByPhone($phone);
            if ($existing) {
                echo json_encode([
                    'success' => true, 
                    'customer_id' => $existing['id'], 
                    'customer_name' => $existing['customer_name'],
                    'message' => 'Customer already exists with this phone number!',
                    'existing' => true
                ]);
                exit();
            }
            
            $data = [
                'customer_name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'customer_type' => $type,
                'status' => 'active'
            ];
            
            $customerId = $customerModel->create($data);
            
            if ($customerId) {
                $this->logActivity('create', 'customer', 'Quick added customer: ' . $name, 'customer', $customerId);
                echo json_encode(['success' => true, 'customer_id' => $customerId, 'customer_name' => $name, 'message' => 'Customer added successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error creating customer']);
            }
            exit();
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }

    // Get customer history data for POS
    public function historyData($id = null) {
        header('Content-Type: application/json');
        $this->requireLogin();
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Customer ID required']);
            exit();
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            echo json_encode(['success' => false, 'message' => 'Customer not found']);
            exit();
        }

        // Get customer sales
        $sql = "SELECT s.*, u.full_name as cashier_name 
                FROM sales s 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.customer_id = ? AND s.status = 'completed'
                ORDER BY s.created_at DESC
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $sales = $stmt->fetchAll();

        // Calculate totals from customer table
        $totalPurchase = floatval($customer['total_purchases'] ?? 0);
        $totalPaid = floatval($customer['paid_amount'] ?? 0);
        $totalDue = floatval($customer['total_amount'] ?? 0) - $totalPaid;

        echo json_encode([
            'success' => true,
            'customer' => $customer,
            'sales' => $sales,
            'total_purchase' => $totalPurchase,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue
        ]);
        exit();
    }

    // Show customer profile page
    public function profile($id = null) {
        $this->requireLogin();
        
        if (!$id) {
            $this->redirect('customer/index');
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            die('Customer not found');
        }

        // Get customer sales
        $sql = "SELECT s.*, u.full_name as cashier_name 
                FROM sales s 
                LEFT JOIN users u ON s.user_id = u.id 
                WHERE s.customer_id = ? AND s.status = 'completed'
                ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $sales = $stmt->fetchAll();

        // Calculate total due
        $totalDue = floatval($customer['total_amount']) - floatval($customer['paid_amount']);
        
        // Get payment history
        $paymentHistory = $customerModel->getPaymentHistory($id);

        $this->view('customers/profile', [
            'customer' => $customer,
            'sales' => $sales,
            'total_due' => $totalDue,
            'payment_history' => $paymentHistory
        ]);
    }

    // Receive payment from customer
    public function receivePayment($id = null) {
        $this->requireLogin();
        
        if (!$id) {
            $this->redirect('customer/index');
        }

        $customerModel = $this->model('Customer');
        $customer = $customerModel->getById($id);
        
        if (!$customer) {
            die('Customer not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = floatval($_POST['amount'] ?? 0);
            $method = $_POST['payment_method'] ?? 'cash';
            $notes = $_POST['notes'] ?? null;
            
            if ($amount > 0) {
                $customerModel->addPayment($id, $amount, $method, null, $notes);
                $this->logActivity('payment', 'customer', 'Received payment ৳' . number_format($amount, 2) . ' from ' . $customer['customer_name'], 'customer', $id);
                $this->success('Payment of ৳' . number_format($amount, 2) . ' received successfully!');
            }
            
            $this->redirect('customer/profile/' . $id);
        }

        $totalDue = floatval($customer['total_amount']) - floatval($customer['paid_amount']);
        
        $this->view('customers/receive_payment', [
            'customer' => $customer,
            'total_due' => $totalDue
        ]);
    }
}
