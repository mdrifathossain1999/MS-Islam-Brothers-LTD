<?php
class SettingsController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
    }

    public function rolePermission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role_name = $_POST['role_name'];
            
            $this->db->prepare("DELETE FROM role_permissions WHERE role_name = ?")->execute([$role_name]);
            
            $modules = $_POST['modules'] ?? [];
            foreach ($modules as $module => $perms) {
                $stmt = $this->db->prepare("
                    INSERT INTO role_permissions (role_name, module, can_view, can_create, can_edit, can_delete)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $role_name,
                    $module,
                    $perms['view'] ?? 'no',
                    $perms['create'] ?? 'no',
                    $perms['edit'] ?? 'no',
                    $perms['delete'] ?? 'no'
                ]);
            }
            
            $this->success('Permissions updated successfully!');
            $this->redirect('settings/rolePermission');
        }
        
        $permissions = $this->db->query("SELECT * FROM role_permissions")->fetchAll();
        
        $rolePerms = [];
        foreach ($permissions as $p) {
            $rolePerms[$p['role_name']][$p['module']] = $p;
        }
        
        $this->view('settings/rolePermission', ['rolePerms' => $rolePerms]);
    }
}