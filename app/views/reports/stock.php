<?php 
$pageTitle = 'Stock Report';
$currentPage = 'reports';
ob_start();
?>

<style>
/* Page Header - Smaller size */
.page-header { 
    margin-bottom: 20px;
    padding: 16px 20px;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
}
.page-header::before, .page-header::after { display: none; }
.page-header h1 { font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
.page-header h1 i { font-size: 1.2rem; background: rgba(255,255,255,0.2); padding: 6px 10px; border-radius: 8px; }
.page-header p { margin: 4px 0 0; font-size: 0.85rem; opacity: 0.85; }
.page-header .btn {
    background: white; color: var(--primary); border: none;
    padding: 10px 18px; font-weight: 600; font-size: 13px;
    border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.page-header .btn:hover { background: var(--primary); color: white; transform: translateY(-1px); }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.stat-card.green::before { background: var(--success); }
.stat-card.red::before { background: var(--danger); }
.stat-card.blue::before { background: var(--primary); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 800;
    margin: 0 0 5px;
    color: var(--text-primary);
}

.stat-content p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    font-weight: 500;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-card.green .stat-icon { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: var(--success); }
.stat-card.red .stat-icon { background: linear-gradient(135deg, #fee2e2, #fecaca); color: var(--danger); }
.stat-card.blue .stat-icon { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: var(--primary); }

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 24px;
}

.card-modern .card-header {
    background: linear-gradient(135deg, var(--bg-surface-alt), var(--bg-surface));
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-modern .card-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-modern .card-header h5 i { color: var(--primary); }

.alert-modern {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: none;
    border-radius: var(--radius-lg);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 24px;
}

.alert-modern i {
    font-size: 24px;
    color: var(--danger);
}

.alert-modern strong {
    color: #991b1b;
    font-size: 15px;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 14px 18px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}

.table-custom td {
    padding: 14px 18px;
    font-size: 14px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}

.table-custom tbody tr:hover {
    background: var(--bg-hover);
}

.badge-stock {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-critical { background: #fecaca; color: #7f1d1d; }

@media (max-width: 992px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-boxes"></i> Stock Report</h1>
            <p>Last updated: <?= date('d M Y'); ?></p>
        </div>
        <div class="export-dropdown">
            <button class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= BASE_URL; ?>/report/stock?export=csv">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Download CSV
                </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-content">
            <h3><?= count($products); ?></h3>
            <p>Total Products</p>
        </div>
        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
    </div>
    <div class="stat-card red">
        <div class="stat-content">
            <h3><?= count($lowStock); ?></h3>
            <p>Low Stock Items</p>
        </div>
        <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-content">
            <h3><?= DEFAULT_CURRENCY; ?><?= number_format(array_sum(array_map(fn($p) => $p['stock_quantity'] * $p['sell_price'], $products)), 0); ?></h3>
            <p>Total Stock Value</p>
        </div>
        <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
    </div>
</div>

<?php if (!empty($lowStock)): ?>
<div class="alert-modern">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>
        <strong><?= count($lowStock); ?> items</strong> are running low on stock and need attention.
    </div>
</div>
<?php endif; ?>

<?php if (!empty($lowStock)): ?>
<div class="card-modern">
    <div class="card-header">
        <h5><i class="bi bi-exclamation-triangle"></i> Low Stock Alert</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Threshold</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStock as $index => $product): ?>
                    <tr>
                        <td><span style="background: var(--bg-surface-alt); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;"><?= $index + 1; ?></span></td>
                        <td><strong><?= $product['product_name']; ?></strong></td>
                        <td><span class="badge-stock badge-danger"><?= $product['stock_quantity']; ?></span></td>
                        <td><?= $product['low_stock_threshold']; ?></td>
                        <td>
                            <?php if ($product['stock_quantity'] == 0): ?>
                                <span class="badge-stock badge-critical">Out of Stock</span>
                            <?php elseif ($product['stock_quantity'] < $product['low_stock_threshold'] / 2): ?>
                                <span class="badge-stock badge-critical">Critical</span>
                            <?php else: ?>
                                <span class="badge-stock badge-warning">Low</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card-modern">
    <div class="card-header">
        <h5><i class="bi bi-list-ul"></i> All Products Stock</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Unit</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $index => $product): 
                        $value = $product['stock_quantity'] * $product['sell_price'];
                    ?>
                    <tr>
                        <td><span style="background: var(--bg-surface-alt); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;"><?= $index + 1; ?></span></td>
                        <td><strong><?= $product['product_name']; ?></strong></td>
                        <td><?= $product['category'] ?? '-'; ?></td>
                        <td>
                            <?php if ($product['stock_quantity'] <= $product['low_stock_threshold']): ?>
                                <span class="badge-stock badge-danger"><?= $product['stock_quantity']; ?></span>
                            <?php else: ?>
                                <span class="badge-stock badge-success"><?= $product['stock_quantity']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $product['unit']; ?></td>
                        <td><strong><?= DEFAULT_CURRENCY; ?><?= number_format($value, 2); ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="<?= BASE_URL; ?>/report/index" class="btn" style="background: var(--bg-card); color: var(--text-secondary); border: 1px solid var(--border); padding: 12px 24px; border-radius: var(--radius-sm); font-weight: 600;">
        <i class="bi bi-arrow-left me-2"></i>Back to Reports
    </a>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';