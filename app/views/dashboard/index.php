<?php
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
ob_start();
?>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 20px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-card .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    flex-shrink: 0;
}

.stat-card .stat-info { flex: 1; min-width: 0; }
.stat-card .stat-info h3 { 
    font-size: 24px; 
    font-weight: 800; 
    margin: 0; 
    color: var(--text-primary); 
    white-space: nowrap;
}
.stat-card .stat-info p { 
    margin: 4px 0 0 0; 
    font-size: 13px; 
    color: var(--text-secondary); 
    font-weight: 500; 
}

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
}

.card-modern .card-header {
    background: linear-gradient(135deg, var(--bg-surface-alt) 0%, var(--border-light) 100%);
    padding: 16px 20px;
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

.card-modern .card-header .header-actions {
    display: flex;
    gap: 8px;
}

.card-modern .card-header .btn-export {
    background: var(--primary);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}
.card-modern .card-header .btn-export:hover {
    background: var(--primary-dark);
}

.card-modern .card-body {
    padding: 20px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.action-btn {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 16px;
    text-align: center;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
}

.action-btn i {
    font-size: 28px;
    margin-bottom: 8px;
    display: block;
    color: var(--primary);
}

.action-btn span {
    font-weight: 600;
    font-size: 13px;
}

/* Modern Badge */
.badge-modern {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-info { background: #dbeafe; color: #1e40af; }

body.dark-mode .badge-success { background: #064e3b; color: #6ee7b7; }
body.dark-mode .badge-warning { background: #78350f; color: #fcd34d; }
body.dark-mode .badge-danger { background: #7f1d1d; color: #fca5a5; }
body.dark-mode .badge-info { background: #1e3a8a; color: #93c5fd; }

/* ============= RESPONSIVE ============= */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 992px) {
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { gap: 14px; }
    .stat-card { padding: 16px; gap: 12px; }
    .stat-card .stat-icon { width: 46px; height: 46px; font-size: 20px; }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .quick-actions { gap: 10px; }
    .action-btn { padding: 12px 10px; }
    .action-btn i { font-size: 24px; }
    .action-btn span { font-size: 12px; }
    .card-modern .card-header { flex-wrap: wrap; gap: 8px; }
    .card-modern .card-body { padding: 14px; }
    .chart-container { height: 220px !important; }
    #categoryChart { height: 220px !important; }
    .page-header p { font-size: 0.8rem; }
}
@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; gap: 10px; }
    .quick-actions { grid-template-columns: repeat(2, 1fr); gap: 8px; }
    .stat-card { padding: 14px; gap: 10px; border-radius: 12px; }
    .stat-card .stat-icon { width: 40px; height: 40px; font-size: 18px; border-radius: 10px; }
    .stat-card .stat-info h3 { font-size: 18px; }
    .stat-card .stat-info p { font-size: 12px; }
    .stat-card::before { width: 3px; }
    .card-modern .card-body { padding: 10px; }
    .card-modern .card-header { padding: 12px 14px; }
    .card-modern .card-header h5 { font-size: 14px; }
    .chart-container { height: 180px !important; }
    #categoryChart { height: 180px !important; }
    .page-header { padding: 12px 14px !important; }
    .content-area { padding: 0.8rem; }
}
</style>

<div class="page-header">
    <div>
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p>Welcome back! Here's what's happening today.</p>
    </div>
    <div class="header-actions">
        <div style="font-size: 13px; opacity: 0.8;"><?php echo date('l, F j, Y'); ?></div>
    </div>
</div>

<div class="quick-actions">
    <a href="<?php echo BASE_URL; ?>/pos" class="action-btn">
        <i class="bi bi-cart-check-fill"></i>
        <span>New Sale</span>
    </a>
    <a href="<?php echo BASE_URL; ?>/product/index" class="action-btn">
        <i class="bi bi-box-seam"></i>
        <span>Products</span>
    </a>
    <a href="<?php echo BASE_URL; ?>/customer/index" class="action-btn">
        <i class="bi bi-people"></i>
        <span>Customers</span>
    </a>
    <a href="<?php echo BASE_URL; ?>/report/index" class="action-btn">
        <i class="bi bi-graph-up"></i>
        <span>Reports</span>
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-cart-check"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($today_sales['count'] ?? 0); ?></h3>
            <p>Today's Sales</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), #059669);"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($today_sales['total'] ?? 0, 0); ?></h3>
            <p>Today's Revenue</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);"><i class="bi bi-people"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($total_customers); ?></h3>
            <p>Total Customers</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning), #d97706);"><i class="bi bi-box"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($total_products); ?></h3>
            <p>Total Products</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i> Sales Overview</h5>
                <div class="header-actions">
                    <button class="btn-export"><i class="bi bi-download"></i> Export</button>
                </div>
            </div>
            <div class="card-body">
                <div id="salesChart" class="chart-container" style="height: 280px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart"></i> Top Categories</h5>
            </div>
            <div class="card-body">
                <div id="categoryChart" style="height: 280px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-clock-history"></i> Recent Sales</h5>
                <div class="header-actions">
                    <button class="btn-export"><i class="bi bi-arrow-right"></i> View All</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_sales)): ?>
                        <?php foreach ($recent_sales as $sale): ?>
                        <tr>
                            <td><strong><?php echo $sale['invoice_number']; ?></strong></td>
                            <td><?php echo $sale['customer_name'] ?? 'Walk-in'; ?></td>
                            <td class="text-end fw-bold"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 0); ?></td>
                            <td>
                                <?php 
                                $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                                if ($due <= 0): ?>
                                <span class="badge-modern badge-success">Paid</span>
                                <?php else: ?>
                                <span class="badge-modern badge-warning">Due</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No recent sales</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-exclamation-triangle"></i> Low Stock Products</h5>
                <div class="header-actions">
                    <button class="btn-export"><i class="bi bi-arrow-right"></i> View All</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Stock</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($low_stock_products)): ?>
                        <?php foreach ($low_stock_products as $product): ?>
                        <tr>
                            <td><strong><?php echo $product['product_name']; ?></strong></td>
                            <td class="text-end text-danger fw-bold"><?php echo $product['stock_quantity']; ?></td>
                            <td class="text-end"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($product['sell_price'], 0); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No low stock products</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';