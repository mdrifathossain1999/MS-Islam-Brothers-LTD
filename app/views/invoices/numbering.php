<?php 
$pageTitle = 'Invoice Numbering';
$currentPage = 'invoices';
$subPage = 'numbering';
ob_start();
?>

<style>
.numbering-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    padding: 20px;
    margin-bottom: 20px;
}
.numbering-table th {
    background: var(--bg-surface-alt);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    color: var(--text-muted);
}
.numbering-table td {
    vertical-align: middle;
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-hash"></i> Invoice Numbering</h1>
            <p>Manage invoice number prefixes and sequences</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/invoice/templates" class="btn btn-outline-secondary">
                <i class="bi bi-layout-text-window-reverse me-2"></i>Templates
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4">
        <div class="numbering-card">
            <h5 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Add/Update Prefix</h5>
            <form action="<?php echo BASE_URL; ?>/invoice/saveNumbering" method="POST">
                <div class="mb-3">
                    <label class="form-label">Prefix <span class="text-danger">*</span></label>
                    <input type="text" name="prefix" class="form-control" placeholder="INV" required style="text-transform: uppercase;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Starting Number</label>
                    <input type="number" name="starting_number" class="form-control" value="1" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label">Padding (digits)</label>
                    <input type="number" name="padding" class="form-control" value="4" min="1" max="10">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fiscal Year</label>
                    <input type="text" name="fiscal_year" class="form-control" value="<?php echo date('Y'); ?>" placeholder="2026">
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg me-2"></i>Save Numbering
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="numbering-card">
            <h5 class="mb-4"><i class="bi bi-list-ol me-2"></i>Configured Prefixes</h5>
            <?php if (empty($numberings)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>No numbering prefixes configured yet.
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table numbering-table">
                    <thead>
                        <tr>
                            <th>Prefix</th>
                            <th>Current #</th>
                            <th>Padding</th>
                            <th>Fiscal Year</th>
                            <th>Status</th>
                            <th>Example</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($numberings as $num): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($num['prefix']); ?></strong></td>
                            <td><?php echo $num['current_number']; ?></td>
                            <td><?php echo $num['padding']; ?></td>
                            <td><?php echo $num['fiscal_year'] ?? 'All Years'; ?></td>
                            <td>
                                <span class="badge bg-<?php echo $num['is_active'] === 'yes' ? 'success' : 'secondary'; ?>">
                                    <?php echo $num['is_active'] === 'yes' ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><code><?php echo $num['prefix']; ?>-<?php echo $num['fiscal_year']; ?>-<?php echo str_pad(1, $num['padding'], '0', STR_PAD_LEFT); ?></code></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/invoice/deleteNumbering/<?php echo $num['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this prefix?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
