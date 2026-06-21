<?php 
$pageTitle = 'Invoice Details';
$currentPage = 'invoices';
ob_start();
?>

<style>
.btn-action {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}
.btn-action:hover { 
    background: white;
    color: var(--primary);
    transform: translateY(-2px); 
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.detail-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 20px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}

.detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}
.detail-card.total::before { background: var(--primary); }
.detail-card.paid::before { background: var(--success); }
.detail-card.due::before { background: var(--warning); }
.detail-card.change::before { background: var(--info); }

.detail-card .icon-bg {
    position: absolute;
    right: -10px;
    bottom: -10px;
    font-size: 60px;
    opacity: 0.05;
    color: var(--primary);
}

.detail-card h4 { 
    font-size: 12px; 
    color: var(--text-muted); 
    margin: 0 0 8px 0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-card h2 { 
    font-size: 24px; 
    font-weight: 800; 
    margin: 0; 
    color: var(--text-primary);
}
.detail-card h2.text-success { color: var(--success); }
.detail-card h2.text-warning { color: var(--warning); }
.detail-card h2.text-info { color: var(--info); }

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    margin-bottom: 20px;
    overflow: hidden;
}

.card-modern .card-header {
    background: linear-gradient(135deg, var(--bg-surface-alt), var(--bg-surface));
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-modern .card-header h5 {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-modern .card-header h5 i { 
    color: var(--primary);
    font-size: 18px;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 14px 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}
.table-custom td {
    padding: 14px 20px;
    font-size: 13px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}
.table-custom tbody tr:hover {
    background: linear-gradient(90deg, var(--bg-hover), transparent);
}

.badge-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.badge-success { 
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
    color: #065f46; 
}
.badge-warning { 
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
    color: #92400e; 
}
.badge-danger { 
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); 
    color: #991b1b; 
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px dashed var(--border);
    font-size: 13px;
}
.info-item:last-child { border-bottom: none; }
.info-item .label { color: var(--text-muted); }
.info-item .value { 
    color: var(--text-primary); 
    font-weight: 600;
}

.summary-box {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 20px;
    border-radius: var(--radius-lg);
    text-align: center;
    margin-bottom: 15px;
}
.summary-box h4 {
    font-size: 12px;
    opacity: 0.8;
    margin: 0 0 5px;
    text-transform: uppercase;
}
.summary-box h2 {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 14px;
}
.summary-row:last-child { border-bottom: none; }
.summary-row.total { 
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 18px 24px;
    border-radius: 12px;
    margin-top: 15px;
    font-size: 18px;
    font-weight: 700;
}
.summary-row.total .label,
.summary-row.total .value { color: white; }

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-pay {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-pay:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(5, 150, 105, 0.4);
}

@media (max-width: 768px) {
    .detail-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .detail-grid { grid-template-columns: 1fr; }
}

@media print {
    body { background: white; }
    .page-header { 
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important; 
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        box-shadow: none;
        margin: 0;
        padding: 16px 24px;
    }
    .action-buttons { display: none !important; }
    .detail-grid { grid-template-columns: repeat(3, 1fr); }
    .detail-card { 
        box-shadow: none; 
        border: 1px solid #ddd;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .sidebar, .topbar { display: none !important; }
    .main-content { margin: 0; padding: 0; }
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 style="margin: 0; font-size: 22px; font-weight: 800;">
                <i class="bi bi-receipt me-2"></i>Invoice Details
            </h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">
                Invoice #<?php echo $sale['invoice_number']; ?>
            </p>
        </div>
        <div class="action-buttons">
            <a href="javascript:void(0)" onclick="window.print()" class="btn-action" target="_blank">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/print/<?php echo $sale['id']; ?>" class="btn-action" target="_blank">
                <i class="bi bi-file-post"></i> Print View
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/index" class="btn-action">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<?php 
$due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
$paymentStatus = $due <= 0 ? 'Paid' : ($due < floatval($sale['total_amount']) ? 'Partial' : 'Due');
$statusClass = $paymentStatus == 'Paid' ? 'badge-success' : ($paymentStatus == 'Partial' ? 'badge-warning' : 'badge-danger');
?>

<div class="detail-grid">
    <div class="detail-card total">
        <i class="bi bi-currency-dollar icon-bg"></i>
        <h4><i class="bi bi-cash me-2"></i>Total Amount</h4>
        <h2><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 2); ?></h2>
    </div>
    <div class="detail-card paid">
        <i class="bi bi-check-circle icon-bg"></i>
        <h4><i class="bi bi-check2-circle me-2"></i>Paid Amount</h4>
        <h2 class="text-success"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['paid_amount'], 2); ?></h2>
    </div>
    <div class="detail-card due">
        <i class="bi bi-clock icon-bg"></i>
        <h4><i class="bi bi-hourglass-split me-2"></i>Due Amount</h4>
        <h2 class="text-warning"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(max(0, $due), 2); ?></h2>
    </div>
    <div class="detail-card change">
        <i class="bi bi-arrow-left-circle icon-bg"></i>
        <h4><i class="bi bi-arrow-left me-2"></i>Change</h4>
        <h2 class="text-info"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['change_amount'], 2); ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-box-seam"></i> Items (<?php echo is_array($items) ? count($items) : 0; ?>)</h5>
            </div>
            <div class="card-body p-0">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($items as $item): ?>
                        <tr>
                            <td><span style="background: var(--bg-surface-alt); border-radius: 6px; padding: 4px 8px; font-size: 11px; font-weight: 600;"><?php echo $i++; ?></span></td>
                            <td><strong style="color: var(--text-primary);"><?php echo $item['product_name']; ?></strong></td>
                            <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['unit_price'], 2); ?></td>
                            <td class="text-center"><span style="background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 600;"><?php echo $item['quantity']; ?></span></td>
                            <td class="text-end" style="font-weight: 700; color: var(--primary);"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['total_price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i> Invoice Info</h5>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <span class="label"><i class="bi bi-person me-2"></i>Customer</span>
                    <span class="value"><?php echo $sale['customer_name'] ?? 'Walk-in'; ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-telephone me-2"></i>Phone</span>
                    <span class="value"><?php echo $sale['customer_phone'] ?? '-'; ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-credit-card me-2"></i>Method</span>
                    <span class="value"><?php echo ucfirst($sale['payment_method'] ?? 'Cash'); ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-flag me-2"></i>Status</span>
                    <span class="value"><span class="badge-status <?php echo $statusClass; ?>"><?php echo $paymentStatus; ?></span></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-calendar me-2"></i>Date</span>
                    <span class="value"><?php echo date('d M Y', strtotime($sale['sale_date'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-clock me-2"></i>Time</span>
                    <span class="value"><?php echo $sale['sale_time'] ?? '-'; ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="bi bi-person-badge me-2"></i>Cashier</span>
                    <span class="value"><?php echo $sale['user_name'] ?? 'Admin'; ?></span>
                </div>
            </div>
        </div>

        <?php if ($due > 0): ?>
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-cash-stack"></i> Quick Payment</h5>
            </div>
            <div class="card-body text-center">
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 15px;">Due Amount</p>
                <h3 style="color: var(--warning); font-weight: 800; margin-bottom: 15px;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($due, 2); ?></h3>
                <button class="btn-pay" onclick="location.href='<?php echo BASE_URL; ?>/customer/receivePayment/<?php echo $sale['customer_id']; ?>'">
                    <i class="bi bi-cash me-2"></i>Receive Payment
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-calculator"></i> Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="summary-row">
                    <span><i class="bi bi-cart me-2"></i>Subtotal</span>
                    <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['subtotal'], 2); ?></span>
                </div>
                <div class="summary-row">
                    <span><i class="bi bi-percent me-2"></i>Discount</span>
                    <span style="color: #dc2626;">- <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['discount_amount'], 2); ?></span>
                </div>
                <div class="summary-row">
                    <span><i class="bi bi-tag me-2"></i>Tax</span>
                    <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['tax_amount'], 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span><i class="bi bi-wallet2 me-2"></i>Due Balance</span>
                    <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(max(0, $due), 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';