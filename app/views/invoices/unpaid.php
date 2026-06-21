<?php 
$pageTitle = 'Unpaid Invoices';
$currentPage = 'invoices';
ob_start();
?>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stats-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 24px;
    border: 1px solid var(--border);
    transition: var(--transition);
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stats-card h4 {
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    margin: 0 0 8px 0;
}

.stats-card .number {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}

.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 14px 16px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}

.table-custom td {
    padding: 14px 16px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}

.table-custom tbody tr:hover {
    background: var(--bg-hover);
}

.btn-action {
    background: var(--bg-surface-alt);
    color: var(--text-primary);
    padding: 8px 16px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    text-decoration: none;
    border: 1px solid var(--border);
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-action:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.badge-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.badge-partial {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.badge-paid {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="page-header">
    <div>
        <h1 style="margin: 0; font-size: 24px; font-weight: 800;">
            <i class="bi bi-exclamation-circle me-3"></i>Unpaid Invoices
        </h1>
        <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">All invoices pending payment</p>
    </div>
    <a href="<?php echo BASE_URL; ?>/invoice/index" class="btn-action" style="background: white; color: var(--warning);">
        <i class="bi bi-arrow-left"></i> Back to All
    </a>
</div>

<?php 
$invoices = $sales ?? [];
$totalUnpaid = 0;
$countUnpaid = count($invoices);
foreach ($invoices as $inv) {
    $totalUnpaid += floatval($inv['total_amount']) - floatval($inv['paid_amount']);
}
?>

<div class="stats-grid">
    <div class="stats-card">
        <h4><i class="bi bi-receipt me-2"></i>Total Unpaid</h4>
        <div class="number"><?php echo DEFAULT_CURRENCY.number_format($totalUnpaid, 2); ?></div>
    </div>
    <div class="stats-card">
        <h4><i class="bi bi-clock me-2"></i>Invoices</h4>
        <div class="number"><?php echo $countUnpaid; ?></div>
    </div>
    <div class="stats-card">
        <h4><i class="bi bi-calendar me-2"></i>This Month</h4>
        <div class="number"><?php echo date('M Y'); ?></div>
    </div>
    <div class="stats-card">
        <h4><i class="bi bi-graph-up me-2"></i>Status</h4>
        <div class="number text-warning">Pending</div>
    </div>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <h5><i class="bi bi-list-ul"></i> Unpaid Invoice List</h5>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $sale): ?>
                <?php $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']); ?>
                <tr>
                    <td><strong><?php echo $sale['invoice_number']; ?></strong></td>
                    <td><?php echo $sale['customer_name'] ?? 'Walk-in'; ?></td>
                    <td><?php echo date('d M Y', strtotime($sale['sale_date'])); ?></td>
                    <td><strong><?php echo DEFAULT_CURRENCY.number_format($sale['total_amount'], 2); ?></strong></td>
                    <td><?php echo DEFAULT_CURRENCY.number_format($sale['paid_amount'], 2); ?></td>
                    <td><strong class="text-warning"><?php echo DEFAULT_CURRENCY.number_format($due, 2); ?></strong></td>
                    <td>
                        <?php if ($due > 0): ?>
                            <?php if ($sale['paid_amount'] > 0): ?>
                                <span class="badge-status badge-partial">Partial</span>
                            <?php else: ?>
                                <span class="badge-status badge-pending">Due</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge-status badge-paid">Paid</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" class="btn-action">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5" style="color: var(--text-muted);">
                        <i class="bi bi-check-circle" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
                        No unpaid invoices found!
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';