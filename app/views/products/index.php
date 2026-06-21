<?php
$pageTitle = 'Products';
$currentPage = 'products';
ob_start();
?>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 16px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.3s ease;
    border: 1px solid var(--border);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.stat-card .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
}

.stat-card .stat-info h3 { font-size: 20px; font-weight: 700; margin: 0; color: var(--text-primary); }
.stat-card .stat-info p { margin: 4px 0 0 0; font-size: 12px; color: var(--text-secondary); }

.card-modern {
    background: var(--surface);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 20px;
}

.card-modern .card-header {
    background: var(--surface-alt);
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.card-modern .card-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-modern .card-header h5 i { color: var(--primary); }

.search-filter-bar {
    background: var(--surface);
    padding: 20px;
    border-radius: var(--radius-lg);
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    display: flex;
    gap: 12px;
    align-items: center;
}

.search-box {
    position: relative;
    flex: 1;
}

.search-box input {
    width: 100%;
    padding: 12px 16px;
    padding-left: 44px;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    font-size: 14px;
    background: var(--surface-alt);
    color: var(--text-primary);
}

.search-box input:focus {
    border-color: var(--primary);
    outline: none;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: var(--radius);
    font-size: 14px;
    background: var(--surface);
    color: var(--text-primary);
    min-width: 150px;
}

.product-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    transition: all 0.3s ease;
    cursor: pointer;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.product-card .product-image {
    width: 100%;
    height: 150px;
    background: var(--surface-alt);
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid var(--border);
}

.product-card .product-image i {
    font-size: 3rem;
    color: var(--text-muted);
}

.product-card .product-info {
    padding: 16px;
}

.product-card .product-name {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.product-card .product-price {
    font-size: 18px;
    font-weight: 800;
    color: var(--primary);
}

.product-card .product-price::before {
    content: '৳';
    font-size: 13px;
}

.product-card .product-stock {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.product-card .product-stock.in-stock {
    background: #dcfce7;
    color: #166534;
}

/* Sticky Header */
.stats-grid {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--bg-body);
    padding: 10px 0;
}

.product-card .product-stock.low-stock {
    background: #fef9c3;
    color: #854d0e;
}

.product-card .product-stock.out-of-stock {
    background: #fee2e2;
    color: #991b1b;
}

body.dark-mode .product-card .product-stock.in-stock {
    background: linear-gradient(135deg, #064e3b, #065f46);
    color: #a7f3d0;
}

body.dark-mode .product-card .product-stock.low-stock {
    background: linear-gradient(135deg, #78350f, #92400e);
    color: #fde68a;
}

body.dark-mode .product-card .product-stock.out-of-stock {
    background: rgba(220, 38, 38, 0.2);
    color: #ef4444;
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-content {
    background: var(--bg-card);
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h3 { margin: 0; font-size: 18px; font-weight: 700; color: var(--text-primary); }
.modal-close {
    background: none; border: none;
    font-size: 28px; color: var(--text-muted);
    cursor: pointer; line-height: 1;
}
.modal-close:hover { color: var(--text-primary); }
.modal-body { padding: 24px; }
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}
.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
}
.detail-row span { color: var(--text-muted); font-size: 13px; }
.detail-row strong { color: var(--text-primary); font-weight: 600; }
.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    border: none;
    font-size: 13px;
}
.btn-primary { background: var(--primary); color: white; }
.btn-primary:hover { background: var(--primary-dark); }
.btn-secondary { background: var(--bg-surface-alt); color: var(--text-secondary); }

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state i {
    font-size: 5rem;
    color: var(--text-muted);
    margin-bottom: 20px;
}

.empty-state h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 24px;
}
</style>

<div class="page-header">
    <div>
        <h1><i class="bi bi-box-seam-fill"></i> Products</h1>
        <p>Manage your product inventory</p>
    </div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="<?php echo BASE_URL; ?>/product/create" class="btn">
            <i class="bi bi-plus-lg me-2"></i>Add Product
        </a>
    <?php endif; ?>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
        <div class="stat-info">
            <h3><?php echo count($products); ?></h3>
            <p>Total Products</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), var(--success-light));"><i class="bi bi-check-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo count(array_filter($products, fn($p) => $p['stock_quantity'] > 10)); ?></h3>
            <p>In Stock</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning), var(--warning-light));"><i class="bi bi-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h3><?php echo count($low_stock_products ?? []); ?></h3>
            <p>Low Stock</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-info">
            <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(array_sum(array_map(fn($p) => $p['stock_quantity'] * $p['sell_price'], $products)), 0); ?></h3>
            <p>Total Value</p>
        </div>
    </div>
</div>

<div class="search-filter-bar">
    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="productSearch" placeholder="Search products...">
    </div>
    <select class="filter-select" id="categoryFilter">
        <option value="">All Categories</option>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <select class="filter-select" id="stockFilter">
        <option value="">All Stock</option>
        <option value="in">In Stock</option>
        <option value="low">Low Stock</option>
        <option value="out">Out of Stock</option>
    </select>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state">
        <i class="bi bi-box-seam"></i>
        <h4>No Products Found</h4>
        <p>Add your first product to get started!</p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="<?php echo BASE_URL; ?>/product/create" class="btn btn-primary">Add Product</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; max-height: 70vh; overflow-y: auto; padding-right: 8px; scroll-behavior: smooth;">
        <?php foreach ($products as $product): 
            $stockClass = $product['stock_quantity'] <= 0 ? 'out-of-stock' : ($product['stock_quantity'] <= 10 ? 'low-stock' : 'in-stock');
            $stockText = $product['stock_quantity'] <= 0 ? 'Out of Stock' : ($product['stock_quantity'] <= 10 ? 'Low Stock' : 'In Stock');
            $imageUrl = !empty($product['image']) && file_exists($product['image']) ? BASE_URL . '/' . $product['image'] : '';
        ?>
        <div class="product-card" onclick="showProductModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>', '<?php echo htmlspecialchars($product['category'] ?? ''); ?>', '<?php echo $product['barcode'] ?? ''; ?>', <?php echo $product['sell_price']; ?>, <?php echo $product['cost_price']; ?>, <?php echo $product['stock_quantity']; ?>, '<?php echo $product['unit'] ?? 'pcs'; ?>', '<?php echo $imageUrl; ?>', '<?php echo $product['size_type'] ?? ''; ?>')" data-name="<?php echo strtolower($product['product_name']); ?>" data-category="<?php echo strtolower($product['category'] ?? ''); ?>" data-stock="<?php echo $stockClass; ?>" style="cursor:pointer;">
            <div class="product-image">
                <?php if ($imageUrl): ?>
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                    <i class="bi bi-box-seam"></i>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                <div class="product-price"><?php echo number_format($product['sell_price'], 0); ?></div>
                <span class="product-stock <?php echo $stockClass; ?>"><?php echo $stockText; ?> (<?php echo $product['stock_quantity']; ?>)</span>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="product-actions" style="display:flex;gap:6px;padding:10px 16px 16px;border-top:1px solid var(--border);">
                <a href="<?php echo BASE_URL; ?>/product/edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary" style="flex:1;text-decoration:none;">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="<?php echo BASE_URL; ?>/product/delete/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-danger" style="flex:1;text-decoration:none;" onclick="return confirm('Delete this product?')">
                    <i class="bi bi-trash"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div id="productModal" class="modal-overlay" onclick="closeProductModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 id="modalProductName">Product Details</h3>
                <button class="modal-close" onclick="closeProductModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <img id="modalProductImage" src="" alt="" style="width:100%;max-height:250px;object-fit:cover;border-radius:12px;display:none;">
                        <div id="modalNoImage" style="width:100%;height:200px;background:var(--bg-surface-alt);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:48px;color:var(--text-muted);">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <div class="detail-row"><span>Category:</span><strong id="modalCategory">-</strong></div>
                        <div class="detail-row"><span>Barcode:</span><strong id="modalBarcode">-</strong></div>
                        <div class="detail-row"><span>Sell Price:</span><strong id="modalSellPrice">-</strong></div>
                        <div class="detail-row"><span>Cost Price:</span><strong id="modalCostPrice">-</strong></div>
                        <div class="detail-row"><span>Stock:</span><strong id="modalStock">-</strong></div>
                        <div class="detail-row"><span>Unit:</span><strong id="modalUnit">-</strong></div>
                        <div class="detail-row"><span>Size Type:</span><strong id="modalSizeType">-</strong></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="modalEditLink" href="" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                <button class="btn btn-secondary" onclick="closeProductModal()">Close</button>
            </div>
        </div>
    </div>
    
    <script>
    function showProductModal(id, name, category, barcode, sellPrice, costPrice, stock, unit, imageUrl, sizeType) {
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalCategory').textContent = category || '-';
        document.getElementById('modalBarcode').textContent = barcode || '-';
        document.getElementById('modalSellPrice').textContent = '<?php echo DEFAULT_CURRENCY; ?>' + sellPrice.toLocaleString();
        document.getElementById('modalCostPrice').textContent = '<?php echo DEFAULT_CURRENCY; ?>' + costPrice.toLocaleString();
        document.getElementById('modalStock').textContent = stock + ' ' + unit;
        document.getElementById('modalUnit').textContent = unit;
        document.getElementById('modalSizeType').textContent = sizeType || '-';
        document.getElementById('modalEditLink').href = '<?php echo BASE_URL; ?>/product/edit/' + id;
        
        if (imageUrl) {
            document.getElementById('modalProductImage').src = imageUrl;
            document.getElementById('modalProductImage').style.display = 'block';
            document.getElementById('modalNoImage').style.display = 'none';
        } else {
            document.getElementById('modalProductImage').style.display = 'none';
            document.getElementById('modalNoImage').style.display = 'flex';
        }
        
        document.getElementById('productModal').classList.add('active');
    }
    
    function closeProductModal(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('productModal').classList.remove('active');
    }
    </script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';