<?php 
$pageTitle = 'Supplier List';
$currentPage = 'supplier';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; }
.page-header .btn { background: white; color: #06b6d4; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.card-header-modern { background: var(--bg-surface-alt); padding: 16px 20px; border-bottom: 1px solid var(--border); }
.search-box { position: relative; }
.search-box input { width: 100%; padding: 10px 16px; padding-left: 40px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.btn-primary { background: var(--primary); color: white; border: none; }
.btn-danger { background: var(--danger); color: white; border: none; }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-active { background: #d1fae5; color: #065f46; }
.badge-inactive { background: #fee2e2; color: #991b1b; }
</style>

<div class="page-header">
    <h1><i class="bi bi-truck me-2"></i>Supplier List</h1>
    <a href="<?php echo BASE_URL; ?>/supplier/create" class="btn"><i class="bi bi-plus-lg me-2"></i>Add Supplier</a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-list me-2"></i>All Suppliers</h5>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchSupplier" placeholder="Search...">
            </div>
        </div>
    </div>
    <table class="table-custom">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
            <?php if (!empty($suppliers)): ?>
                <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($s['company_name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($s['phone'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($s['email'] ?? ''); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($s['balance'], 2); ?></td>
                    <td><span class="badge badge-<?php echo $s['status']; ?>"><?php echo ucfirst($s['status']); ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/supplier/edit/<?php echo $s['id']; ?>" class="btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                        <a href="<?php echo BASE_URL; ?>/supplier/delete/<?php echo $s['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center py-4">No suppliers found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchSupplier').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#supplierTableBody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';