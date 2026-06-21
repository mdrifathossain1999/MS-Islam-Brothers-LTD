<?php 
$pageTitle = 'Category-Unit Mapping';
$currentPage = 'admin';
$subPage = 'categoryUnit';
ob_start();
?>

<style>
:root {
    --cu-card: #ffffff;
    --cu-text: #1e293b;
    --cu-text-secondary: #475569;
    --cu-text-muted: #64748b;
    --cu-border: #e2e8f0;
    --cu-bg: #f8fafc;
}
body.dark-mode {
    --cu-card: #1e293b;
    --cu-text: #f1f5f9;
    --cu-text-secondary: #94a3b8;
    --cu-text-muted: #94a3b8;
    --cu-border: #334155;
    --cu-bg: #0f172a;
}
.settings-card { background: var(--cu-card); border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 24px; }
.settings-card h4 { font-size: 16px; font-weight: 600; color: var(--cu-text); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--cu-border); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--cu-text-secondary); margin-bottom: 6px; }
.form-group select { width: 100%; padding: 10px 12px; border: 1px solid var(--cu-border); border-radius: 8px; font-size: 14px; background: var(--cu-card); color: var(--cu-text); }
.form-group select:focus { border-color: #667eea; outline: none; }
.btn-save { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.btn-save:hover { opacity: 0.9; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--cu-text-muted); text-transform: uppercase; background: var(--cu-bg); border-bottom: 2px solid var(--cu-border); }
.data-table td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid var(--cu-border); color: var(--cu-text); }
.data-table tr:hover { background: var(--cu-bg); }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-success { background: #dcfce7; color: #166534; }
body.dark-mode .badge-success { background: #064e3b; color: #6ee7b7; }
</style>

<div class="page-header">
    <h1><i class="bi bi-link"></i> Category-Unit Mapping</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="settings-card">
    <p>Set default unit for each category</p>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background:var(--success,#10b981);color:#fff;border:1px solid var(--success,#10b981);padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background:var(--danger,#ef4444);color:#fff;border:1px solid var(--danger,#ef4444);padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="settings-card">
    <h4><i class="bi bi-plus-circle me-2"></i>Add/Update Mapping</h4>
    <form action="<?php echo BASE_URL; ?>/admin/saveCategoryUnit" method="POST">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="categorySelect" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Default Unit</label>
                    <select name="unit_name" id="unitSelect" required>
                        <option value="">Select Unit</option>
                        <?php foreach ($units as $unit): ?>
                        <option value="<?php echo htmlspecialchars($unit['unit_name']); ?>"><?php echo htmlspecialchars($unit['unit_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-save w-100" id="saveBtn">
                        <i class="bi bi-check-lg me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="settings-card">
    <h4><i class="bi bi-list me-2"></i>Current Mappings (<?php echo count($mappings); ?>)</h4>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Default Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mappings)): ?>
                    <?php foreach ($mappings as $mapping): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mapping['category']); ?></td>
                        <td><span class="badge badge-success"><?php echo htmlspecialchars($mapping['unit_name']); ?></span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editMapping('<?php echo addslashes($mapping['category']); ?>', '<?php echo addslashes($mapping['unit_name']); ?>')">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMapping('<?php echo addslashes($mapping['category']); ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center py-4" style="color:var(--cu-text-muted);">No mappings found. Add a mapping above.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
#cancelBtn { display: none; }
</style>

<script>
function editMapping(category, unit) {
    var categorySelect = document.getElementById('categorySelect');
    var unitSelect = document.getElementById('unitSelect');
    var saveBtn = document.getElementById('saveBtn');
    
    // Set category 
    for (var i = 0; i < categorySelect.options.length; i++) {
        if (categorySelect.options[i].value === category) {
            categorySelect.selectedIndex = i;
            break;
        }
    }
    
    // Set unit
    for (var i = 0; i < unitSelect.options.length; i++) {
        if (unitSelect.options[i].value.toLowerCase() === unit.toLowerCase()) {
            unitSelect.selectedIndex = i;
            break;
        }
    }
    
    // Add cancel button if not exists
    if (!document.getElementById('cancelBtn')) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'cancelBtn';
        btn.className = 'btn btn-secondary w-100 mt-2';
        btn.innerHTML = '<i class="bi bi-x-lg me-1"></i>Cancel';
        btn.onclick = resetForm;
        saveBtn.parentNode.appendChild(btn);
    }
    
    // Update button text
    saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Update';
}

function resetForm() {
    var categorySelect = document.getElementById('categorySelect');
    var unitSelect = document.getElementById('unitSelect');
    var saveBtn = document.getElementById('saveBtn');
    
    categorySelect.selectedIndex = 0;
    unitSelect.selectedIndex = 0;
    saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save';
    
    var cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.style.display = 'none';
    }
}

// Delete confirmation
function deleteMapping(category) {
    if (confirm('Delete mapping for "' + category + '"?')) {
        // Create and submit a form directly for more reliability
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/deleteCategoryUnit';
        
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category';
        input.value = category;
        form.appendChild(input);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';