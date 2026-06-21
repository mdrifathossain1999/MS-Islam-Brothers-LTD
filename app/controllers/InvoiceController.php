<?php
class InvoiceController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    private function getDb() {
        $database = new Database();
        return $database->getConnection();
    }

    public function index() {
        $db = $this->getDb();
        $status = $_GET['status'] ?? '';
        
        if (!empty($status) && $status === 'completed') {
            $stmt = $db->prepare("
                SELECT s.*, c.customer_name, u.full_name as cashier_name
                FROM sales s
                LEFT JOIN customers c ON s.customer_id = c.id
                LEFT JOIN users u ON s.user_id = u.id
                WHERE s.status = 'completed'
                ORDER BY s.id DESC
                LIMIT 50
            ");
            $stmt->execute();
            $sales = $stmt->fetchAll();
        } else {
            $stmt = $db->query("
                SELECT s.*, c.customer_name, u.full_name as cashier_name
                FROM sales s
                LEFT JOIN customers c ON s.customer_id = c.id
                LEFT JOIN users u ON s.user_id = u.id
                ORDER BY s.id DESC
                LIMIT 50
            ");
            $sales = $stmt->fetchAll();
        }

        $this->view('invoices/index', ['sales' => $sales, 'status_filter' => $status]);
    }

    public function show($id = null) {
        if (!$id) {
            $this->redirect('invoice/index');
        }

        $db = $this->getDb();
        $stmt = $db->prepare("
            SELECT s.*, c.customer_name, c.phone as customer_phone, c.address as customer_address, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            die('Invoice not found');
        }

        $saleItemModel = $this->model('SaleItem');
        $items = $saleItemModel->getBySaleId($id);

        $this->view('invoices/view', ['sale' => $sale, 'items' => $items]);
    }

    public function print($id = null) {
        if (!$id) {
            $this->redirect('invoice/index');
        }

        $db = $this->getDb();
        $stmt = $db->prepare("
            SELECT s.*, c.customer_name, c.phone as customer_phone, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            die('Invoice not found');
        }

        $saleItemModel = $this->model('SaleItem');
        $items = $saleItemModel->getBySaleId($id);

        // Get all templates for selection dropdown
        $invoiceModel = $this->model('Invoice');
        $templates = $invoiceModel->getTemplates();
        $defaultTemplate = $invoiceModel->getDefaultTemplate();

        // Check for template ID in URL
        $templateId = $_GET['template'] ?? null;
        $selectedTemplate = null;
        
        if ($templateId) {
            foreach ($templates as $t) {
                if ($t['id'] == $templateId) {
                    $selectedTemplate = $t;
                    break;
                }
            }
        }
        
        // Use selected template or default
        if (!$selectedTemplate && $defaultTemplate) {
            $selectedTemplate = $defaultTemplate;
        } elseif (!$selectedTemplate && !empty($templates)) {
            $selectedTemplate = $templates[0];
        }

        $this->view('invoices/print', [
            'sale' => $sale, 
            'items' => $items, 
            'print' => isset($_GET['print']) && $_GET['print'] === 'true',
            'templates' => $templates,
            'selectedTemplate' => $selectedTemplate
        ]);
    }

    public function search() {
        $invoice = $_GET['invoice'] ?? '';
        
        if (empty($invoice)) {
            $this->redirect('invoice/index');
        }

        $db = $this->getDb();
        $stmt = $db->prepare("SELECT * FROM sales WHERE invoice_number = ?");
        $stmt->execute([$invoice]);
        $sale = $stmt->fetch();

        if (!$sale) {
            die('Invoice not found');
        }

        $saleItemModel = $this->model('SaleItem');
        $items = $saleItemModel->getBySaleId($sale['id']);

        $this->view('invoices/view', ['sale' => $sale, 'items' => $items]);
    }

    public function today() {
        $db = $this->getDb();
        $today = date('Y-m-d');
        
        $stmt = $db->prepare("
            SELECT s.*, c.customer_name, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.sale_date = ?
            ORDER BY s.id DESC
        ");
        $stmt->execute([$today]);
        $sales = $stmt->fetchAll();
        
        $todayTotal = 0;
        $todayPaid = 0;
        
        foreach ($sales as $sale) {
            $todayTotal += floatval($sale['total_amount']);
            $todayPaid += floatval($sale['paid_amount']);
        }
        $todayDue = $todayTotal - $todayPaid;

        $this->view('invoices/today', [
            'sales' => $sales, 
            'today_total' => $todayTotal,
            'today_paid' => $todayPaid,
            'today_due' => $todayDue
        ]);
    }

    public function dateRange() {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $db = $this->getDb();
        $stmt = $db->prepare("
            SELECT s.*, c.customer_name, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.sale_date BETWEEN ? AND ?
            ORDER BY s.sale_date DESC, s.id DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        $sales = $stmt->fetchAll();

        $this->view('invoices/date_range', ['sales' => $sales, 'start_date' => $startDate, 'end_date' => $endDate]);
    }

    public function due() {
        $db = $this->getDb();
        $today = date('Y-m-d');
        
        $stmt = $db->prepare("
            SELECT s.*, c.customer_name, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.sale_date < ? AND (s.total_amount - s.paid_amount) > 0
            ORDER BY s.sale_date ASC
        ");
        $stmt->execute([$today]);
        $sales = $stmt->fetchAll();

        $this->view('invoices/due', ['sales' => $sales, 'days' => 0]);
    }

    public function unpaid() {
        $db = $this->getDb();
        $stmt = $db->query("
            SELECT s.*, c.customer_name, u.full_name as cashier_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE (s.total_amount - s.paid_amount) > 0
            ORDER BY s.sale_date DESC
        ");
        $sales = $stmt->fetchAll();

        $this->view('invoices/unpaid', ['sales' => $sales]);
    }

    public function templates() {
        $db = $this->getDb();
        
        // Ensure table and default templates exist
        try {
            $db->exec("CREATE TABLE IF NOT EXISTS `invoice_templates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `template_name` varchar(100) NOT NULL,
                `template_type` enum('thermal','a4','a5','pos','custom') DEFAULT 'a4',
                `is_default` enum('yes','no') DEFAULT 'no',
                `header_text` text DEFAULT NULL,
                `footer_text` text DEFAULT NULL,
                `show_logo` enum('yes','no') DEFAULT 'yes',
                `show_barcode` enum('yes','no') DEFAULT 'yes',
                `show_qr_code` enum('yes','no') DEFAULT 'no',
                `show_terms` enum('yes','no') DEFAULT 'yes',
                `terms_content` text DEFAULT NULL,
                `show_customer_signature` enum('yes','no') DEFAULT 'no',
                `show_cashier_signature` enum('yes','no') DEFAULT 'no',
                `color_scheme` varchar(20) DEFAULT '#667eea',
                `font_size` enum('small','medium','large') DEFAULT 'medium',
                `is_active` enum('yes','no') DEFAULT 'yes',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            
            $check = $db->query("SELECT COUNT(*) as cnt FROM invoice_templates");
            $result = $check->fetch();
            if ($result['cnt'] == 0) {
                $db->exec("INSERT INTO `invoice_templates` (`template_name`, `template_type`, `is_default`, `header_text`, `footer_text`, `show_logo`, `show_barcode`, `show_qr_code`, `show_terms`, `terms_content`, `show_customer_signature`, `show_cashier_signature`, `color_scheme`, `font_size`, `is_active`) VALUES 
                    ('Default Thermal', 'thermal', 'yes', 'Sumon Enterprise Ltd\\n123 Business Street\\nContact: 01889-933541', 'Thank you for shopping with us!', 'yes', 'yes', 'no', 'yes', 'Goods once sold cannot be returned.', 'yes', 'yes', '#667eea', 'small', 'yes'),
                    ('Default A4', 'a4', 'no', 'Sumon Enterprise Ltd\\nYour Trusted Business Partner\\nAddress: As per records | Phone: 01889-933541', 'Payment is due within 30 days. Thank you for your business!', 'yes', 'yes', 'yes', 'yes', '1. Goods can be exchanged within 7 days.\\n2. Original receipt required.\\n3. Damage due to misuse not covered.', 'yes', 'yes', '#2c3e50', 'medium', 'yes')");
            }
        } catch (Exception $e) {
            // Table may not exist - ignore
        }
        
        $stmt = $db->query("SELECT * FROM invoice_templates WHERE is_active = 'yes' ORDER BY is_default DESC");
        $templates = $stmt->fetchAll();
        $this->view('invoices/templates', ['templates' => $templates]);
    }

    public function saveTemplate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $templateModel = $this->model('InvoiceTemplate');
            
            $data = [
                'template_name' => trim($_POST['template_name']),
                'template_type' => $_POST['template_type'] ?? 'a4',
                'print_size' => $_POST['print_size'] ?? 'auto',
                'is_default' => 'no',
                'header_text' => $_POST['header_text'] ?? null,
                'footer_text' => $_POST['footer_text'] ?? null,
                'show_logo' => isset($_POST['show_logo']) ? 'yes' : 'no',
                'show_barcode' => isset($_POST['show_barcode']) ? 'yes' : 'no',
                'show_qr_code' => isset($_POST['show_qr_code']) ? 'yes' : 'no',
                'show_terms' => isset($_POST['show_terms']) ? 'yes' : 'no',
                'terms_content' => $_POST['terms_content'] ?? null,
                'show_customer_signature' => isset($_POST['show_customer_signature']) ? 'yes' : 'no',
                'show_cashier_signature' => isset($_POST['show_cashier_signature']) ? 'yes' : 'no',
                'color_scheme' => $_POST['color_scheme'] ?? '#667eea',
                'font_size' => $_POST['font_size'] ?? 'medium',
                'custom_html' => $_POST['custom_html'] ?? null,
                'is_active' => 'yes'
            ];

            if (empty($data['template_name'])) {
                $_SESSION['error'] = 'Template name is required!';
                $this->redirect('invoice/templates');
                return;
            }

            if ($templateModel->create($data)) {
                $this->success('Template created successfully!');
            } else {
                $this->error('Error creating template!');
            }
            
            $this->redirect('invoice/templates');
        }
        
        $this->redirect('invoice/templates');
    }

    public function editTemplate($id = null) {
        $templateModel = $this->model('InvoiceTemplate');
        
        error_log("editTemplate called with id: " . $id);
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'template_name' => trim($_POST['template_name']),
                'template_type' => $_POST['template_type'] ?? 'a4',
                'print_size' => $_POST['print_size'] ?? 'auto',
                'header_text' => $_POST['header_text'] ?? null,
                'footer_text' => $_POST['footer_text'] ?? null,
                'show_logo' => isset($_POST['show_logo']) ? 'yes' : 'no',
                'show_barcode' => isset($_POST['show_barcode']) ? 'yes' : 'no',
                'show_qr_code' => isset($_POST['show_qr_code']) ? 'yes' : 'no',
                'show_terms' => isset($_POST['show_terms']) ? 'yes' : 'no',
                'terms_content' => $_POST['terms_content'] ?? null,
                'show_customer_signature' => isset($_POST['show_customer_signature']) ? 'yes' : 'no',
                'show_cashier_signature' => isset($_POST['show_cashier_signature']) ? 'yes' : 'no',
                'color_scheme' => $_POST['color_scheme'] ?? '#667eea',
                'font_size' => $_POST['font_size'] ?? 'medium',
                'custom_html' => $_POST['custom_html'] ?? null,
                'is_active' => isset($_POST['is_active']) ? 'yes' : 'no'
            ];
            
            error_log("Template ID to update: " . $id);
            error_log("Template data: " . print_r($data, true));

            if (empty($data['template_name'])) {
                $_SESSION['error'] = 'Template name is required!';
                $this->redirect('invoice/templates');
                return;
            }

            if ($templateModel->update($id, $data)) {
                $_SESSION['success'] = 'Template updated successfully!';
                error_log("Template updated successfully!");
            } else {
                $_SESSION['error'] = 'Error updating template! ID: ' . $id;
                error_log("Template update failed for ID: " . $id);
            }
            
            $this->redirect('invoice/templates');
            return;
        }
        
        $template = $templateModel->getById($id);
        $this->view('invoices/edit_template', ['template' => $template]);
    }

    public function deleteTemplate($id = null) {
        if ($id) {
            $templateModel = $this->model('InvoiceTemplate');
            
            $template = $templateModel->getById($id);
            if ($template && $template['is_default'] === 'yes') {
                $this->error('Cannot delete default template!');
            } elseif ($templateModel->delete($id)) {
                $this->success('Template deleted successfully!');
            } else {
                $this->error('Error deleting template!');
            }
        }
        
        $this->redirect('invoice/templates');
    }

    public function setDefaultTemplate($id = null) {
        if ($id) {
            $templateModel = $this->model('InvoiceTemplate');
            
            if ($templateModel->setDefault($id)) {
                $this->success('Default template updated!');
            } else {
                $this->error('Error setting default template!');
            }
        }
        
        $this->redirect('invoice/templates');
    }

    public function toggleTemplateStatus($id = null) {
        if ($id) {
            $templateModel = $this->model('InvoiceTemplate');
            
            if ($templateModel->toggleStatus($id)) {
                $this->success('Template status updated!');
            } else {
                $this->error('Error updating template status!');
            }
        }
        
        $this->redirect('invoice/templates');
    }

    public function numbering() {
        $db = $this->getDb();
        
        // Ensure table and default data exists
        try {
            $db->exec("CREATE TABLE IF NOT EXISTS `invoice_numbering` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `prefix` varchar(20) NOT NULL DEFAULT 'INV',
                `starting_number` int(11) NOT NULL DEFAULT 1,
                `current_number` int(11) NOT NULL DEFAULT 1,
                `padding` int(11) NOT NULL DEFAULT 4,
                `fiscal_year` varchar(10) DEFAULT NULL,
                `is_active` enum('yes','no') DEFAULT 'yes',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `prefix` (`prefix`,`fiscal_year`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            
            $check = $db->query("SELECT COUNT(*) as cnt FROM invoice_numbering");
            $result = $check->fetch();
            if ($result['cnt'] == 0) {
                $db->exec("INSERT INTO `invoice_numbering` (`prefix`, `starting_number`, `current_number`, `padding`, `fiscal_year`, `is_active`) VALUES ('INV', 1, 1, 4, '2026', 'yes'), ('RET', 1, 1, 4, '2026', 'yes')");
            }
        } catch (Exception $e) {
            // Table may not exist - ignore
        }
        
        $stmt = $db->query("SELECT * FROM invoice_numbering ORDER BY prefix ASC");
        $numberings = $stmt->fetchAll();
        $this->view('invoices/numbering', ['numberings' => $numberings]);
    }

    public function saveNumbering() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $this->getDb();
            
            $prefix = strtoupper(trim($_POST['prefix']));
            $startingNumber = intval($_POST['starting_number']);
            $padding = intval($_POST['padding'] ?? 4);
            $fiscalYear = $_POST['fiscal_year'] ?? date('Y');
            
            if (empty($prefix)) {
                $_SESSION['error'] = 'Prefix is required!';
                $this->redirect('invoice/numbering');
                return;
            }
            
            $checkStmt = $db->prepare("SELECT id FROM invoice_numbering WHERE prefix = ? AND (fiscal_year = ? OR fiscal_year IS NULL)");
            $checkStmt->execute([$prefix, $fiscalYear]);
            
            if ($checkStmt->fetch()) {
                $stmt = $db->prepare("
                    UPDATE invoice_numbering 
                    SET starting_number = ?, padding = ?, fiscal_year = ?, is_active = 'yes'
                    WHERE prefix = ? AND (fiscal_year = ? OR fiscal_year IS NULL)
                ");
                $stmt->execute([$startingNumber, $padding, $fiscalYear, $prefix, $fiscalYear]);
            } else {
                $stmt = $db->prepare("
                    INSERT INTO invoice_numbering (prefix, starting_number, current_number, padding, fiscal_year, is_active)
                    VALUES (?, ?, ?, ?, ?, 'yes')
                ");
                $stmt->execute([$prefix, $startingNumber, $startingNumber, $padding, $fiscalYear]);
            }
            
            $this->success('Invoice numbering saved successfully!');
            $this->redirect('invoice/numbering');
            return;
        }
        
        $this->redirect('invoice/numbering');
    }

    public function deleteNumbering($id = null) {
        if ($id) {
            $db = $this->getDb();
            $stmt = $db->prepare("DELETE FROM invoice_numbering WHERE id = ?");
            $stmt->execute([$id]);
            $this->success('Invoice numbering deleted!');
        }
        $this->redirect('invoice/numbering');
    }

    public function createFromSales() {
        $db = $this->getDb();
        $stmt = $db->query("SELECT * FROM sales ORDER BY id ASC");
        $sales = $stmt->fetchAll();
        
        $created = 0;
        foreach ($sales as $sale) {
            $checkStmt = $db->prepare("SELECT id FROM invoices WHERE sales_id = ?");
            $checkStmt->execute([$sale['id']]);
            if (!$checkStmt->fetch()) {
                $dueAmount = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                $paymentStatus = $dueAmount <= 0 ? 'paid' : 'partial';
                
                $invoiceNum = 'INV-' . date('Y', strtotime($sale['sale_date'])) . '-' . str_pad($sale['id'], 4, '0', STR_PAD_LEFT);
                
                $insertStmt = $db->prepare("INSERT INTO invoices (invoice_number, invoice_prefix, invoice_date, customer_id, customer_name, customer_phone, sales_id, user_id, cashier_name, subtotal, discount_amount, tax_amount, total_amount, paid_amount, due_amount, change_amount, payment_status, payment_method, mobile_type, invoice_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $insertStmt->execute([
                    $invoiceNum, 'INV', $sale['sale_date'], $sale['customer_id'], 
                    $sale['customer_name'], '', $sale['id'], $sale['user_id'],
                    $sale['cashier_name'], $sale['subtotal'], $sale['discount_amount'],
                    $sale['tax_amount'], $sale['total_amount'], $sale['paid_amount'],
                    $dueAmount, $sale['change_amount'], $paymentStatus, $sale['payment_method'],
                    $sale['mobile_type'], 'regular', 'completed'
                ]);
                $created++;
            }
        }
        
        echo "Created $created invoices from existing sales!";
    }
}