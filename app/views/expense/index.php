<?php 
$pageTitle = 'Expense List';
$currentPage = 'expense';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; }
.page-header .btn { background: white; color: #ef4444; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.card-header-modern { background: var(--bg-surface-alt); padding: 16px 20px; border-bottom: 1px solid var(--border); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
</style>

<div class="page-header">
    <h1><i class="bi bi-wallet2 me-2"></i>Expense List</h1>
    <a href="<?php echo BASE_URL; ?>/expense/create" class="btn"><i class="bi bi-plus-lg me-2"></i>Add Expense</a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-modern p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" placeholder="Search..." class="form-control" value="<?php echo $_GET['search'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo (($_GET['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>><?php echo $cat['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card-modern">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Reference</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expenses)): ?>
                <?php foreach ($expenses as $e): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($e['expense_date'])); ?></td>
                    <td><?php echo htmlspecialchars($e['category_name'] ?? ''); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($e['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($e['description'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($e['reference_no'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($e['full_name'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center py-4">No expenses found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';