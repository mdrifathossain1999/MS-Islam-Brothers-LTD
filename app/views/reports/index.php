<?php 
$pageTitle = 'Reports';
$currentPage = 'reports';
ob_start();
$today = date('Y-m-d');
$firstDayMonth = date('Y-m-01');
$lastDayMonth = date('Y-m-t');
?>

<style>
.quick-links {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.quick-link {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 13px;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.3);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    font-weight: 500;
}

.quick-link:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-2px);
}

.report-card {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: 30px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    text-align: center;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: block;
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.report-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
}

.report-card:hover::before {
    transform: scaleX(1);
}

.report-icon {
    width: 90px;
    height: 90px;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    transition: all 0.3s ease;
}

.report-card:hover .report-icon {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
}

.report-card h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.report-card p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

@media (max-width: 768px) {
    .quick-links { justify-content: center; }
    .report-card { padding: 20px; }
    .report-icon { width: 70px; height: 70px; font-size: 28px; }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-graph-up-arrow"></i> Reports</h1>
            <p>View and analyze your business data</p>
        </div>
        <div class="quick-links">
            <a href="<?php echo BASE_URL; ?>/report/daily" class="quick-link">
                <i class="bi bi-calendar-day me-1"></i> Daily Sales
            </a>
            <a href="<?php echo BASE_URL; ?>/report/monthly" class="quick-link">
                <i class="bi bi-calendar-month me-1"></i> Monthly
            </a>
            <a href="<?php echo BASE_URL; ?>/report/stock" class="quick-link">
                <i class="bi bi-boxes me-1"></i> Stock
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/daily" class="report-card">
            <div class="report-icon"><i class="bi bi-calendar-day"></i></div>
            <h5>Daily Sales Report</h5>
            <p>Today's sales and transactions</p>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/monthly" class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #ec4899, #db2777); box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);"><i class="bi bi-calendar-month"></i></div>
            <h5>Monthly Report</h5>
            <p>Sales summary for this month</p>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/customRange" class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);"><i class="bi bi-calendar-range"></i></div>
            <h5>Custom Range</h5>
            <p>Custom date range report</p>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/productSales" class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);"><i class="bi bi-box-seam"></i></div>
            <h5>Product Sales</h5>
            <p>Sales by product analysis</p>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/stock" class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);"><i class="bi bi-boxes"></i></div>
            <h5>Stock Report</h5>
            <p>Inventory and stock levels</p>
        </a>
    </div>
    <div class="col-md-4 mb-4">
        <a href="<?php echo BASE_URL; ?>/report/profit" class="report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);"><i class="bi bi-currency-dollar"></i></div>
            <h5>Profit Report</h5>
            <p>Profit and loss analysis</p>
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';