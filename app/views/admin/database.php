<?php 
$pageTitle = 'Database Management';
$currentPage = 'admin';
$subPage = 'database';
ob_start();
$today = date('Y-m-d');
$firstDayMonth = date('Y-m-01');
$lastDayMonth = date('Y-m-t');
?>

<style>
.db-card { background: var(--bg-card); border-radius: 12px; padding: 20px; box-shadow: var(--shadow-md); }
.db-section { margin-bottom: 32px; }
.db-section h4 { font-size: 16px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.db-btn { padding: 10px 20px; border-radius: var(--radius-sm); font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; font-size: 14px; }
.db-btn-success { background: var(--success); color: var(--bg-card); }
.db-btn-success:hover { background: #16a34a; }
.db-btn-primary { background: var(--primary); color: var(--bg-card); }
.db-btn-primary:hover { background: var(--primary-dark); }
.db-btn-info { background: var(--info); color: var(--bg-card); }
.db-btn-info:hover { background: #0891b2; }
.db-btn-warning { background: var(--warning); color: var(--bg-card); }
.db-btn-warning:hover { background: #d97706; }
.db-btn-sm { padding: 6px 12px; font-size: 13px; }
.info-box { background: var(--bg-surface-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px; }
.info-box p { margin: 0; font-size: 14px; color: var(--text-secondary); }
.export-card { background: var(--bg-surface-alt); border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-bottom: 15px; }
.export-card h5 { margin: 0 0 8px 0; font-size: 15px; }
.export-card p { margin: 0 0 12px 0; font-size: 13px; color: var(--text-muted); }
.export-card .btn-group { display: flex; gap: 8px; flex-wrap: wrap; }
</style>

<div class="page-header">
    <h1><i class="bi bi-database"></i> Database Management</h1>
    <a href="<?php echo BASE_URL; ?>/admin" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="db-card">
    
    <div class="db-section">
        <h4><i class="bi bi-download text-success"></i> Database Backup</h4>
        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i> Create a backup of your database including all products, customers, sales, and user data.</p>
        </div>
        <button class="db-btn db-btn-success" id="backupBtn">
            <i class="bi bi-database"></i> Create Backup Now
        </button>
        <div id="backupResult" class="mt-3"></div>
    </div>

    <div class="db-section">
        <h4><i class="bi bi-file-earmark-spreadsheet text-info"></i> Reports Export</h4>
        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i> Export reports in CSV format for Excel or other spreadsheet applications.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-calendar-day me-2"></i>Daily Sales Report</h5>
                    <p>Export sales for a specific date</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/report/daily?date=<?= $today; ?>&export=csv" class="db-btn db-btn-success db-btn-sm">
                            <i class="bi bi-download me-1"></i>Today CSV
                        </a>
                        <a href="<?= BASE_URL; ?>/report/daily?date=<?= date('Y-m-d', strtotime('-1 day')); ?>&export=csv" class="db-btn db-btn-success db-btn-sm" style="background:var(--bg-card);color:var(--success);border:1px solid var(--success);">
                            <i class="bi bi-download me-1"></i>Yesterday
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-calendar-month me-2"></i>Monthly Sales Report</h5>
                    <p>Export sales for an entire month</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/report/monthly?year=<?= date('Y'); ?>&month=<?= date('m'); ?>&export=csv" class="db-btn db-btn-info db-btn-sm">
                            <i class="bi bi-download me-1"></i>This Month CSV
                        </a>
                        <a href="<?= BASE_URL; ?>/report/monthly?year=<?= date('Y'); ?>&month=<?= str_pad(date('m') - 1, 2, '0', STR_PAD_LEFT); ?>&export=csv" class="db-btn db-btn-info db-btn-sm" style="background:var(--bg-card);color:var(--info);border:1px solid var(--info);">
                            <i class="bi bi-download me-1"></i>Last Month
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-calendar-range me-2"></i>Custom Date Range</h5>
                    <p>Select any date range for export</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/report/customRange?start_date=<?= $firstDayMonth; ?>&end_date=<?= $lastDayMonth; ?>&export=csv" class="db-btn db-btn-primary db-btn-sm">
                            <i class="bi bi-download me-1"></i>This Month CSV
                        </a>
                        <a href="<?= BASE_URL; ?>/report/customRange?start_date=<?= date('Y-01-01'); ?>&end_date=<?= $today; ?>&export=csv" class="db-btn db-btn-primary db-btn-sm" style="background:var(--bg-card);color:var(--primary);border:1px solid var(--primary);">
                            <i class="bi bi-download me-1"></i>This Year
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-boxes me-2"></i>Stock Report</h5>
                    <p>Export current inventory status</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/report/stock?export=csv" class="db-btn db-btn-warning db-btn-sm">
                            <i class="bi bi-download me-1"></i>Stock CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-bar-chart me-2"></i>Product Sales Report</h5>
                    <p>Export product-wise sales breakdown</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/report/productSales?start_date=<?= $firstDayMonth; ?>&end_date=<?= $lastDayMonth; ?>&export=csv" class="db-btn db-btn-primary db-btn-sm" style="background:#6c757d;color:#fff;border:none;">
                            <i class="bi bi-download me-1"></i>This Month CSV
                        </a>
                        <a href="<?= BASE_URL; ?>/report/productSales?start_date=<?= $today; ?>&end_date=<?= $today; ?>&export=csv" class="db-btn db-btn-sm" style="background:#fff;color:#6c757d;border:1px solid #6c757d;">
                            <i class="bi bi-download me-1"></i>Today Only
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="export-card">
                    <h5><i class="bi bi-people me-2"></i>Customer Data</h5>
                    <p>Export customer list with due information</p>
                    <div class="btn-group">
                        <a href="<?= BASE_URL; ?>/admin/exportCustomers" class="db-btn db-btn-sm" style="background:#6c757d;color:#fff;border:none;">
                            <i class="bi bi-download me-1"></i>Customers CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="db-section">
        <h4><i class="bi bi-table text-primary"></i> Database Tables</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Table Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>users</td><td>System users and admins</td></tr>
                <tr><td>products</td><td>Product catalog</td></tr>
                <tr><td>customers</td><td>Customer database</td></tr>
                <tr><td>sales</td><td>Sales transactions</td></tr>
                <tr><td>sale_items</td><td>Individual sale items</td></tr>
                <tr><td>payments</td><td>Payment records</td></tr>
                <tr><td>stock_logs</td><td>Stock movement history</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$('#backupBtn').on('click', function() {
    if (!confirm('Create a database backup now?')) return;
    
    $(this).prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Creating backup...');
    
    $.ajax({
        url: BASE_URL + '/admin/backup',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#backupResult').html(
                    '<div class="alert" style="background:var(--success);color:#fff;border:1px solid var(--success);padding:12px 16px;border-radius:var(--radius-sm);">' +
                    '<i class="bi bi-check-circle me-2"></i>' + response.message + 
                    ' <a href="' + response.download_url + '" class="db-btn db-btn-success db-btn-sm" style="text-decoration:none;"><i class="bi bi-download"></i> Download</a>' +
                    '</div>'
                );
            } else {
                $('#backupResult').html('<div class="alert" style="background:var(--danger);color:#fff;border:1px solid var(--danger);padding:12px 16px;border-radius:var(--radius-sm);"><i class="bi bi-x-circle me-2"></i>' + response.message + '</div>');
            }
        },
        error: function(xhr) {
            $('#backupResult').html('<div class="alert" style="background:var(--danger);color:#fff;border:1px solid var(--danger);padding:12px 16px;border-radius:var(--radius-sm);"><i class="bi bi-x-circle me-2"></i>Backup failed! ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error') + '</div>');
        },
        complete: function() {
            $('#backupBtn').prop('disabled', false).html('<i class="bi bi-database"></i> Create Backup Now');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
