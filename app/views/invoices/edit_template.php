<?php 
$pageTitle = 'Edit Invoice Template';
$currentPage = 'invoices';
ob_start();
?>

<style>
.template-editor {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    padding: 24px;
}
.preview-section {
    background: var(--bg-card);
    border-radius: var(--radius-sm);
    padding: 20px;
    margin-top: 20px;
    max-height: 500px;
    overflow-y: auto;
    border: 2px solid var(--primary);
    box-shadow: var(--shadow-md);
}
.color-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.color-option {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--transition);
}
.color-option:hover, .color-option.selected {
    transform: scale(1.1);
    border-color: var(--text-primary);
}
.page-content {
    max-height: calc(100vh - 180px);
    overflow-y: auto;
    padding-right: 15px;
}
.page-content::-webkit-scrollbar { width: 8px; }
.page-content::-webkit-scrollbar-track { background: var(--bg-surface-alt); border-radius: var(--radius-sm); }
.page-content::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: var(--radius-sm); }
.page-content::-webkit-scrollbar-thumb:hover { background: var(--text-secondary); }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-pencil-square"></i> Edit Invoice Template</h1>
            <p>Customize your invoice print template</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>/invoice/print/53" class="btn btn-outline-info" target="_blank">
                <i class="bi bi-eye me-2"></i>Preview in Print Page
            </a>
            <a href="<?php echo BASE_URL; ?>/invoice/templates" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Templates
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

<?php if (!$template || empty($template)): ?>
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle me-2"></i>Template not found! Please go to Invoice Templates and select a valid template.
</div>
<?php else: ?>

<form action="<?php echo BASE_URL; ?>/invoice/editTemplate/<?php echo $template['id'] ?? ''; ?>" method="POST">
    <div class="page-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="template-editor mb-4">
                <h5 class="mb-4"><i class="bi bi-gear me-2"></i>Basic Settings</h5>
                
                <div class="mb-3">
                    <label class="form-label">Template Name <span class="text-danger">*</span></label>
                    <input type="text" name="template_name" class="form-control" value="<?php echo htmlspecialchars($template['template_name'] ?? 'Default Template'); ?>" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Template Type</label>
                        <select name="template_type" class="form-select" id="templateType">
                            <option value="thermal" <?php echo ($template['template_type'] ?? 'thermal') === 'thermal' ? 'selected' : ''; ?>>Thermal (58mm)</option>
                            <option value="a4" <?php echo ($template['template_type'] ?? '') === 'a4' ? 'selected' : ''; ?>>A4</option>
                            <option value="a5" <?php echo ($template['template_type'] ?? '') === 'a5' ? 'selected' : ''; ?>>A5</option>
                            <option value="pos" <?php echo ($template['template_type'] ?? '') === 'pos' ? 'selected' : ''; ?>>POS (80mm)</option>
                            <option value="thermal" <?php echo ($template['template_type'] ?? '') === 'thermal' ? 'selected' : ''; ?>>Thermal (58mm)</option>
                            <option value="general" <?php echo ($template['template_type'] ?? '') === 'general' ? 'selected' : ''; ?>>General Invoice</option>
                            <option value="custom" <?php echo ($template['template_type'] ?? '') === 'custom' ? 'selected' : ''; ?>>Custom HTML</option>
                        </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Print Size</label>
                            <select name="print_size" class="form-select">
                                <option value="auto" <?php echo ($template['print_size'] ?? 'auto') === 'auto' ? 'selected' : ''; ?>>Auto (Template Based)</option>
                                <option value="58mm" <?php echo ($template['print_size'] ?? '') === '58mm' ? 'selected' : ''; ?>>Thermal (58mm)</option>
                                <option value="80mm" <?php echo ($template['print_size'] ?? '') === '80mm' ? 'selected' : ''; ?>>POS (80mm)</option>
                                <option value="a5" <?php echo ($template['print_size'] ?? '') === 'a5' ? 'selected' : ''; ?>>A5 (148mm)</option>
                                <option value="a4" <?php echo ($template['print_size'] ?? '') === 'a4' ? 'selected' : ''; ?>>A4 (210mm)</option>
                            </select>
                        </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Color Scheme</label>
                    <div class="color-options">
                        <input type="radio" name="color_scheme" value="#667eea" class="btn-check" id="color1" <?php echo ($template['color_scheme'] ?? '#667eea') === '#667eea' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#667eea;" for="color1"></label>
                        
                        <input type="radio" name="color_scheme" value="#2c3e50" class="btn-check" id="color2" <?php echo ($template['color_scheme'] ?? '') === '#2c3e50' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#2c3e50;" for="color2"></label>
                        
                        <input type="radio" name="color_scheme" value="#16a085" class="btn-check" id="color3" <?php echo ($template['color_scheme'] ?? '') === '#16a085' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#16a085;" for="color3"></label>
                        
                        <input type="radio" name="color_scheme" value="#e74c3c" class="btn-check" id="color4" <?php echo ($template['color_scheme'] ?? '') === '#e74c3c' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#e74c3c;" for="color4"></label>
                        
                        <input type="radio" name="color_scheme" value="#8e44ad" class="btn-check" id="color5" <?php echo ($template['color_scheme'] ?? '') === '#8e44ad' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#8e44ad;" for="color5"></label>
                        
                        <input type="radio" name="color_scheme" value="#f39c12" class="btn-check" id="color6" <?php echo ($template['color_scheme'] ?? '') === '#f39c12' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#f39c12;" for="color6"></label>
                        
                        <input type="radio" name="color_scheme" value="#000000" class="btn-check" id="color7" <?php echo ($template['color_scheme'] ?? '') === '#000000' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary color-option" style="background:#000000;" for="color7"></label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?php echo ($template['is_active'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>
            
            <div class="template-editor mb-4">
                <h5 class="mb-4"><i class="bi bi-card-text me-2"></i>Content Settings</h5>
                
                <div class="mb-3">
                    <label class="form-label">Header Text</label>
                    <textarea name="header_text" class="form-control" rows="3" placeholder="Company Name&#10;Address&#10;Phone"><?php echo htmlspecialchars($template['header_text'] ?? ''); ?></textarea>
                    <small class="text-muted">Line breaks will be preserved</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Footer Text</label>
                    <textarea name="footer_text" class="form-control" rows="2" placeholder="Thank you message"><?php echo htmlspecialchars($template['footer_text'] ?? ''); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Terms & Conditions</label>
                    <textarea name="terms_content" class="form-control" rows="3" placeholder="Terms and conditions content"><?php echo htmlspecialchars($template['terms_content'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="template-editor">
                <h5 class="mb-4"><i class="bi bi-eye me-2"></i>Display Options</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_logo" id="showLogo" <?php echo ($template['show_logo'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showLogo">Show Company Logo</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_barcode" id="showBarcode" <?php echo ($template['show_barcode'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showBarcode">Show Barcode</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_qr_code" id="showQrCode" <?php echo ($template['show_qr_code'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showQrCode">Show QR Code</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_terms" id="showTerms" <?php echo ($template['show_terms'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showTerms">Show Terms & Conditions</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_customer_signature" id="showCustomerSig" <?php echo ($template['show_customer_signature'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showCustomerSig">Customer Signature</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="show_cashier_signature" id="showCashierSig" <?php echo ($template['show_cashier_signature'] ?? '') === 'yes' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="showCashierSig">Cashier Signature</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="template-editor position-sticky" style="top: 20px;">
                <h5 class="mb-4"><i class="bi bi-eye me-2"></i>Live Preview</h5>
                
                <div class="preview-section" id="previewArea" style="min-height: 400px;">
                    <div style="text-align: center; padding: 20px;">
                        <p><i class="bi bi-eye" style="font-size: 3rem; color: #667eea;"></i></p>
                        <p><strong>Click below to preview template</strong></p>
                        <a href="<?php echo BASE_URL; ?>/invoice/print/53" class="btn btn-primary" target="_blank">
                            <i class="bi bi-printer"></i> Preview in Print Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-4" id="customHtmlSection" style="display: <?php echo ($template['template_type'] ?? '') === 'custom' ? 'block' : 'none'; ?>;">
                <h5 class="mb-3"><i class="bi bi-code me-2"></i>Custom HTML</h5>
                <textarea name="custom_html" class="form-control font-monospace" rows="12" placeholder="<div>{{INVOICE_NUMBER}}</div>">{{ITEMS_TABLE}}</textarea>
                <small class="text-muted d-block mt-2">
                    <strong>Available Placeholders:</strong><br>
                    {{INVOICE_NUMBER}} | {{DATE}} | {{TIME}} | {{CUSTOMER}} | {{CASHIER}}<br>
                    {{SUBTOTAL}} | {{DISCOUNT}} | {{TAX}} | {{TOTAL}} | {{PAID}} | {{DUE}}<br>
                    {{SITE_NAME}} | {{COMPANY_ADDRESS}} | {{COMPANY_PHONE}} | {{ITEMS_TABLE}}
                </small>
                <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="insertSampleTemplate()">
                    <i class="bi bi-file-earmark-code"></i> Insert Sample Template
                </button>
            </div>
                
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-check-lg me-2"></i>Save Template
                </button>
                <a href="<?php echo BASE_URL; ?>/invoice/templates" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
</form>

<script>
document.getElementById('templateType').addEventListener('change', function() {
    var customSection = document.getElementById('customHtmlSection');
    if (customSection) {
        customSection.style.display = this.value === 'custom' ? 'block' : 'none';
    }
});

document.querySelectorAll('input[name="color_scheme"]').forEach(function(radio) {
    radio.addEventListener('change', updatePreview);
});

document.querySelectorAll('select[name="font_size"]').forEach(function(select) {
    select.addEventListener('change', updatePreview);
});

document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', updatePreview);
});

document.querySelectorAll('textarea[name="header_text"], textarea[name="footer_text"], textarea[name="terms_content"]').forEach(function(textarea) {
    textarea.addEventListener('input', updatePreview);
});

function updatePreview() {
    var colorScheme = document.querySelector('input[name="color_scheme"]:checked')?.value || '#667eea';
    var fontSize = document.querySelector('select[name="font_size"]')?.value || 'medium';
    var fontSizes = {'small': '12px', 'medium': '14px', 'large': '16px'};
    
    var previewArea = document.getElementById('previewArea');
    var borderBottom = previewArea.querySelector('div:first-child');
    if (borderBottom) {
        borderBottom.style.borderBottomColor = colorScheme;
    }
    var h6 = previewArea.querySelector('h6');
    if (h6) {
        h6.style.color = colorScheme;
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function insertSampleTemplate() {
    var sampleTemplate = `<div class="container-fluid invoice-container" style="font-family: 'Segoe UI', Arial, sans-serif; max-width: 500px; margin: auto; border: 1px solid #eee; padding: 20px;">
  <header style="text-align: center; margin-bottom: 15px;">
    <h5 style="margin: 0; font-size: 20px; text-transform: uppercase;">{{SITE_NAME}}</h5>
    <small style="color: var(--text-secondary);">{{COMPANY_ADDRESS}}<br>Phone: {{COMPANY_PHONE}}</small>
    <hr style="border: 0; border-top: 1px dashed var(--border); margin: 15px 0;">
  </header>

  <main>
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 13px;">
      <div><strong>Inv:</strong> {{INVOICE_NUMBER}}<br><strong>Date:</strong> {{DATE}}</div>
      <div style="text-align: right;"><strong>Customer:</strong> {{CUSTOMER}}<br><strong>Cashier:</strong> {{CASHIER}}</div>
    </div>

    <div style="border: 1px solid #dee2e6; border-radius: 5px; overflow: hidden; margin-bottom: 10px;">
      <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
          <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <th style="padding: 10px; text-align: left;">Item Description</th>
            <th style="padding: 10px; text-align: center;">Qty</th>
            <th style="padding: 10px; text-align: right;">Price</th>
            <th style="padding: 10px; text-align: right;">Total</th>
          </tr>
        </thead>
        <tbody>
          {{ITEMS_TABLE}}
        </tbody>
      </table>
    </div>

    <table style="width: 100%; max-width: 200px; margin-left: auto; font-size: 12px; border-top: 1px solid #eee; margin-bottom: 5px;">
      <tr><td style="padding: 2px 5px; text-align: right;">Subtotal:</td><td style="padding: 2px 5px; text-align: right;">{{SUBTOTAL}}</td></tr>
      <tr><td style="padding: 2px 5px; text-align: right;">Discount:</td><td style="padding: 2px 5px; text-align: right;">{{DISCOUNT}}</td></tr>
      <tr><td style="padding: 2px 5px; text-align: right;">Tax:</td><td style="padding: 2px 5px; text-align: right;">{{TAX}}</td></tr>
      <tr><td style="padding: 2px 5px; text-align: right; font-weight: bold;">Total:</td><td style="padding: 2px 5px; text-align: right; font-weight: bold;">{{TOTAL}}</td></tr>
      <tr><td style="padding: 2px 5px; text-align: right;">Paid:</td><td style="padding: 2px 5px; text-align: right;">{{PAID}}</td></tr>
      <tr><td style="padding: 2px 5px; text-align: right; font-weight: bold; color: #dc2626;">Due:</td><td style="padding: 2px 5px; text-align: right; font-weight: bold; color: #dc2626;">{{DUE}}</td></tr>
    </table>

    <div style="text-align: right; margin-top: 0px; font-size: 11px; padding-right: 5px;">
      <strong>Payment Method:</strong> {{PAYMENT_METHOD}}
    </div>
  </main>

  <footer style="margin-top: 50px;">
    <div style="display: flex; justify-content: space-between; gap: 40px;">
      <div style="text-align: center; flex: 1;">
<div style="border-top: 1px solid var(--text-primary); margin-bottom: 5px;"></div>
        <span style="font-size: 11px; font-weight: bold; color: var(--text-secondary);">Customer Signature</span>
      </div>
      <div style="text-align: center; flex: 1;">
        <div style="border-top: 1px solid var(--text-primary); margin-bottom: 5px;"></div>
        <span style="font-size: 11px; font-weight: bold; color: var(--text-secondary);">Cashier Signature</span>
      </div>
    </div>

    <div style="text-align: center; margin-top: 30px; font-size: 11px; color: var(--text-muted); font-style: italic;">
      <p style="margin: 0;">{{FOOTER_TEXT}}</p>
      <p style="margin: 5px 0;">*** Thank You For Shopping With Us ***</p>
    </div>
  </footer>
</div>`;
    
    var textarea = document.querySelector('textarea[name="custom_html"]');
    if (textarea) {
        textarea.value = sampleTemplate;
    }
}

const debouncedUpdate = debounce(updatePreview, 300);
document.querySelectorAll('textarea').forEach(function(textarea) {
    textarea.addEventListener('input', debouncedUpdate);
});
</script>

<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
