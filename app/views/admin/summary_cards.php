<?php 
$pageTitle = 'Summary Cards';
$currentPage = 'admin';
$subPage = 'summaryCards';
ob_start();
?>

<style>
.sc-card { background: var(--admin-card, #fff); border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
.sc-section { margin-bottom: 24px; }
.sc-section h4 { font-size: 14px; font-weight: 600; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.color-options { display: flex; gap: 6px; flex-wrap: wrap; }
.color-option { width: 28px; height: 28px; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; }
.color-option:hover, .color-option.selected { border-color: #333; transform: scale(1.1); }
.color-blue { background: linear-gradient(135deg, #667eea, #764ba2); }
.color-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
.color-orange { background: linear-gradient(135deg, #f093fb, #f5576c); }
.color-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }
.color-cyan { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.color-yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
.color-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.preview-card { padding: 16px; border-radius: 10px; color: #fff; min-width: 140px; text-align: center; }
.preview-card h3 { font-size: 24px; font-weight: 700; margin: 0 0 4px 0; }
.preview-card p { margin: 0; opacity: 0.9; font-size: 12px; }
</style>

<div class="page-header">
    <h1><i class="bi bi-collection"></i> Summary Cards</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="sc-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCardModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Card
        </button>
    </div>

    <div class="row">
        <?php foreach ($cards as $card): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="position-relative">
                <div class="preview-card <?php echo 'bg-' . $card['color_class']; ?>" style="background: linear-gradient(135deg, var(--card-color-1, #667eea), var(--card-color-2, #764ba2));">
                    <i class="<?php echo $card['icon_class']; ?>" style="font-size: 24px; margin-bottom: 8px;"></i>
                    <h3><?php echo htmlspecialchars($card['card_title']); ?></h3>
                    <p><?php echo ucfirst($card['card_type']); ?></p>
                </div>
                <div class="card-actions position-absolute top-0 end-0 p-2 d-flex gap-1">
                    <a href="<?php echo BASE_URL; ?>/admin/editSummaryCard/<?php echo $card['id']; ?>" class="btn btn-sm btn-light">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <?php if ($card['card_type'] === 'custom'): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/deleteSummaryCard/<?php echo $card['id']; ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this card?')">
                        <i class="bi bi-trash"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <span class="badge <?php echo $card['is_active'] === 'yes' ? 'bg-success' : 'bg-secondary'; ?>">
                        <?php echo $card['is_active'] === 'yes' ? 'Active' : 'Inactive'; ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>/admin/toggleSummaryCard/<?php echo $card['id']; ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-toggle-on me-1"></i><?php echo $card['is_active'] === 'yes' ? 'Disable' : 'Enable'; ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Card Modal -->
<div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Summary Card</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>/admin/createSummaryCard" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Card Title *</label>
                        <input type="text" class="form-control" name="card_title" required placeholder="e.g., Total Sales">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Card Type</label>
                        <select class="form-select" name="card_type" id="cardTypeSelect">
                            <option value="custom">Custom Query</option>
                            <option value="users">Total Users</option>
                            <option value="products">Total Products</option>
                            <option value="customers">Total Customers</option>
                            <option value="low_stock">Low Stock Count</option>
                        </select>
                    </div>
                    <div class="mb-3" id="customQuerySection">
                        <label class="form-label">Custom SQL Query</label>
                        <textarea class="form-control" name="custom_query" rows="2" placeholder="SELECT COUNT(*) as count FROM table_name"></textarea>
                        <small class="text-muted">Query must return a 'count' column</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <select class="form-select" name="icon_class">
                            <option value="bi bi-people">People</option>
                            <option value="bi bi-box-seam">Box/Package</option>
                            <option value="bi bi-cart-check">Cart</option>
                            <option value="bi bi-currency-dollar">Dollar</option>
                            <option value="bi bi-graph-up">Graph</option>
                            <option value="bi bi-receipt">Receipt</option>
                            <option value="bi bi-speedometer2">Speed</option>
                            <option value="bi bi-wallet2">Wallet</option>
                            <option value="bi bi-building">Building</option>
                            <option value="bi bi-truck">Truck</option>
                            <option value="bi bi-collection">Collection</option>
                            <option value="bi bi-bar-chart">Bar Chart</option>
                            <option value="bi bi-pie-chart">Pie Chart</option>
                            <option value="bi bi-heart">Heart</option>
                            <option value="bi bi-star">Star</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="color-options">
                            <div class="color-option color-purple selected" data-color="purple"></div>
                            <div class="color-option color-green" data-color="green"></div>
                            <div class="color-option color-orange" data-color="orange"></div>
                            <div class="color-option color-red" data-color="red"></div>
                            <div class="color-option color-cyan" data-color="cyan"></div>
                            <div class="color-option color-yellow" data-color="yellow"></div>
                            <div class="color-option color-blue" data-color="blue"></div>
                        </div>
                        <input type="hidden" name="color_class" id="selectedColor" value="purple">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" value="10" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Add Card
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedColor').value = this.dataset.color;
    });
});

document.getElementById('cardTypeSelect').addEventListener('change', function() {
    const querySection = document.getElementById('customQuerySection');
    if (this.value === 'custom') {
        querySection.style.display = 'block';
    } else {
        querySection.style.display = 'none';
    }
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
