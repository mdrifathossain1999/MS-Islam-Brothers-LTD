<?php 
$pageTitle = 'Receive Payment - ' . ($customer['customer_name'] ?? 'Unknown');
$currentPage = 'customers';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--success), #059669);
    padding: 25px 30px;
    border-radius: var(--radius-lg);
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
}

.btn-action {
    background: var(--bg-surface);
    border: none;
    color: var(--text-primary);
    padding: 12px 24px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-action:hover { 
    background: var(--text-primary); 
    color: white;
    transform: translateY(-2px); 
}
.btn-success {
    background: linear-gradient(135deg, var(--success), var(--success-light));
    color: white;
    border: none;
}
.btn-success:hover { 
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
}

.form-group { margin-bottom: 20px; }
.form-group label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 10px;
    display: block;
}
.form-group .form-control, .form-group .form-select {
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    border: 2px solid var(--border);
    font-size: 15px;
    transition: all 0.3s ease;
    background: var(--bg-surface);
    color: var(--text-primary);
}
.form-group .form-control:focus, .form-group .form-select:focus {
    border-color: var(--success);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    outline: none;
}

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
}
.card-modern .card-body { padding: 30px; }

.alert-box {
    padding: 16px 20px;
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.alert-success { 
    background: linear-gradient(135deg, #d1fae5, #a7f3d0); 
    color: #065f46; 
    border: 1px solid var(--success); 
}

.input-with-icon {
    display: flex;
    align-items: center;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    transition: all 0.3s ease;
}
.input-with-icon:focus-within {
    border-color: var(--success);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
.input-with-icon .input-icon {
    background: var(--bg-surface-alt);
    border: none;
    padding: 14px 18px;
    font-weight: 700;
    color: var(--text-muted);
}
.input-with-icon input {
    border: none;
    padding: 14px 18px;
    font-size: 18px;
    font-weight: 700;
    background: var(--bg-surface);
    color: var(--text-primary);
    width: 100%;
}
.input-with-icon input:focus { outline: none; box-shadow: none; }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="<?php echo BASE_URL; ?>/customer/profile/<?php echo $customer['id']; ?>" class="btn-action">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <div>
                <h3 style="margin: 0; font-size: 24px; font-weight: 700;"><i class="bi bi-cash-stack me-2"></i>Receive Payment</h3>
                <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 15px;">
                    <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($customer['customer_name']); ?>
                </p>
            </div>
        </div>
        <div style="text-align: right;">
            <small style="opacity: 0.8;">Current Due</small>
            <div style="font-size: 32px; font-weight: 800;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_due, 0); ?></div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-modern">
            <div class="card-body">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert-box alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Amount to Receive</label>
                        <div class="input-with-icon">
                            <span class="input-icon"><?php echo DEFAULT_CURRENCY; ?></span>
                            <input type="number" name="amount" class="form-control" 
                                   step="1" min="1" max="<?php echo $total_due; ?>" 
                                   value="<?php echo $total_due; ?>" required>
                        </div>
                        <small style="color: var(--text-muted); margin-top: 8px; display: block;">Maximum: <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_due, 0); ?></small>
                    </div>

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="mobile">Mobile Banking</option>
                            <option value="card">Card</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Add a note..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100" style="padding: 16px; font-size: 16px;">
                        <i class="bi bi-check-circle-fill"></i> Confirm Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';