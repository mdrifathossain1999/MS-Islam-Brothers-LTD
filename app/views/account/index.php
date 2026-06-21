<?php 
$pageTitle = 'Accounting Dashboard';
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
.page-header h1 i {
    font-size: 1.4rem;
    background: rgba(255,255,255,0.25);
    padding: 10px;
    border-radius: 10px;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}
.stat-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid var(--border);
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}
.stat-card .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.stat-card .stat-info h3 {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    color: var(--text-primary);
}
.stat-card .stat-info p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--text-muted);
    font-weight: 500;
}
.icon-income { background: linear-gradient(135deg, #059669, #10b981); color: white; }
.icon-expense { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; }
.icon-cash { background: linear-gradient(135deg, #6366f1, #818cf8); color: white; }
.icon-sales { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }

.card-modern {
    background: var(--bg-card);
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
}
.card-header {
    background: var(--bg-surface-alt);
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-header h5 {
    margin: 0;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-body {
    padding: 0;
}
.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom th {
    background: var(--bg-surface-alt);
    padding: 14px 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}
.table-custom td {
    padding: 14px 16px;
    font-size: 13px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
}
.table-custom tr:hover {
    background: var(--bg-hover);
}
.amount-plus { color: #059669; font-weight: 600; }
.amount-minus { color: #dc2626; font-weight: 600; }
.date-badge {
    background: var(--bg-surface-alt);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
}
.btn-action {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
}
.btn-primary { background: #059669; color: white; }
.btn-primary:hover { background: #047857; }
.btn-danger { background: #dc2626; color: white; }
.btn-danger:hover { background: #b91c1c; }
.btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
.btn-outline:hover { background: var(--bg-hover); }

.badge-deposit { background: rgba(5, 150, 105, 0.15); color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-withdraw { background: rgba(220, 38, 38, 0.15); color: #dc2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }

@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-header">
    <h1><i class="bi bi-cash-stack"></i> Accounting Dashboard</h1>
    <div style="display: flex; gap: 10px;">
        <a href="<?php echo BASE_URL; ?>/account/create" class="btn-action btn-primary">
            <i class="bi bi-plus-circle"></i> New Account
        </a>
        <a href="<?php echo BASE_URL; ?>/account/deposit" class="btn-action btn-outline" style="color: white; border-color: rgba(255,255,255,0.5);">
            <i class="bi bi-arrow-down-circle"></i> Deposit
        </a>
        <a href="<?php echo BASE_URL; ?>/account/withdraw" class="btn-action btn-outline" style="color: white; border-color: rgba(255,255,255,0.5);">
            <i class="bi bi-arrow-up-circle"></i> Withdraw
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background: #d1fae5; color: #065f46; border: 1px solid #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background: #fee2e2; color: #991b1b; border: 1px solid #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-cash"><i class="bi bi-cash"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($totalCash ?? 0, 0); ?></h3>
            <p>Cash in Hand</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-income"><i class="bi bi-arrow-down-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($todayIncome ?? 0, 0); ?></h3>
            <p>Today's Income</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-expense"><i class="bi bi-arrow-up-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($todayExpense ?? 0, 0); ?></h3>
            <p>Today's Expense</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-sales"><i class="bi bi-cart-check"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($todaySales ?? 0, 0); ?></h3>
            <p>Today's Sales</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-wallet"></i> Accounts</h5>
                <a href="<?php echo BASE_URL; ?>/account/create" class="btn-action btn-primary" style="font-size: 11px;">
                    <i class="bi bi-plus"></i> Add
                </a>
            </div>
            <div class="card-body">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($accounts)): ?>
                            <?php foreach ($accounts as $acc): ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($acc['account_name']); ?></td>
                            <td><span class="date-badge"><?php echo ucfirst($acc['account_type']); ?></span></td>
                            <td style="font-weight: 600; color: <?php echo $acc['current_balance'] >= 0 ? '#059669' : '#dc2626'; ?>;">
                                <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($acc['current_balance'], 0); ?>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/account/edit/<?php echo $acc['id']; ?>" class="btn-action btn-outline" style="padding: 4px 8px;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                No accounts yet. <a href="<?php echo BASE_URL; ?>/account/create">Create one</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card-modern">
            <div class="card-header">
                <h5><i class="bi bi-clock-history"></i> Transaction History</h5>
            </div>
            <div class="card-body">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $txn): ?>
                        <tr>
                            <td><span class="date-badge"><?php echo date('d M', strtotime($txn['transaction_date'])); ?></span></td>
                            <td><?php echo htmlspecialchars($txn['account_name'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($txn['type'] === 'deposit'): ?>
                                <span class="badge-deposit"><i class="bi bi-arrow-down"></i> Deposit</span>
                                <?php else: ?>
                                <span class="badge-withdraw"><i class="bi bi-arrow-up"></i> Withdraw</span>
                                <?php endif; ?>
                            </td>
                            <td class="<?php echo $txn['type'] === 'deposit' ? 'amount-plus' : 'amount-minus'; ?>">
                                <?php echo $txn['type'] === 'deposit' ? '+' : '-'; ?><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($txn['amount'], 0); ?>
                            </td>
                        </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                No transactions yet
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';