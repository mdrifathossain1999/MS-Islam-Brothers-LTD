<?php 
$pageTitle = 'Employee';
$currentPage = 'hrm';
ob_start();
?>

<style>
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-active { background: #d1fae5; color: #065f46; }
.badge-inactive { background: #fee2e2; color: #991b1b; }
.btn-icon { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; background: var(--bg-surface-alt); border: 1px solid var(--border); color: var(--text-muted); transition: all 0.2s; }
.btn-icon:hover { background: var(--primary); color: white; border-color: var(--primary); }
.btn-icon.danger:hover { background: #dc2626; border-color: #dc2626; }
</style>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-person-badge me-2"></i>Employee</h1>
    </div>
    <button class="btn" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-2"></i>Add Employee</button>
</div>

<div class="card-modern">
    <table class="table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $e): ?>
                <tr>
                    <td><?php echo htmlspecialchars($e['employee_id']); ?></td>
                    <td><?php echo htmlspecialchars($e['first_name'] . ' ' . $e['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($e['department_name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($e['designation'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($e['phone'] ?? ''); ?></td>
                    <td><?php echo DEFAULT_CURRENCY . number_format($e['salary'], 2); ?></td>
                    <td><span class="badge badge-<?php echo $e['status']; ?>"><?php echo ucfirst($e['status']); ?></span></td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <a href="<?php echo BASE_URL; ?>/hrm/editEmployee/<?php echo $e['id']; ?>" class="btn-icon" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="<?php echo BASE_URL; ?>/hrm/deleteEmployee/<?php echo $e['id']; ?>" class="btn-icon danger" title="Delete" onclick="return confirm('Delete this employee?')"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center py-4">No employees found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5 class="modal-title">Add Employee</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>First Name</label><input type="text" name="first_name" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Last Name</label><input type="text" name="last_name"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Department</label><select name="department_id"><?php foreach ($departments as $d): ?><option value="<?php echo $d['id']; ?>"><?php echo $d['department_name']; ?></option><?php endforeach; ?></select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Designation</label><input type="text" name="designation"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Phone</label><input type="text" name="phone"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" name="email"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Salary</label><input type="number" name="salary" step="0.01"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Join Date</label><input type="date" name="join_date" value="<?php echo date('Y-m-d'); ?>"></div></div>
                    </div>
                    <div class="form-group"><label>Address</label><textarea name="address" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';