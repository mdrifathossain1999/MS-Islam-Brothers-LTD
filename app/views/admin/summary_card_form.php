<?php 
$pageTitle = 'Edit Summary Card';
$currentPage = 'admin';
$subPage = 'summaryCards';
ob_start();
$card = $card ?? null;
?>

<style>
:root {
    --sc-card-bg: #ffffff;
    --sc-text: #1e293b;
}
body.dark-mode {
    --sc-card-bg: #1e293b;
    --sc-text: #f1f5f9;
}
.sc-card { background: var(--sc-card-bg); border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.color-options { display: flex; gap: 8px; flex-wrap: wrap; }
.color-option { width: 32px; height: 32px; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; }
.color-option:hover, .color-option.selected { border-color: #333; transform: scale(1.1); }
.color-blue { background: linear-gradient(135deg, #667eea, #764ba2); }
.color-green { background: linear-gradient(135deg, #11998e, #38ef7d); }
.color-orange { background: linear-gradient(135deg, #f093fb, #f5576c); }
.color-red { background: linear-gradient(135deg, #ff416c, #ff4b2b); }
.color-cyan { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.color-yellow { background: linear-gradient(135deg, #f59e0b, #d97706); }
.color-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
</style>

<div class="sc-card">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?php echo BASE_URL; ?>/admin/summaryCards" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Summary Card</h4>
    </div>

    <?php if ($card): ?>
    <form action="<?php echo BASE_URL; ?>/admin/editSummaryCard/<?php echo $card['id']; ?>" method="POST">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Card Title *</label>
                    <input type="text" class="form-control" name="card_title" required value="<?php echo htmlspecialchars($card['card_title']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Card Type</label>
                    <select class="form-select" name="card_type" id="cardTypeSelect">
                        <option value="custom" <?php echo $card['card_type'] === 'custom' ? 'selected' : ''; ?>>Custom Query</option>
                        <option value="users" <?php echo $card['card_type'] === 'users' ? 'selected' : ''; ?>>Total Users</option>
                        <option value="products" <?php echo $card['card_type'] === 'products' ? 'selected' : ''; ?>>Total Products</option>
                        <option value="customers" <?php echo $card['card_type'] === 'customers' ? 'selected' : ''; ?>>Total Customers</option>
                        <option value="low_stock" <?php echo $card['card_type'] === 'low_stock' ? 'selected' : ''; ?>>Low Stock Count</option>
                    </select>
                </div>
                <div class="mb-3" id="customQuerySection" style="<?php echo $card['card_type'] !== 'custom' ? 'display:none;' : ''; ?>">
                    <label class="form-label">Custom SQL Query</label>
                    <textarea class="form-control" name="custom_query" rows="2"><?php echo htmlspecialchars($card['custom_query'] ?? ''); ?></textarea>
                    <small class="text-muted">Query must return a 'count' column</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon Class</label>
                    <select class="form-select" name="icon_class">
                        <?php
                        $icons = [
                            'bi bi-people' => 'People',
                            'bi bi-box-seam' => 'Box/Package',
                            'bi bi-cart-check' => 'Cart',
                            'bi bi-currency-dollar' => 'Dollar',
                            'bi bi-graph-up' => 'Graph',
                            'bi bi-receipt' => 'Receipt',
                            'bi bi-speedometer2' => 'Speed',
                            'bi bi-wallet2' => 'Wallet',
                            'bi bi-building' => 'Building',
                            'bi bi-truck' => 'Truck',
                            'bi bi-collection' => 'Collection',
                            'bi bi-bar-chart' => 'Bar Chart',
                            'bi bi-pie-chart' => 'Pie Chart',
                            'bi bi-heart' => 'Heart',
                            'bi bi-star' => 'Star'
                        ];
                        foreach ($icons as $icon => $label): ?>
                            <option value="<?php echo $icon; ?>" <?php echo $card['icon_class'] === $icon ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Color</label>
                    <div class="color-options">
                        <?php
                        $colors = ['purple', 'green', 'orange', 'red', 'cyan', 'yellow', 'blue'];
                        foreach ($colors as $color): ?>
                            <div class="color-option color-<?php echo $color; ?> <?php echo $card['color_class'] === $color ? 'selected' : ''; ?>" data-color="<?php echo $color; ?>"></div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="color_class" id="selectedColor" value="<?php echo $card['color_class']; ?>">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="<?php echo $card['display_order']; ?>" min="1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?php echo $card['is_active'] === 'yes' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="isActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <h6 class="mb-3">Preview</h6>
                    <div class="preview-card" id="cardPreview" style="background: linear-gradient(135deg, var(--preview-color-1, #8b5cf6), var(--preview-color-2, #7c3aed));">
                        <i id="previewIcon" class="<?php echo $card['icon_class']; ?>" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <h3 id="previewTitle"><?php echo htmlspecialchars($card['card_title']); ?></h3>
                        <p id="previewType"><?php echo ucfirst($card['card_type']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Save Changes
            </button>
            <a href="<?php echo BASE_URL; ?>/admin/summaryCards" class="btn btn-secondary">Cancel</a>
            <?php if ($card['card_type'] === 'custom'): ?>
            <a href="<?php echo BASE_URL; ?>/admin/deleteSummaryCard/<?php echo $card['id']; ?>" class="btn btn-danger ms-auto" onclick="return confirm('Delete this card?')">
                <i class="bi bi-trash me-2"></i>Delete
            </a>
            <?php endif; ?>
        </div>
    </form>
    <?php else: ?>
    <div class="alert alert-danger">Card not found!</div>
    <a href="<?php echo BASE_URL; ?>/admin/summaryCards" class="btn btn-secondary">Back</a>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedColor').value = this.dataset.color;
        updatePreview();
    });
});

document.getElementById('cardTypeSelect').addEventListener('change', function() {
    const querySection = document.getElementById('customQuerySection');
    if (this.value === 'custom') {
        querySection.style.display = 'block';
    } else {
        querySection.style.display = 'none';
    }
    document.getElementById('previewType').textContent = this.options[this.selectedIndex].text;
});

document.querySelector('input[name="card_title"]').addEventListener('input', function() {
    document.getElementById('previewTitle').textContent = this.value || 'Card Title';
});

document.querySelector('select[name="icon_class"]').addEventListener('change', function() {
    document.getElementById('previewIcon').className = this.value;
});

function updatePreview() {
    const colors = {
        'purple': ['#8b5cf6', '#7c3aed'],
        'green': ['#11998e', '#38ef7d'],
        'orange': ['#f093fb', '#f5576c'],
        'red': ['#ff416c', '#ff4b2b'],
        'cyan': ['#4facfe', '#00f2fe'],
        'yellow': ['#f59e0b', '#d97706'],
        'blue': ['#667eea', '#764ba2']
    };
    const color = document.getElementById('selectedColor').value;
    const preview = document.getElementById('cardPreview');
    preview.style.setProperty('--preview-color-1', colors[color][0]);
    preview.style.setProperty('--preview-color-2', colors[color][1]);
}

document.querySelectorAll('.color-option').forEach(option => {
    option.addEventListener('click', updatePreview);
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
