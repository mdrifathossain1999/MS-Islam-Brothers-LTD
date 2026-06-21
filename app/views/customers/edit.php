<?php 
$pageTitle = 'Edit Customer';
$currentPage = 'customers';
$customer = $customer;
$due_amount = $customer['total_amount'] - $customer['paid_amount'];
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    padding: 25px 30px;
    border-radius: var(--radius-lg);
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
}

.form-card { 
    background: var(--bg-card); 
    border-radius: var(--radius-lg); 
    padding: 24px; 
    box-shadow: var(--shadow-md); 
    border: 1px solid var(--border);
}
.form-group { margin-bottom: 16px; }
.form-group label { 
    display: block; 
    font-size: 13px; 
    font-weight: 600; 
    color: var(--text-secondary); 
    margin-bottom: 8px; 
}
.form-group label span { color: var(--danger); }
.form-group input, .form-group select, .form-group textarea { 
    width: 100%; 
    padding: 12px 16px; 
    border: 2px solid var(--border); 
    border-radius: var(--radius-sm); 
    font-size: 14px; 
    background: var(--bg-surface);
    color: var(--text-primary);
    transition: var(--transition);
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { 
    border-color: var(--primary); 
    outline: none; 
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}
</style>

<div class="page-header">
    <h1 style="margin: 0; font-size: 24px; font-weight: 800;">
        <i class="bi bi-pencil-square me-3"></i>Edit Customer
    </h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Update customer information</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="form-card">
            <form action="<?php echo BASE_URL; ?>/customer/edit/<?php echo $customer['id']; ?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name <span>*</span></label>
                            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customer['customer_name']); ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?php echo ($customer['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($customer['status'] ?? 'active') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3" class="form-control"><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Customer
                        </button>
                        <a href="<?php echo BASE_URL; ?>/customer/index" class="btn btn-outline-primary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-card">
            <h5 style="color: var(--text-primary); margin-bottom: 16px;">
                <i class="bi bi-info-circle me-2"></i>Customer Stats
            </h5>
            <div style="padding: 16px; background: var(--bg-surface-alt); border-radius: var(--radius-sm);">
                <p style="margin: 8px 0; color: var(--text-secondary);">
                    <strong>Total Purchases:</strong> <?php echo DEFAULT_CURRENCY.number_format($customer['total_amount'] ?? 0, 2); ?>
                </p>
                <p style="margin: 8px 0; color: var(--text-secondary);">
                    <strong>Total Paid:</strong> <?php echo DEFAULT_CURRENCY.number_format($customer['paid_amount'] ?? 0, 2); ?>
                </p>
                <p style="margin: 8px 0; color: var(--danger); font-weight: 600;">
                    <strong>Due Amount:</strong> <?php echo DEFAULT_CURRENCY.number_format(max(0, $due_amount), 2); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';