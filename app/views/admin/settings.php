<?php 
$pageTitle = 'System Settings';
$currentPage = 'admin';
$subPage = 'settings';
ob_start();
?>

<style>
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.settings-card { background: var(--bg-card); border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-md); margin-bottom: 20px; }
.settings-card h4 { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 8px; }
.form-group { margin-bottom: 14px; }
.form-group label { display: block; font-size: 12px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 13px; transition: all 0.3s; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--primary); outline: none; }
.btn-save { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
.btn-save:hover { opacity: 0.9; }
.switch { position: relative; display: inline-block; width: 44px; height: 22px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--text-muted); transition: all 0.3s; border-radius: 22px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: all 0.3s; border-radius: 50%; }
input:checked + .slider { background-color: var(--success); }
input:checked + .slider:before { transform: translateX(22px); }
.logo-preview { width: 100px; height: 100px; border: 2px dashed var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; overflow: hidden; background: var(--bg-surface-alt); }
.logo-preview img { max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 50%; }
.logo-preview i { font-size: 2.5rem; color: var(--border); }
.logo-tabs { display: flex; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; margin-bottom: 10px; width: fit-content; margin: 0 auto 10px; }
.logo-tabs button { padding: 8px 16px; border: none; background: var(--bg-card); cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 6px; }
.logo-tabs button:first-child { border-right: 1px solid var(--border); }
.logo-tabs button.active { background: var(--primary); color: white; }
.logo-option { display: none; }
.logo-option.active { display: block; }
.logo-option input[type="file"], .logo-option input[type="url"] { width: 100%; }
</style>

<div class="page-header">
    <h1><i class="bi bi-gear"></i> System Settings</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back to Admin</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div style="background:var(--success);color:#fff;border:1px solid var(--success);padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:20px;">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
<div style="background:var(--danger);color:#fff;border:1px solid var(--danger);padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:20px;">
    <i class="bi bi-x-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>/admin/updateSettings" method="POST" enctype="multipart/form-data">
    <div class="settings-grid">
        <!-- General Settings -->
        <div class="settings-card">
            <h4><i class="bi bi-building"></i> Company Information</h4>
            <div class="form-group text-center">
                <?php 
                $logoUrl = '';
                if (!empty(COMPANY_LOGO)) {
                    if (strpos(COMPANY_LOGO, 'http') === 0) {
                        $logoUrl = COMPANY_LOGO;
                    } else {
                        $logoUrl = BASE_URL . '/' . COMPANY_LOGO;
                    }
                }
                ?>
                <div class="logo-preview" id="logoPreview">
                    <?php if (!empty($logoUrl)): ?>
                        <img src="<?php echo $logoUrl; ?>" alt="Company Logo" id="currentLogo">
                    <?php else: ?>
                        <i class="bi bi-image"></i>
                    <?php endif; ?>
                </div>
                
                <!-- Tab Buttons -->
                <div class="logo-tabs">
                    <button type="button" class="active" onclick="showLogoOption('upload')">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                    <button type="button" onclick="showLogoOption('url')">
                        <i class="bi bi-link-45deg"></i> URL
                    </button>
                </div>
                
                <!-- Upload Option -->
                <div class="logo-option active" id="uploadOption">
                    <input type="file" name="company_logo" id="companyLogo" accept="image/*" onchange="previewLogo(this)">
                    <small class="text-muted d-block mt-2">Select image from computer</small>
                </div>
                
                <!-- URL Option -->
                <div class="logo-option" id="urlOption">
                    <input type="text" name="company_logo_url" id="companyLogoUrl" class="form-control" placeholder="https://example.com/logo.png" value="<?php echo strpos($logoUrl, 'http') === 0 ? htmlspecialchars($logoUrl) : ''; ?>">
                    <small class="text-muted d-block mt-2">Paste image URL</small>
                </div>
            </div>
            <div class="form-group">
                <label>Site Name</label>
                <input type="text" name="site_name" value="<?php echo htmlspecialchars(SITE_NAME); ?>">
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" value="<?php echo htmlspecialchars(COMPANY_NAME); ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="company_phone" value="<?php echo htmlspecialchars(COMPANY_PHONE); ?>">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="company_email" value="<?php echo htmlspecialchars(COMPANY_EMAIL); ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="company_address" rows="2"><?php echo htmlspecialchars(COMPANY_ADDRESS); ?></textarea>
            </div>
        </div>
        
        <!-- Business Settings -->
        <div class="settings-card">
            <h4><i class="bi bi-cash-stack"></i> Business Settings</h4>
            <div class="form-group">
                <label>Currency Symbol</label>
                <select name="currency">
                    <option value="৳" <?php echo DEFAULT_CURRENCY === '৳' ? 'selected' : ''; ?>>৳ BDT (Taka)</option>
                    <option value="Tk" <?php echo DEFAULT_CURRENCY === 'Tk' ? 'selected' : ''; ?>>Tk Taka</option>
                    <option value="$" <?php echo DEFAULT_CURRENCY === '$' ? 'selected' : ''; ?>>$ USD</option>
                    <option value="€" <?php echo DEFAULT_CURRENCY === '€' ? 'selected' : ''; ?>>€ Euro</option>
                    <option value="₹" <?php echo DEFAULT_CURRENCY === '₹' ? 'selected' : ''; ?>>₹ Rupee</option>
                </select>
            </div>
            <div class="form-group">
                <label>Default Tax Rate (%)</label>
                <input type="number" name="tax_rate" value="<?php echo TAX_RATE; ?>" step="0.01" min="0" max="100">
            </div>
            <div class="form-group">
                <label>Low Stock Alert Threshold</label>
                <input type="number" name="low_stock_threshold" value="<?php echo LOW_STOCK_THRESHOLD; ?>" min="1">
            </div>
            <div class="form-group">
                <label>Show Barcode Field in Product</label>
                <div class="d-flex align-items-center gap-3">
                    <label class="switch">
                        <input type="checkbox" id="showBarcodeToggle" <?php echo (defined('SHOW_BARCODE') && SHOW_BARCODE) ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                    <span class="text-muted" id="barcodeStatus"><?php echo (defined('SHOW_BARCODE') && SHOW_BARCODE) ? 'ON' : 'OFF'; ?></span>
                    <input type="hidden" name="show_barcode" id="showBarcodeValue" value="<?php echo (defined('SHOW_BARCODE') && SHOW_BARCODE) ? '1' : '0'; ?>">
                </div>
                <small class="text-muted">Toggle to show/hide barcode field in Add/Edit Product</small>
            </div>
        </div>
    </div>
    
    <div class="text-end" style="margin-top: 20px;">
        <button type="submit" class="btn-save">
            <i class="bi bi-check-lg me-2"></i>Save Settings
        </button>
    </div>
</form>

<script>
function showLogoOption(option) {
    document.querySelectorAll('.logo-tabs button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.logo-option').forEach(div => div.classList.remove('active'));
    
    if (option === 'upload') {
        document.querySelector('.logo-tabs button:first-child').classList.add('active');
        document.getElementById('uploadOption').classList.add('active');
    } else {
        document.querySelector('.logo-tabs button:last-child').classList.add('active');
        document.getElementById('urlOption').classList.add('active');
    }
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('currentLogo').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('companyLogoUrl').addEventListener('input', function(e) {
    var url = e.target.value;
    if (url) {
        document.getElementById('currentLogo').src = url;
    }
});

document.getElementById('showBarcodeToggle').addEventListener('change', function() {
    document.getElementById('barcodeStatus').textContent = this.checked ? 'ON' : 'OFF';
    document.getElementById('showBarcodeValue').value = this.checked ? '1' : '0';
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
