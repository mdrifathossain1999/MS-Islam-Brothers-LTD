<?php 
$pageTitle = 'Attendance';
$currentPage = 'hrm';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
.page-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px; }
.page-header .btn-back { background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; }
.page-header .btn-back:hover { background: white; color: #8b5cf6; }
.card-modern { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow-md); border: 1px solid var(--border); overflow: hidden; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-primary); }
.btn-primary { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.table-custom { width: 100%; border-collapse: collapse; }
.table-custom th { background: var(--bg-surface-alt); padding: 12px 16px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); border-bottom: 2px solid var(--border); }
.table-custom td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-present { background: #d1fae5; color: #065f46; }
.badge-absent { background: #fee2e2; color: #991b1b; }
.badge-leave { background: #dbeafe; color: #1e40af; }
.badge-late { background: #fef3c7; color: #92400e; }
</style>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-calendar-check me-2"></i>Attendance</h1>
    </div>
</div>
    <h1><i class="bi bi-calendar-check me-2"></i>Attendance</h1>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-modern p-4">
            <form method="POST">
                <div class="form-group">
                    <label>Employee</label>
                    <select name="employee_id" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $e): ?>
                        <option value="<?php echo $e['id']; ?>"><?php echo $e['first_name'] . ' ' . $e['last_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="leave">Leave</option>
                        <option value="late">Late</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Check In</label>
                    <input type="time" name="check_in">
                </div>
                <div class="form-group">
                    <label>Check Out</label>
                    <input type="time" name="check_out">
                </div>
                <button type="submit" class="btn-primary w-100">Mark Attendance</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-modern">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendances)): ?>
                        <?php foreach ($attendances as $a): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($a['attendance_date'])); ?></td>
                            <td><?php echo htmlspecialchars($a['first_name'] . ' ' . $a['last_name']); ?></td>
                            <td><span class="badge badge-<?php echo $a['status']; ?>"><?php echo ucfirst($a['status']); ?></span></td>
                            <td><?php echo $a['check_in'] ?? '-'; ?></td>
                            <td><?php echo $a['check_out'] ?? '-'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">No attendance records</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';