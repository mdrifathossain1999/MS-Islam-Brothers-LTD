<?php 
$pageTitle = 'Daily Report';
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

.export-dropdown .dropdown-menu { min-width: 140px; }

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

.stat-card.blue::before { background: var(--primary); }
.stat-card.green::before { background: var(--success); }
.stat-card.red::before { background: var(--danger); }
.stat-card.cyan::before { background: var(--info); }

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

.stat-card.blue .stat-icon { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: var(--primary); }
.stat-card.green .stat-icon { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: var(--success); }
.stat-card.red .stat-icon { background: linear-gradient(135deg, #fee2e2, #fecaca); color: var(--danger); }
.stat-card.cyan .stat-icon { background: linear-gradient(135deg, #cffafe, #a5f3fc); color: var(--info); }

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
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

.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 16px 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}

.table-custom td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}

.table-custom tbody tr:hover {
    background: var(--bg-hover);
}

.badge-modern {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.btn-action {
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-view {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
}

.btn-view:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
}

.btn-print {
    background: var(--bg-surface-alt);
    color: var(--text-secondary);
    border: 1px solid var(--border);
}

.btn-print:hover {
    background: var(--info);
    color: white;
    border-color: var(--info);
}

@media (max-width: 992px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-calendar-day"></i> Daily Sales Report</h1>
            <p><?= date('d M Y', strtotime($date)); ?></p>
        </div>
        <div class="d-flex gap-3 align-items-center flex-wrap">
            <form method="GET" class="d-flex gap-2">
                <input type="date" class="form-control" name="date" value="<?= $date; ?>" style="padding: 10px 15px; border-radius: var(--radius-sm);">
                <button type="submit" class="btn" style="background: white; color: var(--primary); font-weight: 600;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <div class="export-dropdown">
                <button class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= BASE_URL; ?>/report/daily?date=<?= $date; ?>&export=csv">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Download CSV
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-content">
            <h3><?= DEFAULT_CURRENCY; ?><?= number_format($totalSales, 0); ?></h3>
            <p>Total Sales</p>
        </div>
        <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
    </div>
    <div class="stat-card green">
        <div class="stat-content">
            <h3><?= DEFAULT_CURRENCY; ?><?= number_format($totalPaid, 0); ?></h3>
            <p>Total Paid</p>
        </div>
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
    </div>
    <div class="stat-card red">
        <div class="stat-content">
            <h3><?= DEFAULT_CURRENCY; ?><?= number_format($totalDue, 0); ?></h3>
            <p>Total Due</p>
        </div>
        <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-content">
            <h3><?= count($sales); ?></h3>
            <p>Transactions</p>
        </div>
        <div class="stat-icon"><i class="bi bi-receipt"></i></div>
    </div>
</div>

<div class="card-modern">
    <div class="card-header">
        <h5><i class="bi bi-list-ul"></i> Sales Details</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted); opacity: 0.5;"></i>
                            <p class="mt-3 text-muted">No sales found for this date</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($sales as $index => $sale): 
                        $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                    ?>
                    <tr>
                        <td><span style="background: var(--bg-surface-alt); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;"><?= $index + 1; ?></span></td>
                        <td><strong style="color: var(--primary);"><?= $sale['invoice_number']; ?></strong></td>
                        <td><?= $sale['customer_name'] ?? 'Walk-in'; ?></td>
                        <td><strong><?= DEFAULT_CURRENCY; ?><?= number_format($sale['total_amount'], 2); ?></strong></td>
                        <td style="color: var(--success);"><?= DEFAULT_CURRENCY; ?><?= number_format($sale['paid_amount'], 2); ?></td>
                        <td>
                            <?php if ($due > 0): ?>
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;"><?= DEFAULT_CURRENCY; ?><?= number_format($due, 2); ?></span>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-modern" style="background: var(--bg-surface-alt); color: var(--text-secondary);"><?= ucfirst($sale['payment_method']); ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL; ?>/invoice/view/<?= $sale['id']; ?>" class="btn-action btn-view">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= BASE_URL; ?>/invoice/print/<?= $sale['id']; ?>" class="btn-action btn-print" target="_blank">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
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