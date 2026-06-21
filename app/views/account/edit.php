<?php 
$pageTitle = 'Edit Account';
$currentPage = 'accounting';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(5, 150, 105, 0.35);
}
.page-header h1 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.form-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 24px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    background: var(--bg-surface);
    color: var(--text-primary);
    transition: all 0.2s;
}
.form-control:focus {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
}
.btn-submit {
    background: #059669;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-submit:hover {
    background: #047857;
    transform: translateY(-2px);
}
.btn-cancel {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border);
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
}
.btn-cancel:hover {
    background: var(--bg-hover);
}
</style>

<div class="page-header">
    <h1><i class="bi bi-wallet-plus"></i> Edit Account</h1>
</div>

<div class="form-card">
    <form method="POST" action="<?php echo BASE_URL; ?>/account/edit/<?php echo $account['id']; ?>">
        <div class="form-group">
            <label>Account Name *</label>
            <input type="text" name="account_name" class="form-control" required value="<?php echo htmlspecialchars($account['account_name']); ?>">
        </div>
        <div class="form-group">
            <label>Account Number</label>
            <input type="text" name="account_number" class="form-control" value="<?php echo htmlspecialchars($account['account_number'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Account Type</label>
            <select name="account_type" class="form-control">
                <option value="cash" <?php echo ($account['account_type'] ?? 'cash') === 'cash' ? 'selected' : ''; ?>>Cash</option>
                <option value="bank" <?php echo ($account['account_type'] ?? '') === 'bank' ? 'selected' : ''; ?>>Bank</option>
                <option value="mobile_banking" <?php echo ($account['account_type'] ?? '') === 'mobile_banking' ? 'selected' : ''; ?>>Mobile Banking</option>
            </select>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_default" value="yes" <?php echo ($account['is_default'] ?? '') === 'yes' ? 'checked' : ''; ?>> Set as default account
            </label>
        </div>
        <button type="submit" class="btn-submit">
            <i class="bi bi-check-circle"></i> Update Account
        </button>
        <a href="<?php echo BASE_URL; ?>/account/index" class="btn-cancel">Cancel</a>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';