<?php 
$pageTitle = 'Sale Return';
$currentPage = 'sale';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
</style>

<div class="page-header">
    <h1><i class="bi bi-arrow-return-left"></i> Sale Return</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card-modern">
            <div class="card-header-modern" style="background: var(--bg-surface-alt); padding: 16px 20px; border-bottom: 1px solid var(--border);">
                <h5 style="margin:0;font-weight:600;">New Return</h5>
            </div>
            <div class="p-4">
                <form method="POST">
                    <div class="form-group">
                        <label>Sale Invoice</label>
                        <select name="sale_id" required>
                            <option value="">Select Sale</option>
                            <?php if (!empty($sales)): ?>
                                <?php foreach ($sales as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo $s['invoice_no'] ?? $s['id']; ?> - <?php echo $s['customer_name'] ?? 'Walk-in'; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product</label>
                        <select name="product_id" required>
                            <option value="">Select Product</option>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $p): ?>
                                <option value="<?php echo $p['id']; ?>"><?php echo $p['product_name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea name="reason" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-100">Submit Return</button>
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
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Amount</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center py-4">No returns yet</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';