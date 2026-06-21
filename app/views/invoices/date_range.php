<?php 
$pageTitle = 'Sales by Date Range';
ob_start();
?>
<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-calendar-range"></i> Sales by Date Range</h4>

    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?php echo $sale['invoice_number']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                            <td><?php echo $sale['customer_name'] ?: 'Walk-in'; ?></td>
                            <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 2); ?></td>
                            <td><?php echo ucfirst($sale['payment_method']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $sale['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/invoice/print/<?php echo $sale['id']; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
