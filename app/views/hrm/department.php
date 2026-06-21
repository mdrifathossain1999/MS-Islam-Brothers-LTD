<?php 
$pageTitle = 'Department';
$currentPage = 'hrm';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px; }
.page-header .btn { background: white; color: #8b5cf6; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
.page-header .btn-back { background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; }
.page-header .btn-back:hover { background: white; color: #8b5cf6; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-yes { background: #d1fae5; color: #065f46; }
.badge-no { background: #fee2e2; color: #991b1b; }
.btn-icon { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; background: var(--bg-surface-alt); border: 1px solid var(--border); color: var(--text-muted); transition: all 0.2s; }
.btn-icon:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.danger:hover { background: #dc2626; border-color: #dc2626; }
</style>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/hrm/employee" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-building me-2"></i>Department</h1>
    </div>
    <button class="btn" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-2"></i>Add Department</button>
</div>

<div class="card-modern">
    <table class="table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($departments)): ?>
                <?php foreach ($departments as $d): ?>
                <tr>
                    <td><?php echo $d['id']; ?></td>
                    <td><?php echo htmlspecialchars($d['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($d['description'] ?? ''); ?></td>
                    <td><span class="badge badge-<?php echo $d['is_active']; ?>"><?php echo $d['is_active'] === 'yes' ? 'Active' : 'Inactive'; ?></span></td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <a href="<?php echo BASE_URL; ?>/hrm/editDepartment/<?php echo $d['id']; ?>" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="<?php echo BASE_URL; ?>/hrm/deleteDepartment/<?php echo $d['id']; ?>" class="btn-icon danger" title="Delete" onclick="return confirm('Delete this department?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center py-4">No departments found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5 class="modal-title">Add Department</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="department_name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';