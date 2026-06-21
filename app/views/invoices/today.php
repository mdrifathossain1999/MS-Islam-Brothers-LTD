<?php 
$pageTitle = "Today's Sales";
$currentPage = 'invoices';
ob_start();
?>
<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-calendar-check"></i> Today's Sales - <?php echo date('M d, Y'); ?></h4> 

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Sales</h6>
                    <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($today_total ?? 0, 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Paid</h6>
                    <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($today_paid ?? 0, 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Total Due</h6>
                    <h3><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($today_due ?? 0, 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Transactions</h6>
                    <h3><?php echo count($sales); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): 
                            $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
                        ?>
                        <tr>
                            <td><?php echo $sale['invoice_number']; ?></td>
                            <td><?php echo $sale['sale_time']; ?></td>
                            <td><?php echo $sale['customer_name'] ?: 'Walk-in'; ?></td>
                            <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'], 0); ?></td>
                            <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['paid_amount'], 0); ?></td>
                            <td class="<?php echo $due > 0 ? 'text-danger fw-bold' : 'text-success'; ?>">
                                <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($due, 0); ?>
                            </td>
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
