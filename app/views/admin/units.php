<?php 
$pageTitle = 'Units';
$currentPage = 'admin';
$subPage = 'units';
ob_start();
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">

<style>
.units-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
}
.unit-card {
    background: var(--admin-card);
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid var(--admin-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
}
.unit-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.unit-info h4 {
    margin: 0 0 4px;
    font-size: 16px;
    font-weight: 700;
    color: var(--admin-text);
}
.unit-info p {
    margin: 0;
    font-size: 12px;
    color: var(--admin-text-muted);
}
.unit-actions {
    display: flex;
    gap: 8px;
}
</style>

<div class="admin-page-header">
    <h1><i class="bi bi-rulers"></i> Units</h1>
    <button class="admin-btn admin-btn-primary">
        <i class="bi bi-plus-lg"></i> Add Unit
    </button>
</div>

<?php if (empty($units)): ?>
<div class="admin-card admin-empty">
    <i class="bi bi-rulers"></i>
    <h4>No Units Found</h4>
    <p>Add your first unit to get started!</p>
</div>
<?php else: ?>
<div class="units-grid">
    <?php foreach ($units as $unit): ?>
    <div class="unit-card">
        <div class="unit-info">
            <h4><?php echo htmlspecialchars($unit['unit_name']); ?></h4>
            <p><?php echo $unit['unit_count'] ?? 0; ?> products</p>
        </div>
        <div class="unit-actions">
            <a href="#" class="admin-icon-btn"><i class="bi bi-pencil"></i></a>
            <a href="#" class="admin-icon-btn danger"><i class="bi bi-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';