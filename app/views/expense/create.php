<?php 
$pageTitle = 'Add Expense';
$currentPage = 'expense';
ob_start();
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/header-menu.css">

<style>
.expense-form { background: var(--bg-card); border-radius: 16px; padding: 28px; box-shadow: var(--shadow-md); border: 1px solid var(--border); max-width: 600px; margin: 0 auto; }
.form-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.form-header i { font-size: 26px; color: #dc2626; }
.form-header h2 { margin: 0; font-size: 24px; color: var(--text-primary); }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 14px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 15px; background: var(--bg-surface); color: var(--text-primary); }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #dc2626; outline: none; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.btn-back { background: var(--bg-surface-alt); color: var(--text-primary); padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.btn-back:hover { background: var(--border); }
.btn-save { background: #dc2626; color: white; padding: 12px 28px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; }
.btn-save:hover { background: #b91c1c; }
.form-actions { display: flex; gap: 12px; margin-top: 28px; }
</style>

<div class="page-header" style="background: linear-gradient(135deg, #dc2626, #991b1b); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin:0;font-size:1.25rem;font-weight:700;display:flex;align-items:center;gap:12px;">
        <i class="bi bi-wallet2"></i> Add Expense
    </h1>
    <a href="<?php echo BASE_URL; ?>/expense/index" class="btn-back" style="background: rgba(255,255,255,0.2); color: white;"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="expense-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/expense/create">
        <div class="form-row">
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="expense_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" placeholder="0.00" step="0.01" required>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" placeholder="Enter expense description" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Reference No (Optional)</label>
            <input type="text" name="reference_no" placeholder="Enter reference number">
        </div>
        <div class="form-actions">
            <a href="<?php echo BASE_URL; ?>/expense/index" class="btn-back">Cancel</a>
            <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Save Expense</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';