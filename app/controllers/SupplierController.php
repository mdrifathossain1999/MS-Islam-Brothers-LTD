<?php
class SupplierController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireLogin();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE supplier_name LIKE ? OR company_name LIKE ? OR phone LIKE ?");
            $stmt->execute(["%$search%", "%$search%", "%$search%"]);
            $suppliers = $stmt->fetchAll();
        } else {
            $suppliers = $this->db->query("SELECT * FROM suppliers ORDER BY supplier_name")->fetchAll();
        }
        
        $this->view('supplier/index', ['suppliers' => $suppliers ?? []]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                INSERT INTO suppliers (supplier_name, company_name, phone, email, address, balance, status)
                VALUES (?, ?, ?, ?, ?, ?, 'active')
            ");
            $stmt->execute([
                $_POST['supplier_name'],
                $_POST['company_name'] ?? '',
                $_POST['phone'] ?? '',
                $_POST['email'] ?? '',
                $_POST['address'] ?? '',
                $_POST['balance'] ?? 0
            ]);
            
            $this->success('Supplier added successfully!');
            $this->redirect('supplier/index');
        }
        
        $this->view('supplier/create');
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('supplier/index');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("
                UPDATE suppliers SET supplier_name = ?, company_name = ?, phone = ?, email = ?, address = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['supplier_name'],
                $_POST['company_name'] ?? '',
                $_POST['phone'] ?? '',
                $_POST['email'] ?? '',
                $_POST['address'] ?? '',
                $_POST['status'] ?? 'active',
                $id
            ]);
            
            $this->success('Supplier updated!');
            $this->redirect('supplier/index');
        }
        
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        $supplier = $stmt->fetch();
        
        $this->view('supplier/edit', ['supplier' => $supplier]);
    }

    public function delete($id = null) {
        if ($id) {
            $this->db->prepare("DELETE FROM suppliers WHERE id = ?")->execute([$id]);
            $this->success('Supplier deleted!');
        }
        $this->redirect('supplier/index');
    }
}