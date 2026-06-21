<?php 
$pageTitle = 'Add Customer';
$currentPage = 'customers';
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
        <i class="bi bi-person-plus me-3"></i>Add New Customer
    </h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Create a new customer account</p>
</div>

<div class="form-card">
    <form action="<?php echo BASE_URL; ?>/customer/create" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Customer Name <span>*</span></label>
                    <input type="text" name="customer_name" required placeholder="Enter customer name" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone Number <span>*</span></label>
                    <input type="text" name="phone" required placeholder="Enter phone number" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email address" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Customer Type</label>
                    <select name="customer_type" class="form-select">
                        <option value="Retailer">Retailer</option>
                        <option value="Dealer">Dealer</option>
                        <option value="Wholesaler">Wholesaler</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3" placeholder="Enter address" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create Customer
                </button>
                <a href="<?php echo BASE_URL; ?>/customer/index" class="btn btn-outline-primary ms-2">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';