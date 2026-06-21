<?php 
$pageTitle = 'Invoice Management';
$currentPage = 'invoices';
ob_start();
?>

<style>
body { background: var(--bg-body); margin: 0; padding: 0; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 16px;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 14px;
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

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.stat-card .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card .stat-icon.total { background: linear-gradient(135deg, var(--primary), var(--accent-light)); color: white; }
.stat-card .stat-icon.paid { background: linear-gradient(135deg, var(--success), #34d399); color: white; }
.stat-card .stat-icon.due { background: linear-gradient(135deg, var(--warning), #fbbf24); color: white; }
.stat-card .stat-icon.overdue { background: linear-gradient(135deg, var(--danger), #f87171); color: white; }

.stat-card .stat-info h3 { font-size: 20px; font-weight: 700; margin: 0; color: var(--text-primary); }
.stat-card .stat-info p { margin: 4px 0 0 0; font-size: 12px; color: var(--text-secondary); font-weight: 500; }

.filter-bar {
    background: var(--bg-card);
    padding: 14px;
    border-radius: var(--radius-lg);
    margin-bottom: 20px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
}

.filter-btn {
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid var(--border);
    background: var(--bg-surface);
    color: var(--text-secondary);
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: scale(1.02);
}

.filter-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: 0 4px 15px var(--accent-glow);
}

.card-modern {
    background: var(--bg-card);
    border-radius: var(--radius);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    border: 1px solid var(--border);
    overflow: hidden;
}

.card-modern .card-header {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, #283548 100%);
    padding: 20px 25px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-modern .card-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-modern .card-header h5 i { color: var(--primary); }

.search-box input {
    background: var(--bg-secondary);
    border: 2px solid var(--border);
    border-radius: 25px;
    padding: 10px 20px;
    padding-left: 40px;
    font-size: 14px;
    color: var(--text-primary);
    width: 100%;
}
.search-box input::placeholder { color: var(--text-muted); }
.search-box input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--accent-glow);
    outline: none;
}
.search-box { position: relative; }
.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.date-input {
    background: var(--bg-secondary);
    border: 2px solid var(--border);
    border-radius: 25px;
    padding: 10px 16px;
    font-size: 13px;
    color: var(--text-primary);
}
.date-input:focus {
    border-color: var(--primary);
    outline: none;
}

.table-dark {
    width: 100%;
    border-collapse: collapse;
    position: relative;
}
.table-dark thead {
    position: sticky;
    top: 0;
    z-index: 100;
}
.table-dark thead th {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, #283548 100%);
    padding: 16px 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
    border-bottom: 2px solid var(--border);
    text-align: left;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.table-dark td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.table-dark tbody tr { transition: all 0.2s ease; }
.table-dark tbody tr:hover { background: var(--bg-card-hover); }

.invoice-number { font-weight: 700; color: var(--primary); font-size: 14px; }

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-paid { background: linear-gradient(135deg, #064e3b, #065f46); color: #a7f3d0; }
.status-partial { background: linear-gradient(135deg, #78350f, #92400e); color: #fde68a; }
.status-unpaid { background: linear-gradient(135deg, #7f1d1d, #991b1b); color: #fecaca; }
.status-overdue { background: linear-gradient(135deg, #7f1d1d, #dc2626); color: #fca5a5; }

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s ease;
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    color: var(--text-primary);
    text-decoration: none;
}
.btn-action:hover { transform: scale(1.1); background: var(--primary); color: white; border-color: var(--primary); }

.badge-count {
    background: linear-gradient(135deg, var(--primary), var(--accent-light));
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<div class="page-header">
    <div>
        <h1 style="margin: 0; font-size: 28px; font-weight: 800;">
            <i class="bi bi-receipt me-3"></i>Invoice Management
        </h1>
        <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 15px;">
            Track and manage all your sales invoices in one place
        </p>
    </div>
    <div class="d-flex gap-3">
        <button class="btn" style="background: white; color: var(--bg-primary); border-radius: 25px; font-weight: 600;">
            <i class="bi bi-arrow-clockwise me-2"></i>Refresh
        </button>
    </div>
</div>

<?php 
$totalAmount = 0;
$totalPaid = 0;
$totalDue = 0;
$overdueCount = 0;

if (!empty($sales)) {
    foreach ($sales as $s) {
        $totalAmount += floatval($s['total_amount']);
        $totalPaid += floatval($s['paid_amount']);
        $due = floatval($s['total_amount']) - floatval($s['paid_amount']);
        $totalDue += $due;
        if ($due > 0 && strtotime($s['sale_date']) < strtotime('-30 days')) {
            $overdueCount++;
        }
    }
}
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($totalAmount, 0); ?></h3>
            <p>Total Sales</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon paid"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($totalPaid, 0); ?></h3>
            <p>Total Paid</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon due"><i class="bi bi-clock-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($totalDue, 0); ?></h3>
            <p>Total Due</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon overdue"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="stat-info">
            <h3><?php echo $overdueCount; ?></h3>
            <p>Overdue</p>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo BASE_URL; ?>/invoice/index" class="filter-btn <?php echo empty($status_filter) ? 'active' : ''; ?>">
                <i class="bi bi-grid-3x3-gap"></i> All
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/index?status=completed" class="filter-btn <?php echo $status_filter === 'completed' ? 'active' : ''; ?>">
                <i class="bi bi-check-circle"></i> Completed
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/unpaid" class="filter-btn">
                <i class="bi bi-clock-history"></i> Due/Partial
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/due?days=30" class="filter-btn">
                <i class="bi bi-exclamation-octagon"></i> Overdue
            </a>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="<?php echo BASE_URL; ?>/invoice/today" class="btn" style="background: var(--primary); color: white; border-radius: 25px; font-size: 13px; padding: 8px 16px;">
                <i class="bi bi-calendar-day me-1"></i> Today
            </a>
            <form method="GET" action="<?php echo BASE_URL; ?>/invoice/dateRange" class="d-flex gap-2">
                <input type="date" name="start_date" class="date-input" value="<?php echo date('Y-m-01'); ?>">
                <input type="date" name="end_date" class="date-input" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit" class="btn" style="background: var(--primary); color: white; border-radius: 25px;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="mt-3">
        <form method="GET" action="<?php echo BASE_URL; ?>/invoice/search" class="d-flex gap-2" style="max-width: 400px;">
            <div class="search-box flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" name="invoice" placeholder="Search invoice number...">
            </div>
            <button type="submit" class="btn" style="background: var(--primary); color: white; border-radius: 25px;">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="card-modern" style="position: relative;">
    <div class="card-header" style="position: sticky; top: 0; z-index: 200; background: var(--bg-card); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h5><i class="bi bi-table"></i> Invoice List (<?php echo is_array($sales) ? count($sales) : 0; ?>)</h5>
        <span class="badge-count"><?php echo is_array($sales) ? count($sales) : 0; ?> Invoices</span>
    </div>
    <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto; position: relative;">
        <div class="table-responsive" style="position: relative;">
            <table class="table-dark">
                <thead>
                    <tr>
                        <th width="60"><i class="bi bi-hash me-2"></i>SL</th>
                        <th><i class="bi bi-file-earmark-text me-2"></i>Invoice</th>
                        <th><i class="bi bi-calendar me-2"></i>Date</th>
                        <th><i class="bi bi-person me-2"></i>Customer</th>
                        <th><i class="bi bi-person-badge me-2"></i>Cashier</th>
                        <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>Total</th>
                        <th class="text-end"><i class="bi bi-check-all me-2"></i>Paid</th>
                        <th class="text-end"><i class="bi bi-wallet2 me-2"></i>Due</th>
                        <th><i class="bi bi-toggle-on me-2"></i>Status</th>
                        <th width="120"><i class="bi bi-gear me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5" style="color: var(--text-muted);">
                            <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                            <p class="mt-3" style="font-size: 16px;">No invoices found</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $totalSales = count($sales);
                    foreach ($sales as $index => $sale): 
                        $sl = $totalSales - $index;
                        $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                        $customerName = !empty($sale['customer_name']) ? $sale['customer_name'] : 'Walk-in';
                        $isWalkin = empty($sale['customer_id']) || $sale['customer_id'] == 8;
                        
                        if ($due <= 0) {
                            $statusClass = 'status-paid';
                            $statusText = 'Paid';
                        } elseif ($due < floatval($sale['total_amount'])) {
                            $statusClass = 'status-partial';
                            $statusText = 'Partial';
                        } else {
                            if (strtotime($sale['sale_date']) < strtotime('-30 days')) {
                                $statusClass = 'status-overdue';
                                $statusText = 'Overdue';
                            } else {
                                $statusClass = 'status-unpaid';
                                $statusText = 'Due';
                            }
                        }
                        
                        $invoiceNum = $sale['invoice_number'] ?? 'INV-' . date('Ymd', strtotime($sale['sale_date'])) . '-' . str_pad($sale['id'], 4, '0', STR_PAD_LEFT);
                    ?>
                    <tr>
                        <td><span style="background: var(--bg-secondary); border-radius: 10px; padding: 8px 12px; font-size: 12px;"><?php echo str_pad($sl, 3, '0', STR_PAD_LEFT); ?></span></td>
                        <td><a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" class="invoice-number" title="View Invoice" style="color: var(--primary); font-weight: 600; text-decoration: none;"><?php echo $invoiceNum; ?></a></td>
                        <td><?php echo date('d M Y', strtotime($sale['sale_date'])); ?></td>
                        <td>
                            <?php if ($isWalkin): ?>
                                <span style="color: var(--text-muted);"><i class="bi bi-person-walking me-1"></i>Walk-in</span>
                            <?php else: ?>
                                <a href="<?php echo BASE_URL; ?>/customer/profile/<?php echo $sale['customer_id']; ?>" style="color: var(--primary); font-weight: 600; text-decoration: none;" title="View Customer Profile">
                                    <?php echo htmlspecialchars($customerName); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><span style="background: var(--bg-secondary); border-radius: 15px; padding: 6px 12px; font-size: 12px;"><?php echo $sale['cashier_name'] ?? 'System'; ?></span></td>
                        <td class="text-end fw-bold" style="font-size: 15px;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 2); ?></td>
                        <td class="text-end" style="color: var(--success); font-weight: 600;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['paid_amount'], 2); ?></td>
                        <td class="text-end <?php echo $due > 0 ? 'text-danger' : ''; ?>" style="<?php echo $due > 0 ? 'color: var(--danger);' : 'color: var(--text-muted);'; ?> font-weight: 600;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(max(0, $due), 2); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" class="btn-action" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/invoice/print/<?php echo $sale['id']; ?>" class="btn-action" title="Print" target="_blank">
                                <i class="bi bi-printer"></i>
                            </a>
                            <?php if ($due > 0 && !$isWalkin): ?>
                            <a href="<?php echo BASE_URL; ?>/customer/receivePayment/<?php echo $sale['customer_id']; ?>" class="btn-action" title="Receive Payment" style="background: var(--success); border-color: var(--success);">
                                <i class="bi bi-cash-stack"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';