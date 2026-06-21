<?php 
$isPrint = true;
$pageTitle = 'Print Invoice';
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo $invoice['invoice_number']; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; font-size: 12px; color: var(--text-primary); }
        .invoice-print { max-width: 80mm; margin: 0 auto; padding: 10px; }
        
        .invoice-header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid var(--primary); padding-bottom: 10px; }
        .company-name { font-size: 18px; font-weight: 700; color: var(--primary); }
        .company-address { font-size: 10px; color: var(--text-secondary); margin-top: 3px; }
        
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .invoice-info div { font-size: 11px; }
        .invoice-info strong { color: var(--primary); }
        
        .customer-info { background: var(--bg-surface-alt); padding: 8px; border-radius: var(--radius-sm); margin-bottom: 15px; }
        .customer-info div { margin-bottom: 3px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th { background: var(--primary); color: white; padding: 6px; text-align: left; font-size: 10px; }
        .items-table td { padding: 6px; border-bottom: 1px solid var(--border); }
        .items-table tr:last-child td { border-bottom: none; }
        
        .summary-section { display: flex; flex-direction: column; align-items: flex-end; }
        .summary-row { display: flex; justify-content: space-between; width: 150px; padding: 3px 0; }
        .summary-row.total { font-weight: 700; font-size: 14px; border-top: 2px solid var(--primary); padding-top: 5px; margin-top: 5px; }
        
        .payment-info { background: var(--bg-surface-alt); padding: 10px; border-radius: var(--radius-sm); margin-top: 15px; }
        .payment-info div { margin-bottom: 3px; }
        
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid var(--border); }
        .footer-text { font-size: 10px; color: var(--text-muted); }
        .thank-you { font-size: 12px; color: var(--primary); font-weight: 600; margin-bottom: 5px; }
        
        .print-btn { position: fixed; top: 20px; right: 20px; }
        
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .print-btn { display: none; }
            .invoice-print { width: 100%; max-width: none; }
        }
    </style>
</head>
<body>
    <button class="btn btn-primary print-btn" onclick="window.print()">
        <i class="bi bi-printer"></i> Print
    </button>
    
    <div class="invoice-print">
        <!-- Company Header -->
        <div class="invoice-header">
            <div class="company-name"><?php echo isset($template['header_text']) ? nl2br($template['header_text']) : 'Sumon Enterprise Ltd'; ?></div>
        </div>
        
        <!-- Invoice Info -->
        <div class="invoice-info">
            <div>
                <strong>Invoice #:</strong> <?php echo $invoice['invoice_number']; ?><br>
                <strong>Date:</strong> <?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?>
                <?php if (!empty($invoice['due_date'])): ?>
                <br><strong>Due:</strong> <?php echo date('d M Y', strtotime($invoice['due_date'])); ?>
                <?php endif; ?>
            </div>
            <div style="text-align: right;">
                <strong>Status:</strong> <?php echo ucfirst($invoice['payment_status']); ?><br>
                <strong>Type:</strong> <?php echo ucfirst($invoice['invoice_type']); ?>
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="customer-info">
            <strong><i class="bi bi-person"></i> Customer:</strong> <?php echo $invoice['customer_name'] ?? 'Walk-in Customer'; ?><br>
            <?php if (!empty($invoice['customer_phone'])): ?>
            <strong><i class="bi bi-phone"></i> Phone:</strong> <?php echo $invoice['customer_phone']; ?><br>
            <?php endif; ?>
            <?php if (!empty($invoice['customer_address'])): ?>
            <strong><i class="bi bi-geo-alt"></i> Address:</strong> <?php echo $invoice['customer_address']; ?>
            <?php endif; ?>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['unit_price'], 2); ?></td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['total_price'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['subtotal'], 2); ?></span>
            </div>
            <?php if ($invoice['discount_amount'] > 0): ?>
            <div class="summary-row">
                <span>Discount:</span>
                <span class="text-danger">-<?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['discount_amount'], 2); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($invoice['tax_amount'] > 0): ?>
            <div class="summary-row">
                <span>Tax:</span>
                <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['tax_amount'], 2); ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row total">
                <span>Total:</span>
                <span><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['total_amount'], 2); ?></span>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div class="payment-info">
            <div><strong>Payment Method:</strong> <?php echo ucfirst($invoice['payment_method']); ?></div>
            <div><strong>Paid:</strong> <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['paid_amount'], 2); ?></div>
            <div><strong>Due:</strong> <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['due_amount'], 2); ?></div>
            <?php if ($invoice['change_amount'] > 0): ?>
            <div><strong>Change:</strong> <?php echo DEFAULT_CURRENCY; ?><?php echo number_format($invoice['change_amount'], 2); ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank You for Shopping!</div>
            <div class="footer-text"><?php echo isset($template['footer_text']) ? $template['footer_text'] : 'Please come again'; ?></div>
            <?php if (isset($template['show_terms']) && $template['show_terms'] == 'yes' && !empty($template['terms_content'])): ?>
            <div class="footer-text" style="margin-top: 10px; font-size: 9px;">
                <?php echo nl2br($template['terms_content']); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;