<?php 
$pageTitle = 'Edit Customer Type';
$currentPage = 'admin';
$subPage = 'customerTypes';
ob_start();
?>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.form-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0; }
.back-btn { width: 40px; height: 40px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s; }
.back-btn:hover { border-color: #667eea; color: #667eea; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px; }
.form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px 14px; font-size: 14px; border: 1px solid #e2e8f0; border-radius: 8px; transition: all 0.2s; }
.form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
.form-group .hint { font-size: 12px; color: #94a3b8; margin-top: 4px; }
.checkbox-group { display: flex; align-items: center; gap: 10px; }
.checkbox-group input[type="checkbox"] { width: 18px; height: 18px; }
.form-actions { display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0; }
.submit-btn { flex: 1; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.submit-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); }
.cancel-btn { padding: 12px 24px; background: #fff; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.cancel-btn:hover { border-color: #64748b; color: #374151; }
</style>

<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> Edit Customer Type</h1>
    <p>Update customer type information</p>
</div>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <a href="<?php echo BASE_URL; ?>/admin/customerTypes" class="back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 style="margin: 0; font-size: 20px; font-weight: 600;">Edit: <?php echo htmlspecialchars($customer_type['type_name']); ?></h2>
                <small class="text-muted">Update the customer type details</small>
            </div>
        </div>

        <form action="<?php echo BASE_URL; ?>/admin/editCustomerType/<?php echo $customer_type['id']; ?>" method="POST">
            <div class="form-group">
                <label>Type Name <span style="color:red">*</span></label>
                <input type="text" name="type_name" value="<?php echo htmlspecialchars($customer_type['type_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Discount (%)</label>
                <input type="number" name="discount_percent" value="<?php echo $customer_type['discount_percent']; ?>" min="0" max="100" step="0.01">
                <p class="hint">Discount percentage applied to orders for this customer type</p>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of this customer type"><?php echo htmlspecialchars($customer_type['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Display Order</label>
                <input type="number" name="display_order" value="<?php echo $customer_type['display_order']; ?>" min="0">
                <p class="hint">Lower numbers appear first</p>
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="is_active" id="is_active" <?php echo $customer_type['is_active'] === 'yes' ? 'checked' : ''; ?>>
                    <label for="is_active" style="margin: 0;">Active</label>
                </div>
                <p class="hint">Inactive customer types won't appear in the dropdown</p>
            </div>

            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/admin/customerTypes" class="cancel-btn">Cancel</a>
                <button type="submit" class="submit-btn">
                    <i class="bi bi-check-lg me-2"></i>Update Customer Type
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
