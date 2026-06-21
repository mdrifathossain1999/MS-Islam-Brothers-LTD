<?php 
$pageTitle = 'Admin Dashboard';
$currentPage = 'admin';
ob_start();
?>

<style>
:root {
    --admin-primary: #6366f1;
    --admin-primary-dark: #4f46e5;
    --admin-primary-light: #818cf8;
    --admin-success: #10b981;
    --admin-warning: #f59e0b;
    --admin-danger: #ef4444;
    --admin-bg: #f8fafc;
    --admin-card: #ffffff;
    --admin-text: #1e293b;
    --admin-text-muted: #64748b;
    --admin-border: #e2e8f0;
}
body.dark-mode {
    --admin-bg: #0f172a;
    --admin-card: #1e293b;
    --admin-text: #f1f5f9;
    --admin-text-muted: #94a3b8;
    --admin-border: #334155;
}

.admin-hero {
    background: linear-gradient(135deg, var(--admin-primary) 0%, #4f46e5 50%, #4338ca 100%);
    padding: 32px 36px;
    border-radius: 20px;
    color: white;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.admin-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    pointer-events: none;
}
.admin-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: 20%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.03);
    border-radius: 50%;
    pointer-events: none;
}
.admin-hero h1 {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 1;
}
.admin-hero h1 i {
    background: rgba(255,255,255,0.2);
    padding: 10px;
    border-radius: 12px;
    font-size: 1.5rem;
}
.admin-hero .hero-date {
    font-size: 13px;
    opacity: 0.85;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    position: relative;
    z-index: 1;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}
.stat-card {
    background: var(--admin-card);
    border-radius: 16px;
    padding: 22px 24px;
    border: 1px solid var(--admin-border);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
    border-radius: 0 4px 4px 0;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}
.stat-card.purple::before { background: linear-gradient(180deg, #6366f1, #818cf8); }
.stat-card.green::before { background: linear-gradient(180deg, #10b981, #34d399); }
.stat-card.blue::before { background: linear-gradient(180deg, #3b82f6, #60a5fa); }
.stat-card.orange::before { background: linear-gradient(180deg, #f59e0b, #fbbf24); }

.stat-card .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.stat-card.purple .stat-icon { background: rgba(99, 102, 241, 0.12); color: #818cf8; }
.stat-card.green .stat-icon { background: rgba(16, 185, 129, 0.12); color: #34d399; }
.stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }
.stat-card.orange .stat-icon { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }

.stat-card .stat-info h3 {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    color: var(--admin-text);
    line-height: 1.1;
}
.stat-card .stat-info p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--admin-text-muted);
    font-weight: 500;
}

/* Management Grid */
.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}
.mgmt-card {
    background: var(--admin-card);
    border-radius: 14px;
    padding: 20px;
    border: 1px solid var(--admin-border);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
}
.mgmt-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    border-color: var(--admin-primary);
}
.mgmt-card .mgmt-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.mgmt-card .mgmt-info h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--admin-text);
}
.mgmt-card .mgmt-info p {
    margin: 3px 0 0;
    font-size: 11px;
    color: var(--admin-text-muted);
}
.mgmt-card .mgmt-arrow {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--admin-text-muted);
    font-size: 12px;
    opacity: 0;
    transition: all 0.3s;
}
.mgmt-card:hover .mgmt-arrow {
    opacity: 1;
    right: 12px;
}

/* Bottom Row */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.info-panel {
    background: var(--admin-card);
    border-radius: 16px;
    border: 1px solid var(--admin-border);
    overflow: hidden;
}
.info-panel .panel-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--admin-border);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 700;
    color: var(--admin-text);
}
.info-panel .panel-header i {
    color: var(--admin-primary);
    font-size: 16px;
}
.info-panel .panel-body {
    padding: 8px 0;
}
.info-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    transition: background 0.2s;
}
.info-item:hover {
    background: var(--admin-bg);
}
.info-item .item-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.info-item .item-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
}
.info-item .item-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
}
.info-item .item-sub {
    font-size: 11px;
    color: var(--admin-text-muted);
}
.info-item .item-badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 600;
}
.info-item .item-badge.admin { background: rgba(99, 102, 241, 0.1); color: #818cf8; }
.info-item .item-badge.cashier { background: rgba(16, 185, 129, 0.1); color: #34d399; }
.info-item .item-value {
    font-size: 13px;
    font-weight: 700;
    color: var(--admin-success);
}
.info-item .item-value.danger {
    color: var(--admin-danger);
}

/* Stock items */
.stock-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    transition: background 0.2s;
}
.stock-item:hover {
    background: var(--admin-bg);
}
.stock-item .stock-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.stock-item .stock-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.stock-item .stock-dot.critical { background: var(--admin-danger); }
.stock-item .stock-dot.warning { background: var(--admin-warning); }
.stock-item .stock-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
}
.stock-item .stock-qty {
    font-size: 13px;
    font-weight: 700;
    color: var(--admin-danger);
}

/* Section title */
.section-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--admin-text);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-title i {
    color: var(--admin-primary);
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .admin-hero { padding: 24px 20px; }
    .admin-hero h1 { font-size: 1.35rem; }
    .stats-grid { grid-template-columns: 1fr; }
    .management-grid { grid-template-columns: repeat(2, 1fr); }
    .stat-card .stat-info h3 { font-size: 24px; }
}
@media (max-width: 480px) {
    .management-grid { grid-template-columns: 1fr; }
}
</style>

<div class="admin-hero">
    <h1><i class="bi bi-gear-fill"></i> Admin Dashboard</h1>
    <div class="hero-date"><i class="bi bi-calendar3"></i> <?php echo date('l, d F Y'); ?></div>
</div>

<div class="stats-grid">
    <div class="stat-card purple">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalUsers); ?></h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalProducts); ?></h3>
            <p>Total Products</p>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="bi bi-cart-check-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($totalSales); ?></h3>
            <p>Total Sales</p>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($totalRevenue ?? 0, 0); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<div class="section-title"><i class="bi bi-grid-3x3-gap-fill"></i> Management</div>
<div class="management-grid">
    <a href="<?php echo BASE_URL; ?>/admin/users" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(99,102,241,0.12);color:#818cf8;"><i class="bi bi-people"></i></div>
        <div class="mgmt-info">
            <h4>Users</h4>
            <p>Manage accounts & roles</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/settings" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(16,185,129,0.12);color:#34d399;"><i class="bi bi-sliders"></i></div>
        <div class="mgmt-info">
            <h4>Settings</h4>
            <p>System configuration</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/categories" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(20,184,166,0.12);color:#2dd4bf;"><i class="bi bi-tags"></i></div>
        <div class="mgmt-info">
            <h4>Categories</h4>
            <p>Product categories</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/units" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(6,182,212,0.12);color:#22d3ee;"><i class="bi bi-rulers"></i></div>
        <div class="mgmt-info">
            <h4>Units</h4>
            <p>Measurements & sizes</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/customerTypes" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(168,85,247,0.12);color:#a78bfa;"><i class="bi bi-person-badge"></i></div>
        <div class="mgmt-info">
            <h4>Customer Types</h4>
            <p>Customer classification</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/summaryCards" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(59,130,246,0.12);color:#60a5fa;"><i class="bi bi-card-text"></i></div>
        <div class="mgmt-info">
            <h4>Summary Cards</h4>
            <p>Dashboard card manager</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/productImportExport" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(132,204,22,0.12);color:#a3e635;"><i class="bi bi-arrow-left-right"></i></div>
        <div class="mgmt-info">
            <h4>Import/Export</h4>
            <p>Bulk product operations</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/uploads" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(139,92,246,0.12);color:#a78bfa;"><i class="bi bi-cloud-upload"></i></div>
        <div class="mgmt-info">
            <h4>Uploads</h4>
            <p>File & media manager</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/categoryUnit" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(244,63,94,0.12);color:#fb7185;"><i class="bi bi-diagram-3"></i></div>
        <div class="mgmt-info">
            <h4>Category Units</h4>
            <p>Category-to-unit mapping</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/activityLogs" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(249,115,22,0.12);color:#fb923c;"><i class="bi bi-activity"></i></div>
        <div class="mgmt-info">
            <h4>Activity Logs</h4>
            <p>User activity audit</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
    <a href="<?php echo BASE_URL; ?>/admin/database" class="mgmt-card">
        <div class="mgmt-icon" style="background: rgba(236,72,153,0.12);color:#f472b6;"><i class="bi bi-hdd"></i></div>
        <div class="mgmt-info">
            <h4>Database</h4>
            <p>Backup & restore</p>
        </div>
        <div class="mgmt-arrow"><i class="bi bi-chevron-right"></i></div>
    </a>
</div>

<div class="bottom-row">
    <div class="info-panel">
        <div class="panel-header"><i class="bi bi-people"></i> Recent Users</div>
        <div class="panel-body">
            <?php if (!empty($recent_users)): ?>
                <?php foreach ($recent_users as $u): ?>
                <div class="info-item">
                    <div class="item-left">
                        <div class="item-avatar"><?php echo strtoupper(substr($u['full_name'] ?? $u['username'], 0, 1)); ?></div>
                        <div>
                            <div class="item-name"><?php echo htmlspecialchars($u['full_name'] ?? $u['username']); ?></div>
                            <div class="item-sub"><?php echo htmlspecialchars($u['email'] ?? ''); ?></div>
                        </div>
                    </div>
                    <span class="item-badge <?php echo $u['role'] ?? 'cashier'; ?>"><?php echo ucfirst($u['role'] ?? 'cashier'); ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="info-item"><div class="item-name" style="color:var(--admin-text-muted);">No users found</div></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="info-panel">
        <div class="panel-header"><i class="bi bi-exclamation-triangle"></i> Low Stock Alerts</div>
        <div class="panel-body">
            <?php if (!empty($low_stock_products)): ?>
                <?php foreach ($low_stock_products as $p): ?>
                <div class="stock-item">
                    <div class="stock-left">
                        <div class="stock-dot <?php echo $p['stock_quantity'] <= 0 ? 'critical' : 'warning'; ?>"></div>
                        <span class="stock-name"><?php echo htmlspecialchars($p['product_name']); ?></span>
                    </div>
                    <span class="stock-qty"><?php echo (int)$p['stock_quantity']; ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="stock-item"><span class="stock-name" style="color:var(--admin-text-muted);">All products well stocked</span></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
