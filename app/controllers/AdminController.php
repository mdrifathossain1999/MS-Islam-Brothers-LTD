<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            $this->redirect('dashboard');
            exit();
        }
    }
    
    public function index() {
        $userModel = $this->model('User');
        $productModel = $this->model('Product');
        $customerModel = $this->model('Customer');
        $summaryCardModel = $this->model('SummaryCard');
        $saleModel = $this->model('Sale');
        
        $summaryCards = $summaryCardModel->getActive();
        $cardData = [];
        foreach ($summaryCards as $card) {
            $cardData[] = [
                'card' => $card,
                'value' => $summaryCardModel->getCardValue($card)
            ];
        }
        
        // Get totals for stats
        $totalUsers = count($userModel->getAll());
        $totalProducts = count($productModel->getAll());
        $totalCustomers = count($customerModel->getAll());
        
        // Get sales data
        $salesStmt = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE status != 'cancelled'");
        $salesData = $salesStmt->fetch();
        $totalRevenue = floatval($salesData['total']);
        $totalSales = $this->db->query("SELECT COUNT(*) as count FROM sales WHERE status != 'cancelled'")->fetch()['count'];
        
        $data = [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'low_stock_products' => $productModel->getLowStock(20),
            'recent_users' => $userModel->getRecent(5),
            'summary_cards' => $cardData
        ];
        
        $this->view('admin/index', $data);
    }
    
    public function settings() {
        // Clear any cached config
        if (function_exists('opcache_get_status') && opcache_get_status() !== false) {
            opcache_reset();
        }
        
        $this->view('admin/settings');
    }
    
    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $showBarcode = isset($_POST['show_barcode']) && $_POST['show_barcode'] == '1' ? 'true' : 'false';
            
            $logoPath = COMPANY_LOGO;
            
            // Handle logo URL from webhost
            if (!empty($_POST['company_logo_url'])) {
                $logoUrl = $_POST['company_logo_url'];
                if (filter_var($logoUrl, FILTER_VALIDATE_URL) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $logoUrl)) {
                    $logoPath = $logoUrl;
                }
            }
            // Handle logo upload
            elseif (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
                $uploadDir = 'uploads/logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $file = $_FILES['company_logo'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($ext, $allowed) && $file['size'] <= 2 * 1024 * 1024) {
                    $siteNameSlug = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower(SITE_NAME));
                    $siteNameSlug = preg_replace('/_+/', '_', $siteNameSlug);
                    $siteNameSlug = trim($siteNameSlug, '_');
                    $logoName = $siteNameSlug . '_logo.' . $ext;
                    $logoPath = $uploadDir . $logoName;
                    
                    // Delete old logo
                    if (!empty(COMPANY_LOGO) && file_exists(COMPANY_LOGO)) {
                        @unlink(COMPANY_LOGO);
                    }
                    
                    // Move uploaded file
                    if (is_uploaded_file($file['tmp_name'])) {
                        move_uploaded_file($file['tmp_name'], $logoPath);
                    } else {
                        copy($file['tmp_name'], $logoPath);
                    }
                }
            }
            
            $configFile = 'app/config/config.php';
            
            if (!is_writable($configFile)) {
                $this->error('Config file is not writable! Please check file permissions.');
                $this->redirect('admin/settings');
                return;
            }
            
            $lines = file($configFile);
            $newLines = [];
            
            foreach ($lines as $line) {
                if (preg_match("/define\('SITE_NAME'/", $line)) {
                    $newLines[] = "define('SITE_NAME', '" . addslashes($_POST['site_name'] ?? SITE_NAME) . "');\n";
                } elseif (preg_match("/define\('COMPANY_NAME'/", $line)) {
                    $newLines[] = "define('COMPANY_NAME', '" . addslashes($_POST['company_name'] ?? '') . "');\n";
                } elseif (preg_match("/define\('COMPANY_PHONE'/", $line)) {
                    $newLines[] = "define('COMPANY_PHONE', '" . addslashes($_POST['company_phone'] ?? COMPANY_PHONE) . "');\n";
                } elseif (preg_match("/define\('COMPANY_EMAIL'/", $line)) {
                    $newLines[] = "define('COMPANY_EMAIL', '" . addslashes($_POST['company_email'] ?? COMPANY_EMAIL) . "');\n";
                } elseif (preg_match("/define\('COMPANY_ADDRESS'/", $line)) {
                    $newLines[] = "define('COMPANY_ADDRESS', '" . addslashes($_POST['company_address'] ?? '') . "');\n";
                } elseif (preg_match("/define\('COMPANY_LOGO'/", $line)) {
                    $newLines[] = "define('COMPANY_LOGO', '" . addslashes($logoPath) . "');\n";
                } elseif (preg_match("/define\('DEFAULT_CURRENCY'/", $line)) {
                    $newLines[] = "define('DEFAULT_CURRENCY', '" . ($_POST['currency'] ?? DEFAULT_CURRENCY) . "');\n";
                } elseif (preg_match("/define\('TAX_RATE'/", $line)) {
                    $newLines[] = "define('TAX_RATE', " . floatval($_POST['tax_rate'] ?? TAX_RATE) . ");\n";
                } elseif (preg_match("/define\('LOW_STOCK_THRESHOLD'/", $line)) {
                    $newLines[] = "define('LOW_STOCK_THRESHOLD', " . intval($_POST['low_stock_threshold'] ?? LOW_STOCK_THRESHOLD) . ");\n";
                } elseif (preg_match("/define\('SHOW_BARCODE'/", $line)) {
                    $newLines[] = "define('SHOW_BARCODE', $showBarcode);\n";
                } elseif (preg_match("/define\('FOOTER_DEVELOP_BY'/", $line)) {
                    $footerDevelopBy = !empty($_POST['footer_develop_by']) ? $_POST['footer_develop_by'] : (defined('FOOTER_DEVELOP_BY') ? FOOTER_DEVELOP_BY : 'Digital Ledger Solutions');
                    $newLines[] = "define('FOOTER_DEVELOP_BY', '" . addslashes($footerDevelopBy) . "');\n";
                } elseif (preg_match("/define\('FOOTER_DEVELOP_BY_LINK'/", $line)) {
                    $footerDevelopByLink = !empty($_POST['footer_develop_by_link']) ? $_POST['footer_develop_by_link'] : (defined('FOOTER_DEVELOP_BY_LINK') ? FOOTER_DEVELOP_BY_LINK : '');
                    $newLines[] = "define('FOOTER_DEVELOP_BY_LINK', '" . addslashes($footerDevelopByLink) . "');\n";
                } elseif (preg_match("/define\('FOOTER_DESIGN_BY'/", $line)) {
                    $footerDesignBy = !empty($_POST['footer_design_by']) ? $_POST['footer_design_by'] : (defined('FOOTER_DESIGN_BY') ? FOOTER_DESIGN_BY : '');
                    $newLines[] = "define('FOOTER_DESIGN_BY', '" . addslashes($footerDesignBy) . "');\n";
                } elseif (preg_match("/define\('FOOTER_DESIGN_BY_LINK'/", $line)) {
                    $footerDesignByLink = !empty($_POST['footer_design_by_link']) ? $_POST['footer_design_by_link'] : (defined('FOOTER_DESIGN_BY_LINK') ? FOOTER_DESIGN_BY_LINK : '');
                    $newLines[] = "define('FOOTER_DESIGN_BY_LINK', '" . addslashes($footerDesignByLink) . "');\n";
                } elseif (preg_match("/define\('FOOTER_WHATSAPP'/", $line)) {
                    $footerWhatsapp = !empty($_POST['footer_whatsapp']) ? $_POST['footer_whatsapp'] : (defined('FOOTER_WHATSAPP') ? FOOTER_WHATSAPP : '');
                    $newLines[] = "define('FOOTER_WHATSAPP', '" . addslashes($footerWhatsapp) . "');\n";
                } elseif (preg_match("/define\('FOOTER_COPYRIGHT'/", $line)) {
                    $footerCopyright = !empty($_POST['footer_copyright']) ? $_POST['footer_copyright'] : (defined('FOOTER_COPYRIGHT') ? FOOTER_COPYRIGHT : '');
                    $newLines[] = "define('FOOTER_COPYRIGHT', '" . addslashes($footerCopyright) . "');\n";
                } elseif (preg_match("/define\('FOOTER_CUSTOM_TEXT'/", $line)) {
                    $footerCustomText = !empty($_POST['footer_custom_text']) ? $_POST['footer_custom_text'] : (defined('FOOTER_CUSTOM_TEXT') ? FOOTER_CUSTOM_TEXT : '');
                    $newLines[] = "define('FOOTER_CUSTOM_TEXT', '" . addslashes($footerCustomText) . "');\n";
                } else {
                    $newLines[] = $line;
                }
            }
            
            $result = file_put_contents($configFile, implode('', $newLines));
            
            if ($result === false) {
                $this->error('Failed to save settings! Please try again.');
                $this->redirect('admin/settings');
                return;
            }
            
            // Clear APC/OPcache if available
            if (function_exists('opcache_get_status') && opcache_get_status() !== false) {
                opcache_reset();
            }
            
            // Force reload by clearing require cache
            $cachedFiles = ['config.php'];
            foreach ($cachedFiles as $cf) {
                $path = 'app/config/' . $cf;
                if (function_exists('opcache_get_status')) {
                    @opcache_invalidate($path, true);
                }
            }
            
            $this->logActivity('update', 'settings', 'Updated system settings');
            $this->success('Settings updated successfully!');
            $this->redirect('admin/settings');
        }
        
        $this->redirect('admin/settings');
    }
    
    public function users() {
        $userModel = $this->model('User');
        $data = [
            'users' => $userModel->getAll()
        ];
        $this->view('admin/users', $data);
    }
    
    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'cashier'
            ];
            
            if ($userModel->create($data)) {
                $this->logActivity('create', 'user', 'Created user: ' . $_POST['username'] . ' (' . $_POST['role'] . ')', 'user', $this->db->lastInsertId());
                $this->success('User created successfully!');
            } else {
                $this->error('Error creating user. Username may already exist.');
            }
            
            $this->redirect('admin/users');
        }
        
        $this->view('admin/user-form');
    }
    
    public function editUser($id = null) {
        $userModel = $this->model('User');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'cashier',
                'status' => $_POST['status'] ?? 'active'
            ];
            
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }
            
            $userModel->update($id, $data);
            $this->logActivity('update', 'user', 'Updated user: ' . $_POST['username'], 'user', $id);
            
            if ($userModel->update($id, $data)) {
                $this->success('User updated successfully!');
            } else {
                $this->error('Error updating user.');
            }
            
            $this->redirect('admin/users');
        }
        
        $data = [
            'user' => $userModel->getById($id)
        ];
        $this->view('admin/user-form', $data);
    }
    
    public function deleteUser($id) {
        $userModel = $this->model('User');
        
        if ($id == $_SESSION['user_id']) {
            $this->error('You cannot delete your own account!');
            $this->redirect('admin/users');
            return;
        }
        
        $user = $userModel->getById($id);
        if ($userModel->delete($id)) {
            $this->logActivity('delete', 'user', 'Deleted user: ' . $user['username'], 'user', $id);
            $this->success('User deleted successfully!');
        } else {
            $this->error('Error deleting user.');
        }
        
        $this->redirect('admin/users');
    }
    
    public function database() {
        $this->view('admin/database');
    }
    
    public function uploads() {
        $uploadDir = 'uploads/';
        $files = [];
        
        if (is_dir($uploadDir)) {
            $this->scanDirectory($uploadDir, $files);
        }
        
        $data = [
            'files' => $files,
            'baseUrl' => BASE_URL,
            'uploadDir' => $uploadDir
        ];
        $this->view('admin/uploads', $data);
    }
    
    private function scanDirectory($dir, &$files, $relativePath = '') {
        $items = scandir($dir);
        if (!$items) return;
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $fullPath = rtrim($dir, '/') . '/' . $item;
            $relPath = $relativePath . $item;
            
            if (is_dir($fullPath)) {
                $this->scanDirectory($fullPath . '/', $files, $relPath . '/');
            } else {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico']);
                
                $files[] = [
                    'name' => $item,
                    'path' => $relPath,
                    'fullPath' => $fullPath,
                    'url' => BASE_URL . '/uploads/' . $relPath,
                    'size' => filesize($fullPath),
                    'modified' => filemtime($fullPath),
                    'isImage' => $isImage,
                    'extension' => $ext
                ];
            }
        }
    }
    
    public function deleteUpload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $path = $_POST['path'] ?? '';
            
            if (!empty($path) && file_exists($path) && strpos($path, 'uploads/') === 0) {
                if (is_dir($path)) {
                    rmdir($path);
                } else {
                    unlink($path);
                }
                $this->success('File deleted successfully!');
            } else {
                $this->error('Invalid file path!');
            }
        }
        $this->redirect('admin/uploads');
    }
    
    public function backup() {
        header('Content-Type: application/json');
        
        try {
            $backupDir = 'backups';
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, 0777, true)) {
                    throw new Exception('Cannot create backups directory');
                }
            }
            
            $filename = $backupDir . '/backup_' . date('Y-m-d_His') . '.sql';
            
            $db = new Database();
            $conn = $db->getConnection();
            
            $tables = $conn->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
            
            $output = "-- Jolchobi POS Database Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                $output .= "DROP TABLE IF EXISTS `$table`;\n";
                $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch();
                $output .= $create['Create Table'] . ";\n\n";
                
                $rows = $conn->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $values = array_map(function($val) use ($conn) {
                            return $val === null ? 'NULL' : "'" . addslashes($val) . "'";
                        }, array_values($row));
                        $output .= "INSERT INTO `$table` (`" . implode('`, `', array_keys($row)) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $output .= "\n";
                }
            }
            
            if (file_put_contents($filename, $output) === false) {
                throw new Exception('Cannot write backup file');
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Backup created: ' . basename($filename), 
                'file' => $filename,
                'download_url' => BASE_URL . '/admin/downloadBackup?file=' . basename($filename)
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Backup failed: ' . $e->getMessage()]);
        }
        exit();
    }
    
    public function downloadBackup() {
        $file = $_GET['file'] ?? '';
        $safeFile = basename($file);
        $filepath = 'backups/' . $safeFile;
        
        if (file_exists($filepath)) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $safeFile . '"');
            readfile($filepath);
            exit();
        } else {
            $this->error('Backup file not found');
            $this->redirect('admin/database');
        }
    }
    
    public function categories() {
        $this->requireAdmin();
        
        $productModel = $this->model('Product');
        $sizeTypeModel = $this->model('SizeType');
        
        $data = [
            'categories' => $productModel->getCategories(),
            'size_types' => $sizeTypeModel->getAll()
        ];
        $this->view('admin/categories', $data);
    }
    
    public function addCategory() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryName = trim($_POST['category_name'] ?? '');
            $sizeType = trim($_POST['size_type'] ?? '');
            $sizeOptions = trim($_POST['size_options'] ?? '');
            
            if (!empty($categoryName)) {
                $productModel = $this->model('Product');
                $result = $productModel->addCategory($categoryName, $sizeType, $sizeOptions);
                
                if ($result) {
                    $_SESSION['success'] = 'Category "' . htmlspecialchars($categoryName) . '" added successfully!';
                } else {
                    $_SESSION['error'] = 'Category already exists!';
                }
            } else {
                $_SESSION['error'] = 'Category name cannot be empty!';
            }
        }
        header("Location: " . BASE_URL . "/admin/categories");
        exit();
    }
    
    public function editCategory($id = null) {
        $this->requireAdmin();
        
        $productModel = $this->model('Product');
        $categories = $productModel->getCategories();
        
        $category = null;
        foreach ($categories as $cat) {
            if ($cat['id'] == $id) {
                $category = $cat;
                break;
            }
        }
        
        if (!$category) {
            $_SESSION['error'] = 'Category not found!';
            header("Location: " . BASE_URL . "/admin/categories");
            exit();
        }
        
        $this->view('admin/category-edit', [
            'category' => $category
        ]);
    }
    
    public function updateCategory() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldCategory = trim($_POST['old_category'] ?? '');
            $newCategory = trim($_POST['new_category'] ?? '');
            
            if (!empty($oldCategory) && !empty($newCategory)) {
                $productModel = $this->model('Product');
                if ($productModel->updateCategory($oldCategory, $newCategory)) {
                    $_SESSION['success'] = 'Category renamed successfully!';
                } else {
                    $_SESSION['error'] = 'Error updating category!';
                }
            }
        }
        header("Location: " . BASE_URL . "/admin/categories");
        exit();
    }
    
    public function deleteCategory($id = null) {
        $this->requireAdmin();
        
        if ($id) {
            $stmt = $this->db->prepare("SELECT category_name FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $cat = $stmt->fetch();
            if ($cat) {
                $productModel = $this->model('Product');
                if ($productModel->deleteCategory($cat['category_name'])) {
                    $_SESSION['success'] = 'Category deleted successfully!';
                } else {
                    $_SESSION['error'] = 'Error deleting category!';
                }
            } else {
                $_SESSION['error'] = 'Category not found!';
            }
        }
        header("Location: " . BASE_URL . "/admin/categories");
        exit();
    }
    
    public function addSizeType() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sizeTypeName = trim($_POST['size_type_name'] ?? '');
            $sizeTypeOptions = trim($_POST['size_type_options'] ?? '');
            
            if (!empty($sizeTypeName) && !empty($sizeTypeOptions)) {
                $sizeTypeModel = $this->model('SizeType');
                $sizeTypeModel->create([
                    'size_type_name' => $sizeTypeName,
                    'size_type_options' => $sizeTypeOptions
                ]);
                $_SESSION['success'] = 'Size Type added successfully!';
            } else {
                $_SESSION['error'] = 'Size Type name and options are required!';
            }
        }
        header("Location: " . BASE_URL . "/admin/categories");
        exit();
    }
    
    public function deleteSizeType() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sizeTypeName = trim($_POST['size_type_name'] ?? '');
            if (!empty($sizeTypeName)) {
                $sizeTypeModel = $this->model('SizeType');
                $sizeTypeModel->delete($sizeTypeName);
                $_SESSION['success'] = 'Size Type deleted successfully!';
            }
        }
        header("Location: " . BASE_URL . "/admin/categories");
        exit();
    }
    
    public function units() {
        $productModel = $this->model('Product');
        $data = [
            'units' => $productModel->getUnits()
        ];
        $this->view('admin/units', $data);
    }
    
    public function addUnit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $unitName = trim($_POST['unit_name'] ?? '');
            
            if (!empty($unitName)) {
                $productModel = $this->model('Product');
                if ($productModel->addUnit($unitName)) {
                    echo json_encode(['success' => true, 'message' => 'Unit added!']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Unit already exists!']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unit name cannot be empty!']);
            }
        }
        exit();
    }
    
    public function updateUnit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $newName = trim($_POST['unit_name'] ?? '');
            
            if ($id > 0 && !empty($newName)) {
                $productModel = $this->model('Product');
                if ($productModel->updateUnit($id, $newName)) {
                    $this->success('Unit updated successfully!');
                } else {
                    $this->error('Error updating unit!');
                }
            }
        }
        $this->redirect('admin/units');
    }
    
    public function deleteUnit($id = null) {
        if ($id) {
            $productModel = $this->model('Product');
            if ($productModel->deleteUnit($id)) {
                $this->success('Unit deleted successfully!');
            } else {
                $this->error('Error deleting unit!');
            }
        }
        $this->redirect('admin/units');
    }

    public function summaryCards() {
        $summaryCardModel = $this->model('SummaryCard');
        $data = [
            'cards' => $summaryCardModel->getAll()
        ];
        $this->view('admin/summary_cards', $data);
    }

    public function createSummaryCard() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $summaryCardModel = $this->model('SummaryCard');
            
            $cardKey = strtolower(str_replace(' ', '_', trim($_POST['card_title'])));
            $cardKey = preg_replace('/[^a-z0-9_]/', '', $cardKey);
            
            $data = [
                'card_key' => $cardKey . '_' . time(),
                'card_title' => trim($_POST['card_title']),
                'card_type' => $_POST['card_type'] ?? 'custom',
                'icon_class' => $_POST['icon_class'] ?? 'bi bi-collection',
                'color_class' => $_POST['color_class'] ?? 'blue',
                'custom_query' => $_POST['custom_query'] ?? null,
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active' => 'yes'
            ];

            if ($summaryCardModel->create($data)) {
                $this->success('Summary card added successfully!');
            } else {
                $this->error('Error adding summary card!');
            }
            
            $this->redirect('admin/summaryCards');
        }
        
        $this->redirect('admin/summaryCards');
    }

    public function editSummaryCard($id = null) {
        $summaryCardModel = $this->model('SummaryCard');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'card_title' => trim($_POST['card_title']),
                'card_type' => $_POST['card_type'] ?? 'custom',
                'icon_class' => $_POST['icon_class'] ?? 'bi bi-collection',
                'color_class' => $_POST['color_class'] ?? 'blue',
                'custom_query' => $_POST['custom_query'] ?? null,
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 'yes' : 'no'
            ];

            if ($summaryCardModel->update($id, $data)) {
                $this->success('Summary card updated successfully!');
            } else {
                $this->error('Error updating summary card!');
            }
            
            $this->redirect('admin/summaryCards');
        }
        
        $data = [
            'card' => $summaryCardModel->getById($id)
        ];
        $this->view('admin/summary_card_form', $data);
    }

    public function deleteSummaryCard($id = null) {
        if ($id) {
            $summaryCardModel = $this->model('SummaryCard');
            if ($summaryCardModel->delete($id)) {
                $this->success('Summary card deleted successfully!');
            } else {
                $this->error('Cannot delete default system cards!');
            }
        }
        $this->redirect('admin/summaryCards');
    }

    public function toggleSummaryCard($id = null) {
        if ($id) {
            $summaryCardModel = $this->model('SummaryCard');
            if ($summaryCardModel->toggleStatus($id)) {
                $this->success('Card status updated!');
            } else {
                $this->error('Error updating card status!');
            }
        }
        $this->redirect('admin/summaryCards');
    }

    public function exportCustomers() {
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();

        $filename = 'customers_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['Jolchobi Setabganj - Customer List']);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'Name', 'Phone', 'Email', 'Address', 'Total Purchases', 'Total Amount', 'Paid Amount', 'Due Amount', 'Status']);

        $index = 1;
        foreach ($customers as $customer) {
            $due = floatval($customer['total_amount']) - floatval($customer['paid_amount']);
            fputcsv($output, [
                $index++,
                $customer['customer_name'],
                $customer['phone'] ?? '-',
                $customer['email'] ?? '-',
                $customer['address'] ?? '-',
                number_format($customer['total_purchases'], 2),
                number_format($customer['total_amount'], 2),
                number_format($customer['paid_amount'], 2),
                number_format($due, 2),
                $customer['status']
            ]);
        }

        fclose($output);
        exit;
    }

    public function productImportExport() {
        $this->requireAdmin();
        $this->view('admin/product_import_export');
    }

    public function exportProducts() {
        $this->requireAdmin();
        
        $productModel = $this->model('Product');
        $products = $productModel->getAll();

        $filename = 'products_export_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Product Export - Jolchobi Setabganj']);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        
        fputcsv($output, [
            'Product Name', 'Barcode', 'Category', 'Unit', 
            'Cost Price', 'Sell Price', 'Stock Quantity', 
            'Low Stock Threshold', 'Description', 'Status'
        ]);
        
        foreach ($products as $product) {
            fputcsv($output, [
                $product['product_name'],
                $product['barcode'] ?? '',
                $product['category'] ?? '',
                $product['unit'] ?? '',
                $product['cost_price'],
                $product['sell_price'],
                $product['stock_quantity'],
                $product['low_stock_threshold'],
                $product['description'] ?? '',
                $product['status']
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function importProducts() {
        $this->requireAdmin();
        
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] != 0) {
            $this->error('No file uploaded or upload error!');
            $this->redirect('admin/productImportExport');
            return;
        }

        $file = $_FILES['import_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, ['csv', 'xlsx', 'xls'])) {
            $this->error('Invalid file format! Please upload CSV or Excel file.');
            $this->redirect('admin/productImportExport');
            return;
        }

        $productModel = $this->model('Product');
        
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            $this->error('Cannot read file!');
            $this->redirect('admin/productImportExport');
            return;
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->error('Invalid CSV format!');
            $this->redirect('admin/productImportExport');
            return;
        }

        $created = 0;
        $updated = 0;
        $errors = 0;
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            
            if (count($data) < 6) {
                $errors++;
                continue;
            }

            $productData = [
                'product_name' => trim($data[0] ?? ''),
                'barcode' => trim($data[1] ?? ''),
                'category' => trim($data[2] ?? ''),
                'unit' => trim($data[3] ?? 'pcs'),
                'cost_price' => floatval($data[4] ?? 0),
                'sell_price' => floatval($data[5] ?? 0),
                'stock_quantity' => intval($data[6] ?? 0),
                'low_stock_threshold' => intval($data[7] ?? 10),
                'description' => trim($data[8] ?? ''),
                'status' => strtolower(trim($data[9] ?? 'active')) === 'inactive' ? 'inactive' : 'active'
            ];

            if (empty($productData['product_name'])) {
                $errors++;
                continue;
            }

            if (!empty($productData['barcode'])) {
                $existingByBarcode = $productModel->getByBarcode($productData['barcode']);
                if ($existingByBarcode) {
                    $productModel->update($existingByBarcode['id'], $productData);
                    $updated++;
                    continue;
                }
            }

            $existingByName = $productModel->getByName($productData['product_name']);
            if ($existingByName) {
                $productModel->update($existingByName['id'], $productData);
                $updated++;
            } else {
                $productModel->create($productData);
                $created++;
            }
        }

        fclose($handle);

        $message = "Import completed! Created: $created, Updated: $updated";
        if ($errors > 0) {
            $message .= ", Errors: $errors";
        }
        
        $this->success($message);
        $this->redirect('admin/productImportExport');
    }

    public function customerTypes() {
        $customerTypeModel = $this->model('CustomerType');
        $data = [
            'customer_types' => $customerTypeModel->getAll()
        ];
        $this->view('admin/customer_types', $data);
    }

    public function createCustomerType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerTypeModel = $this->model('CustomerType');
            
            $data = [
                'type_name' => trim($_POST['type_name']),
                'description' => trim($_POST['description'] ?? ''),
                'discount_percent' => floatval($_POST['discount_percent'] ?? 0),
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active' => 'yes'
            ];

            if (empty($data['type_name'])) {
                $this->error('Customer type name is required!');
                $this->redirect('admin/customerTypes');
                return;
            }

            if ($customerTypeModel->exists($data['type_name'])) {
                $this->error('Customer type already exists!');
                $this->redirect('admin/customerTypes');
                return;
            }

            if ($customerTypeModel->create($data)) {
                $this->success('Customer type added successfully!');
            } else {
                $this->error('Error adding customer type!');
            }
            
            $this->redirect('admin/customerTypes');
        }
        
        $this->redirect('admin/customerTypes');
    }

    public function editCustomerType($id = null) {
        $customerTypeModel = $this->model('CustomerType');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'type_name' => trim($_POST['type_name']),
                'description' => trim($_POST['description'] ?? ''),
                'discount_percent' => floatval($_POST['discount_percent'] ?? 0),
                'display_order' => intval($_POST['display_order'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? 'yes' : 'no'
            ];

            if (empty($data['type_name'])) {
                $this->error('Customer type name is required!');
                $this->redirect('admin/customerTypes');
                return;
            }

            $existing = $customerTypeModel->getByName($data['type_name']);
            if ($existing && $existing['id'] != $id) {
                $this->error('Customer type name already exists!');
                $this->redirect('admin/customerTypes');
                return;
            }

            if ($customerTypeModel->update($id, $data)) {
                $this->success('Customer type updated successfully!');
            } else {
                $this->error('Error updating customer type!');
            }
            
            $this->redirect('admin/customerTypes');
            return;
        }
        
        $data = [
            'customer_type' => $customerTypeModel->getById($id)
        ];
        $this->view('admin/customer_type_form', $data);
    }

    public function deleteCustomerType($id = null) {
        if ($id) {
            $customerTypeModel = $this->model('CustomerType');
            if ($customerTypeModel->delete($id)) {
                $this->success('Customer type deleted successfully!');
            } else {
                $this->error('Error deleting customer type!');
            }
        }
        $this->redirect('admin/customerTypes');
    }

    public function toggleCustomerType($id = null) {
        if ($id) {
            $customerTypeModel = $this->model('CustomerType');
            if ($customerTypeModel->toggleStatus($id)) {
                $this->success('Customer type status updated!');
            } else {
                $this->error('Error updating customer type status!');
            }
        }
        $this->redirect('admin/customerTypes');
    }

    public function downloadSample() {
        $this->requireAdmin();
        
        $filename = 'products_sample.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Product Import Sample - Jolchobi Setabganj']);
        fputcsv($output, ['Downloaded:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        fputcsv($output, ['Instructions:']);
        fputcsv($output, ['1. Fill in the product data below']);
        fputcsv($output, ['2. Do not change the header row']);
        fputcsv($output, ['3. For existing products, match by Barcode or Product Name']);
        fputcsv($output, ['4. Save as CSV and upload']);
        fputcsv($output, []);
        
        fputcsv($output, [
            'Product Name', 'Barcode', 'Category', 'Unit', 
            'Cost Price', 'Sell Price', 'Stock Quantity', 
            'Low Stock Threshold', 'Description', 'Status'
        ]);
        
        $sampleProducts = [
            ['Premium Rice 5kg', '8901234567890', 'Rice', 'bag', 350, 450, 50, 10, 'Premium quality rice', 'active'],
            ['Sugar 1kg', '8901234567891', 'Sugar', 'kg', 85, 100, 100, 20, 'White crystal sugar', 'active'],
            ['Cooking Oil 5L', '8901234567892', 'Oil', 'bottle', 550, 650, 30, 5, 'Pure mustard oil', 'active'],
            ['Salt 1kg', '8901234567893', 'Salt', 'packet', 20, 25, 200, 50, 'Iodized salt', 'active'],
            ['Flour 2kg', '8901234567894', 'Flour', 'bag', 120, 150, 40, 10, 'Whole wheat flour', 'active'],
        ];
        
        foreach ($sampleProducts as $sample) {
            fputcsv($output, $sample);
        }
        
        fclose($output);
        exit;
    }

    public function activityLogs() {
        $activityLogModel = $this->model('ActivityLog');
        $userModel = $this->model('User');

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $filters = [];
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $filters['user_id'] = intval($_GET['user_id']);
        }
        if (isset($_GET['module']) && !empty($_GET['module'])) {
            $filters['module'] = $_GET['module'];
        }
        if (isset($_GET['action']) && !empty($_GET['action'])) {
            $filters['action'] = $_GET['action'];
        }
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
        }
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
        }

        $logs = $activityLogModel->getAll($perPage, $offset, $filters);
        $totalCount = $activityLogModel->getTotalCount($filters);
        $totalPages = ceil($totalCount / $perPage);

        $modules = $activityLogModel->getModules();
        $actions = $activityLogModel->getActions();
        $users = $userModel->getAll();

        $data = [
            'logs' => $logs,
            'users' => $users,
            'modules' => $modules,
            'actions' => $actions,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'perPage' => $perPage
        ];

        $this->view('admin/activity_logs', $data);
    }

    public function clearActivityLogs() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $days = isset($_POST['days']) ? intval($_POST['days']) : 30;
            $activityLogModel = $this->model('ActivityLog');
            $activityLogModel->clearOld($days);
            $this->success("Logs older than {$days} days have been cleared!");
        }
        $this->redirect('admin/activityLogs');
    }

    public function categoryUnit() {
        $productModel = $this->model('Product');
        $mappingModel = $this->model('CategoryUnitMapping');
        
        $categories = $productModel->getCategories();
        $units = $productModel->getUnits();
        $mappings = $mappingModel ? $mappingModel->getAll() : [];
        
        $data = [
            'categories' => $categories,
            'units' => $units,
            'mappings' => $mappings
        ];
        
        $this->view('admin/category_unit', $data);
    }

    public function saveCategoryUnit() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = trim($_POST['category']);
            $unitName = trim($_POST['unit_name']);
            
            if (!empty($category) && !empty($unitName)) {
                $mappingModel = $this->model('CategoryUnitMapping');
                
                if ($mappingModel) {
                    $exists = $mappingModel->exists($category);
                    
                    if ($exists) {
                        $mappingModel->update($category, $unitName);
                        $_SESSION['success'] = "Unit for '$category' updated to '$unitName'!";
                    } else {
                        $mappingModel->create(['category' => $category, 'unit_name' => $unitName]);
                        $_SESSION['success'] = "Unit '$unitName' mapped to category '$category'!";
                    }
                }
            } else {
                $_SESSION['error'] = "Category and Unit are required!";
            }
        }
        
        header("Location: " . BASE_URL . "/admin/categoryUnit");
        exit();
    }

    public function deleteCategoryUnit($category = null) {
        $this->requireAdmin();
        
        // Accept category from URL or POST body
        $postCategory = $_POST['category'] ?? null;
        
        if (!empty($postCategory)) {
            $category = $postCategory;
        }
        
        if (!empty($category)) {
            $mappingModel = $this->model('CategoryUnitMapping');
            if ($mappingModel && $mappingModel->delete($category)) {
                $_SESSION['success'] = "Mapping for '$category' deleted!";
            } else {
                $_SESSION['error'] = "Error deleting mapping!";
            }
        } else {
            $_SESSION['error'] = "Category not specified. POST: " . print_r($_POST, true);
        }
        
        header("Location: " . BASE_URL . "/admin/categoryUnit");
        exit();
    }

    public function createCategoryUnitTable() {
        $this->requireAdmin();
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS `category_unit_mapping` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `category` varchar(100) NOT NULL,
                `unit_name` varchar(50) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `category` (`category`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

            $this->db->exec("INSERT IGNORE INTO `category_unit_mapping` (`category`, `unit_name`) VALUES
                ('Beverages', 'bottle'),
                ('Dairy', 'packet'),
                ('Groceries', 'kg'),
                ('Bakery', 'piece'),
                ('T-Shert', 'piece'),
                ('White T-Shert', 'piece')");

            $this->success("Category-Unit mapping table created successfully!");
        } catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
        $this->redirect('admin/categoryUnit');
    }

    public function debugCategoryUnit() {
        $this->requireAdmin();
        header('Content-Type: text/plain');
        echo "Testing CategoryUnitMapping:\n\n";
        
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'category_unit_mapping'");
            echo "Table exists check: " . ($result && $result->rowCount() > 0 ? "YES" : "NO") . "\n";
        } catch (Exception $e) {
            echo "Table exists check error: " . $e->getMessage() . "\n";
        }
        
        try {
            $result = $this->db->query("SELECT * FROM category_unit_mapping ORDER BY category ASC");
            $rows = $result->fetchAll();
            echo "Total rows: " . count($rows) . "\n";
            print_r($rows);
        } catch (Exception $e) {
            echo "Select error: " . $e->getMessage() . "\n";
        }
        exit();
    }
}
