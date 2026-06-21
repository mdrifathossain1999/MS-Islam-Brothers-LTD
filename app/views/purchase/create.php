<?php 
$pageTitle = 'Add Purchase';
$currentPage = 'purchase';
ob_start();
?>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 15px 24px;
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.35);
}
.page-header h1 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.page-header h1 i {
    font-size: 1.4rem;
    background: rgba(255,255,255,0.25);
    padding: 10px;
    border-radius: 10px;
}
.page-header .btn {
    background: white;
    color: #667eea;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.form-control {
    border: 2px solid var(--border);
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 14px;
    transition: all 0.2s;
}
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    outline: none;
}
.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}
.card-modern {
    background: var(--bg-card);
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
    overflow: hidden;
}
.card-header-modern {
    background: var(--bg-surface-alt);
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-header-modern h5 {
    margin: 0;
    font-weight: 600;
    color: var(--text-primary);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
.btn-secondary {
    background: var(--bg-surface-alt);
    color: var(--text-primary);
    border: 2px solid var(--border);
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}
.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom th {
    background: var(--bg-surface-alt);
    padding: 12px 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 2px solid var(--border);
    text-align: left;
}
.table-custom td {
    padding: 12px 16px;
    font-size: 13px;
    border-bottom: 1px solid var(--border);
    color: var(--text-secondary);
}
</style>

<div class="page-header">
    <h1><i class="bi bi-cart-plus"></i> Add New Purchase</h1>
    <a href="<?php echo BASE_URL; ?>/purchase/index" class="btn">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>/purchase/create">
    <div class="row">
        <div class="col-md-8">
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5><i class="bi bi-box-seam"></i> Product Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['id']; ?>">
                                <?php echo $product['product_name']; ?> - <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($product['cost_price'], 2); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Unit Cost</label>
                                <input type="number" name="cost" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5><i class="bi bi-info-circle"></i> Purchase Info</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Select Supplier --</option>
                            <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?php echo $supplier['id']; ?>">
                                <?php echo $supplier['supplier_name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Amount</label>
                        <input type="number" name="total_amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" name="paid_amount" class="form-control" step="0.01" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary flex-grow-1">
                    <i class="bi bi-check-lg"></i> Save Purchase
                </button>
                <a href="<?php echo BASE_URL; ?>/purchase/index" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';