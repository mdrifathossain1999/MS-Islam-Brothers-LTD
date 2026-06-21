<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config/config.php';
require_once 'app/core/Database.php';
require_once 'app/core/Controller.php';
require_once 'app/routes/router.php';

$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/' && $scriptName !== '\\') {
    $requestUri = str_replace($scriptName, '', $requestUri);
}
if (strpos($requestUri, '?') !== false) {
    $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
}
$_SERVER['REQUEST_URI'] = $requestUri;

$skipRoutes = ['setup.php', 'create_db.php'];
foreach ($skipRoutes as $skip) {
    if (strpos($requestUri, $skip) !== false) {
        $GLOBALS['SKIP_ROUTER'] = true;
        break;
    }
}

$router = new Router();

$router->get('/', function() {
    header('Location: auth/login');
    exit();
});

if (!isset($_GET['url']) && isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptDir !== '/' && $scriptDir !== '\\') {
        $uri = str_replace($scriptDir, '', $uri);
    }
    if (strpos($uri, '?') !== false) {
        $uri = substr($uri, 0, strpos($uri, '?'));
    }
    $uri = trim($uri, '/');
    if (!empty($uri)) {
        $_GET['url'] = $uri;
    }
}

$router->get('auth/login', ['AuthController', 'login']);
$router->post('auth/login', ['AuthController', 'login']);
$router->get('auth/logout', ['AuthController', 'logout']);
$router->get('auth/profile', ['AuthController', 'profile']);
$router->post('auth/profile', ['AuthController', 'profile']);

$router->get('dashboard', ['DashboardController', 'index']);
$router->get('dashboard/chartData', ['DashboardController', 'chartData']);

$router->get('product/index', ['ProductController', 'index']);
$router->get('product/create', ['ProductController', 'create']);
$router->post('product/create', ['ProductController', 'create']);
$router->get('product/edit/{id}', ['ProductController', 'edit']);
$router->post('product/edit/{id}', ['ProductController', 'edit']);
$router->get('product/delete/{id}', ['ProductController', 'delete']);
$router->get('product/search', ['ProductController', 'search']);
$router->get('product/barcode/{barcode}', ['ProductController', 'barcode']);

// Purchase Routes
$router->get('purchase/index', ['PurchaseController', 'index']);
$router->get('purchase/create', ['PurchaseController', 'create']);
$router->post('purchase/create', ['PurchaseController', 'create']);
$router->get('purchase/return', ['PurchaseController', 'returnPurchase']);
$router->post('purchase/return', ['PurchaseController', 'returnPurchase']);

// Sale Return Routes
$router->get('sale/return', ['SaleReturnController', 'index']);
$router->post('sale/return', ['SaleReturnController', 'index']);

// Expense Routes
$router->get('expense/index', ['ExpenseController', 'index']);
$router->get('expense/create', ['ExpenseController', 'create']);
$router->post('expense/create', ['ExpenseController', 'create']);

// Account Routes
$router->get('account/index', ['AccountController', 'index']);
$router->get('account/create', ['AccountController', 'create']);
$router->post('account/create', ['AccountController', 'create']);
$router->get('account/edit/{id}', ['AccountController', 'edit']);
$router->post('account/edit/{id}', ['AccountController', 'edit']);
$router->get('account/delete/{id}', ['AccountController', 'delete']);
$router->get('account/deposit', ['AccountController', 'deposit']);
$router->post('account/deposit', ['AccountController', 'deposit']);
$router->get('account/withdraw', ['AccountController', 'withdraw']);
$router->post('account/withdraw', ['AccountController', 'withdraw']);

// HRM Routes
$router->get('hrm/department', ['HrmController', 'department']);
$router->post('hrm/department', ['HrmController', 'department']);
$router->get('hrm/editDepartment/{id}', ['HrmController', 'editDepartment']);
$router->post('hrm/editDepartment/{id}', ['HrmController', 'editDepartment']);
$router->get('hrm/deleteDepartment/{id}', ['HrmController', 'deleteDepartment']);
$router->get('hrm/employee', ['HrmController', 'employee']);
$router->post('hrm/employee', ['HrmController', 'employee']);
$router->get('hrm/editEmployee/{id}', ['HrmController', 'editEmployee']);
$router->post('hrm/editEmployee/{id}', ['HrmController', 'editEmployee']);
$router->get('hrm/deleteEmployee/{id}', ['HrmController', 'deleteEmployee']);
$router->get('hrm/attendance', ['HrmController', 'attendance']);
$router->post('hrm/attendance', ['HrmController', 'attendance']);
$router->get('hrm/holiday', ['HrmController', 'holiday']);
$router->post('hrm/holiday', ['HrmController', 'holiday']);

// Supplier Routes
$router->get('supplier/index', ['SupplierController', 'index']);
$router->get('supplier/create', ['SupplierController', 'create']);
$router->post('supplier/create', ['SupplierController', 'create']);
$router->get('supplier/edit/{id}', ['SupplierController', 'edit']);
$router->post('supplier/edit/{id}', ['SupplierController', 'edit']);
$router->get('supplier/delete/{id}', ['SupplierController', 'delete']);

// Settings Routes
$router->get('settings/rolePermission', ['SettingsController', 'rolePermission']);
$router->post('settings/rolePermission', ['SettingsController', 'rolePermission']);

$router->get('pos', ['POSController', 'index']);
$router->get('pos/index', ['POSController', 'index']);
$router->post('pos/createSale', ['POSController', 'processSale']);
$router->post('pos/processSale', ['POSController', 'processSale']);
$router->post('pos/holdSale', ['POSController', 'holdSale']);
$router->get('pos/getHeldSales', ['POSController', 'getHeldSales']);
$router->get('pos/resumeHeldSale/{id}', ['POSController', 'resumeHeldSale']);
$router->get('pos/deleteHeldSale/{id}', ['POSController', 'deleteHeldSale']);

$router->get('invoice/index', ['InvoiceController', 'index']);
$router->get('invoice/today', ['InvoiceController', 'today']);
$router->get('invoice/dateRange', ['InvoiceController', 'dateRange']);
$router->get('invoice/view/{id}', ['InvoiceController', 'show']);
$router->get('invoice/print/{id}', ['InvoiceController', 'print']);
$router->get('invoice/search', ['InvoiceController', 'search']);
$router->get('invoice/due', ['InvoiceController', 'due']);
$router->get('invoice/unpaid', ['InvoiceController', 'unpaid']);
$router->get('invoice/templates', ['InvoiceController', 'templates']);
$router->post('invoice/saveTemplate', ['InvoiceController', 'saveTemplate']);
$router->get('invoice/editTemplate/{id}', ['InvoiceController', 'editTemplate']);
$router->post('invoice/editTemplate/{id}', ['InvoiceController', 'editTemplate']);
$router->get('invoice/deleteTemplate/{id}', ['InvoiceController', 'deleteTemplate']);
$router->get('invoice/setDefaultTemplate/{id}', ['InvoiceController', 'setDefaultTemplate']);
$router->get('invoice/toggleTemplateStatus/{id}', ['InvoiceController', 'toggleTemplateStatus']);
$router->get('invoice/numbering', ['InvoiceController', 'numbering']);
$router->post('invoice/saveNumbering', ['InvoiceController', 'saveNumbering']);
$router->get('invoice/deleteNumbering/{id}', ['InvoiceController', 'deleteNumbering']);
$router->get('invoice/createFromSales', ['InvoiceController', 'createFromSales']);

$router->get('customer/index', ['CustomerController', 'index']);
$router->get('customer/create', ['CustomerController', 'create']);
$router->post('customer/create', ['CustomerController', 'create']);
$router->post('customer/quickAdd', ['CustomerController', 'quickAdd']);
$router->get('customer/edit/{id}', ['CustomerController', 'edit']);
$router->post('customer/edit/{id}', ['CustomerController', 'edit']);
$router->get('customer/delete/{id}', ['CustomerController', 'delete']);
$router->get('customer/search', ['CustomerController', 'search']);
$router->get('customer/history/{id}', ['CustomerController', 'history']);
$router->get('customer/historyData/{id}', ['CustomerController', 'historyData']);
$router->get('customer/profile/{id}', ['CustomerController', 'profile']);
$router->get('customer/receivePayment/{id}', ['CustomerController', 'receivePayment']);
$router->post('customer/receivePayment/{id}', ['CustomerController', 'receivePayment']);
$router->post('customer/payment/{id}', ['CustomerController', 'payment']);

$router->get('report/index', ['ReportController', 'index']);
$router->get('report/daily', ['ReportController', 'daily']);
$router->get('report/monthly', ['ReportController', 'monthly']);
$router->get('report/productSales', ['ReportController', 'productSales']);
$router->get('report/stock', ['ReportController', 'stock']);
$router->get('report/customRange', ['ReportController', 'customRange']);
$router->get('report/profit', ['ReportController', 'profit']);

$router->get('admin', ['AdminController', 'index']);
$router->get('admin/index', ['AdminController', 'index']);
$router->get('admin/settings', ['AdminController', 'settings']);
$router->post('admin/updateSettings', ['AdminController', 'updateSettings']);
$router->get('admin/users', ['AdminController', 'users']);
$router->get('admin/createUser', ['AdminController', 'createUser']);
$router->post('admin/createUser', ['AdminController', 'createUser']);
$router->get('admin/editUser/{id}', ['AdminController', 'editUser']);
$router->post('admin/editUser/{id}', ['AdminController', 'editUser']);
$router->get('admin/deleteUser/{id}', ['AdminController', 'deleteUser']);
$router->get('admin/categories', ['AdminController', 'categories']);
$router->post('admin/addCategory', ['AdminController', 'addCategory']);
$router->get('admin/editCategory/{id}', ['AdminController', 'editCategory']);
$router->post('admin/updateCategory', ['AdminController', 'updateCategory']);
$router->get('admin/deleteCategory/{id}', ['AdminController', 'deleteCategory']);
$router->post('admin/deleteCategory/{id}', ['AdminController', 'deleteCategory']);
$router->post('admin/addSizeType', ['AdminController', 'addSizeType']);
$router->post('admin/deleteSizeType', ['AdminController', 'deleteSizeType']);
$router->get('admin/units', ['AdminController', 'units']);
$router->post('admin/addUnit', ['AdminController', 'addUnit']);
$router->post('admin/updateUnit', ['AdminController', 'updateUnit']);
$router->get('admin/deleteUnit/{id}', ['AdminController', 'deleteUnit']);
$router->get('admin/database', ['AdminController', 'database']);
$router->get('admin/uploads', ['AdminController', 'uploads']);
$router->post('admin/deleteUpload', ['AdminController', 'deleteUpload']);
$router->get('admin/backup', ['AdminController', 'backup']);
$router->get('admin/downloadBackup', ['AdminController', 'downloadBackup']);
$router->get('admin/exportCustomers', ['AdminController', 'exportCustomers']);
$router->get('admin/summaryCards', ['AdminController', 'summaryCards']);
$router->post('admin/createSummaryCard', ['AdminController', 'createSummaryCard']);
$router->get('admin/editSummaryCard/{id}', ['AdminController', 'editSummaryCard']);
$router->post('admin/editSummaryCard/{id}', ['AdminController', 'editSummaryCard']);
$router->get('admin/deleteSummaryCard/{id}', ['AdminController', 'deleteSummaryCard']);
$router->get('admin/toggleSummaryCard/{id}', ['AdminController', 'toggleSummaryCard']);
$router->get('admin/productImportExport', ['AdminController', 'productImportExport']);
$router->get('admin/exportProducts', ['AdminController', 'exportProducts']);
$router->post('admin/importProducts', ['AdminController', 'importProducts']);
$router->post('admin/downloadSample', ['AdminController', 'downloadSample']);
$router->get('admin/customerTypes', ['AdminController', 'customerTypes']);
$router->post('admin/createCustomerType', ['AdminController', 'createCustomerType']);
$router->get('admin/editCustomerType/{id}', ['AdminController', 'editCustomerType']);
$router->post('admin/editCustomerType/{id}', ['AdminController', 'editCustomerType']);
$router->get('admin/deleteCustomerType/{id}', ['AdminController', 'deleteCustomerType']);
$router->get('admin/toggleCustomerType/{id}', ['AdminController', 'toggleCustomerType']);
$router->get('admin/activityLogs', ['AdminController', 'activityLogs']);
$router->post('admin/clearActivityLogs', ['AdminController', 'clearActivityLogs']);
$router->get('admin/categoryUnit', ['AdminController', 'categoryUnit']);
$router->post('admin/saveCategoryUnit', ['AdminController', 'saveCategoryUnit']);
$router->post('admin/deleteCategoryUnit', ['AdminController', 'deleteCategoryUnit']);
$router->get('admin/createCategoryUnitTable', ['AdminController', 'createCategoryUnitTable']);

if (!empty($GLOBALS['SKIP_ROUTER'])) {
    exit;
}

$router->dispatch();
