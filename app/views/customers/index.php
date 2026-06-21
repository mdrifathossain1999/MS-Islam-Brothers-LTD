<?php 
$pageTitle = 'Customers';
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
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header p {
    margin: 4px 0 0;
    font-size: 0.85rem;
    opacity: 0.9;
}

.page-header .btn-add {
    background: white;
    color: var(--primary);
    padding: 8px 16px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.2s;
}
.page-header .btn-add:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 14px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: var(--primary);
}

.stat-card:nth-child(1)::before { background: var(--primary); }
.stat-card:nth-child(2)::before { background: var(--warning); }
.stat-card:nth-child(3)::before { background: var(--success); }
.stat-card:nth-child(4)::before { background: #8b5cf6; }

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stat-card .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; }
.stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, var(--warning), #fbbf24); color: white; }
.stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, var(--success), #34d399); color: white; }
.stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white; }

.stat-card .stat-info h3 { font-size: 18px; font-weight: 700; margin: 0; color: var(--text-primary); }
.stat-card .stat-info p { margin: 3px 0 0 0; font-size: 11px; color: var(--text-muted); font-weight: 500; }

.search-bar {
    background: var(--bg-card);
    padding: 14px 20px;
    border-radius: var(--radius-lg);
    margin-bottom: 20px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.search-bar .search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-bar .search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.search-bar .search-box input {
    width: 100%;
    padding: 10px 14px 10px 40px;
    border: 2px solid var(--border);
    border-radius: var(--radius-lg);
    font-size: 13px;
    background: var(--bg-surface);
    color: var(--text-primary);
    transition: all 0.2s;
}

.search-bar .search-box input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-bar .badge-count {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

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
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-custom td {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.table-custom tbody tr {
    transition: all 0.2s ease;
}

.table-custom tbody tr:hover {
    background: var(--bg-hover);
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.customer-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.customer-name {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
}

.customer-name:hover {
    text-decoration: underline;
}

.badge-due {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-danger { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; }
.badge-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }

.badge-status {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.action-btns {
    display: flex;
    gap: 8px;
}

.action-btns a {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid var(--border);
    background: var(--bg-surface);
    color: var(--text-secondary);
}

.action-btns a:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btns a.btn-edit:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.action-btns a.btn-delete:hover {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .search-bar { flex-direction: column; align-items: stretch; }
    .search-bar .search-box { max-width: 100%; }
}

@media (max-width: 576px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-header">
    <div>
        <h1><i class="bi bi-people-fill"></i> Customers</h1>
        <p>Manage your customer database</p>
    </div>
    <a href="<?php echo BASE_URL; ?>/customer/create" class="btn-add">
        <i class="bi bi-plus-circle"></i> Add Customer
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo number_format($total_customers); ?></h3>
            <p>Total Customers</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-clock"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_due, 0); ?></h3>
            <p>Total Due</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_paid, 0); ?></h3>
            <p>Total Paid</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-cart"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($total_amount, 0); ?></h3>
            <p>Total Purchase</p>
        </div>
    </div>
</div>

<div class="search-bar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="customerSearch" placeholder="Search by name, phone or address..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    </div>
    <span class="badge-count">
        <i class="bi bi-people"></i>
        <?php echo count($customers); ?> Customers
    </span>
</div>

<script>
document.getElementById('customerSearch').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        var search = this.value.trim();
        if (search) {
            window.location.href = '<?php echo BASE_URL; ?>/customer/index?search=' + encodeURIComponent(search);
        } else {
            window.location.href = '<?php echo BASE_URL; ?>/customer/index';
        }
    }
});
</script>

<div class="card-modern">
    <div class="card-header">
        <h5><i class="bi bi-list-ul"></i> Customer List</h5>
    </div>
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="6" class="text-center py-4" style="color: var(--text-muted);">
                        <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 8px;"></i>
                        No customers found
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($customers as $customer): 
                    $due = floatval($customer['total_amount']) - floatval($customer['paid_amount']);
                    $phone = $customer['phone'] ?? '';
                    if (!empty($phone) && $phone[0] !== '0') { $phone = '0' . $phone; }
                ?>
                <tr>
                    <td>
                        <div class="customer-info">
                            <div class="customer-avatar"><i class="bi bi-person-fill"></i></div>
                            <a href="<?php echo BASE_URL; ?>/customer/profile/<?php echo $customer['id']; ?>" class="customer-name">
                                <?php echo htmlspecialchars($customer['customer_name']); ?>
                            </a>
                        </div>
                    </td>
                    <td><?php echo !empty($phone) ? htmlspecialchars($phone) : '<span style="color: var(--text-muted);">N/A</span>'; ?></td>
                    <td><?php echo !empty($customer['address']) ? htmlspecialchars($customer['address']) : '<span style="color: var(--text-muted);">N/A</span>'; ?></td>
                    <td>
                        <?php if ($due > 0): ?>
                        <span class="badge-due badge-danger"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($due, 2); ?></span>
                        <?php else: ?>
                        <span class="badge-due badge-success">No Due</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge-status" style="background: <?php echo $customer['status'] === 'active' ? 'linear-gradient(135deg, #d1fae5, #a7f3d0)' : 'linear-gradient(135deg, #f1f5f9, #e2e8f0)'; ?>; color: <?php echo $customer['status'] === 'active' ? '#065f46' : '#475569'; ?>;">
                            <?php echo ucfirst($customer['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="<?php echo BASE_URL; ?>/customer/edit/<?php echo $customer['id']; ?>" class="btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="<?php echo BASE_URL; ?>/customer/delete/<?php echo $customer['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this customer?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';