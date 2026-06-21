<?php 
$pageTitle = 'Role Permission';
$currentPage = 'settings';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; }
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; margin-bottom: 20px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group select { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.permission-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
.permission-card { background: var(--bg-surface-alt); padding: 16px; border-radius: 8px; border: 1px solid var(--border); }
.permission-card label { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-primary); }
.permission-card input[type="checkbox"] { width: 18px; height: 18px; }
.permission-card span { font-size: 12px; color: var(--text-muted); }
</style>

<div class="page-header">
    <h1><i class="bi bi-shield-lock me-2"></i>Role Permission</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card-modern p-4">
            <form method="POST">
                <div class="form-group">
                    <label>Select Role</label>
                    <select name="role_name" id="roleSelect" onchange="this.form.submit()">
                        <option value="">-- Select Role --</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="cashier">Cashier</option>
                        <option value="accountant">Accountant</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <?php if (!empty($_POST['role_name'])): ?>
        <div class="card-modern p-4">
            <form method="POST">
                <input type="hidden" name="role_name" value="<?php echo $_POST['role_name']; ?>">
                <h5 class="mb-4">Permissions for <?php echo ucfirst($_POST['role_name']); ?></h5>
                <div class="permission-grid">
                    <?php 
                    $modules = ['dashboard', 'products', 'customers', 'suppliers', 'purchase', 'sale', 'expense', 'account', 'hrm', 'reports', 'settings'];
                    foreach ($modules as $module): 
                    ?>
                    <div class="permission-card">
                        <label><?php echo ucfirst($module); ?></label>
                        <div class="d-flex flex-wrap gap-2">
                            <span><input type="checkbox" name="modules[<?php echo $module; ?>][view]" <?php echo (isset($rolePerms[$_POST['role_name']][$module]['can_view']) && $rolePerms[$_POST['role_name']][$module]['can_view'] === 'yes') ? 'checked' : ''; ?>> View</span>
                            <span><input type="checkbox" name="modules[<?php echo $module; ?>][create]" <?php echo (isset($rolePerms[$_POST['role_name']][$module]['can_create']) && $rolePerms[$_POST['role_name']][$module]['can_create'] === 'yes') ? 'checked' : ''; ?>> Create</span>
                            <span><input type="checkbox" name="modules[<?php echo $module; ?>][edit]" <?php echo (isset($rolePerms[$_POST['role_name']][$module]['can_edit']) && $rolePerms[$_POST['role_name']][$module]['can_edit'] === 'yes') ? 'checked' : ''; ?>> Edit</span>
                            <span><input type="checkbox" name="modules[<?php echo $module; ?>][delete]" <?php echo (isset($rolePerms[$_POST['role_name']][$module]['can_delete']) && $rolePerms[$_POST['role_name']][$module]['can_delete'] === 'yes') ? 'checked' : ''; ?>> Delete</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn-primary mt-4">Save Permissions</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';