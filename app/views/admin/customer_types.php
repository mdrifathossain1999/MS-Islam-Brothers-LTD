<?php 
$pageTitle = 'Customer Types';
$currentPage = 'admin';
$subPage = 'customerTypes';
ob_start();
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">

<style>
.customer-types-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
.type-card { background: var(--admin-card); border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid var(--admin-border); transition: all 0.3s ease; }
.type-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); border-color: var(--admin-primary); }
.type-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px; }
.type-card.purple .type-icon { background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white; }
.type-card.green .type-icon { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
.type-card.blue .type-icon { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white; }
.type-card.orange .type-icon { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
.type-name { font-size: 18px; font-weight: 700; color: var(--admin-text); margin: 0 0 8px; }
.type-count { font-size: 13px; color: var(--admin-text-muted); }
.type-count strong { color: var(--admin-primary); }
.btn-back-admin { background: rgba(255,255,255,0.2); color: white; padding: 10px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.btn-back-admin:hover { background: white; color: var(--admin-primary); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--admin-text); margin-bottom: 6px; }
.form-group input { width: 100%; padding: 12px 14px; border: 2px solid var(--admin-border); border-radius: 10px; font-size: 14px; background: var(--admin-card); color: var(--admin-text); }
.form-group input:focus { border-color: var(--admin-primary); outline: none; }
.modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; }
.modal-overlay.show { display: flex; align-items: center; justify-content: center; }
.modal { background: var(--admin-card); border-radius: 16px; padding: 28px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.modal h2 { margin: 0 0 20px; font-size: 20px; }
.modal-actions { display: flex; gap: 12px; margin-top: 20px; }
.modal-actions button { flex: 1; padding: 12px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; }
.btn-save { background: var(--admin-primary); color: white; }
.btn-save:hover { background: var(--admin-primary-dark); }
.btn-cancel { background: var(--admin-bg); color: var(--admin-text); border: 2px solid var(--admin-border); }
.btn-cancel:hover { border-color: var(--admin-primary); }
</style>

<div class="admin-page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back-admin"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-person-badge-fill"></i> Customer Types</h1>
    </div>
    <button class="admin-btn admin-btn-primary" onclick="document.getElementById('addModal').classList.add('show')"><i class="bi bi-plus-lg"></i> Add Type</button>
</div>

<?php if (empty($customer_types)): ?>
<div class="admin-card admin-empty">
    <i class="bi bi-person-badge"></i>
    <h4>No Customer Types</h4>
    <p>Add your first customer type!</p>
</div>
<?php else: ?>
<div class="customer-types-grid">
    <?php $colors = ['purple', 'green', 'blue', 'orange']; $i = 0; ?>
    <?php foreach ($customer_types as $type): ?>
    <div class="type-card <?php echo $colors[$i++ % 4]; ?>">
        <div class="type-icon"><i class="bi bi-person-fill"></i></div>
        <h3 class="type-name"><?php echo htmlspecialchars($type['type_name'] ?? 'Unnamed'); ?></h3>
        <p class="type-count"><strong><?php echo $type['customer_count'] ?? 0; ?></strong> customers</p>
        <div style="display:flex;gap:8px;margin-top:16px;">
            <a href="<?php echo BASE_URL; ?>/admin/editCustomerType/<?php echo $type['id']; ?>" class="admin-icon-btn"><i class="bi bi-pencil"></i></a>
            <a href="<?php echo BASE_URL; ?>/admin/deleteCustomerType/<?php echo $type['id']; ?>" class="admin-icon-btn danger" onclick="return confirm('Delete this type?')"><i class="bi bi-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2><i class="bi bi-plus-circle"></i> Add Customer Type</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/createCustomerType">
            <div class="form-group">
                <label>Type Name</label>
                <input type="text" name="type_name" placeholder="Enter type name" required>
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