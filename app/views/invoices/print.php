<?php 
$print = isset($_GET['print']) && $_GET['print'] === 'true';
$cleanPrint = isset($_GET['clean']) && $_GET['clean'] === 'true';
ob_start();

$template = $selectedTemplate ?? [];
$tpl = [
    'header_text' => $template['header_text'] ?? SITE_NAME,
    'footer_text' => $template['footer_text'] ?? 'Thank you for shopping with us!',
    'show_barcode' => $template['show_barcode'] ?? 'yes',
    'show_logo' => $template['show_logo'] ?? 'yes',
    'color_scheme' => $template['color_scheme'] ?? '#667eea',
    'template_type' => $template['template_type'] ?? 'a4',
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice: <?php echo $sale['invoice_number'] ?? ''; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #667eea;
            --border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --bg-surface: #f8fafc;
        }
        @page { size: A4; margin: 10mm; }
        body { 
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; 
            font-size: 12px;
            color: var(--text-primary);
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .invoice-a4 {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 15mm;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 3px solid var(--primary);
        }
        .company-info h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .company-info p {
            margin: 2px 0;
            color: var(--text-secondary);
            font-size: 11px;
        }
        .company-info .company-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            letter-spacing: 3px;
        }
        .invoice-title p {
            margin: 5px 0 0;
            color: var(--text-secondary);
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .info-box {
            background: var(--bg-surface);
            padding: 15px;
            border-radius: 8px;
            width: 48%;
        }
        .info-box h6 {
            font-size: 10px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin: 0 0 8px 0;
            letter-spacing: 1px;
        }
        .info-box p {
            margin: 0;
            font-size: 13px;
            color: var(--text-primary);
            line-height: 1.6;
        }
        .info-box strong {
            color: var(--primary);
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table thead tr {
            background: var(--primary);
            color: white;
        }
        .items-table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table th:nth-child(2),
        .items-table th:nth-child(3),
        .items-table th:nth-child(4) {
            text-align: center;
        }
        .items-table th:last-child {
            text-align: right;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
        }
        .items-table td:nth-child(2),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: center;
        }
        .items-table td:last-child {
            text-align: right;
        }
        .items-table tbody tr:hover {
            background: var(--bg-surface);
        }
        .items-table tbody tr:last-child td {
            border-bottom: 2px solid var(--primary);
        }
        
        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }
        .totals-table {
            width: 280px;
            border-collapse: collapse;
        }
        .totals-table tr {
            border-bottom: 1px solid var(--border);
        }
        .totals-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
        .totals-table td:first-child {
            color: var(--text-secondary);
            text-align: left;
        }
        .totals-table td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .totals-table .grand-total {
            background: var(--primary);
            color: white;
            font-size: 16px;
            font-weight: 700;
        }
        .totals-table .grand-total td {
            padding: 12px;
        }
        .totals-table .due-amount {
            color: #dc2626;
            font-weight: 700;
        }
        .totals-table .change-amount {
            color: #059669;
            font-weight: 600;
        }
        
        /* Payment Info */
        .payment-section {
            background: var(--bg-surface);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .payment-method i {
            font-size: 20px;
            color: var(--primary);
        }
        .payment-method span {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px dashed var(--border);
        }
        .signature-box {
            text-align: center;
            width: 40%;
        }
        .signature-line {
            border-bottom: 2px solid var(--text-primary);
            margin-bottom: 8px;
            height: 40px;
        }
        .signature-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid var(--border);
        }
        .footer-message {
            font-size: 14px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 10px;
            font-style: italic;
        }
        .footer-note {
            font-size: 10px;
            color: var(--text-muted);
        }
        
        /* Print Button */
        .no-print {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
        }
        .no-print .btn {
            padding: 12px 30px;
            font-size: 14px;
            border-radius: 8px;
        }
        
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .invoice-a4 {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-a4">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1><?php echo SITE_NAME; ?></h1>
                <p class="company-name"><?php echo COMPANY_NAME; ?></p>
                <p><i class="bi bi-geo-alt"></i> <?php echo COMPANY_ADDRESS; ?></p>
                <p><i class="bi bi-telephone"></i> <?php echo COMPANY_PHONE; ?></p>
                <p><i class="bi bi-envelope"></i> <?php echo COMPANY_EMAIL; ?></p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>#<?php echo $sale['invoice_number'] ?? ''; ?></strong></p>
                <p><i class="bi bi-calendar"></i> <?php echo date('d M Y', strtotime($sale['sale_date'] ?? date('Y-m-d'))); ?></p>
                <p><i class="bi bi-clock"></i> <?php echo $sale['sale_time'] ?? date('H:i'); ?></p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h6><i class="bi bi-person"></i> Bill To</h6>
                <p>
                    <strong><?php echo $sale['customer_name'] ?: 'Walk-in Customer'; ?></strong><br>
                    <?php if (!empty($sale['customer_phone'])): ?>
                    <i class="bi bi-telephone"></i> <?php echo $sale['customer_phone']; ?><br>
                    <?php endif; ?>
                </p>
            </div>
            <div class="info-box">
                <h6><i class="bi bi-person-badge"></i> From</h6>
                <p>
                    <strong>Cashier:</strong> <?php echo $sale['cashier_name'] ?? 'Admin'; ?><br>
                    <strong>Status:</strong> <?php echo ($sale['paid_amount'] ?? 0) >= ($sale['total_amount'] ?? 0) ? '<span style="color: #059669;">Paid</span>' : '<span style="color: #dc2626;">Due</span>'; ?>
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45%;">Item Description</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 20%;">Unit Price</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo $item['product_name'] ?? 'Unknown'; ?></td>
                    <td><?php echo $item['quantity'] ?? 1; ?></td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($item['unit_price'] ?? 0, 2); ?></td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0), 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['subtotal'] ?? 0, 2); ?></td>
                </tr>
                <?php if (($sale['discount_amount'] ?? 0) > 0): ?>
                <tr>
                    <td>Discount</td>
                    <td style="color: #dc2626;">-<?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['discount_amount'], 2); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (($sale['tax_amount'] ?? 0) > 0): ?>
                <tr>
                    <td>Tax (<?php echo $sale['tax_percent'] ?? 0; ?>%)</td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['tax_amount'], 2); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['total_amount'] ?? 0, 2); ?></td>
                </tr>
                <tr>
                    <td>Paid Amount</td>
                    <td style="color: #059669;"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['paid_amount'] ?? 0, 2); ?></td>
                </tr>
                <?php $due = ($sale['total_amount'] ?? 0) - ($sale['paid_amount'] ?? 0); ?>
                <?php if ($due > 0): ?>
                <tr>
                    <td>Due Balance</td>
                    <td class="due-amount"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($due, 2); ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td>Change Given</td>
                    <td class="change-amount"><?php echo DEFAULT_CURRENCY; ?><?php echo number_format($sale['change_amount'] ?? 0, 2); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
            <div class="payment-method">
                <i class="bi bi-credit-card"></i>
                <span><?php echo ucfirst($sale['payment_method'] ?? 'Cash'); ?></span>
            </div>
            <?php if ($tpl['show_barcode'] === 'yes'): ?>
            <div>
                <img src="https://barcode.tec-it.com/barcode.tec-it.com/png?code=<?php echo urlencode($sale['invoice_number']); ?>&type=Code128&dpi=72" alt="Barcode" style="height: 35px;">
            </div>
            <?php endif; ?>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Customer Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-message">*** Thank You For Shopping With Us ***</div>
            <div class="footer-note">
                <p><?php echo nl2br(htmlspecialchars($tpl['footer_text'])); ?></p>
                <p>Powered by Digital Ledger Solutions | <?php echo date('Y'); ?></p>
            </div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="bi bi-printer"></i> Print Invoice
        </button>
    </div>
</body>
</html>

<?php
if ($print) {
    echo ob_get_clean();
    exit;
}
echo ob_get_clean();