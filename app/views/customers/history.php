<?php 
$pageTitle = 'Purchase History';
$currentPage = 'customers';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    padding: 25px 30px;
    border-radius: var(--radius-lg);
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
}

.btn-action {
    background: var(--bg-surface);
    color: var(--text-primary);
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid var(--border);
}
.btn-action:hover { 
    background: var(--primary); 
    color: white; 
    border-color: var(--primary);
    transform: translateY(-2px); 
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    text-align: center;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.stat-card h6 { font-size: 13px; color: var(--text-muted); margin: 0 0 10px 0; font-weight: 600; text-transform: uppercase; }
.stat-card h4 { font-size: 28px; font-weight: 800; margin: 0; color: var(--text-primary); }
.stat-card.primary { border-top: 4px solid var(--primary); }
.stat-card.success { border-top: 4px solid var(--success); }
.stat-card.info { border-top: 4px solid var(--info); }
.stat-card h4.text-primary { color: var(--primary); }
.stat-card h4.text-success { color: var(--success); }
.stat-card h4.text-info { color: var(--info); }

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom thead th {
    background: var(--bg-surface-alt);
    padding: 14px 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
}
.table-custom td {
    padding: 14px 20px;
    font-size: 14px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}
.table-custom tbody tr { transition: all 0.2s ease; }
.table-custom tbody tr:hover { background: var(--bg-hover); }

.badge-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
}
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 style="margin: 0; font-size: 24px; font-weight: 700;">
                <i class="bi bi-clock-history me-2"></i>Purchase History
            </h3>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">
                <?php echo $customer['customer_name']; ?> - <?php echo $customer['phone']; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/customer/profile/<?php echo $customer['id']; ?>" class="btn-action">
                <i class="bi bi-person me-2"></i>Profile
            </a>
            <a href="<?php echo BASE_URL; ?>/customer/index" class="btn-action">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card primary">
            <h6><i class="bi bi-cart me-2"></i>Total Purchases</h6>
            <h4 class="text-primary"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($customer['total_purchases'], 2); ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success">
            <h6><i class="bi bi-star me-2"></i>Loyalty Points</h6>
            <h4 class="text-success"><?php echo $customer['loyalty_points'] ?? 0; ?></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card info">
            <h6><i class="bi bi-receipt me-2"></i>Total Transactions</h6>
            <h4 class="text-info"><?php echo count($sales); ?></h4>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Cashier</th>
                    <th class="text-end">Total</th>
                    <th>Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><strong><?php echo $sale['invoice_number']; ?></strong></td>
                    <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                    <td><?php echo $sale['cashier_name'] ?? 'System'; ?></td>
                    <td class="text-end fw-bold"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 2); ?></td>
                    <td><span class="badge-status"><?php echo ucfirst($sale['payment_method']); ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" style="background: var(--primary); color: white; border-radius: 8px; padding: 8px 12px; text-decoration: none; font-size: 14px;">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';