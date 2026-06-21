<?php 
$pageTitle = 'Edit Category';
$currentPage = 'admin';
$subPage = 'categories';
ob_start();
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">

<style>
.edit-card { background: var(--admin-card); border-radius: 16px; padding: 32px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid var(--admin-border); max-width: 500px; margin: 0 auto; }
.edit-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.edit-header i { font-size: 24px; color: var(--admin-primary); }
.edit-header h2 { margin: 0; font-size: 24px; color: var(--admin-text); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 14px; font-weight: 600; color: var(--admin-text); margin-bottom: 8px; }
.form-group input { width: 100%; padding: 14px 16px; border: 2px solid var(--admin-border); border-radius: 10px; font-size: 15px; background: var(--admin-card); color: var(--admin-text); }
.form-group input:focus { border-color: var(--admin-primary); outline: none; }
.btn-back-admin { background: rgba(255,255,255,0.2); color: white; padding: 10px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.btn-back-admin:hover { background: white; color: var(--admin-primary); }
.modal-actions { display: flex; gap: 12px; margin-top: 28px; }
.modal-actions button { flex: 1; padding: 14px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; font-size: 14px; }
.btn-save { background: var(--admin-primary); color: white; }
.btn-save:hover { background: var(--admin-primary-dark); }
.btn-cancel { background: var(--admin-bg); color: var(--admin-text); border: 2px solid var(--admin-border); text-decoration: none; text-align: center; }
</style>

<div class="admin-page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn-back-admin"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-tag-fill"></i> Edit Category</h1>
    </div>
</div>

<div class="edit-card">
    <div class="edit-header">
        <i class="bi bi-pencil-square"></i>
        <h2>Edit Category</h2>
    </div>
    <form method="POST" action="<?php echo BASE_URL; ?>/admin/updateCategory">
        <input type="hidden" name="old_category" value="<?php echo $category['category_name'] ?? $category['category'] ?? ''; ?>">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="new_category" value="<?php echo htmlspecialchars($category['category_name'] ?? ''); ?>" required>
        </div>
        <div class="modal-actions">
            <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Save Changes</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';