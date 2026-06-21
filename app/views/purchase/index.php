<?php 
$pageTitle = 'Purchase List';
$currentPage = 'purchase';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.35);
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
.page-header .btn {
    background: white;
    color: #667eea;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
}
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.card-header-modern { background: var(--bg-surface-alt); padding: 16px 20px; border-bottom: 1px solid var(--border); }
.card-header-modern h5 { margin: 0; font-weight: 600; color: var(--text-primary); }
.search-box { position: relative; }
.search-box input { width: 100%; padding: 10px 16px; padding-left: 40px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); text-align: left; }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
.table-custom tr:hover { background: var(--bg-hover); }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-received { background: #d1fae5; color: #065f46; }
.badge-returned { background: #fee2e2; color: #991b1b; }
.btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.btn-primary { background: var(--primary); color: white; border: none; }
.btn-danger { background: var(--danger); color: white; border: none; }
</style>

<div class="page-header">
    <h1><i class="bi bi-cart"></i> Purchase List</h1>
    <a href="<?php echo BASE_URL; ?>/purchase/create" class="btn"><i class="bi bi-plus-lg me-2"></i>Add Purchase</a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-list me-2"></i>All Purchases</h5>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPurchase" placeholder="Search...">
            </div>
        </div>
    </div>
    <table class="table-custom">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="purchaseTableBody">
            <?php if (!empty($purchases)): ?>
                <?php foreach ($purchases as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['invoice_no'] ?? ''); ?></td>
                    <td><?php echo date('d M Y', strtotime($p['purchase_date'])); ?></td>
                    <td><?php echo htmlspecialchars($p['supplier_name'] ?? 'N/A'); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($p['total_amount'], 2); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($p['paid_amount'], 2); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($p['due_amount'], 2); ?></td>
                    <td><span class="badge badge-<?php echo $p['status']; ?>"><?php echo ucfirst($p['status']); ?></span></td>
                    <td>
                        <a href="#" class="btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center py-4">No purchases found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchPurchase').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#purchaseTableBody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';