<?php 
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
ob_start();
?>

<style>
.dashboard { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }

/* Header */
.dash-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 28px 32px;
    border-radius: 20px;
    color: white;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.dash-header::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 350px; height: 350px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    pointer-events: none;
}
.dash-header-left { position: relative; z-index: 1; }
.dash-header-left h1 {
    font-size: 1.6rem; font-weight: 800; margin: 0;
    display: flex; align-items: center; gap: 10px;
}
.dash-header-left p { margin: 4px 0 0; opacity: 0.85; font-size: 14px; }
.dash-header-right { text-align: right; position: relative; z-index: 1; }
.dash-header-right .dash-date { font-size: 13px; opacity: 0.85; }
.dash-header-right .dash-stats { font-size: 12px; opacity: 0.75; margin-top: 2px; }

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
.summary-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid var(--border);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    overflow: hidden;
}
.summary-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
}
.summary-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.summary-card.green::before { background: linear-gradient(90deg, #22c55e, #4ade80); }
.summary-card.purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
.summary-card.orange::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.summary-card.red::before { background: linear-gradient(90deg, #ef4444, #f87171); }
.summary-card.cyan::before { background: linear-gradient(90deg, #06b6d4, #22d3ee); }
.summary-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }

.summary-card .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.summary-card .card-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
}
.summary-card.blue .card-icon { background: rgba(59,130,246,0.12); color: #60a5fa; }
.summary-card.green .card-icon { background: rgba(34,197,94,0.12); color: #4ade80; }
.summary-card.purple .card-icon { background: rgba(139,92,246,0.12); color: #a78bfa; }
.summary-card.orange .card-icon { background: rgba(245,158,11,0.12); color: #fbbf24; }
.summary-card.red .card-icon { background: rgba(239,68,68,0.12); color: #f87171; }
.summary-card.cyan .card-icon { background: rgba(6,182,212,0.12); color: #22d3ee; }

.card-value { font-size: 22px; font-weight: 800; color: var(--text-primary); line-height: 1.1; }
.card-label { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
.card-growth {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 10px; font-weight: 600;
    padding: 3px 8px; border-radius: 20px; margin-top: 8px;
}
.card-growth.up { background: var(--success); color: #fff; }
.card-growth.down { background: var(--danger); color: #fff; }

/* Charts Row */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    margin-bottom: 28px;
}
.chart-box {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--border);
}
.chart-box .ch-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 16px; padding-bottom: 14px;
    border-bottom: 1px solid var(--border);
}
.chart-box .ch-header h3 { font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 8px; }
.ch-box-body { min-height: 260px; }

/* Overview panel */
.overview-panel .stat-list { padding: 0; }
.overview-panel .stat-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0;
}
.overview-panel .stat-row + .stat-row { border-top: 1px solid var(--border); }
.overview-panel .stat-left { display: flex; align-items: center; gap: 10px; }
.overview-panel .stat-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.overview-panel .stat-dot.green { background: #22c55e; }
.overview-panel .stat-dot.red { background: #ef4444; }
.overview-panel .stat-dot.purple { background: #8b5cf6; }
.overview-panel .stat-dot.orange { background: #f59e0b; }
.overview-panel .stat-label { font-size: 13px; color: var(--text-secondary); }
.overview-panel .stat-val { font-size: 14px; font-weight: 700; color: var(--text-primary); }
.overview-panel .stat-val.text-success { color: var(--success); }
.overview-panel .stat-val.text-danger { color: var(--danger); }

/* Bottom grid */
.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 28px;
}
.info-box {
    background: var(--bg-card);
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
}
.info-box .ib-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between; align-items: center;
}
.info-box .ib-header h3 { font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 8px; }
.info-box .ib-header .badge-count {
    background: var(--danger); color: white;
    font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
}
.info-box .ib-body { padding: 8px 0; }
.stock-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 20px; transition: background 0.2s;
}
.stock-row:hover { background: var(--bg-surface-alt); }
.stock-row .sr-left { display: flex; align-items: center; gap: 10px; }
.stock-row .sr-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.stock-row .sr-dot.critical { background: var(--danger); }
.stock-row .sr-dot.warning { background: var(--warning); }
.stock-row .sr-name { font-size: 13px; font-weight: 500; color: var(--text-primary); }
.stock-row .sr-qty { font-size: 13px; font-weight: 700; color: var(--danger); }

/* Recent Sales */
.sales-card {
    background: var(--bg-card);
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
}
.sales-card .sc-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
}
.sales-card .sc-header h3 { font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 8px; }
.sales-card .sc-header a { font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 500; }
.sales-card .sc-header a:hover { text-decoration: underline; }
.sales-card .table { margin: 0; }
.sales-card .table thead th {
    background: var(--bg-surface-alt);
    padding: 12px 20px; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.5px;
    color: var(--text-secondary); border: none;
}
.sales-card .table tbody td {
    padding: 14px 20px; font-size: 13px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.sales-card .table tbody tr:last-child td { border-bottom: none; }
.sales-card .table tbody tr:hover { background: var(--bg-hover); }

.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
}
.status-badge.paid { background: var(--success); color: #fff; }
.status-badge.due { background: var(--warning); color: #fff; }

/* Period buttons */
.period-group { display: flex; gap: 6px; }
.period-btn {
    padding: 5px 14px; border: 1px solid var(--border);
    border-radius: 20px; background: var(--bg-card);
    font-size: 11px; font-weight: 500;
    color: var(--text-secondary); cursor: pointer;
    transition: all 0.2s;
}
.period-btn:hover { border-color: var(--primary); color: var(--primary); }
.period-btn.active { background: var(--primary); border-color: var(--primary); color: white; }

.chart-loading {
    display: flex; align-items: center; justify-content: center;
    height: 260px; color: var(--text-secondary);
}
.chart-loading .spinner {
    width: 28px; height: 28px;
    border: 3px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Dark mode overrides */
body.dark-mode .summary-card { border-color: rgba(255,255,255,0.08); }
body.dark-mode .summary-card .card-value { color: #f1f5f9; }
body.dark-mode .summary-card .card-label { color: #94a3b8; }
body.dark-mode .card-growth.up { background: #065f46; color: #6ee7b7; }
body.dark-mode .card-growth.down { background: #7f1d1d; color: #fca5a5; }
body.dark-mode .status-badge.paid { background: #065f46; color: #6ee7b7; }
body.dark-mode .status-badge.due { background: #78350f; color: #fcd34d; }

/* Responsive */
@media (max-width: 1200px) {
    .summary-cards { grid-template-columns: repeat(3, 1fr); }
    .charts-row { grid-template-columns: 1fr; }
    .bottom-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .dash-header { flex-direction: column; text-align: center; padding: 24px 20px; }
    .dash-header-right { text-align: center; margin-top: 8px; }
    .dash-header-left h1 { font-size: 1.3rem; justify-content: center; }
    .summary-cards { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .summary-card { padding: 16px; }
    .card-value { font-size: 20px; }
}
@media (max-width: 480px) {
    .summary-cards { grid-template-columns: 1fr; }
}
</style>

<?php
$userName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User';
$todayCount = count($todaySales ?? []);
?>

<div class="dashboard">
    <div class="dash-header">
        <div class="dash-header-left">
            <h1><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</h1>
            <p><i class="bi bi-hand-wave"></i> Welcome back, <?= htmlspecialchars($userName); ?>!</p>
        </div>
        <div class="dash-header-right">
            <div class="dash-date"><i class="bi bi-calendar3"></i> <?= date('l, d F Y'); ?></div>
            <div class="dash-stats"><i class="bi bi-receipt"></i> <?= $todayCount; ?> transactions today</div>
        </div>
    </div>

    <div class="summary-cards">
        <div class="summary-card blue">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-currency-dollar"></i></div>
            </div>
            <div class="card-value"><?= DEFAULT_CURRENCY; ?><?= number_format($today_sales ?? 0, 0); ?></div>
            <div class="card-label">Today's Sales</div>
            <span class="card-growth up"><i class="bi bi-arrow-up"></i> Today</span>
        </div>
        <div class="summary-card green">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <div class="card-value"><?= DEFAULT_CURRENCY; ?><?= number_format($today_profit ?? 0, 0); ?></div>
            <div class="card-label">Today's Profit</div>
            <span class="card-growth up"><i class="bi bi-arrow-up"></i> Revenue</span>
        </div>
        <div class="summary-card purple">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-box-seam"></i></div>
            </div>
            <div class="card-value"><?= number_format($total_products ?? 0); ?></div>
            <div class="card-label">Total Products</div>
            <span class="card-growth up"><i class="bi bi-box"></i> Inventory</span>
        </div>
        <div class="summary-card orange">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-people"></i></div>
            </div>
            <div class="card-value"><?= number_format($total_customers ?? 0); ?></div>
            <div class="card-label">Total Customers</div>
            <span class="card-growth up"><i class="bi bi-person"></i> Active</span>
        </div>
        <div class="summary-card red">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
            <div class="card-value"><?= DEFAULT_CURRENCY; ?><?= number_format($today_due ?? 0, 0); ?></div>
            <div class="card-label">Due Amount</div>
            <span class="card-growth down"><i class="bi bi-arrow-down"></i> Pending</span>
        </div>
        <div class="summary-card cyan">
            <div class="card-top">
                <div class="card-icon"><i class="bi bi-calendar-event"></i></div>
            </div>
            <div class="card-value"><?= DEFAULT_CURRENCY; ?><?= number_format($this_month_sales ?? 0, 0); ?></div>
            <div class="card-label">This Month Sales</div>
            <span class="card-growth up"><i class="bi bi-calendar"></i> Monthly</span>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-box">
            <div class="ch-header">
                <h3><i class="bi bi-bar-chart-fill" style="color:var(--primary);"></i> Revenue Overview</h3>
                <div class="period-group">
                    <button class="period-btn" data-days="7" onclick="loadChartData(7)">7 Days</button>
                    <button class="period-btn active" data-days="28" onclick="loadChartData(28)">28 Days</button>
                </div>
            </div>
            <div id="revenueChart"><div class="chart-loading"><div class="spinner"></div><span>Loading chart...</span></div></div>
        </div>
        <div class="chart-box overview-panel">
            <div class="ch-header">
                <h3><i class="bi bi-pie-chart-fill" style="color:var(--primary);"></i> Overview</h3>
            </div>
            <div id="overviewChart" style="height:180px;"></div>
            <div class="stat-list" style="padding-top:12px;">
                <div class="stat-row">
                    <div class="stat-left"><span class="stat-dot green"></span><span class="stat-label">Total Profit</span></div>
                    <span class="stat-val text-success"><?= DEFAULT_CURRENCY; ?><?= number_format($total_profit_all ?? 0, 0); ?></span>
                </div>
                <div class="stat-row">
                    <div class="stat-left"><span class="stat-dot red"></span><span class="stat-label">Total Loss</span></div>
                    <span class="stat-val text-danger"><?= DEFAULT_CURRENCY; ?><?= number_format($total_loss_all ?? 0, 0); ?></span>
                </div>
                <div class="stat-row">
                    <div class="stat-left"><span class="stat-dot purple"></span><span class="stat-label">Month Sales</span></div>
                    <span class="stat-val"><?= DEFAULT_CURRENCY; ?><?= number_format($this_month_sales ?? 0, 0); ?></span>
                </div>
                <div class="stat-row">
                    <div class="stat-left"><span class="stat-dot orange"></span><span class="stat-label">Month Due</span></div>
                    <span class="stat-val"><?= DEFAULT_CURRENCY; ?><?= number_format($this_month_due ?? 0, 0); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-grid">
        <div class="info-box">
            <div class="ib-header">
                <h3><i class="bi bi-exclamation-triangle" style="color:var(--danger);"></i> Low Stock</h3>
                <span class="badge-count"><?= count($low_stock_products ?? []); ?></span>
            </div>
            <div class="ib-body">
                <?php if (!empty($low_stock_products)): ?>
                    <?php foreach (array_slice($low_stock_products, 0, 5) as $p): ?>
                    <div class="stock-row">
                        <div class="sr-left">
                            <div class="sr-dot <?= ($p['stock_quantity'] ?? 0) <= 0 ? 'critical' : 'warning'; ?>"></div>
                            <span class="sr-name"><?= htmlspecialchars($p['product_name']); ?></span>
                        </div>
                        <span class="sr-qty"><?= (int)($p['stock_quantity'] ?? 0); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:24px;color:var(--text-muted);">
                        <i class="bi bi-check-circle" style="font-size:2rem;"></i>
                        <p class="mt-2 mb-0">All stock levels are good</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-box">
            <div class="ib-header">
                <h3><i class="bi bi-receipt" style="color:var(--primary);"></i> Recent Activity</h3>
                <a href="<?= BASE_URL; ?>/invoice/index" style="font-size:12px;color:var(--primary);text-decoration:none;">View All</a>
            </div>
            <div class="ib-body">
                <?php if (!empty($recent_sales)): $count = 0; ?>
                    <?php foreach ($recent_sales as $sale): if (++$count > 5) break; ?>
                    <div class="stock-row">
                        <div class="sr-left">
                            <span class="sr-name"><?= htmlspecialchars($sale['invoice_number'] ?? '#' . $sale['id']); ?></span>
                            <span style="font-size:11px;color:var(--text-muted);"><?= date('d M', strtotime($sale['created_at'])); ?></span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:var(--text-primary);"><?= DEFAULT_CURRENCY; ?><?= number_format($sale['total_amount'] ?? 0, 0); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:24px;color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:2rem;"></i>
                        <p class="mt-2 mb-0">No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sales-card">
        <div class="sc-header">
            <h3><i class="bi bi-clock-history" style="color:var(--primary);"></i> Recent Sales</h3>
            <a href="<?= BASE_URL; ?>/invoice/index"><i class="bi bi-arrow-right"></i> View All</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_sales)): ?>
                        <?php foreach (array_slice($recent_sales, 0, 8) as $sale): 
                            $due = floatval($sale['total_amount'] ?? 0) - floatval($sale['paid_amount'] ?? 0);
                            $customerName = $sale['customer_name'] ?? 'Walk-in';
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($sale['invoice_number'] ?? '#' . $sale['id']); ?></strong></td>
                            <td><?= date('d M Y', strtotime($sale['created_at'])); ?></td>
                            <td>
                                <?php if (!empty($sale['customer_name'])): ?>
                                    <a href="<?= BASE_URL; ?>/customer/profile/<?= $sale['customer_id']; ?>" class="text-decoration-none">
                                        <span style="color:var(--primary);"><?= htmlspecialchars($customerName); ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Walk-in</span>
                                <?php endif; ?>
                            </td>
                            <td><?= DEFAULT_CURRENCY; ?><?= number_format($sale['total_amount'] ?? 0, 2); ?></td>
                            <td><?= DEFAULT_CURRENCY; ?><?= number_format($sale['paid_amount'] ?? 0, 2); ?></td>
                            <td>
                                <?php if ($due > 0): ?>
                                    <span class="text-danger fw-bold"><?= DEFAULT_CURRENCY; ?><?= number_format($due, 2); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($due > 0): ?>
                                    <span class="status-badge due"><i class="bi bi-clock"></i> Due</span>
                                <?php else: ?>
                                    <span class="status-badge paid"><i class="bi bi-check-circle"></i> Paid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size:2.5rem;color:#ccc;"></i>
                                <p class="mt-2 text-muted mb-0">No recent sales found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var revenueChart;
var overviewChart;
var overviewInitialized = false;
var allLabels = <?= $daily_labels ?? '[]'; ?>;
var allSales = <?= $daily_sales_amount ?? '[]'; ?>;
var allDue = <?= $daily_due_amount ?? '[]'; ?>;

function chartColors() {
    var d = document.body.classList.contains('dark-mode') || localStorage.getItem('darkMode') === 'true';
    return {
        text: d ? '#cbd5e1' : '#64748b',
        grid: d ? '#1e293b' : '#f1f5f9',
        legend: d ? '#94a3b8' : '#475569',
        tooltip: d ? 'dark' : 'light',
        donutVal: d ? '#f1f5f9' : '#1e293b',
        donutTotal: d ? '#94a3b8' : '#64748b'
    };
}

function fillMissingDays(salesData, dueData, totalDays) {
    var filledLabels = [];
    var filledSales = [];
    var filledDue = [];
    var today = new Date();
    var dateMap = {};
    for (var i = 0; i < allLabels.length; i++) {
        dateMap[allLabels[i]] = { sales: allSales[i], due: allDue[i] };
    }
    for (var i = totalDays - 1; i >= 0; i--) {
        var d = new Date(today);
        d.setDate(d.getDate() - i);
        var dateStr = d.toISOString().split('T')[0];
        filledLabels.push(dateStr);
        if (dateMap[dateStr]) {
            filledSales.push(Number(dateMap[dateStr].sales));
            filledDue.push(Number(dateMap[dateStr].due));
        } else {
            filledSales.push(0);
            filledDue.push(0);
        }
    }
    return { labels: filledLabels, sales: filledSales, due: filledDue };
}

function formatDateDisplay(dateStr) {
    var d = new Date(dateStr + 'T00:00:00');
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return d.getDate() + ' ' + months[d.getMonth()];
}

document.addEventListener('DOMContentLoaded', function() {
    loadChartData(28);
});

function loadChartData(days) {
    document.querySelectorAll('.period-btn').forEach(function(btn) {
        btn.classList.remove('active');
        if (parseInt(btn.getAttribute('data-days')) === days) {
            btn.classList.add('active');
        }
    });
    var data = fillMissingDays(allSales, allDue, days);
    var displayLabels = data.labels.map(function(d) { return formatDateDisplay(d); });
    renderRevenueChart(displayLabels, data.sales, data.due);
}

function renderRevenueChart(labels, salesData, dueData) {
    if (labels.length === 0) {
        labels = ['No Data']; salesData = [0]; dueData = [0];
    }
    var c = chartColors();
    var options = {
        series: [
            { name: 'Sales', data: salesData },
            { name: 'Due', data: dueData }
        ],
        chart: {
            type: 'area', height: 300,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        colors: ['#22c55e', '#ef4444'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: labels,
            labels: {
                style: { colors: c.text, fontSize: '11px', fontWeight: 500 },
                rotate: labels.length > 14 ? -45 : 0,
                hideOverlappingLabels: true
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
            tooltip: { enabled: false }
        },
        yaxis: {
            labels: {
                style: { colors: c.text, fontSize: '12px', fontWeight: 500 },
                formatter: function(val) { return '<?= DEFAULT_CURRENCY; ?>' + val.toLocaleString(); }
            }
        },
        grid: { borderColor: c.grid, strokeDashArray: 4, padding: { left: 10, right: 10 } },
        legend: {
            position: 'top', horizontalAlign: 'right',
            fontSize: '12px', fontWeight: 600,
            labels: { colors: c.legend },
            markers: { width: 10, height: 10, radius: 3 },
            itemMargin: { horizontal: 12, vertical: 0 }
        },
        tooltip: {
            theme: c.tooltip,
            style: { fontSize: '12px', fontFamily: 'Inter, sans-serif' },
            y: { formatter: function(val) { return '<?= DEFAULT_CURRENCY; ?>' + val.toLocaleString(); } }
        },
        markers: { size: 0, hover: { size: 5 } }
    };

    if (revenueChart) {
        revenueChart.updateOptions(options);
    } else {
        revenueChart = new ApexCharts(document.querySelector("#revenueChart"), options);
        revenueChart.render();
    }

    if (!overviewInitialized) {
        initOverviewChart();
        overviewInitialized = true;
    }
}

function initOverviewChart() {
    var c = chartColors();
    var profitVal = Math.max(0, <?= $total_profit_all ?? 0; ?>);
    var lossVal = Math.max(0, <?= $total_loss_all ?? 0; ?>);
    var monthSalesVal = Math.max(0, <?= $this_month_sales ?? 0; ?>);
    var monthDueVal = Math.max(0, <?= $this_month_due ?? 0; ?>);

    var series = [profitVal, lossVal, monthSalesVal, monthDueVal];
    var hasData = series.some(function(v) { return v > 0; });
    if (!hasData) series = [1, 1, 1, 1];

    var overviewOptions = {
        series: series,
        chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
        colors: ['#22c55e', '#ef4444', '#8b5cf6', '#f59e0b'],
        labels: ['Profit', 'Loss', 'Month Sales', 'Month Due'],
        dataLabels: { enabled: false },
        legend: {
            show: true, position: 'bottom', horizontalAlign: 'center',
            fontSize: '10px', fontWeight: 600,
            labels: { colors: c.legend },
            markers: { width: 8, height: 8, radius: 3 },
            itemMargin: { horizontal: 8, vertical: 4 }
        },
        stroke: { width: 0 },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: { show: false },
                        value: {
                            show: true,
                            fontSize: '13px', fontWeight: 700,
                            color: c.donutVal,
                            formatter: function(val) { return '<?= DEFAULT_CURRENCY; ?>' + Number(val).toLocaleString(); }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '11px', fontWeight: 600,
                            color: c.donutTotal,
                            formatter: function(w) {
                                return '<?= DEFAULT_CURRENCY; ?>' + w.globals.seriesTotals.reduce(function(a,b){return a+b;},0).toLocaleString();
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: { formatter: function(val) { return '<?= DEFAULT_CURRENCY; ?>' + Number(val).toLocaleString(); } }
        }
    };

    overviewChart = new ApexCharts(document.querySelector("#overviewChart"), overviewOptions);
    overviewChart.render();
}

function updateChartsTheme() {
    if (revenueChart) {
        var data = fillMissingDays(allSales, allDue, 28);
        var displayLabels = data.labels.map(function(d) { return formatDateDisplay(d); });
        var c = chartColors();
        revenueChart.updateOptions({
            xaxis: { labels: { style: { colors: c.text } } },
            yaxis: { labels: { style: { colors: c.text } } },
            grid: { borderColor: c.grid },
            legend: { labels: { colors: c.legend } },
            tooltip: { theme: c.tooltip }
        });
    }
    if (overviewChart) {
        var c = chartColors();
        overviewChart.updateOptions({
            legend: { labels: { colors: c.legend } },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            value: { color: c.donutVal },
                            total: { color: c.donutTotal }
                        }
                    }
                }
            }
        });
    }
}

var themeObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.attributeName === 'class') {
            updateChartsTheme();
        }
    });
});
themeObserver.observe(document.body, { attributes: true });
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
