<?php
class ReportController extends Controller {
    public function index() {
        $this->requireLogin();
        $this->view('reports/index');
    }

    public function daily($format = null) {
        $this->requireLogin();

        $date = $_GET['date'] ?? date('Y-m-d');
        $export = $_GET['export'] ?? $format;

        $saleModel = $this->model('Sale');
        $sales = $saleModel->getByDate($date);

        $totalSales = 0;
        $totalPaid = 0;
        $totalDue = 0;
        
        foreach ($sales as $sale) {
            if ($sale['status'] === 'completed') {
                $totalSales += $sale['total_amount'];
                $totalPaid += $sale['paid_amount'];
                $totalDue += ($sale['total_amount'] - $sale['paid_amount']);
            }
        }

        if ($export === 'csv') {
            $this->exportDailyCSV($sales, $date, $totalSales, $totalPaid, $totalDue);
            return;
        }

        $this->view('reports/daily', [
            'sales' => $sales,
            'date' => $date,
            'totalSales' => $totalSales,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'transactionCount' => count($sales)
        ]);
    }

    private function exportDailyCSV($sales, $date, $totalSales, $totalPaid, $totalDue) {
        $filename = 'daily_report_' . $date . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Jolchobi Setabganj - Daily Sales Report']);
        fputcsv($output, ['Date:', $date]);
        fputcsv($output, []);
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Sales', number_format($totalSales, 2)]);
        fputcsv($output, ['Total Paid', number_format($totalPaid, 2)]);
        fputcsv($output, ['Total Due', number_format($totalDue, 2)]);
        fputcsv($output, ['Total Transactions', count($sales)]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'Invoice', 'Customer', 'Total', 'Paid', 'Due', 'Payment Method', 'Status']);
        
        $index = 1;
        foreach ($sales as $sale) {
            $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
            fputcsv($output, [
                $index++,
                $sale['invoice_number'],
                $sale['customer_name'] ?? 'Walk-in',
                number_format($sale['total_amount'], 2),
                number_format($sale['paid_amount'], 2),
                number_format($due, 2),
                ucfirst($sale['payment_method']),
                $sale['status']
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function monthly($format = null) {
        $this->requireLogin();

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        $export = $_GET['export'] ?? $format;

        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $saleModel = $this->model('Sale');
        $sales = $saleModel->getByDateRange($startDate, $endDate);

        $totalSales = 0;
        $totalPaid = 0;
        $totalDue = 0;
        $totalCash = 0;
        $totalCard = 0;
        $totalMobile = 0;

        foreach ($sales as $sale) {
            if ($sale['status'] === 'completed') {
                $totalSales += $sale['total_amount'];
                $totalPaid += $sale['paid_amount'];
                $totalDue += ($sale['total_amount'] - $sale['paid_amount']);
                
                if ($sale['payment_method'] === 'cash') {
                    $totalCash += $sale['paid_amount'];
                } elseif ($sale['payment_method'] === 'card') {
                    $totalCard += $sale['paid_amount'];
                } elseif ($sale['payment_method'] === 'mobile') {
                    $totalMobile += $sale['paid_amount'];
                }
            }
        }

        if ($export === 'csv') {
            $this->exportMonthlyCSV($sales, $year, $month, $totalSales, $totalPaid, $totalDue, $totalCash, $totalCard, $totalMobile);
            return;
        }

        $this->view('reports/monthly', [
            'sales' => $sales,
            'year' => $year,
            'month' => $month,
            'totalSales' => $totalSales,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'totalCash' => $totalCash,
            'totalCard' => $totalCard,
            'totalMobile' => $totalMobile,
            'transactionCount' => count($sales)
        ]);
    }

    private function exportMonthlyCSV($sales, $year, $month, $totalSales, $totalPaid, $totalDue, $totalCash, $totalCard, $totalMobile) {
        $monthName = date('F Y', strtotime("$year-$month-01"));
        $filename = 'monthly_report_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Jolchobi Setabganj - Monthly Sales Report']);
        fputcsv($output, ['Month:', $monthName]);
        fputcsv($output, []);
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Sales', number_format($totalSales, 2)]);
        fputcsv($output, ['Total Paid', number_format($totalPaid, 2)]);
        fputcsv($output, ['Total Due', number_format($totalDue, 2)]);
        fputcsv($output, ['Cash Payments', number_format($totalCash, 2)]);
        fputcsv($output, ['Card Payments', number_format($totalCard, 2)]);
        fputcsv($output, ['Mobile Payments', number_format($totalMobile, 2)]);
        fputcsv($output, ['Total Transactions', count($sales)]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'Invoice', 'Date', 'Customer', 'Total', 'Paid', 'Due', 'Payment Method', 'Status']);
        
        $index = 1;
        foreach ($sales as $sale) {
            $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
            fputcsv($output, [
                $index++,
                $sale['invoice_number'],
                $sale['sale_date'],
                $sale['customer_name'] ?? 'Walk-in',
                number_format($sale['total_amount'], 2),
                number_format($sale['paid_amount'], 2),
                number_format($due, 2),
                ucfirst($sale['payment_method']),
                $sale['status']
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function productSales($format = null) {
        $this->requireLogin();

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $export = $_GET['export'] ?? $format;

        $saleItemModel = $this->model('SaleItem');
        $products = $saleItemModel->getProductSalesReport($startDate, $endDate);

        $totalSales = 0;
        $totalQuantity = 0;
        foreach ($products as $p) {
            $totalSales += $p['total_sales'];
            $totalQuantity += $p['total_quantity'];
        }

        if ($export === 'csv') {
            $this->exportProductSalesCSV($products, $startDate, $endDate, $totalSales, $totalQuantity);
            return;
        }

        $this->view('reports/product_sales', [
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalQuantity' => $totalQuantity
        ]);
    }

    private function exportProductSalesCSV($products, $startDate, $endDate, $totalSales, $totalQuantity) {
        $filename = 'product_sales_' . $startDate . '_to_' . $endDate . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Jolchobi Setabganj - Product Sales Report']);
        fputcsv($output, ['Period:', $startDate . ' to ' . $endDate]);
        fputcsv($output, []);
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Sales', number_format($totalSales, 2)]);
        fputcsv($output, ['Total Quantity', $totalQuantity]);
        fputcsv($output, ['Products Sold', count($products)]);
        fputcsv($output, []);
        fputcsv($output, ['#', 'Product', 'Barcode', 'Quantity Sold', 'Total Sales']);
        
        $index = 1;
        foreach ($products as $product) {
            fputcsv($output, [
                $index++,
                $product['product_name'],
                $product['barcode'] ?? '-',
                $product['total_quantity'],
                number_format($product['total_sales'], 2)
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function stock($format = null) {
        $this->requireAdmin();
        
        $export = $_GET['export'] ?? $format;
        $productModel = $this->model('Product');
        $products = $productModel->getAll();
        $lowStock = $productModel->getLowStock();

        if ($export === 'csv') {
            $this->exportStockCSV($products, $lowStock);
            return;
        }

        $this->view('reports/stock', [
            'products' => $products,
            'lowStock' => $lowStock
        ]);
    }

    private function exportStockCSV($products, $lowStock) {
        $filename = 'stock_report_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Jolchobi Setabganj - Stock Report']);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        fputcsv($output, ['Low Stock Alert (' . count($lowStock) . ' items)']);
        fputcsv($output, ['Product', 'Current Stock', 'Threshold']);
        foreach ($lowStock as $product) {
            fputcsv($output, [
                $product['product_name'],
                $product['stock_quantity'],
                $product['low_stock_threshold']
            ]);
        }
        fputcsv($output, []);
        fputcsv($output, ['All Products Stock']);
        fputcsv($output, ['#', 'Product', 'Barcode', 'Category', 'Stock', 'Unit', 'Value']);
        
        $index = 1;
        foreach ($products as $product) {
            $value = $product['stock_quantity'] * $product['sell_price'];
            fputcsv($output, [
                $index++,
                $product['product_name'],
                $product['barcode'] ?? '-',
                $product['category'] ?? '-',
                $product['stock_quantity'],
                $product['unit'],
                number_format($value, 2)
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function customRange() {
        $this->requireLogin();
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $export = $_GET['export'] ?? null;

        $saleModel = $this->model('Sale');
        $saleItemModel = $this->model('SaleItem');
        
        $sales = $saleModel->getByDateRange($startDate, $endDate);
        $products = $saleItemModel->getProductSalesReport($startDate, $endDate);

        $totalSales = 0;
        $totalPaid = 0;
        $totalDue = 0;
        
        foreach ($sales as $sale) {
            if ($sale['status'] === 'completed') {
                $totalSales += $sale['total_amount'];
                $totalPaid += $sale['paid_amount'];
                $totalDue += ($sale['total_amount'] - $sale['paid_amount']);
            }
        }

        $totalQty = 0;
        foreach ($products as $p) {
            $totalQty += $p['total_quantity'];
        }

        if ($export === 'csv') {
            $this->exportCustomRangeCSV($sales, $products, $startDate, $endDate, $totalSales, $totalPaid, $totalDue, $totalQty);
            return;
        }

        $this->view('reports/custom_range', [
            'sales' => $sales,
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'totalQuantity' => $totalQty
        ]);
    }

    private function exportCustomRangeCSV($sales, $products, $startDate, $endDate, $totalSales, $totalPaid, $totalDue, $totalQty) {
        $filename = 'report_' . $startDate . '_to_' . $endDate . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Jolchobi Setabganj - Sales Report']);
        fputcsv($output, ['Period:', $startDate . ' to ' . $endDate]);
        fputcsv($output, []);
        fputcsv($output, ['Summary']);
        fputcsv($output, ['Total Sales', number_format($totalSales, 2)]);
        fputcsv($output, ['Total Paid', number_format($totalPaid, 2)]);
        fputcsv($output, ['Total Due', number_format($totalDue, 2)]);
        fputcsv($output, ['Total Transactions', count($sales)]);
        fputcsv($output, ['Total Items Sold', $totalQty]);
        fputcsv($output, []);
        fputcsv($output, ['Sales Details']);
        fputcsv($output, ['#', 'Invoice', 'Date', 'Customer', 'Total', 'Paid', 'Due', 'Payment']);
        
        $index = 1;
        foreach ($sales as $sale) {
            $due = floatval($sale['total_amount']) - floatval($sale['paid_amount']);
            fputcsv($output, [
                $index++,
                $sale['invoice_number'],
                $sale['sale_date'],
                $sale['customer_name'] ?? 'Walk-in',
                number_format($sale['total_amount'], 2),
                number_format($sale['paid_amount'], 2),
                number_format($due, 2),
                ucfirst($sale['payment_method'])
            ]);
        }
        
        fputcsv($output, []);
        fputcsv($output, ['Product Sales Summary']);
        fputcsv($output, ['#', 'Product', 'Barcode', 'Quantity', 'Total Sales']);
        
        $index = 1;
        foreach ($products as $product) {
            fputcsv($output, [
                $index++,
                $product['product_name'],
                $product['barcode'] ?? '-',
                $product['total_quantity'],
                number_format($product['total_sales'], 2)
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function profit() {
        $this->requireLogin();

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $salesData = $this->db->query("
            SELECT 
                SUM(total_amount) as total_sales,
                SUM(paid_amount) as total_collected
            FROM sales 
            WHERE sale_date >= '$startDate' 
            AND sale_date <= '$endDate'
            AND status = 'completed'
        ")->fetch();

        $productSalesData = $this->db->query("
            SELECT 
                si.product_id,
                p.product_name,
                p.cost_price,
                SUM(si.quantity) as quantity_sold,
                SUM(si.quantity * si.unit_price) as total_sales_amount,
                SUM(si.quantity * p.cost_price) as total_cost
            FROM sale_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.product_id = p.id
            WHERE s.sale_date >= '$startDate'
            AND s.sale_date <= '$endDate'
            AND s.status = 'completed'
            GROUP BY si.product_id, p.product_name, p.cost_price
        ")->fetchAll();

        $grossProfit = 0;
        $totalCost = 0;
        foreach ($productSalesData as $item) {
            $grossProfit += ($item['total_sales_amount'] - $item['total_cost']);
            $totalCost += $item['total_cost'];
        }

        $expenseData = $this->db->query("
            SELECT COALESCE(SUM(amount), 0) as total_expenses
            FROM expenses 
            WHERE expense_date >= '$startDate'
            AND expense_date <= '$endDate'
        ")->fetch();

        $totalSales = floatval($salesData['total_sales'] ?? 0);
        $totalExpenses = floatval($expenseData['total_expenses'] ?? 0);
        $netProfit = $grossProfit - $totalExpenses;

        $this->view('reports/profit', [
            'year' => $year,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalCost' => $totalCost,
            'grossProfit' => $grossProfit,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'productSales' => $productSalesData
        ]);
    }
}
