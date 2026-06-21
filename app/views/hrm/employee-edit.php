<?php 
$pageTitle = 'Edit Employee';
$currentPage = 'hrm';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px; }
.page-header .btn-back { background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; }
.page-header .btn-back:hover { background: white; color: #8b5cf6; }
.edit-form { background: var(--bg-card); border-radius: 12px; padding: 28px; box-shadow: var(--shadow-md); border: 1px solid var(--border); max-width: 600px; margin: 0 auto; }
.form-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.form-header i { font-size: 24px; color: #8b5cf6; }
.form-header h2 { margin: 0; font-size: 22px; color: var(--text-primary); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select { width: 100%; padding: 12px 14px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.form-group input:focus, .form-group select:focus { border-color: #8b5cf6; outline: none; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.btn-save { background: #8b5cf6; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; }
.btn-save:hover { background: #7c3aed; }
.form-actions { display: flex; gap: 12px; margin-top: 24px; }
.btn-cancel { background: var(--bg-surface-alt); color: var(--text-primary); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; }
</style>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/hrm/employee" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-person-badge"></i> Edit Employee</h1>
    </div>
</div>

<div class="edit-form">
    <form method="POST" action="<?php echo BASE_URL; ?>/hrm/editEmployee/<?php echo $employee['id']; ?>">
        <div class="form-header">
            <i class="bi bi-pencil-square"></i>
            <h2>Edit Employee</h2>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($employee['last_name'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" required>
                    <?php foreach ($departments as $d): ?>
                    <option value="<?php echo $d['id']; ?>" <?php echo ($employee['department_id'] == $d['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['department_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Designation</label>
                <input type="text" name="designation" value="<?php echo htmlspecialchars($employee['designation'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($employee['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Salary</label>
                <input type="number" name="salary" value="<?php echo $employee['salary'] ?? 0; ?>" step="0.01">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?php echo ($employee['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($employee['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?php echo BASE_URL; ?>/hrm/employee" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Save Changes</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';