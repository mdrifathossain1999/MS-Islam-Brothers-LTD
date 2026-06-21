<?php 
$pageTitle = 'Categories';
$currentPage = 'admin';
$subPage = 'categories';
ob_start();
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">

<style>
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.category-card {
    background: var(--admin-card);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid var(--admin-border);
    transition: all 0.3s ease;
}
.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.category-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    color: white;
    margin-bottom: 16px;
}
.category-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--admin-text);
    margin: 0 0 8px;
}
.category-count { font-size: 13px; color: var(--admin-text-muted); }
.category-count strong { color: var(--admin-primary); }
.btn-back-admin {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.btn-back-admin:hover { background: white; color: var(--admin-primary); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--admin-text); margin-bottom: 6px; }
.form-group input, .form-group select { width: 100%; padding: 12px 14px; border: 2px solid var(--admin-border); border-radius: 10px; font-size: 14px; background: var(--admin-card); color: var(--admin-text); }
.form-group input:focus, .form-group select:focus { border-color: var(--admin-primary); outline: none; }
.modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; }
.modal-overlay.show { display: flex; align-items: center; justify-content: center; }
.modal { background: var(--admin-card); border-radius: 16px; padding: 28px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.modal h2 { margin: 0 0 20px; font-size: 20px; }
.modal-actions { display: flex; gap: 12px; margin-top: 20px; }
.modal-actions button { flex: 1; padding: 12px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; }
.btn-save { background: var(--admin-primary); color: white; }
.btn-save:hover { background: var(--admin-primary-dark); }
.btn-cancel { background: var(--admin-bg); color: var(--admin-text); border: 2px solid var(--admin-border) !important; }
</style>

<div class="admin-page-header">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back-admin"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-tags-fill"></i> Categories</h1>
    </div>
    <button class="admin-btn admin-btn-primary" onclick="document.getElementById('addModal').classList.add('show')"><i class="bi bi-plus-lg"></i> Add Category</button>
</div>

<?php if (empty($categories)): ?>
<div class="admin-card admin-empty">
    <i class="bi bi-tags"></i>
    <h4>No Categories Found</h4>
    <p>Add your first category!</p>
</div>
<?php else: ?>
<div class="categories-grid">
    <?php foreach ($categories as $category): ?>
    <div class="category-card">
        <div class="category-icon"><i class="bi bi-tag-fill"></i></div>
        <h3 class="category-name"><?php echo htmlspecialchars($category['category_name'] ?? $category['category'] ?? 'Unnamed'); ?></h3>
        <p class="category-count"><strong><?php echo $category['count'] ?? 0; ?></strong> products</p>
        <div style="display:flex;gap:8px;margin-top:16px;">
            <a href="<?php echo BASE_URL; ?>/admin/editCategory/<?php echo $category['id'] ?? ''; ?>" class="admin-icon-btn"><i class="bi bi-pencil"></i></a>
            <a href="<?php echo BASE_URL; ?>/admin/deleteCategory/<?php echo $category['id'] ?? ''; ?>" class="admin-icon-btn danger" onclick="return confirm('Delete this category?')"><i class="bi bi-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2><i class="bi bi-plus-circle"></i> Add Category</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/addCategory">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" placeholder="Enter category name" required>
            </div>
            <div class="form-group">
                <label>Size Type</label>
                <select name="size_type"><option value="">No Size</option><option value="single">Single Size</option><option value="multiple">Multiple Sizes</option></select>
            </div>
            <div class="form-group">
                <label>Size Options</label>
                <input type="text" name="size_options" placeholder="S, M, L, XL">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('addModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';