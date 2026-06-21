<?php 
$pageTitle = 'Deposit';
$currentPage = 'account';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
}
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.card-header-modern { background: var(--bg-surface-alt); padding: 16px 20px; border-bottom: 1px solid var(--border); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.badge-deposit { background: #d1fae5; color: #065f46; }
</style>

<div class="page-header">
    <h1><i class="bi bi-arrow-down-circle me-2"></i>Deposit Money</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header-modern"><h5>New Deposit</h5></div>
            <div class="p-4">
                <form method="POST">
                    <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" required>
                            <option value="">Select Account</option>
                            <?php foreach ($accounts as $a): ?>
                            <option value="<?php echo $a['id']; ?>"><?php echo $a['account_name']; ?> (<?php echo DEFAULT_CURRENCY . number_format($a['current_balance'], 2); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Reference No</label>
                        <input type="text" name="reference_no" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-100">Deposit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($t['transaction_date'])); ?></td>
                            <td><?php echo htmlspecialchars($t['account_name']); ?></td>
                            <td><?php echo htmlspecialchars($t['reference_no'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($t['description'] ?? ''); ?></td>
                            <td><span class="badge badge-deposit">+<?php echo DEFAULT_CURRENCY . number_format($t['amount'], 2); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">No deposits yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';