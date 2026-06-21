<?php 
$pageTitle = 'Invoice Details';
$currentPage = 'invoices';
ob_start();
?>

<style>
.invoice-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 20px;
    border-radius: var(--radius-lg);
    margin-bottom: 20px;
}
.invoice-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 15px;
}
.invoice-info-item label {
    font-size: 12px;
    opacity: 0.8;
}
.invoice-info-item value {
    font-size: 16px;
    font-weight: 600;
}
.status-badge-lg {
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}
.invoice-details-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin-bottom: 20px;
}
.invoice-details-card .card-header {
    background: var(--bg-surface-alt);
    border-bottom: 1px solid var(--border);
    padding: 15px 20px;
    font-weight: 600;
}
.items-table th {
    background: var(--bg-surface-alt);
    font-size: 12px;
    text-transform: uppercase;
}
.items-table td {
    font-size: 13px;
}
.amount-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.summary-box {
    padding: 15px;
    border-radius: var(--radius-sm);
    text-align: center;
}
.summary-box.total { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; }
.summary-box.paid { background: #dcfce7; color: #166534; }
.summary-box.due { background: #fee2e2; color: #991b1b; }
.summary-box.change { background: #dbeafe; color: #1e40af; }
.payment-history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid var(--border);
}
.payment-history-item:last-child {
    border-bottom: none;
}
.activity-timeline {
    position: relative;
    padding-left: 20px;
}
.activity-timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border);
}
.activity-item {
    position: relative;
    padding: 10px 0;
}
.activity-item::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 15px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--primary);
    border: 2px solid var(--bg-card);
}
.action-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-file-earmark-text-fill"></i> Invoice Details</h1>
            <p>View invoice information and manage payments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/invoice/index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="<?php echo BASE_URL; ?>/invoice/print/<?php echo $invoice['id']; ?>" class="btn btn-primary" target="_blank">
        <i class="bi bi-printer me-2"></i>Print Invoice
    </a>
    <a href="<?php echo BASE_URL; ?>/invoice/download/<?php echo $invoice['id']; ?>" class="btn btn-outline-primary">
        <i class="bi bi-download me-2"></i>Download
    </a>
    <?php if ($invoice['due_amount'] > 0): ?>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        <i class="bi bi-cash-stack me-2"></i>Add Payment
    </button>
    <?php endif; ?>
    <button class="btn btn-outline-danger" onclick="voidInvoice(<?php echo $invoice['id']; ?>)">
        <i class="bi bi-x-circle me-2"></i>Void Invoice
    </button>
</div>

<!-- Invoice Header -->
<div class="invoice-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h2 style="margin: 0; font-size: 28px;"><?php echo $invoice['invoice_number']; ?></h2>
            <p style="margin: 5px 0 0 0; opacity: 0.8;">
                <span class="status-badge-lg status-<?php echo $invoice['payment_status']; ?>">
                    <?php echo ucfirst($invoice['payment_status']); ?>
                </span>
            </p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 14px; opacity: 0.8;">Invoice Date</div>
            <div style="font-size: 20px; font-weight: 600;"><?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?></div>
        </div>
    </div>
    <div class="invoice-info-grid">
        <div class="invoice-info-item">
            <label>Customer</label>
            <div style="font-size: 16px; font-weight: 600;"><?php echo $invoice['customer_name'] ?? 'Walk-in Customer'; ?></div>
        </div>
        <div class="invoice-info-item">
            <label>Phone</label>
            <div style="font-size: 16px;"><?php echo $invoice['customer_phone'] ?? 'N/A'; ?></div>
        </div>
        <div class="invoice-info-item">
            <label>Cashier</label>
            <div style="font-size: 16px;"><?php echo $invoice['cashier_name'] ?? 'System'; ?></div>
        </div>
        <div class="invoice-info-item">
            <label>Payment Method</label>
            <div style="font-size: 16px; text-transform: capitalize;"><?php echo $invoice['payment_method']; ?></div>
        </div>
    </div>
</div>

<!-- Amount Summary -->
<div class="amount-summary mb-4">
    <div class="summary-box total">
        <div style="font-size: 12px; opacity: 0.8;">Total Amount</div>
        <div style="font-size: 28px; font-weight: 700;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['total_amount'], 2); ?></div>
    </div>
    <div class="summary-box paid">
        <div style="font-size: 12px;">Paid Amount</div>
        <div style="font-size: 28px; font-weight: 700;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['paid_amount'], 2); ?></div>
    </div>
    <div class="summary-box due">
        <div style="font-size: 12px;">Due Amount</div>
        <div style="font-size: 28px; font-weight: 700;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['due_amount'], 2); ?></div>
    </div>
    <div class="summary-box change">
        <div style="font-size: 12px;">Change Given</div>
        <div style="font-size: 28px; font-weight: 700;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['change_amount'], 2); ?></div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Invoice Items -->
        <div class="invoice-details-card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Invoice Items (<?php echo count($items ?? []); ?>)
            </div>
            <div class="card-body p-0">
                <table class="table table-hover items-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No items found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $item['item_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['unit_price'], 2); ?></td>
                            <td><strong><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['total_price'], 2); ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History -->
        <div class="invoice-details-card">
            <div class="card-header">
                <i class="bi bi-credit-card me-2"></i>Payment History (<?php echo count($payments ?? []); ?>)
            </div>
            <div class="card-body">
                <?php if (empty($payments)): ?>
                <p class="text-muted text-center py-3">No payment history</p>
                <?php else: ?>
                <?php foreach ($payments as $payment): ?>
                <div class="payment-history-item">
                    <div>
                        <strong><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($payment['amount'], 2); ?></strong>
                        <span class="text-muted ms-2">via <?php echo ucfirst($payment['payment_method']); ?></span>
                        <?php if (!empty($payment['notes'])): ?>
                        <br><small class="text-muted"><?php echo $payment['notes']; ?></small>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <div><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></div>
                        <small class="text-muted"><?php echo date('h:i A', strtotime($payment['payment_time'])); ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Invoice Summary -->
        <div class="invoice-details-card">
            <div class="card-header">
                <i class="bi bi-calculator me-2"></i>Summary
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['subtotal'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Discount</span>
                    <span class="text-danger">-<?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['discount_amount'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax</span>
                    <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['tax_amount'], 2); ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['total_amount'], 2); ?></strong>
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="invoice-details-card">
            <div class="card-header">
                <i class="bi bi-activity me-2"></i>Activity Log
            </div>
            <div class="card-body">
                <?php if (empty($activities)): ?>
                <p class="text-muted text-center py-3">No activities</p>
                <?php else: ?>
                <div class="activity-timeline">
                    <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <div style="font-size: 13px;">
                            <strong><?php echo ucfirst($activity['activity_type']); ?></strong>
                        </div>
                        <div style="font-size: 12px;" class="text-muted">
                            <?php echo $activity['description']; ?>
                        </div>
                        <div style="font-size: 11px;" class="text-muted">
                            <?php echo date('d M Y h:i A', strtotime($activity['created_at'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-stack me-2"></i>Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPaymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" value="<?php echo $invoice['due_amount']; ?>" min="1" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="mobile">Mobile Banking</option>
                            <option value="card">Card</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3" id="mobileTypeDiv" style="display: none;">
                        <label class="form-label">Mobile Type</label>
                        <select class="form-select" name="mobile_type">
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                            <option value="upay">Upay</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID (Optional)</label>
                        <input type="text" class="form-control" name="transaction_id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('[name="payment_method"]').addEventListener('change', function() {
    document.getElementById('mobileTypeDiv').style.display = this.value === 'mobile' ? 'block' : 'none';
});

document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?php echo BASE_URL; ?>/invoice/addPayment/<?php echo $invoice['id']; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment added successfully!');
            location.reload();
        } else {
            alert(data.message || 'Error adding payment');
        }
    });
});

function voidInvoice(id) {
    if (confirm('Are you sure you want to void this invoice? This action cannot be undone.')) {
        fetch('<?php echo BASE_URL; ?>/invoice/void/' + id, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Invoice voided successfully!');
                window.location.href = '<?php echo BASE_URL; ?>/invoice/index';
            } else {
                alert(data.message || 'Error voiding invoice');
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';