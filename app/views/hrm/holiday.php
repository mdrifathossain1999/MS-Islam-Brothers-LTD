<?php 
$pageTitle = 'Holiday';
$currentPage = 'hrm';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 15px 24px; border-radius: 12px; color: white; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
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
</style>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?php echo BASE_URL; ?>/admin" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
        <h1><i class="bi bi-calendar-event me-2"></i>Holiday</h1>
    </div>
    <button class="btn" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-lg me-2"></i>Add Holiday</button>
</div>

<div class="card-modern">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Date</th>
                <th>Holiday Name</th>
                <th>Description</th>
                <th>Recurring</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($holidays)): ?>
                <?php foreach ($holidays as $h): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($h['holiday_date'])); ?></td>
                    <td><?php echo htmlspecialchars($h['holiday_name']); ?></td>
                    <td><?php echo htmlspecialchars($h['description'] ?? ''); ?></td>
                    <td><?php echo $h['is_recurring'] === 'yes' ? 'Yes' : 'No'; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center py-4">No holidays found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5 class="modal-title">Add Holiday</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Holiday Name</label>
                        <input type="text" name="holiday_name" required>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="holiday_date" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Recurring Yearly</label>
                        <select name="is_recurring">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';