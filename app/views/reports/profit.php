<?php 
$pageTitle = 'Profit Report';
$currentPage = 'reports';
ob_start();
$monthName = date('F Y', strtotime("$year-$month-01"));
?>

<style>
.page-header { 
    margin-bottom: 20px;
    padding: 16px 20px;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.25);
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
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-md);
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

.stat-card.blue::before { background: var(--primary); }
.stat-card.green::before { background: #10b981; }
.stat-card.orange::before { background: #f59e0b; }
.stat-card.red::before { background: var(--danger); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-content h3 {
    font-size: 26px;
    font-weight: 800;
    margin: 0 0 5px;
    color: var(--text-primary);
}

.stat-content p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.05));
    color: var(--primary);
}

.stat-card.green .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));
    color: #10b981;
}

.stat-card.orange .stat-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));
    color: #f59e0b;
}

.stat-card.red .stat-icon {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
    color: var(--danger);
}

.report-table {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
}

.table-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.table-header h5 {
    margin: 0;
    font-weight: 700;
    color: var(--text-primary);
    font-size: 16px;
}

.report-table table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.report-table th {
    background: var(--bg-body);
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

.report-table tr:last-child td {
    border-bottom: none;
}

.report-table tr:hover td {
    background: rgba(99, 102, 241, 0.03);
}

.positive {
    color: #10b981;
    font-weight: 600;
}

.negative {
    color: var(--danger);
    font-weight: 600;
}

.month-selector {
    display: flex;
    gap: 10px;
    align-items: center;
}

.month-selector select {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
}

.month-selector select option {
    background: var(--bg-card);
    color: var(--text-primary);
}

@media (max-width: 992px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; }
    .table-header { flex-direction: column; align-items: flex-start; }
    .month-selector { width: 100%; }
    .month-selector select { flex: 1; }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-currency-dollar"></i> Profit Report</h1>
            <p>Profit and loss analysis for <?php echo $monthName; ?></p>
        </div>
        <form method="get" class="month-selector">
            <select name="year" onchange="this.form.submit()">
                <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
            <select name="month" onchange="this.form.submit()">
                <?php for($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?php echo $month == $m ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $m)); ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-content">
            <h3><?php echo number_format($totalSales, 2); ?></h3>
            <p>Total Sales</p>
        </div>
        <div class="stat-icon"><i class="bi bi-cart3"></i></div>
    </div>
    <div class="stat-card orange">
        <div class="stat-content">
            <h3><?php echo number_format($totalCost, 2); ?></h3>
            <p>Cost of Goods</p>
        </div>
        <div class="stat-icon"><i class="bi bi-receipt"></i></div>
    </div>
    <div class="stat-card green">
        <div class="stat-content">
            <h3><?php echo number_format($grossProfit, 2); ?></h3>
            <p>Gross Profit</p>
        </div>
        <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
    </div>
    <div class="stat-card red">
        <div class="stat-content">
            <h3><?php echo number_format($totalExpenses, 2); ?></h3>
            <p>Total Expenses</p>
        </div>
        <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    </div>
</div>

<?php if ($netProfit >= 0): ?>
<div class="alert alert-success" style="border-radius: 16px; margin-bottom: 24px; padding: 20px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border: 1px solid rgba(16, 185, 129, 0.3);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-check-circle-fill" style="font-size: 28px; color: #10b981;"></i>
        <div>
            <h5 class="mb-1" style="font-weight: 700; color: #10b981;">Net Profit: <?php echo number_format($netProfit, 2); ?></h5>
            <p class="mb-0" style="color: var(--text-muted);">Your business is profitable this month!</p>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-danger" style="border-radius: 16px; margin-bottom: 24px; padding: 20px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05)); border: 1px solid rgba(239, 68, 68, 0.3);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-exclamation-triangle-fill" style="font-size: 28px; color: var(--danger);"></i>
        <div>
            <h5 class="mb-1" style="font-weight: 700; color: var(--danger);">Net Loss: <?php echo number_format(abs($netProfit), 2); ?></h5>
            <p class="mb-0" style="color: var(--text-muted);">Your expenses exceeded your gross profit this month.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="report-table">
    <div class="table-header">
        <h5><i class="bi bi-box-seam me-2"></i>Product Profit Details</h5>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty Sold</th>
                <th>Sales Amount</th>
                <th>Cost</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($productSales)): ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">No sales data found for this period.</td>
            </tr>
            <?php else: ?>
            <?php foreach($productSales as $product): 
                $profit = $product['total_sales_amount'] - ($product['quantity_sold'] * $product['cost_price']);
            ?>
            <tr>
                <td style="font-weight: 600;"><?php echo htmlspecialchars($product['product_name']); ?></td>
                <td><?php echo number_format($product['quantity_sold']); ?></td>
                <td><?php echo number_format($product['total_sales_amount'], 2); ?></td>
                <td><?php echo number_format($product['quantity_sold'] * $product['cost_price'], 2); ?></td>
                <td class="<?php echo $profit >= 0 ? 'positive' : 'negative'; ?>">
                    <?php echo number_format($profit, 2); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';