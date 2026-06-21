<?php
require_once 'app/models/StockLog.php';

class ProductController extends Controller {
    public function index() {
        $this->requireLogin();

        $productModel = $this->model('Product');
        $category = $_GET['category'] ?? '';
        
        if (!empty($category)) {
            $products = $productModel->getByCategory($category);
        } else {
            $products = $productModel->getAll();
        }
        
        $categories = $productModel->getCategories();
        
        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'current_category' => $category
        ]);
    }

    public function create() {
        $this->requireAdmin();

        $productModel = $this->model('Product');
        $categories = $productModel->getCategories();
        $units = $productModel->getUnits();
        $mappingModel = $this->model('CategoryUnitMapping');
        $categoryUnitMap = [];
        if ($mappingModel) {
            $mappings = $mappingModel->getAll();
            foreach ($mappings as $mapping) {
                $categoryUnitMap[$mapping['category']] = $mapping['unit_name'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagePath = null;
            $productName = $_POST['product_name'];
            
            if (!empty($_FILES['product_image']['name'])) {
                $uploadDir = 'uploads/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($productName));
                $imageName = $safeName . '.' . $ext;
                $imagePath = $uploadDir . $imageName;
                move_uploaded_file($_FILES['product_image']['tmp_name'], $imagePath);
            }
            
            // Auto-generate barcode if not provided
            $barcode = !empty($_POST['barcode']) ? $_POST['barcode'] : $this->generateBarcode();
            
            $data = [
                'barcode' => $barcode,
                'product_name' => $productName,
                'category' => $_POST['category'] ?? null,
                'description' => $_POST['description'] ?? null,
                'cost_price' => $_POST['cost_price'] ?? 0,
                'sell_price' => $_POST['sell_price'],
                'stock_quantity' => $_POST['stock_quantity'] ?? 0,
                'low_stock_threshold' => $_POST['low_stock_threshold'] ?? 10,
                'unit' => $_POST['unit'] ?? 'piece',
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'active'
            ];

            try {
                $productModel->create($data);
                $newProductId = $this->db->lastInsertId();
                
                if ($data['stock_quantity'] > 0) {
                    $log = new StockLog($this->db);
                    $log->create([
                        'product_id' => $newProductId,
                        'previous_quantity' => 0,
                        'new_quantity' => $data['stock_quantity'],
                        'change_type' => 'add',
                        'user_id' => $_SESSION['user_id'],
                        'notes' => 'Initial stock on product creation'
                    ]);
                }

                $this->logActivity('create', 'product', 'Created product: ' . $productName, 'product', $newProductId);
                $this->success('Product created successfully!');
                $this->redirect('product/index');
            } catch (Exception $e) {
                $this->view('products/create', ['error' => $e->getMessage(), 'categories' => $categories, 'units' => $units]);
            }
        }

        $this->view('products/create', [
            'categories' => $categories, 
            'units' => $units,
            'show_barcode' => defined('SHOW_BARCODE') ? SHOW_BARCODE : true,
            'category_unit_map' => $categoryUnitMap
        ]);
    }

    public function edit($id = null) {
        $this->requireAdmin();

        if (!$id) {
            $this->redirect('product/index');
        }

        $productModel = $this->model('Product');
        $product = $productModel->getById($id);
        $categories = $productModel->getCategories();
        $units = $productModel->getUnits();
        $mappingModel = $this->model('CategoryUnitMapping');
        $categoryUnitMap = [];
        if ($mappingModel) {
            $mappings = $mappingModel->getAll();
            foreach ($mappings as $mapping) {
                $categoryUnitMap[$mapping['category']] = $mapping['unit_name'];
            }
        }

        if (!$product) {
            die('Product not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldQuantity = $product['stock_quantity'];
            $newQuantity = $_POST['stock_quantity'] ?? 0;
            $quantityDiff = $newQuantity - $oldQuantity;
            $productName = $_POST['product_name'];

            $imagePath = $product['image'];
            if (!empty($_FILES['product_image']['name'])) {
                if ($imagePath && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $uploadDir = 'uploads/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($productName));
                $imageName = $safeName . '.' . $ext;
                $imagePath = $uploadDir . $imageName;
                move_uploaded_file($_FILES['product_image']['tmp_name'], $imagePath);
            }

            $data = [
                'barcode' => $_POST['barcode'] ?? null,
                'product_name' => $productName,
                'category' => $_POST['category'] ?? null,
                'description' => $_POST['description'] ?? null,
                'cost_price' => $_POST['cost_price'] ?? 0,
                'sell_price' => $_POST['sell_price'],
                'stock_quantity' => $newQuantity,
                'low_stock_threshold' => $_POST['low_stock_threshold'] ?? 10,
                'unit' => $_POST['unit'] ?? 'piece',
                'image' => $imagePath,
                'status' => $_POST['status'] ?? 'active'
            ];

            $productModel->update($id, $data);

            if ($quantityDiff != 0) {
                $log = new StockLog($this->db);
                $log->create([
                    'product_id' => $id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'change_type' => $quantityDiff > 0 ? 'add' : 'remove',
                    'user_id' => $_SESSION['user_id'],
                    'notes' => 'Stock updated via product edit'
                ]);
            }

            $this->logActivity('update', 'product', 'Updated product: ' . $productName, 'product', $id);
            $this->success('Product updated successfully!');
            $this->redirect('product/index');
        }

        $this->view('products/edit', [
            'product' => $product, 
            'categories' => $categories, 
            'units' => $units,
            'show_barcode' => defined('SHOW_BARCODE') ? SHOW_BARCODE : true,
            'category_unit_map' => $categoryUnitMap
        ]);
    }

    public function delete($id = null) {
        $this->requireAdmin();

        if ($id) {
            $productModel = $this->model('Product');
            $product = $productModel->getById($id);
            $productModel->delete($id);
            $this->logActivity('delete', 'product', 'Deleted product: ' . ($product['product_name'] ?? 'ID: ' . $id), 'product', $id);
            $this->success('Product deleted successfully!');
        }

        $this->redirect('product/index');
    }

    public function search() {
        header('Content-Type: application/json');
        
        $term = $_GET['term'] ?? '';
        $productModel = $this->model('Product');
        $products = $productModel->search($term);
        
        echo json_encode($products);
        exit();
    }

    public function barcode($barcode) {
        header('Content-Type: application/json');
        
        $productModel = $this->model('Product');
        $product = $productModel->getByBarcode($barcode);
        
        if ($product) {
            echo json_encode(['success' => true, 'product' => $product]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
        exit();
    }

    private function generateBarcode() {
        // Generate unique barcode: timestamp + random number
        $timestamp = date('ymdHis');
        $random = rand(1000, 9999);
        return $timestamp . $random;
    }
}
