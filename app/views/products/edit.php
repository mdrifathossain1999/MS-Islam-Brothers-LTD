<?php 
$pageTitle = 'Edit Product';
$currentPage = 'products';
ob_start();
?>

<style>
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
.image-upload { 
    border: 2px dashed var(--border); 
    border-radius: var(--radius-lg); 
    padding: 30px; 
    text-align: center; 
    cursor: pointer; 
    transition: all 0.2s; 
    background: var(--bg-surface-alt);
}
.image-upload:hover { 
    border-color: var(--primary); 
    background: var(--bg-hover); 
}
.image-upload img { max-width: 150px; max-height: 150px; border-radius: var(--radius-sm); margin-bottom: 10px; }
.image-upload input[type="file"] { display: none; }
</style>

<div class="form-card">
    <h4 class="mb-4" style="color: var(--text-primary);"><i class="bi bi-pencil me-2"></i>Edit Product</h4>
    
    <form action="<?php echo BASE_URL; ?>/product/edit/<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-md-8">
                <div class="row g-3">
                    <?php if ($show_barcode): ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Product Name <span>*</span></label>
                            <input type="text" name="product_name" required value="<?php echo htmlspecialchars($product['product_name']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Barcode</label>
                            <input type="text" name="barcode" value="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>">
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Product Name <span>*</span></label>
                            <input type="text" name="product_name" required value="<?php echo htmlspecialchars($product['product_name']); ?>">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category</label>
                            <div class="input-group">
                                <select name="category" id="categorySelect" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo ($product['category'] ?? '') === $cat['category'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['category']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="showQuickCategoryModal()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <div class="input-group">
                                <select name="unit" id="unitSelect" class="form-select">
                                    <option value="">Select Unit</option>
                                    <?php foreach ($units as $unit): ?>
                                    <option value="<?php echo htmlspecialchars($unit['unit_name']); ?>" <?php echo ($product['unit'] ?? '') === $unit['unit_name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($unit['unit_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" onclick="showQuickUnitModal()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cost Price <span>*</span></label>
                            <input type="number" name="cost_price" step="0.01" min="0" required value="<?php echo $product['cost_price']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sell Price <span>*</span></label>
                            <input type="number" name="sell_price" step="0.01" min="0" required value="<?php echo $product['sell_price']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_quantity" min="0" value="<?php echo $product['stock_quantity']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Low Stock Alert</label>
                            <input type="number" name="low_stock_threshold" min="0" value="<?php echo $product['low_stock_threshold']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?php echo ($product['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($product['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="2"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Image -->
            <div class="col-md-4">
                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload" onclick="document.getElementById('productImage').click()">
                        <?php 
                        $hasImage = !empty($product['image']) && file_exists($product['image']);
                        $imgSrc = $hasImage ? BASE_URL . '/' . $product['image'] : '';
                        ?>
                        <?php if ($hasImage): ?>
                        <img id="imagePreview" src="<?php echo $imgSrc; ?>" alt="Product Image" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-bottom: 10px;">
                        <svg id="imagePreviewPlaceholder" width="100" height="100" viewBox="0 0 100 100" style="display: none; border-radius: 8px; margin-bottom: 10px;">
                            <rect fill="#f1f5f9" width="100" height="100" rx="8"/>
                            <text x="50" y="45" text-anchor="middle" fill="#94a3b8" font-size="12">No Image</text>
                            <text x="50" y="60" text-anchor="middle" fill="#94a3b8" font-size="10">Click to upload</text>
                        </svg>
                        <?php else: ?>
                        <img id="imagePreview" src="" alt="Product Image" style="display: none; max-width: 150px; max-height: 150px; border-radius: 8px; margin-bottom: 10px;">
                        <svg id="imagePreviewPlaceholder" width="100" height="100" viewBox="0 0 100 100" style="border-radius: 8px; margin-bottom: 10px;">
                            <rect fill="#f1f5f9" width="100" height="100" rx="8"/>
                            <text x="50" y="45" text-anchor="middle" fill="#94a3b8" font-size="12">No Image</text>
                            <text x="50" y="60" text-anchor="middle" fill="#94a3b8" font-size="10">Click to upload</text>
                        </svg>
                        <?php endif; ?>
                        <p class="mb-0 text-muted"><i class="bi bi-cloud-arrow-up me-1"></i>Click to change image</p>
                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                    </div>
                    <input type="file" id="productImage" name="product_image" accept="image/*" onchange="previewImage(this)">
                </div>
            </div>
        </div>
        
        <div style="margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update Product
            </button>
            <a href="<?php echo BASE_URL; ?>/product/index" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- Quick Category Modal -->
<div class="modal fade" id="quickCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Add Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickCategoryForm">
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>Category Name</label>
                        <input type="text" id="newCategoryName" class="form-control" required placeholder="Enter category name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Unit Modal -->
<div class="modal fade" id="quickUnitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-rulers me-2"></i>Add Unit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickUnitForm">
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>Unit Name</label>
                        <input type="text" id="newUnitName" class="form-control" required placeholder="e.g., Piece, Kg, Box">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('imagePreviewPlaceholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function showQuickCategoryModal() {
    var modal = new bootstrap.Modal(document.getElementById('quickCategoryModal'));
    modal.show();
}

function showQuickUnitModal() {
    var modal = new bootstrap.Modal(document.getElementById('quickUnitModal'));
    modal.show();
}

var categoryUnitMap = {};
<?php 
if (is_array($category_unit_map)) {
    foreach ($category_unit_map as $cat => $unit) {
        echo "categoryUnitMap['" . addslashes($cat) . "'] = '" . addslashes($unit) . "';\n";
    }
}
?>

document.getElementById('categorySelect').addEventListener('change', function() {
    var category = this.value;
    var unitSelect = document.getElementById('unitSelect');
    if (category && categoryUnitMap[category]) {
        var mappedUnit = categoryUnitMap[category];
        for (var i = 0; i < unitSelect.options.length; i++) {
            if (unitSelect.options[i].value.toLowerCase() === mappedUnit.toLowerCase()) {
                unitSelect.selectedIndex = i;
                break;
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('quickCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var name = document.getElementById('newCategoryName').value.trim();
        if (name) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo BASE_URL; ?>/admin/addCategory', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                var select = document.getElementById('categorySelect');
                var option = document.createElement('option');
                option.value = name;
                option.text = name;
                option.selected = true;
                select.appendChild(option);
                bootstrap.Modal.getInstance(document.getElementById('quickCategoryModal')).hide();
                document.getElementById('newCategoryName').value = '';
            };
            xhr.send('category_name=' + encodeURIComponent(name));
        }
    });

    document.getElementById('quickUnitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var name = document.getElementById('newUnitName').value.trim();
        if (name) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo BASE_URL; ?>/admin/addUnit', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                var select = document.getElementById('unitSelect');
                var option = document.createElement('option');
                option.value = name;
                option.text = name;
                option.selected = true;
                select.appendChild(option);
                bootstrap.Modal.getInstance(document.getElementById('quickUnitModal')).hide();
                document.getElementById('newUnitName').value = '';
            };
            xhr.send('unit_name=' + encodeURIComponent(name));
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
