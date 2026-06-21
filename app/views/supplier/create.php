<?php 
$pageTitle = 'Add Supplier';
$currentPage = 'supplier';
ob_start();
?>

<style>
.page-header { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 20px 28px; border-radius: 12px; color: white; margin-bottom: 20px; }
.page-header h1 { margin: 0; font-size: 1.25rem; font-weight: 700; }
.form-card { background: var(--bg-card); border-radius: 12px; padding: 24px; box-shadow: var(--shadow-md); border: 1px solid var(--border); }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
.form-group label span { color: var(--danger); }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; background: var(--bg-surface); color: var(--text-primary); transition: border-color 0.2s; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
</style>

<div class="page-header">
    <h1><i class="bi bi-truck me-2"></i>Add New Supplier</h1>
</div>

<div class="form-card">
    <form action="<?php echo BASE_URL; ?>/supplier/create" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Supplier Name <span>*</span></label>
                    <input type="text" name="supplier_name" required placeholder="Enter supplier name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" placeholder="Enter company name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone <span>*</span></label>
                    <input type="text" name="phone" required placeholder="Enter phone number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email address">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3" placeholder="Enter address"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Opening Balance</label>
                    <input type="number" name="balance" step="0.01" value="0" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Create Supplier</button>
                <a href="<?php echo BASE_URL; ?>/supplier/index" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-circle me-2"></i>Cancel</a>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
