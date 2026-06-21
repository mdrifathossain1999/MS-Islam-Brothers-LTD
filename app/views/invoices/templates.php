<?php 
$pageTitle = 'Invoice Templates';
$currentPage = 'invoices';
$subPage = 'templates';
ob_start();
?>

<style>
.template-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin-bottom: 20px;
    transition: var(--transition);
}
.template-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}
.template-card.active {
    border: 2px solid var(--primary);
}
.template-preview {
    background: var(--bg-surface-alt);
    padding: 20px;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    min-height: 200px;
}
.template-info {
    padding: 15px;
    border-top: 1px solid var(--border);
}
.template-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-default { background: #dbeafe; color: #1e40af; }
.badge-thermal { background: #dcfce7; color: #166534; }
.badge-a4 { background: #fef9c3; color: #854d0e; }
.badge-a5 { background: #fce7f3; color: #9d174d; }
.badge-pos { background: #e0e7ff; color: #3730a3; }
.badge-general { background: #fce7f3; color: #9d174d; }
.template-card-small {
    background: var(--bg-card);
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow-md);
    margin-bottom: 12px;
    transition: var(--transition);
}
.template-card-small:hover {
    box-shadow: var(--shadow-lg);
}
.template-card-small .template-preview-small {
    background: var(--bg-surface-alt);
    padding: 10px;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    min-height: 60px;
}
.template-card-small .template-info {
    padding: 10px;
    border-top: 1px solid var(--border);
}
.template-card-small h6 { font-size: 13px; margin-bottom: 4px; }
.template-card-small .template-badge { padding: 2px 8px; font-size: 10px; }
.template-card-small .btn-sm { padding: 2px 6px; font-size: 11px; }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-layout-text-window-reverse"></i> Invoice Templates</h1>
            <p>Manage invoice print templates</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/invoice/numbering" class="btn btn-outline-secondary">
                <i class="bi bi-hash me-2"></i>Numbering
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/templates" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<?php if (empty($templates)): ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    No templates found. Database may need initialization.
</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Create New Template</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>/invoice/saveTemplate" method="POST" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Template Name <span class="text-danger">*</span></label>
                <input type="text" name="template_name" class="form-control" placeholder="My Custom Template" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Template Type</label>
                <select name="template_type" class="form-select" id="templateTypeSelect">
                    <option value="thermal">Thermal (58mm)</option>
                    <option value="a4">A4</option>
                    <option value="a5">A5</option>
                    <option value="pos">POS (80mm)</option>
                    <option value="general">General Invoice</option>
                    <option value="custom">Custom HTML</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Color Scheme</label>
                <input type="color" name="color_scheme" class="form-control form-control-color" value="#667eea">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i>Create
                </button>
            </div>
            <div class="col-md-3" id="customHtmlSection" style="display:none;">
                <label class="form-label">Custom HTML Code</label>
                <textarea name="custom_html" class="form-control" rows="2" placeholder="<div>Your custom HTML...</div>"></textarea>
            </div>
        </form>
        <script>
        document.getElementById('templateTypeSelect').addEventListener('change', function() {
            document.getElementById('customHtmlSection').style.display = this.value === 'custom' ? 'block' : 'none';
        });
        </script>
    </div>
</div>

<?php if (!empty($templates)): ?>

<div class="page-header">
    <h4><i class="bi bi-layout-text-window-reverse me-2"></i>Existing Templates</h4>
</div>

<div class="row">
    <?php foreach ($templates as $template): ?>
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="template-card-small <?php echo $template['is_default'] === 'yes' ? 'border border-primary' : ''; ?>">
            <div class="template-preview-small">
                <div style="text-align: center; color: var(--text-muted);">
                    <i class="bi bi-file-earmark-text" style="font-size: 1.5rem;"></i>
                    <span class="template-badge badge-<?php echo $template['template_type']; ?>">
                        <?php echo strtoupper($template['template_type']); ?>
                    </span>
                </div>
            </div>
            <div class="template-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?php echo htmlspecialchars($template['template_name']); ?></h6>
                        <small class="text-muted">
                            <?php if ($template['is_default'] === 'yes'): ?>
                            <span class="badge badge-default">Default</span>
                            <?php endif; ?>
                            <?php if ($template['is_active'] === 'no'): ?>
                            <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="<?php echo BASE_URL; ?>/invoice/editTemplate/<?php echo $template['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($template['is_default'] !== 'yes'): ?>
                        <a href="<?php echo BASE_URL; ?>/invoice/setDefaultTemplate/<?php echo $template['id']; ?>" class="btn btn-sm btn-outline-success" title="Set as Default">
                            <i class="bi bi-check-circle"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/invoice/deleteTemplate/<?php echo $template['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
