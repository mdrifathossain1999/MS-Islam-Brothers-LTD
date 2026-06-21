<?php 
$pageTitle = 'Customer Profile - ' . ($customer['customer_name'] ?? 'Unknown');
$currentPage = 'customers';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 15px 20px;
    border-radius: var(--radius-lg);
    color: white;
    margin-bottom: 20px;
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
}

.page-header h3 {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.page-header p {
    margin: 6px 0 0 0;
    opacity: 0.9;
    font-size: 13px;
}

.page-header p span {
    margin-right: 15px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-action {
    background: var(--bg-surface);
    border: 1px solid var(--border);
    color: var(--text-primary);
    padding: 8px 14px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 13px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-action:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
    color: white;
    background: var(--primary);
}
.btn-action-light {
    background: white;
    color: var(--primary);
}
.btn-action-light:hover {
    background: var(--primary);
}
.btn-success {
    background: linear-gradient(135deg, var(--success), #34d399);
    border: none;
    color: white;
}
.btn-primary-gradient {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    color: white;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 16px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    text-align: center;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}
.stat-card.total { border-top: 3px solid var(--primary); }
.stat-card.paid { border-top: 3px solid var(--success); }
.stat-card.due { border-top: 3px solid var(--danger); }

.stat-card h6 {
    font-size: 11px;
    color: var(--text-muted);
    margin: 0 0 6px 0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-card h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
}
.stat-card h3.text-primary { color: var(--primary); }
.stat-card h3.text-success { color: var(--success); }
.stat-card h3.text-danger { color: var(--danger); }

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-modern .card-header {
    background: var(--bg-surface-alt);
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-modern .card-header h5 {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-modern .card-header h5 i { color: var(--primary); }

.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 12px 16px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}
.table-custom td {
    padding: 10px 16px;
    font-size: 13px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}
.table-custom tbody tr { transition: all 0.2s ease; }
.table-custom tbody tr:hover { background: var(--bg-hover); }

.badge-status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
.badge-danger { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; }
.badge-warning { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
.badge-primary { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4f46e5; }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo BASE_URL; ?>/customer/index" class="btn-action btn-action-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <div>
                <h3 style="margin: 0; font-size: 24px; font-weight: 800;">
                    <i class="bi bi-person-circle me-2"></i><?php echo htmlspecialchars($customer['customer_name']); ?>
                </h3>
                <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">
                    <span class="me-3"><i class="bi bi-telephone me-1"></i><?php echo $customer['phone'] ?: 'No phone'; ?></span>
                    <?php if (!empty($customer['email'])): ?>
                        <span class="me-3"><i class="bi bi-envelope me-1"></i><?php echo $customer['email']; ?></span>
                    <?php endif; ?>
                    <?php if (!empty($customer['address'])): ?>
                        <span><i class="bi bi-geo-alt me-1"></i><?php echo $customer['address']; ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/customer/edit/<?php echo $customer['id']; ?>" class="btn-action">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="<?php echo BASE_URL; ?>/pos" class="btn-action btn-success">
                <i class="bi bi-cart-plus"></i> New Sale
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card total">
            <h6><i class="bi bi-cart-fill me-2"></i>Total Purchase</h6>
            <h3 class="text-primary"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(floatval($customer['total_purchases']), 0); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card paid">
            <h6><i class="bi bi-check-circle-fill me-2"></i>Total Paid</h6>
            <h3 class="text-success"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(floatval($customer['paid_amount']), 0); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card due">
            <h6><i class="bi bi-clock-fill me-2"></i>Total Due</h6>
            <h3 class="text-danger"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_due, 0); ?></h3>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="card-header">
        <h5><i class="bi bi-clock-history"></i> Purchase History</h5>
        <?php if ($total_due > 0): ?>
        <a href="<?php echo BASE_URL; ?>/customer/receivePayment/<?php echo $customer['id']; ?>" class="btn-action btn-primary-gradient" style="padding: 8px 16px; font-size: 13px;">
            <i class="bi bi-cash me-1"></i> Receive Payment
        </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($sales) > 0): ?>
                    <?php foreach ($sales as $sale): 
                        $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                    ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" style="color: var(--primary); font-weight: 700; text-decoration: none;">
                                <?php echo $sale['invoice_number']; ?>
                            </a>
                        </td>
                        <td class="text-end fw-bold"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 0); ?></td>
                        <td class="text-end" style="color: var(--success);"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['paid_amount'], 0); ?></td>
                        <td class="text-end <?php echo $due > 0 ? 'text-danger fw-bold' : ''; ?>" style="<?php echo $due > 0 ? 'color: var(--danger);' : 'color: var(--success);'; ?>">
                            <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($due, 0); ?>
                        </td>
                        <td>
                            <?php 
                            $method = ucfirst($sale['payment_method']);
                            if ($sale['payment_method'] === 'mobile' && !empty($sale['mobile_type'])) {
                                $method = 'Mobile (' . ucfirst($sale['mobile_type']) . ')';
                            }
                            ?>
                            <span class="badge-status badge-primary"><?php echo $method; ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: var(--text-muted);">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No purchase history found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card-modern mt-4">
    <div class="card-header">
        <h5><i class="bi bi-cash-stack"></i> Payment History</h5>
    </div>
    <div class="card-body p-0">
        <table class="table-custom">
            <thead>
                <tr>
                    <th><i class="bi bi-calendar me-1"></i>Date</th>
                    <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Amount</th>
                    <th><i class="bi bi-payment-method me-1"></i>Method</th>
                    <th><i class="bi bi-person me-1"></i>Received By</th>
                    <th><i class="bi bi-card-text me-1"></i>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payment_history)): ?>
                    <?php foreach ($payment_history as $payment): ?>
                    <tr>
                        <td>
                            <?php echo date('M d, Y', strtotime($payment['created_at'])); ?>
                            <span style="color: var(--text-muted); font-size: 12px; display: block;"><?php echo date('h:i A', strtotime($payment['created_at'])); ?></span>
                        </td>
                        <td class="text-end">
                            <span class="badge-status badge-success">
                                <i class="bi bi-plus-circle me-1"></i><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($payment['amount'], 2); ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $methodIcons = [
                                'cash' => 'bi-cash',
                                'card' => 'bi-credit-card',
                                'mobile' => 'bi-phone',
                                'bank' => 'bi-bank'
                            ];
                            $method = ucfirst($payment['payment_method']);
                            $icon = $methodIcons[$payment['payment_method']] ?? 'bi-cash';
                            ?>
                            <span class="badge-status badge-warning">
                                <i class="bi <?php echo $icon; ?> me-1"></i><?php echo $method; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($payment['user_name'] ?? 'System'); ?></td>
                        <td>
                            <?php echo !empty($payment['notes']) ? htmlspecialchars($payment['notes']) : '<span style="color: var(--text-muted);">-</span>'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                            <i class="bi bi-cash-stack" style="font-size: 1.5rem;"></i>
                            <p class="mt-2 mb-0">No payment history found</p>
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