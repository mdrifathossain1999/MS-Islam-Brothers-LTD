<?php 
$pageTitle = 'Point of Sale';
$currentPage = 'pos';
ob_start();
?>

<style>
:root {
    --pos-primary: #6366f1;
    --pos-primary-dark: #4f46e5;
    --pos-primary-light: #818cf8;
    --pos-success: #10b981;
    --pos-success-dark: #059669;
    --pos-warning: #f59e0b;
    --pos-danger: #ef4444;
    --pos-surface: #ffffff;
    --pos-bg: #f8fafc;
    --pos-bg-alt: #f1f5f9;
    --pos-text: #1e293b;
    --pos-text-light: #64748b;
    --pos-text-muted: #94a3b8;
    --pos-border: #e2e8f0;
    --pos-shadow: 0 4px 20px rgba(0,0,0,0.08);
    --pos-shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
    --pos-shadow-hover: 0 12px 28px rgba(99, 102, 241, 0.25);
    --pos-radius: 16px;
    --pos-radius-sm: 10px;
    --pos-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Header */
.page-header { 
    margin-bottom: 20px; 
    padding: 0 !important; 
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%) !important;
    border-radius: 16px;
    padding: 24px !important;
    box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35);
}
.page-header h1 { 
    font-size: 1.6rem; 
    font-weight: 800; 
    display: flex; 
    align-items: center; 
    gap: 12px; 
    color: white;
}
.page-header h1 i { 
    background: rgba(255, 255, 255, 0.25);
    padding: 12px; 
    border-radius: 14px; 
    color: white; 
    font-size: 1.4rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.page-header p { 
    margin: 6px 0 0; 
    font-size: 0.9rem; 
    color: rgba(255, 255, 255, 0.85); 
}
.header-actions .btn {
    background: white;
    color: #6366f1;
    border: none;
    padding: 10px 20px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: var(--pos-transition);
}
.header-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* Main Layout - Products Left, Cart Right */
.pos-wrapper {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 20px;
    height: calc(100vh - 180px);
    min-height: 600px;
}

/* Responsive */
@media (max-width: 1200px) { 
    .pos-wrapper { grid-template-columns: 1fr 2fr; } 
}
@media (max-width: 992px) { 
    .pos-wrapper { grid-template-columns: 1fr; height: auto; }
    .pos-products-panel { max-height: 450px; }
    .pos-cart-panel { max-height: none; }
}

/* Left Panel - Products */
.pos-products-panel {
    display: flex;
    flex-direction: column;
    background: var(--pos-surface);
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow-lg);
    border: 1px solid var(--pos-border);
    overflow: hidden;
}

.products-header {
    padding: 12px 14px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid var(--pos-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.products-header h4 { 
    margin: 0; 
    font-size: 0.9rem; 
    font-weight: 700; 
    display: flex; 
    align-items: center; 
    gap: 8px; 
    color: var(--pos-text);
}
.products-header h4 i { color: var(--pos-primary); font-size: 14px; }
.products-header .badge {
    background: linear-gradient(135deg, var(--pos-primary), var(--pos-primary-dark));
    color: white;
    padding: 4px 10px;
    border-radius: 14px;
    font-size: 10px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
}

.products-filters {
    padding: 10px 14px;
    background: white;
    border-bottom: 1px solid var(--pos-border);
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.products-filters .search-box {
    flex: 1;
    min-width: 200px;
    position: relative;
}
.products-filters .search-box i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--pos-text-muted);
    font-size: 12px;
    transition: var(--pos-transition);
}
.products-filters .search-box input {
    width: 100%;
    padding: 8px 10px 8px 32px;
    border: 2px solid var(--pos-border);
    border-radius: var(--pos-radius-sm);
    font-size: 12px;
    transition: var(--pos-transition);
}
.products-filters .search-box input:focus {
    border-color: var(--pos-primary);
    outline: none;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}
.products-filters .search-box input:focus + i {
    color: var(--pos-primary);
}
.products-filters select {
    padding: 8px 28px 8px 10px;
    border: 2px solid var(--pos-border);
    border-radius: var(--pos-radius-sm);
    font-size: 11px;
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 8px center;
    appearance: none;
    cursor: pointer;
    transition: var(--pos-transition);
}
.products-filters select:focus {
    border-color: var(--pos-primary);
    outline: none;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}

/* Product Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 8px;
    padding: 10px;
    overflow-y: auto;
    flex: 1;
}
.products-grid::-webkit-scrollbar { width: 8px; }
.products-grid::-webkit-scrollbar-track { background: var(--pos-bg); }
.products-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.products-grid::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.product-card {
    background: white;
    border: 2px solid var(--pos-border);
    border-radius: var(--pos-radius-sm);
    padding: 10px;
    cursor: pointer;
    transition: var(--pos-transition);
    display: block;
    position: relative;
    overflow: hidden;
}
.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--pos-primary), var(--pos-primary-light));
    transform: scaleX(0);
    transition: var(--pos-transition);
}
.product-card:hover {
    border-color: var(--pos-primary);
    transform: translateY(-5px);
    box-shadow: var(--pos-shadow-hover);
}
.product-card:hover::before {
    transform: scaleX(1);
}
.product-card.out-of-stock:hover {
    transform: none;
    box-shadow: none;
    border-color: var(--pos-border);
}

.product-image {
    width: 100%;
    height: 60px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
    overflow: hidden;
    transition: var(--pos-transition);
}
.product-card:hover .product-image {
    transform: scale(1.05);
}
.product-image img { width: 100%; height: 100%; object-fit: cover; }
.product-image i { font-size: 1.8rem; color: var(--pos-text-muted); transition: var(--pos-transition); }
.product-card:hover .product-image i { color: var(--pos-primary); transform: scale(1.1); }

.product-name {
    font-size: 11px;
    font-weight: 700;
    color: var(--pos-text);
    text-align: center;
    height: 26px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.3;
    margin-bottom: 4px;
}

.product-price {
    font-size: 15px;
    font-weight: 800;
    color: var(--pos-primary);
    margin-bottom: 4px;
    transition: var(--pos-transition);
}
.product-card:hover .product-price {
    color: var(--pos-primary-dark);
    transform: scale(1.05);
}

.product-stock {
    font-size: 9px;
    font-weight: 600;
    padding: 3px 7px;
    border-radius: 8px;
    transition: var(--pos-transition);
}
.product-stock.in-stock { background: #dcfce7; color: #166534; }
.product-stock.low-stock { background: #fef9c3; color: #854d0e; }
.product-stock.out-of-stock { background: #fee2e2; color: #991b1b; }

.product-cat {
    font-size: 9px;
    color: var(--pos-text-muted);
    margin-top: 4px;
}
.products-grid::-webkit-scrollbar { width: 8px; }
.products-grid::-webkit-scrollbar-track { background: var(--pos-bg); }
.products-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.products-grid::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.product-card {
    background: white;
    border: 2px solid var(--pos-border);
    border-radius: var(--pos-radius-sm);
    padding: 8px;
    cursor: pointer;
    transition: var(--pos-transition);
    display: block;
    position: relative;
    overflow: hidden;
}
.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--pos-primary), var(--pos-primary-light));
    transform: scaleX(0);
    transition: var(--pos-transition);
}
.product-card:hover {
    border-color: var(--pos-primary);
    transform: translateY(-4px);
    box-shadow: var(--pos-shadow-hover);
}
.product-card:hover::before {
    transform: scaleX(1);
}
.product-card.out-of-stock { 
    opacity: 0.5; 
    cursor: not-allowed; 
    filter: grayscale(0.5);
}
.product-card.out-of-stock:hover {
    transform: none;
    box-shadow: none;
    border-color: var(--pos-border);
}

.product-image {
    width: 100%;
    height: 55px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
    overflow: hidden;
    transition: var(--pos-transition);
}
.product-card:hover .product-image {
    transform: scale(1.05);
}
.product-image img { width: 100%; height: 100%; object-fit: cover; }
.product-image i { font-size: 1.5rem; color: var(--pos-text-muted); transition: var(--pos-transition); }
.product-card:hover .product-image i { color: var(--pos-primary); transform: scale(1.1); }

.product-name {
    font-size: 10px;
    font-weight: 700;
    color: var(--pos-text);
    text-align: center;
    height: 24px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.3;
    margin-bottom: 4px;
}

.product-price {
    font-size: 14px;
    font-weight: 800;
    color: var(--pos-primary);
    margin-bottom: 4px;
    transition: var(--pos-transition);
}
.product-card:hover .product-price {
    color: var(--pos-primary-dark);
    transform: scale(1.05);
}

.product-stock {
    font-size: 9px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 8px;
    transition: var(--pos-transition);
}
.product-stock.in-stock { background: #dcfce7; color: #166534; }
.product-stock.low-stock { background: #fef9c3; color: #854d0e; }
.product-stock.out-of-stock { background: #fee2e2; color: #991b1b; }

.product-cat {
    font-size: 9px;
    color: var(--pos-text-muted);
    margin-top: 4px;
}

/* Right Panel - Cart */
.pos-cart-panel {
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    padding-right: 4px;
    max-height: calc(100vh - 200px);
}
.pos-cart-panel::-webkit-scrollbar { width: 5px; }
.pos-cart-panel::-webkit-scrollbar-track { background: #f1f5f9; }
.pos-cart-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.pos-cart-panel::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* Order Info */
.order-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border: 1px solid var(--pos-border);
    border-radius: var(--pos-radius);
    padding: 16px;
    box-shadow: var(--pos-shadow);
}
.order-card .form-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--pos-text-light);
    text-transform: uppercase;
    margin-bottom: 4px;
}
.order-card .form-control {
    border: 1px solid var(--pos-border);
    border-radius: 8px;
    font-size: 13px;
    padding: 8px 12px;
    transition: var(--pos-transition);
}
.order-card .form-control:focus {
    border-color: var(--pos-primary);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
}
.order-card .quick-btn {
    background: linear-gradient(135deg, var(--pos-primary), var(--pos-primary-dark));
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: var(--pos-transition);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}
.order-card .quick-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
}

/* Cart Card */
.cart-card {
    background: var(--pos-surface);
    border-radius: var(--pos-radius-sm);
    box-shadow: var(--pos-shadow-lg);
    border: 1px solid var(--pos-border);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.cart-header {
    padding: 12px 16px;
    background: linear-gradient(135deg, var(--pos-primary), var(--pos-primary-dark));
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}
.cart-header h5 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-header .cart-count {
    background: rgba(255,255,255,0.2);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
}
.cart-body { 
    padding: 10px; 
    flex-grow: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.cart-header {
    padding: 12px 16px;
    background: linear-gradient(135deg, var(--pos-primary), var(--pos-primary-dark));
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cart-header h5 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-header .cart-count {
    background: rgba(255,255,255,0.2);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
}
.cart-header .clear-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--pos-transition);
}
.cart-header .clear-btn:hover { background: rgba(255,255,255,0.35); transform: scale(1.05); }

.cart-body { padding: 10px; }

/* Cart Table */
.cart-items {
    min-height: 80px;
    max-height: 150px;
    overflow-y: auto;
    margin-bottom: 10px;
    border: 1px solid var(--pos-border);
    border-radius: 8px;
}
.cart-items::-webkit-scrollbar { width: 5px; }
.cart-items::-webkit-scrollbar-track { background: var(--pos-bg); }
.cart-items::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

.cart-table { width: 100%; border-collapse: collapse; }
.cart-table th {
    padding: 6px 4px;
    text-align: left;
    font-size: 9px;
    font-weight: 700;
    color: var(--pos-text-muted);
    text-transform: uppercase;
    background: var(--pos-bg);
    border-bottom: 1px solid var(--pos-border);
    position: sticky;
    top: 0;
}
.cart-table td {
    padding: 6px 4px;
    border-bottom: 1px solid var(--pos-border);
    font-size: 11px;
    vertical-align: middle;
    transition: var(--pos-transition);
}
.cart-table tr:hover { background: #fafbfc; }
.cart-table tr:hover td { color: var(--pos-primary); }
.cart-table .item-name { font-weight: 600; color: var(--pos-text); font-size: 10px; }
.cart-table .item-price { color: var(--pos-text-light); font-size: 9px; }
.cart-table .item-total { font-weight: 700; color: var(--pos-primary); font-size: 10px; }

.cart-empty {
    text-align: center;
    padding: 30px;
    color: var(--pos-text-muted);
}
.cart-empty i { font-size: 1.5rem; opacity: 0.3; margin-bottom: 8px; transition: var(--pos-transition); }
.cart-empty:hover i { opacity: 0.6; transform: scale(1.1); }
.cart-empty p { font-weight: 600; font-size: 11px; margin: 0; }
.cart-empty small { font-size: 9px; }

/* Quantity Controls */
.qty-box {
    display: flex;
    align-items: center;
    gap: 2px;
}
.qty-box button {
    width: 18px;
    height: 18px;
    border: 1px solid var(--pos-border);
    border-radius: 4px;
    background: white;
    cursor: pointer;
    font-size: 10px;
    transition: var(--pos-transition);
    color: var(--pos-text-light);
}
.qty-box button:hover { background: var(--pos-primary); color: white; border-color: var(--pos-primary); transform: scale(1.1); }
.qty-box button:active { transform: scale(0.95); }
.qty-box input {
    width: 24px;
    text-align: center;
    border: 1px solid var(--pos-border);
    border-radius: 4px;
    padding: 2px;
    font-size: 10px;
    font-weight: 600;
}
.btn-remove {
    background: none;
    border: none;
    color: var(--pos-text-muted);
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 4px;
    transition: var(--pos-transition);
}
.btn-remove:hover { background: #fee2e2; color: var(--pos-danger); transform: scale(1.1); }

/* Customer */
.customer-row {
    margin-bottom: 10px;
}
.customer-row label {
    font-size: 9px;
    font-weight: 700;
    color: var(--pos-text-light);
    text-transform: uppercase;
    margin-bottom: 4px;
    display: block;
}
.customer-row .customer-input {
    display: flex;
    gap: 4px;
}
.customer-row input {
    flex: 1;
    padding: 6px 8px;
    border: 2px solid var(--pos-border);
    border-radius: 6px;
    font-size: 11px;
    transition: var(--pos-transition);
}
.customer-row input:focus { border-color: var(--pos-primary); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.customer-row button {
    padding: 6px 10px;
    border: 2px solid var(--pos-border);
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: var(--pos-transition);
    color: var(--pos-primary);
}
.customer-row button:hover { background: var(--pos-primary); color: white; border-color: var(--pos-primary); transform: scale(1.05); }
.customer-row select {
    width: 100%;
    padding: 6px 8px;
    border: 2px solid var(--pos-border);
    border-radius: 6px;
    font-size: 11px;
    background: white;
    transition: var(--pos-transition);
}
.customer-row select:focus { border-color: var(--pos-primary); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

/* Summary */
.summary-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 8px;
    background: white;
    border-radius: 6px;
    margin-bottom: 4px;
    font-size: 11px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: var(--pos-transition);
}
.summary-row:hover { transform: translateX(4px); }
.summary-row span:first-child { color: var(--pos-text-light); font-weight: 500; font-size: 10px; }
.summary-row span:last-child { font-weight: 700; color: var(--pos-text); font-size: 11px; }
.summary-row input {
    width: 45px;
    padding: 2px 6px;
    border: 1px solid var(--pos-border);
    border-radius: 4px;
    font-size: 11px;
    text-align: right;
    transition: var(--pos-transition);
}
.summary-row input:focus { border-color: var(--pos-primary); outline: none; }
.summary-row.total {
    background: linear-gradient(135deg, var(--pos-primary), var(--pos-primary-dark));
    color: white;
    padding: 10px;
    margin-top: 6px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}
.summary-row.total span:first-child { color: white; font-size: 12px; }
.summary-row.total span:last-child { color: white; font-size: 16px; }
.summary-row.total:hover { transform: none; }

/* Payment */
.payment-card {
    background: var(--pos-surface);
    border-radius: var(--pos-radius-sm);
    box-shadow: var(--pos-shadow-lg);
    border: 1px solid var(--pos-border);
    padding: 12px;
}
.payment-label {
    font-size: 9px;
    font-weight: 700;
    color: var(--pos-text-light);
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.payment-methods { display: flex; gap: 6px; margin-bottom: 8px; }
.method-btn {
    flex: 1;
    padding: 8px 4px;
    border: 2px solid var(--pos-border);
    border-radius: 8px;
    background: white;
    text-align: center;
    cursor: pointer;
    transition: var(--pos-transition);
    font-size: 9px;
    font-weight: 600;
    color: var(--pos-text-light);
}
.method-btn:hover { border-color: var(--pos-primary); color: var(--pos-primary); transform: translateY(-2px); }
.method-btn.active { border-color: var(--pos-primary); background: var(--pos-primary); color: white; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
.method-btn i { font-size: 14px; display: block; margin-bottom: 2px; }

.mobile-banks { display: none; gap: 4px; flex-wrap: wrap; margin-bottom: 8px; }
.mobile-banks.show { display: flex; animation: slideDown 0.3s ease; }
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.mobile-banks button {
    padding: 4px 8px;
    border: 1px solid var(--pos-border);
    border-radius: 4px;
    background: white;
    font-size: 9px;
    cursor: pointer;
    transition: var(--pos-transition);
}
.mobile-banks button:hover { background: var(--pos-primary); color: white; transform: scale(1.05); }
.mobile-banks button.active { background: var(--pos-primary); color: white; box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3); }

.payment-inputs { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; margin-bottom: 10px; }
.payment-inputs label { font-size: 8px; font-weight: 600; display: block; margin-bottom: 2px; color: var(--pos-text-light); }
.payment-inputs .form-control {
    padding: 6px;
    border: 2px solid var(--pos-border);
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    transition: var(--pos-transition);
}
.payment-inputs .form-control:focus { border-color: var(--pos-primary); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
.payment-box {
    padding: 6px;
    border-radius: 6px;
    text-align: center;
    font-weight: 700;
    font-size: 11px;
    transition: var(--pos-transition);
}
.payment-box.receive { background: #f0fdf4; color: #166534; }
.payment-box.change { background: #dcfce7; color: #166534; }
.payment-box.due { background: #fef2f2; color: #991b1b; }

.complete-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, var(--pos-success), var(--pos-success-dark));
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--pos-transition);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
}
.complete-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45); }
.complete-btn:disabled { background: #cbd5e1; cursor: not-allowed; box-shadow: none; }
.complete-btn:active:not(:disabled) { transform: translateY(0); }

/* Modals */
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; backdrop-filter: blur(4px); }
.modal.show { display: flex; align-items: center; justify-content: center; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.modal-backdrop { position: fixed; width: 100%; height: 100%; }
.modal-content { background: white; padding: 24px; width: 460px; border-radius: 18px; position: relative; box-shadow: 0 24px 60px rgba(0, 0, 0, 0.2); animation: modalIn 0.3s ease; }
.modal-content.iframe-modal { padding: 0; width: 600px; max-width: 95%; height: 80vh; }
#customerModalFrame { width: 100%; height: calc(100% - 60px); border: none; border-radius: 0 0 8px 8px; }
@keyframes modalIn { from { transform: translateY(-20px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--pos-border); }
.modal-header h4 { margin: 0; font-size: 18px; color: var(--pos-text); }
.modal-close { background: none; border: none; font-size: 26px; cursor: pointer; color: var(--pos-text-light); line-height: 1; transition: var(--pos-transition); }
.modal-close:hover { color: var(--pos-danger); transform: scale(1.1); }
.modal-label { font-weight: 600; font-size: 13px; margin-bottom: 6px; display: block; color: var(--pos-text); }
.modal-input, .modal-select { width: 100%; padding: 12px 14px; border: 2px solid var(--pos-border); border-radius: 10px; margin-bottom: 14px; font-size: 13px; transition: var(--pos-transition); }
.modal-input:focus, .modal-select:focus { border-color: var(--pos-primary); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
.modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; }
.modal-cancel { padding: 12px 22px; border: 2px solid var(--pos-border); border-radius: 10px; background: white; font-weight: 600; cursor: pointer; font-size: 13px; transition: var(--pos-transition); }
.modal-cancel:hover { background: var(--pos-bg); transform: translateY(-2px); }
.modal-save { padding: 12px 22px; background: var(--pos-primary); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 13px; transition: var(--pos-transition); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }
.modal-save:hover { background: var(--pos-primary-dark); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4); }

.success-modal { text-align: center; padding: 30px; }
.success-icon { font-size: 70px; color: var(--pos-success); margin-bottom: 16px; animation: successPop 0.5s ease; }
@keyframes successPop { 0% { transform: scale(0); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
.success-modal h3 { margin: 0 0 16px; font-size: 22px; }
.success-details { background: var(--pos-bg); padding: 18px; border-radius: 12px; margin-bottom: 18px; }
.success-details p { margin: 8px 0; font-size: 14px; }

/* Add to cart animation */
@keyframes addToCart {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
.add-animation { animation: addToCart 0.3s ease; }

/* Responsive */
@media (max-width: 1200px) { .pos-wrapper { grid-template-columns: 1fr 360px; } }
@media (max-width: 992px) { 
    .pos-wrapper { grid-template-columns: 1fr; height: auto; }
    .pos-products-panel { max-height: 450px; }
    .pos-cart-panel { max-height: none; }
}
.order-card { display: none; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1><i class="bi bi-cart-check-fill"></i> Point of Sale</h1>
        <p>Process orders quickly - Scan barcode or click products</p>
    </div>
    <div class="header-actions">
        <a href="<?php echo BASE_URL; ?>/invoice/today" class="btn btn-primary">
            <i class="bi bi-receipt me-2"></i>Today's Sales
        </a>
    </div>
</div>

<!-- Main Wrapper: Products Left | Cart Right -->
<div class="pos-wrapper">
    
    <!-- LEFT: Products Panel -->
    <div class="pos-products-panel">
        <div class="products-header">
            <h4><i class="bi bi-grid-3x3-gap-fill"></i> Products</h4>
            <span class="badge"><?php echo count($products); ?> items</span>
        </div>
        <div class="products-filters">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="productSearch" placeholder="Search by name or barcode..." onkeyup="filterPosProducts()">
            </div>
            <select id="categoryFilter" onchange="filterPosProducts()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="products-grid" id="productGrid">
            <?php foreach ($products as $product): 
                $stock = intval($product['stock_quantity']);
                $unit = !empty($product['unit']) ? $product['unit'] : 'pcs';
                $image = !empty($product['image']) ? (substr($product['image'], 0, 8) === 'uploads/' ? BASE_URL . '/' . $product['image'] : BASE_URL . '/uploads/products/' . $product['image']) : '';
                $category = !empty($product['category']) ? $product['category'] : '';
                $barcode = !empty($product['barcode']) ? $product['barcode'] : '';
                $stockClass = $stock > 10 ? 'in-stock' : ($stock > 0 ? 'low-stock' : 'out-of-stock');
                $stockText = $stock > 10 ? 'In Stock' : ($stock > 0 ? $stock . ' pcs' : 'Out');
                $disabled = $stock <= 0 ? 'out-of-stock' : '';
            ?>
            <div class="product-card <?php echo $disabled; ?>" 
                 data-id="<?php echo $product['id']; ?>"
                 data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                 data-price="<?php echo $product['sell_price']; ?>"
                 data-barcode="<?php echo $barcode; ?>"
                 data-stock="<?php echo $stock; ?>"
                 data-unit="<?php echo $unit; ?>"
                 data-category="<?php echo htmlspecialchars($category); ?>"
                 onclick="addToPOSCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>', <?php echo $product['sell_price']; ?>, <?php echo $stock; ?>)">
                <div class="product-image">
                    <?php if ($image): ?>
                    <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <?php else: ?>
                    <i class="bi bi-box-seam"></i>
                    <?php endif; ?>
                </div>
                <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                <div class="product-price"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($product['sell_price'], 0); ?></div>
                <span class="product-stock <?php echo $stockClass; ?>"><?php echo $stockText; ?></span>
                <?php if ($category): ?>
                <div class="product-cat"><?php echo htmlspecialchars($category); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- RIGHT: Cart Panel -->
    <div class="pos-cart-panel">
        
        <!-- Cart Card -->
        <div class="cart-card">
            <div class="cart-header">
                <h5><i class="bi bi-cart4"></i> Shopping Cart <span class="cart-count" id="cartCount">(0)</span></h5>
                <button class="clear-btn" onclick="clearPOSCart()"><i class="bi bi-trash me-1"></i>Clear</button>
            </div>
            <div class="cart-body">
                <div class="cart-items" id="cartItems">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr><td colspan="5"><div class="cart-empty"><i class="bi bi-cart-x"></i><p>Your cart is empty</p><small>Click products to add</small></div></td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Customer -->
                <div class="customer-row">
                    <label><i class="bi bi-person me-1"></i>Customer</label>
                    <div class="customer-input">
                        <input type="text" id="customerSearch" placeholder="Search customer..." onkeyup="searchCustomersPOS()">
                        <button onclick="showQuickCustomerModal()" title="Quick Add Customer" class="btn btn-success btn-sm"><i class="bi bi-person-plus"></i></button>
                    </div>
                    <select id="cartCustomerSelect" style="margin-top: 8px; max-height: 150px; overflow-y: auto;">
                        <option value="<?php echo $walkin_customer['id']; ?>">🚶 Walk-in Customer</option>
                        <?php foreach ($customers as $customer): 
                            $cust_due = floatval($customer['total_amount'] ?? 0) - floatval($customer['paid_amount'] ?? 0);
                            $cust_type = !empty($customer['customer_type']) ? $customer['customer_type'] : 'Retailer';
                        ?>
                        <option value="<?php echo $customer['id']; ?>" data-phone="<?php echo $customer['phone'] ?? ''; ?>">
                            <?php echo htmlspecialchars($customer['customer_name']); ?> (<?php echo $cust_type; ?>)
                            <?php echo $cust_due > 0 ? ' - Due: ' . DEFAULT_CURRENCY . number_format($cust_due, 0) : ''; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Summary -->
                <div class="summary-card">
                    <div class="summary-row"><span><i class="bi bi-bag me-1"></i>Subtotal</span><span id="subtotalDisplay"><?php echo DEFAULT_CURRENCY; ?>0</span></div>
                    <div class="summary-row">
                        <span><i class="bi bi-percent me-1"></i>VAT</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" id="vatPercent" value="0" min="0" max="100" style="width: 45px;" onchange="updatePOSSummary()">%
                            <span id="vatDisplay"><?php echo DEFAULT_CURRENCY; ?>0</span>
                        </div>
                    </div>
                    <div class="summary-row"><span><i class="bi bi-discount me-1"></i>Discount</span><input type="number" id="discountAmount" value="0" min="0" onchange="updatePOSSummary()"></div>
                    <div class="summary-row total"><span><i class="bi bi-calculator me-1"></i>Total</span><span id="totalDisplay"><?php echo DEFAULT_CURRENCY; ?>0</span></div>
                </div>

                <!-- Payment -->
                <div class="payment-card">
                    <span class="payment-label"><i class="bi bi-credit-card me-1"></i>Payment Method</span>
                    <div class="payment-methods">
                        <div class="method-btn active" data-method="cash" onclick="selectPaymentMethod('cash', this)"><i class="bi bi-cash"></i>Cash</div>
                        <div class="method-btn" data-method="cheque" onclick="selectPaymentMethod('cheque', this)"><i class="bi bi-file-earmark-text"></i>Cheque</div>
                        <div class="method-btn" data-method="mobile" onclick="selectPaymentMethod('mobile', this)"><i class="bi bi-phone"></i>Mobile</div>
                    </div>
                    <div class="mobile-banks" id="mobileBanks">
                        <button onclick="selectMobileBank(this, 'bkash')">💳 Bkash</button>
                        <button onclick="selectMobileBank(this, 'nagad')">💳 Nagad</button>
                        <button onclick="selectMobileBank(this, 'rocket')">💳 Rocket</button>
                        <button onclick="selectMobileBank(this, 'upay')">💳 Upay</button>
                    </div>
                    <div class="payment-inputs">
                        <div><label>Receive</label><input type="number" class="form-control" id="receiveAmount" value="0" min="0" oninput="updatePOSSummary(); autoFillPaidAmount();"></div>
                        <div><label>Change</label><div class="payment-box change" id="changeDisplay"><?php echo DEFAULT_CURRENCY; ?>0</div></div>
                        <div><label>Due</label><div class="payment-box due" id="dueDisplay"><?php echo DEFAULT_CURRENCY; ?>0</div></div>
                    </div>
                    <button class="complete-btn" id="completeSaleBtn" onclick="completePOSSale()" disabled>
                        <i class="bi bi-check-circle-fill me-2"></i>Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Modal -->
<div id="customerModal" class="modal">
    <div class="modal-backdrop" onclick="closeCustomerModal()"></div>
    <div class="modal-content" style="width: 600px; max-width: 95%; height: 80vh;">
        <div class="modal-header">
            <h4><i class="bi bi-person-plus me-2"></i>Add New Customer</h4>
            <button class="modal-close" onclick="closeCustomerModal()">&times;</button>
        </div>
        <iframe id="customerModalFrame" src="" style="width: 100%; height: calc(100% - 60px); border: none; border-radius: 0 0 8px 8px;"></iframe>
    </div>
</div>

<!-- Quick Customer Modal (Bootstrap) -->
<div class="modal fade" id="quickCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Quick Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickCustomerForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Name *</label>
                        <input type="text" class="form-control" id="newCustomerName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="text" class="form-control" id="newCustomerPhone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Type</label>
                        <select class="form-select" id="newCustomerType">
                            <option value="Retailer">Retailer</option>
                            <option value="Dealer">Dealer</option>
                            <option value="Wholesaler">Wholesaler</option>
                            <option value="VIP">VIP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="newCustomerEmail">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" id="newCustomerAddress" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Add Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Modals -->
<div id="customerSuccessModal" class="modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content success-modal">
        <div class="success-icon">✓</div>
        <h3>Customer Added!</h3>
        <p id="newCustomerName"></p>
    </div>
</div>

<!-- Receipt Modal (Bootstrap) -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Sale Complete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-receipt text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Sale Completed Successfully!</h4>
                <p class="mb-1">Invoice: <strong id="receiptInvoice"></strong></p>
                <p class="mb-1">Payment: <strong id="receiptPayment"></strong></p>
                <hr>
                <p class="mb-1"><strong>Total:</strong> <span id="receiptTotal" class="fw-bold"></span></p>
                <p class="mb-1"><strong>Paid:</strong> <span id="receiptPaid" class="text-success fw-bold"></span></p>
                <p class="mb-1" id="changeRow"><strong>Change:</strong> <span id="receiptChange" class="text-success fw-bold"></span></p>
                <p class="mb-1" id="dueRow" style="display: none;"><strong>Due:</strong> <span id="receiptDue" class="text-danger fw-bold"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="printReceiptBtn">
                    <span class="me-2">🖨️</span> Print
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden variable to store last invoice ID -->
<input type="hidden" id="lastInvoiceId" value="">

<script>
// Debug logging
console.log('POS Script Loading...');

var DEFAULT_CURRENCY = typeof DEFAULT_CURRENCY !== 'undefined' ? DEFAULT_CURRENCY : '৳';
var posCart = [];
var selectedPaymentMethod = 'cash';
var selectedMobileBank = '';

// Make functions globally accessible
window.addToPOSCart = function(id, name, price, stock) {
    console.log('Adding to cart:', id, name, price, stock);
    if (stock <= 0) { alert('Out of stock!'); return; }
    var existing = posCart.find(function(item) { return item.id === id; });
    if (existing) { 
        if (existing.qty < stock) existing.qty++; 
        else alert('Not enough stock');
    } else { 
        posCart.push({ id: id, name: name, price: price, qty: 1, maxStock: stock }); 
    }
    console.log('Cart:', posCart);
    renderPOSCart();
    updatePOSSummary();
    autoFillPaidAmount();
};

window.renderPOSCart = function() {
    console.log('Rendering cart, items:', posCart.length);
    var tbody = document.getElementById('cartBody');
    var cartCount = document.getElementById('cartCount');
    if (!tbody || !cartCount) { console.error('Cart elements not found'); return; }
    
    cartCount.textContent = '(' + posCart.length + ')';
    
    if (posCart.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="cart-empty"><i class="bi bi-cart-x"></i><p>Your cart is empty</p><small>Click products to add</small></div></td></tr>';
        document.getElementById('completeSaleBtn').disabled = true;
        return;
    }
    
    document.getElementById('completeSaleBtn').disabled = false;
    
    var html = '';
    posCart.forEach(function(item, index) {
        html += '<tr>';
        html += '<td class="item-name">' + item.name + '</td>';
        html += '<td class="item-price">' + DEFAULT_CURRENCY + item.price.toLocaleString() + '</td>';
        html += '<td><div class="qty-box"><button onclick="updatePOSQty(' + index + ', -1)">-</button><input type="text" value="' + item.qty + '" readonly><button onclick="updatePOSQty(' + index + ', 1)">+</button></div></td>';
        html += '<td class="item-total">' + DEFAULT_CURRENCY + (item.price * item.qty).toLocaleString() + '</td>';
        html += '<td><button class="btn-remove" onclick="removePOSItem(' + index + ')"><i class="bi bi-trash"></i></button></td>';
        html += '</tr>';
    });
    tbody.innerHTML = html;
};

window.updatePOSQty = function(index, change) {
    var item = posCart[index];
    if (!item) return;
    var newQty = item.qty + change;
    if (newQty <= 0) posCart.splice(index, 1);
    else if (newQty <= item.maxStock) item.qty = newQty;
    else alert('Max stock reached');
    renderPOSCart();
    updatePOSSummary();
    autoFillPaidAmount();
};

window.removePOSItem = function(index) { 
    posCart.splice(index, 1); 
    renderPOSCart(); 
    updatePOSSummary(); 
};

window.clearPOSCart = function() { 
    if (confirm('Clear all items from cart?')) {
        posCart = []; 
        renderPOSCart(); 
        updatePOSSummary(); 
    }
};

window.selectPaymentMethod = function(method, btn) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.method-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    var mobileBanks = document.getElementById('mobileBanks');
    if (mobileBanks) mobileBanks.classList.toggle('show', method === 'mobile');
    if (method !== 'mobile') selectedMobileBank = '';
};

window.selectMobileBank = function(btn, bank) {
    selectedMobileBank = bank;
    document.querySelectorAll('.mobile-banks button').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
};

function autoFillPaidAmount() {
    const subtotal = posCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const vatPercent = parseFloat(document.getElementById('vatPercent')?.value) || 0;
    const vatAmount = subtotal * (vatPercent / 100);
    const discountAmount = parseFloat(document.getElementById('discountAmount')?.value) || 0;
    const shippingAmount = parseFloat(document.getElementById('shippingAmount')?.value) || 0;
    const total = subtotal + vatAmount - discountAmount + shippingAmount;
    
    const receiveInput = document.getElementById('receiveAmount');
    if (posCart.length > 0 && receiveInput && parseFloat(receiveInput.value) === 0) {
        receiveInput.value = Math.ceil(total);
    }
}

// Barcode sound feedback
function playBarcodeSound(success) {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        if (success) {
            oscillator.frequency.value = 1200;
            oscillator.type = 'sine';
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.08);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.08);
        } else {
            oscillator.frequency.value = 200;
            oscillator.type = 'square';
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        }
    } catch (e) {
        console.log('Audio not supported');
    }
}

window.scanBarcode = function(barcode) {
    const productCard = document.querySelector(`.product-card[data-barcode="${barcode}"]`);
    if (productCard) {
        playBarcodeSound(true);
        productCard.click();
    } else {
        playBarcodeSound(false);
        alert('Product with barcode "' + barcode + '" not found');
    }
};

// Print receipt
function printReceipt(invoiceId) {
    if (invoiceId) {
        window.open(BASE_URL + '/invoice/print/' + invoiceId, '_blank');
    } else {
        window.print();
    }
}

function selectMobileBank(btn, bank) {
    selectedMobileBank = bank;
    document.querySelectorAll('.mobile-banks button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function updatePOSSummary() {
    const subtotal = posCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const vatPercent = parseFloat(document.getElementById('vatPercent').value) || 0;
    const vatAmount = subtotal * (vatPercent / 100);
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const total = subtotal + vatAmount - discountAmount;
    const receive = parseFloat(document.getElementById('receiveAmount').value) || 0;
    const change = receive > total ? receive - total : 0;
    const due = total > receive ? total - receive : 0;
    
    // Get selected customer name
    const customerSelect = document.getElementById('cartCustomerSelect');
    const customerName = customerSelect.options[customerSelect.selectedIndex]?.text || '';
    
    document.getElementById('subtotalDisplay').textContent = DEFAULT_CURRENCY + subtotal.toLocaleString();
    document.getElementById('vatDisplay').textContent = DEFAULT_CURRENCY + vatAmount.toLocaleString();
    document.getElementById('totalDisplay').textContent = DEFAULT_CURRENCY + total.toLocaleString();
    document.getElementById('changeDisplay').textContent = DEFAULT_CURRENCY + change.toLocaleString();
    
    // Show due with customer name, or hide if no due
    const dueDisplay = document.getElementById('dueDisplay');
    if (due > 0) {
        dueDisplay.textContent = customerName ? customerName + ' - ' + DEFAULT_CURRENCY + due.toLocaleString() : DEFAULT_CURRENCY + due.toLocaleString();
        dueDisplay.style.display = 'block';
    } else {
        dueDisplay.style.display = 'none';
    }
    
    // Enable complete sale if cart has items
    document.getElementById('completeSaleBtn').disabled = posCart.length === 0;
}

function filterPosProducts() {
    const search = document.getElementById('productSearch').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.getAttribute('data-name').toLowerCase();
        const barcode = card.getAttribute('data-barcode') || '';
        const cat = card.getAttribute('data-category') || '';
        const matchSearch = name.includes(search) || barcode.includes(search);
        const matchCat = !category || cat === category;
        card.style.display = (matchSearch && matchCat) ? 'block' : 'none';
    });
}

function openCustomerModal() { 
    document.getElementById('customerModalFrame').src = BASE_URL + '/customer/create';
    document.getElementById('customerModal').classList.add('show'); 
}
function closeCustomerModal() { 
    document.getElementById('customerModal').classList.remove('show'); 
    document.getElementById('customerModalFrame').src = '';
}
function showQuickCustomerModal() {
    var myModal = new bootstrap.Modal(document.getElementById('quickCustomerModal'));
    myModal.show();
}
function closeQuickCustomerModal() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('quickCustomerModal'));
    modal.hide();
    document.getElementById('quickCustomerForm').reset();
}

function saveCustomer(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('customerForm'));
    fetch('<?php echo BASE_URL; ?>/customer/quickAdd', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeCustomerModal();
            document.getElementById('newCustomerName').textContent = data.customer.customer_name;
            document.getElementById('customerSuccessModal').classList.add('show');
            setTimeout(() => { location.reload(); }, 2000);
        } else { alert(data.message || 'Error'); }
    });
}

window.completePOSSale = function() {
    console.log('Complete Sale clicked, cart items:', posCart.length);
    if (posCart.length === 0) return alert('No items in cart!');
    
    const custSelect = document.getElementById('cartCustomerSelect');
    const receiveInput = document.getElementById('receiveAmount');
    if (!custSelect || !receiveInput) {
        alert('Form elements not found!');
        return;
    }
    
    const subtotal = posCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const vatPercent = parseFloat(document.getElementById('vatPercent')?.value) || 0;
    const vatAmount = subtotal * (vatPercent / 100);
    const discountAmount = parseFloat(document.getElementById('discountAmount')?.value) || 0;
    const total = subtotal + vatAmount - discountAmount;
    const receiveAmount = parseFloat(receiveInput.value) || 0;
    const changeAmount = receiveAmount > total ? receiveAmount - total : 0;
    const dueAmount = total > receiveAmount ? total - receiveAmount : 0;
    const paymentMethod = selectedPaymentMethod === 'mobile' && selectedMobileBank ? selectedMobileBank + '_' + selectedPaymentMethod : selectedPaymentMethod;
    
    const saleData = {
        customer_id: custSelect.value || '8',
        items: posCart.map(item => ({
            id: item.id,
            name: item.name,
            product_name: item.name,
            price: item.price,
            sell_price: item.price,
            quantity: item.qty
        })),
        subtotal: subtotal,
        vat_percent: vatPercent,
        vat_amount: vatAmount,
        discount_amount: discountAmount,
        total_amount: total,
        paid_amount: receiveAmount,
        change_amount: changeAmount,
        payment_method: paymentMethod,
        sale_date: new Date().toISOString().slice(0,10)
    };
    
    console.log('Sending sale data:', saleData);
    
    fetch('<?php echo BASE_URL; ?>/pos/processSale', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(saleData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('Sale response:', data);
        if (data.success) {
            // Use jQuery to set values
            $('#receiptInvoice').text(data.invoice_number || '');
            
            // Payment method
            let paymentText = paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1);
            if (paymentMethod === 'mobile' && selectedMobileBank) {
                paymentText = 'Mobile (' + selectedMobileBank + ')';
            }
            $('#receiptPayment').text(paymentText);
            
            // Show amounts
            $('#receiptTotal').text(DEFAULT_CURRENCY + total.toLocaleString());
            $('#receiptPaid').text(DEFAULT_CURRENCY + receiveAmount.toLocaleString());
            
            // Change
            if (changeAmount > 0) {
                $('#changeRow').show();
                $('#receiptChange').text(DEFAULT_CURRENCY + changeAmount.toLocaleString());
            } else {
                $('#changeRow').hide();
            }
            
            // Due
            if (dueAmount > 0) {
                $('#dueRow').show();
                $('#receiptDue').text(DEFAULT_CURRENCY + dueAmount.toLocaleString());
            } else {
                $('#dueRow').hide();
            }
            
            // Store invoice ID for printing
            $('#printReceiptBtn').data('invoiceId', data.sale_id);
            $('#lastInvoiceId').val(data.sale_id);
            
            // Show notification
            showNotification('Sale Completed!', 'Invoice: ' + data.invoice_number + ' - Total: ' + DEFAULT_CURRENCY + total.toLocaleString(), 'success');
            
            // Auto open print after 1 second
            setTimeout(function() {
                var printWindow = window.open('<?php echo BASE_URL; ?>/invoice/print/' + data.sale_id, '_blank');
                if (printWindow) {
                    setTimeout(function() { printWindow.print(); }, 1500);
                }
            }, 1000);
            
            // Reset cart
            posCart = []; 
            renderPOSCart();
            document.getElementById('receiveAmount').value = 0;
            document.getElementById('vatPercent').value = 0;
            document.getElementById('discountAmount').value = 0;
            updatePOSSummary();
        } else { 
            alert(data.message || 'Error'); 
        }
    })
    .catch(err => {
        console.error('Sale error:', err);
        alert('Error: ' + err.message);
    });
}

// Function not needed anymore - using Bootstrap modal

function searchCustomersPOS() {
    const term = document.getElementById('customerSearch').value.toLowerCase();
    const opts = document.getElementById('cartCustomerSelect').options;
    for (let i = 0; i < opts.length; i++) {
        const text = opts[i].text.toLowerCase();
        const phone = opts[i].getAttribute('data-phone') || '';
        opts[i].style.display = (text.includes(term) || phone.includes(term)) ? 'block' : 'none';
    }
}

function handleBarcodeScan(e) {
    if (e.key === 'Enter') {
        const barcode = e.target.value.trim();
        if (barcode) {
            const product = document.querySelector(`.product-card[data-barcode="${barcode}"]`);
            if (product) { product.click(); e.target.value = ''; }
            else { alert('Product not found!'); }
        }
    }
}

function quickSaleMode() {
    document.getElementById('customerSearch').value = '';
    document.getElementById('cartCustomerSelect').value = '<?php echo $walkin_customer['id']; ?>';
    document.getElementById('productSearch').focus();
}

// Quick Customer Form Submit
document.getElementById('quickCustomerForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Quick customer form submitted');
    
    var name = document.getElementById('newCustomerName').value;
    var phone = document.getElementById('newCustomerPhone').value;
    var type = document.getElementById('newCustomerType').value;
    var email = document.getElementById('newCustomerEmail').value;
    var address = document.getElementById('newCustomerAddress').value;
    
    console.log('Form data:', {name, phone, type, email, address});
    
    // Use JSON format like other requests
    var requestData = JSON.stringify({
        customer_name: name,
        phone: phone,
        customer_type: type,
        email: email,
        address: address
    });
    
    console.log('Sending JSON:', requestData);
    
    fetch('<?php echo BASE_URL; ?>/customer/quickAdd', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: requestData
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        throw new Error('Network response was not ok');
    })
    .then(data => {
        console.log('Quick customer response:', data);
        if (data.success) {
            // Show notification
            showNotification('Success!', data.message || 'Customer added successfully', 'success');
            
            // Close modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('quickCustomerModal'));
            if (modal) modal.hide();
            
            // Clear form
            document.getElementById('quickCustomerForm').reset();
            
            // Add new customer to dropdown and select it
            var select = document.getElementById('cartCustomerSelect');
            var newOption = document.createElement('option');
            newOption.value = data.customer_id;
            newOption.text = data.customer_name + ' (New)';
            newOption.selected = true;
            select.add(newOption);
            
            // Show notification
            showNotification('Customer Selected!', data.customer_name + ' is now selected', 'success');
        } else {
            showNotification('Error', data.message || 'Error adding customer', 'error');
        }
    })
    .catch(error => {
        console.log('Error:', error);
        showNotification('Error', 'Failed to add customer', 'error');
    });
});

// Product Search with keyup event
document.getElementById('productSearch')?.addEventListener('keyup', function(e) {
    var searchTerm = this.value.toLowerCase();
    var productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(function(card) {
        var productName = card.getAttribute('data-name').toLowerCase();
        var barcode = card.getAttribute('data-barcode') || '';
        
        if (productName.includes(searchTerm) || barcode.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Handle Enter key for barcode scanning
    if (e.key === 'Enter') {
        var barcode = this.value.trim();
        if (barcode) {
            var productCard = document.querySelector('.product-card[data-barcode="' + barcode + '"]');
            if (productCard) {
                productCard.click();
                this.value = '';
            } else {
                alert('Product not found!');
            }
        }
    }
});

renderPOSCart();
updatePOSSummary();

// jQuery event handlers like jolchobi-pos
$(document).ready(function() {
    console.log('jQuery ready, setting up product handlers');
    
    // Product click to add to cart
    $(document).on('click', '.product-card', function(e) {
        e.preventDefault();
        console.log('Product card clicked via jQuery');
        
        const $card = $(this);
        const product = {
            id: $card.data('id'),
            name: $card.data('name'),
            price: parseFloat($card.data('price')),
            barcode: $card.data('barcode'),
            stock: parseInt($card.data('stock')) || 999,
            unit: $card.data('unit') || 'pcs'
        };
        
        // Visual feedback
        $card.addClass('added');
        setTimeout(() => $card.removeClass('added'), 300);
        
        addToPOSCart(product.id, product.name, product.price, product.stock);
    });

    // Clear cart button
    $('#clearCart').on('click', function() {
        clearPOSCart();
    });

    // VAT, Discount, Shipping change
    $('#vatPercent').on('input', function() {
        updatePOSSummary();
        autoFillPaidAmount();
    });
    
    $('#discountAmount').on('input', function() {
        updatePOSSummary();
        autoFillPaidAmount();
    });

    // Receive amount change
    $('#receiveAmount').on('input', function() {
        updatePOSSummary();
    });
    
    // Print receipt button - use event delegation for Bootstrap modal
    $(document).on('click', '#printReceiptBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Print button clicked');
        
        // Get invoice ID from button data attribute
        var invoiceId = $(this).data('invoiceId') || $('#lastInvoiceId').val();
        
        if (invoiceId) {
            console.log('Opening invoice print:', invoiceId);
            var printWindow = window.open('<?php echo BASE_URL; ?>/invoice/print/' + invoiceId, '_blank');
            if (printWindow) {
                setTimeout(function() {
                    printWindow.print();
                }, 1500);
            }
        } else {
            alert('Invoice ID not found! Please complete a sale first.');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';