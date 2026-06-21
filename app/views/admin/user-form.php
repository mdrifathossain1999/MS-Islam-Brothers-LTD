<?php 
$pageTitle = isset($user) ? 'Edit User' : 'Add New User';
$currentPage = 'admin';
$subPage = 'users';
$isEdit = isset($user) && !empty($user);
ob_start();
?>

<style>
:root {
    --uf-card: #ffffff;
    --uf-text: #1e293b;
    --uf-text-secondary: #475569;
    --uf-text-muted: #94a3b8;
    --uf-border: #e2e8f0;
    --uf-bg: #f8fafc;
}
body.dark-mode {
    --uf-card: #1e293b;
    --uf-text: #f1f5f9;
    --uf-text-secondary: #94a3b8;
    --uf-text-muted: #94a3b8;
    --uf-border: #334155;
    --uf-bg: #0f172a;
}
.form-card { background: var(--uf-card); border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--uf-text-secondary); margin-bottom: 6px; }
.form-group label span { color: var(--danger, #ef4444); }
.form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid var(--uf-border); border-radius: 8px; font-size: 14px; background: var(--uf-card); color: var(--uf-text); }
.form-group input:focus, .form-group select:focus { border-color: #667eea; outline: none; }
.form-group small { font-size: 12px; color: var(--uf-text-muted); }
.form-actions { display: flex; gap: 12px; margin-top: 24px; }
.btn { padding: 10px 20px; border-radius: 8px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-primary { background: #667eea; color: #fff; border: none; }
.btn-primary:hover { background: #5a6fd6; }
.btn-secondary { background: var(--uf-border); color: var(--uf-text-secondary); border: none; }
.btn-secondary:hover { background: var(--uf-border); opacity: 0.8; }
</style>

<div class="page-header">
    <h1><i class="bi bi-person-<?php echo $isEdit ? 'pencil' : 'plus'; ?>"></i><?php echo $isEdit ? 'Edit User' : 'Add New User'; ?></h1>
    <a href="<?php echo BASE_URL; ?>/admin/users" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="form-card">
    
    <form action="<?php echo BASE_URL; ?>/admin/<?php echo $isEdit ? 'editUser/' . $user['id'] : 'createUser'; ?>" method="POST">
        <div class="form-group">
            <label>Full Name <span>*</span></label>
            <input type="text" name="full_name" value="<?php echo $isEdit ? htmlspecialchars($user['full_name']) : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Username <span>*</span></label>
            <input type="text" name="username" value="<?php echo $isEdit ? htmlspecialchars($user['username']) : ''; ?>" <?php echo $isEdit ? 'readonly' : 'required'; ?>>
            <?php if ($isEdit): ?>
            <small>Username cannot be changed</small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Password <?php echo !$isEdit ? '<span>*</span>' : ''; ?></label>
            <input type="password" name="password" <?php echo !$isEdit ? 'required' : ''; ?>>
            <?php if ($isEdit): ?>
            <small>Leave blank to keep current password</small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $isEdit ? htmlspecialchars($user['email']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label>Role <span>*</span></label>
            <select name="role" required>
                <option value="cashier" <?php echo ($isEdit && $user['role'] === 'cashier') ? 'selected' : ''; ?>>Cashier</option>
                <option value="admin" <?php echo ($isEdit && $user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        
        <?php if ($isEdit): ?>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> <?php echo $isEdit ? 'Update User' : 'Create User'; ?>
            </button>
            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
