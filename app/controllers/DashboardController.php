<?php
class DashboardController extends Controller {
    public function index() {
        $this->requireLogin();

        $productModel = $this->model('Product');
        $saleModel = $this->model('Sale');
        $customerModel = $this->model('Customer');
        $saleItemModel = $this->model('SaleItem');

        // Today's Sales Data
        $todaySales = $saleModel->getTodaySales();
        $todayTotal = 0;
        $todayPaid = 0;
        $todayProfit = 0;

        foreach ($todaySales as $sale) {
            $todayTotal += $sale['total_amount'];
            $todayPaid += $sale['paid_amount'];

            $items = $saleItemModel->getBySaleId($sale['id']);
            foreach ($items as $item) {
                $product = $productModel->getById($item['product_id']);
                if ($product) {
                    $profit = ($item['unit_price'] - $product['cost_price']) * $item['quantity'];
                    $todayProfit += $profit;
                }
            }
        }
        $todayDue = $todayTotal - $todayPaid;

        // This Month Data
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $monthlySales = $saleModel->getByDateRange($monthStart, $monthEnd);
        $thisMonthTotal = 0;
        $thisMonthPaid = 0;
        $thisMonthProfit = 0;
        $thisMonthCost = 0;

        foreach ($monthlySales as $sale) {
            $thisMonthTotal += $sale['total_amount'];
            $thisMonthPaid += $sale['paid_amount'];

            $items = $saleItemModel->getBySaleId($sale['id']);
            foreach ($items as $item) {
                $product = $productModel->getById($item['product_id']);
                if ($product) {
                    $itemCost = $product['cost_price'] * $item['quantity'];
                    $itemProfit = ($item['unit_price'] - $product['cost_price']) * $item['quantity'];
                    $thisMonthProfit += $itemProfit;
                    $thisMonthCost += $itemCost;
                }
            }
        }
        $thisMonthDue = $thisMonthTotal - $thisMonthPaid;
        $thisMonthLoss = $thisMonthCost - $thisMonthTotal;

        // Total Profit (All Time)
        $allSales = $saleModel->getAll();
        $totalProfitAll = 0;
        $totalCostAll = 0;
        $totalSalesAll = 0;

        foreach ($allSales as $sale) {
            $totalSalesAll += $sale['total_amount'];
            $items = $saleItemModel->getBySaleId($sale['id']);
            foreach ($items as $item) {
                $product = $productModel->getById($item['product_id']);
                if ($product) {
                    $totalCostAll += $product['cost_price'] * $item['quantity'];
                    $totalProfitAll += ($item['unit_price'] - $product['cost_price']) * $item['quantity'];
                }
            }
        }
        $totalLossAll = $totalCostAll - $totalSalesAll;

        // Hourly Data
        $hourlyData = $saleModel->getHourlySalesData();
        $hours = [];
        $hourly_sales = [];
        $hourly_due = [];
        
        for ($i = 8; $i <= 22; $i++) {
            $hours[] = sprintf('%02d:00', $i);
            $hourly_sales[] = 0;
            $hourly_due[] = 0;
        }

        foreach ($hourlyData as $data) {
            $hourIndex = intval($data['hour']) - 8;
            if ($hourIndex >= 0 && $hourIndex < 15) {
                $hourly_sales[$hourIndex] = floatval($data['total']);
                $hourly_due[$hourIndex] = floatval($data['due']);
            }
        }

        // Default: Daily Sales for Chart (Last 35 Days for flexibility)
        $dailySalesData = $saleModel->getDailySalesData(35);
        $dailyLabels = [];
        $dailySalesAmount = [];
        $dailyDueAmount = [];
        
        foreach ($dailySalesData as $day) {
            $dailyLabels[] = date('Y-m-d', strtotime($day['sale_date']));
            $dailySalesAmount[] = floatval($day['total']);
            $dailyDueAmount[] = floatval($day['total']) - floatval($day['paid']);
        }

        $recentSales = $saleModel->getRecentSales(10);

        $data = [
            'total_products' => $productModel->getTotalProducts(),
            'low_stock_products' => $productModel->getLowStock(),
            'today_sales' => $todayTotal,
            'today_profit' => $todayProfit,
            'today_due' => $todayDue,
            'today_transactions' => count($todaySales),
            'total_customers' => $customerModel->getTotalCustomers(),
            'total_customer_due' => $customerModel->getTotalDue(),
            'this_month_customer_due' => $customerModel->getThisMonthDue(),
            'this_month_sales' => $thisMonthTotal,
            'this_month_profit' => $thisMonthProfit,
            'this_month_due' => $thisMonthDue,
            'total_profit_all' => $totalProfitAll,
            'total_loss_all' => max(0, $totalLossAll),
            'daily_labels' => json_encode($dailyLabels),
            'daily_sales_amount' => json_encode($dailySalesAmount),
            'daily_due_amount' => json_encode($dailyDueAmount),
            'recent_sales' => $recentSales
        ];

        $this->view('dashboard/modern', $data);
    }

    public function chartData() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Unauthorized', 'labels' => [], 'sales' => [], 'due' => []]);
            exit;
        }

        $days = isset($_GET['days']) ? intval($_GET['days']) : 7;
        if ($days != 7 && $days != 28) {
            $days = 7;
        }

        $saleModel = $this->model('Sale');
        $dailySalesData = $saleModel->getDailySalesData($days);
        
        $labels = [];
        $salesAmount = [];
        $dueAmount = [];
        
        foreach ($dailySalesData as $day) {
            $labels[] = date('d M', strtotime($day['sale_date']));
            $salesAmount[] = floatval($day['total']);
            $dueAmount[] = floatval($day['due']);
        }

        echo json_encode([
            'labels' => $labels,
            'sales' => $salesAmount,
            'due' => $dueAmount,
            'days' => $days
        ]);
        exit;
    }
}
