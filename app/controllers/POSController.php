<?php
class POSController extends Controller {
    public function index() {
        $this->requireLogin();

        $productModel = $this->model('Product');
        $customerModel = $this->model('Customer');
        $customerTypeModel = $this->model('CustomerType');

        $walkinCustomer = $customerModel->getWalkinCustomer();

        $data = [
            'products' => $productModel->getActive(),
            'customers' => $customerModel->getActive(),
            'categories' => $productModel->getCategories(),
            'customer_types' => $customerTypeModel->getActive(),
            'walkin_customer' => $walkinCustomer
        ];

        $this->view('pos/index', $data);
    }

    public function holdSale() {
        header('Content-Type: application/json');
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['items'])) {
                echo json_encode(['success' => false, 'message' => 'No items to hold']);
                exit();
            }

            try {
                $heldSaleModel = $this->model('HeldSale');
                
                $holdName = !empty($data['hold_name']) ? $data['hold_name'] : 'Hold ' . date('H:i:s');
                
                $saleData = [
                    'hold_name' => $holdName,
                    'customer_id' => !empty($data['customer_id']) ? intval($data['customer_id']) : null,
                    'user_id' => $_SESSION['user_id'],
                    'items_json' => json_encode($data['items']),
                    'subtotal' => floatval($data['subtotal']),
                    'vat_percent' => floatval($data['vat_percent'] ?? 0),
                    'vat_amount' => floatval($data['vat_amount'] ?? 0),
                    'discount' => floatval($data['discount'] ?? 0),
                    'total' => floatval($data['total']),
                    'sale_date' => $data['sale_date'] ?? date('Y-m-d')
                ];

                $holdId = $heldSaleModel->create($saleData);

                $this->logActivity('hold', 'sale', 'Sale held: ' . $holdName, 'held_sale', $holdId);

                echo json_encode([
                    'success' => true,
                    'message' => 'Sale held successfully!',
                    'hold_id' => intval($holdId)
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit();
    }

    public function getHeldSales() {
        header('Content-Type: application/json');
        $this->requireLogin();

        try {
            $heldSaleModel = $this->model('HeldSale');
            $heldSales = $heldSaleModel->getAll();
            
            foreach ($heldSales as &$sale) {
                $sale['items'] = json_decode($sale['items_json'], true);
                $sale['item_count'] = count($sale['items']);
            }
            
            echo json_encode([
                'success' => true,
                'held_sales' => $heldSales,
                'count' => count($heldSales)
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function resumeHeldSale($id = null) {
        header('Content-Type: application/json');
        $this->requireLogin();

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Hold ID required']);
            exit();
        }

        try {
            $heldSaleModel = $this->model('HeldSale');
            $heldSale = $heldSaleModel->getById($id);
            
            if (!$heldSale) {
                echo json_encode(['success' => false, 'message' => 'Held sale not found']);
                exit();
            }

            $items = json_decode($heldSale['items_json'], true);
            
            echo json_encode([
                'success' => true,
                'held_sale' => [
                    'id' => $heldSale['id'],
                    'hold_name' => $heldSale['hold_name'],
                    'customer_id' => $heldSale['customer_id'],
                    'items' => $items,
                    'subtotal' => floatval($heldSale['subtotal']),
                    'vat_percent' => floatval($heldSale['vat_percent']),
                    'vat_amount' => floatval($heldSale['vat_amount']),
                    'discount' => floatval($heldSale['discount']),
                    'total' => floatval($heldSale['total']),
                    'sale_date' => $heldSale['sale_date']
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function deleteHeldSale($id = null) {
        header('Content-Type: application/json');
        $this->requireLogin();

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Hold ID required']);
            exit();
        }

        try {
            $heldSaleModel = $this->model('HeldSale');
            $heldSale = $heldSaleModel->getById($id);
            
            if (!$heldSale) {
                echo json_encode(['success' => false, 'message' => 'Held sale not found']);
                exit();
            }

            $heldSaleModel->delete($id);
            
            $this->logActivity('delete', 'held_sale', 'Deleted held sale: ' . $heldSale['hold_name'], 'held_sale', $id);

            echo json_encode([
                'success' => true,
                'message' => 'Held sale deleted successfully'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit();
    }

    public function processSale() {
        header('Content-Type: application/json');
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Debug
            error_log('processSale received: ' . json_encode($data));
            
            if (empty($data['items'])) {
                echo json_encode(['success' => false, 'message' => 'No items in cart']);
                exit();
            }

            try {
                $saleModel = $this->model('Sale');
                $saleItemModel = $this->model('SaleItem');
                $productModel = $this->model('Product');
                $customerModel = $this->model('Customer');

                $invoiceNumber = $saleModel->generateInvoiceNumber();
                $subtotal = floatval($data['subtotal'] ?? 0);
                $vatAmount = floatval($data['vat_amount'] ?? 0);
                $discount = floatval($data['discount_amount'] ?? $data['discount'] ?? 0);
                $shipping = floatval($data['shipping'] ?? 0);
                $total = floatval($data['grand_total'] ?? $data['total'] ?? ($subtotal + $vatAmount - $discount + $shipping));
                $paid = floatval($data['paid_amount'] ?? $data['receive_amount'] ?? 0);
                $change = $paid > $total ? $paid - $total : 0;
                $customerId = !empty($data['customer_id']) ? intval($data['customer_id']) : null;
                $mobileType = !empty($data['mobile_type']) ? $data['mobile_type'] : null;
                $paymentMethod = $data['payment_method'] ?? 'cash';
                
                // Debug
                error_log("Total: $total, Paid: $paid, Change: $change, Method: $paymentMethod");
                
                $saleData = [
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $customerId,
                    'user_id' => $_SESSION['user_id'],
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'tax_amount' => $vatAmount,
                    'total_amount' => $total,
                    'payment_method' => $paymentMethod,
                    'mobile_type' => $mobileType,
                    'paid_amount' => $paid,
                    'change_amount' => $change,
                    'sale_date' => $data['sale_date'] ?? date('Y-m-d'),
                    'sale_time' => date('H:i:s')
                ];

                $saleId = $saleModel->create($saleData);

                foreach ($data['items'] as $item) {
                    $saleItemModel->create([
                        'sale_id' => $saleId,
                        'product_id' => $item['product_id'] ?? $item['id'] ?? null,
                        'barcode' => $item['barcode'] ?? null,
                        'product_name' => $item['product_name'] ?? $item['name'] ?? 'Unknown Product',
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['sell_price'] ?? $item['price'] ?? 0,
                        'total_price' => $item['quantity'] * ($item['sell_price'] ?? $item['price'] ?? 0)
                    ]);

                    $productModel->updateStock($item['product_id'] ?? $item['id'] ?? 0, $item['quantity'], 'remove', $_SESSION['user_id']);
                }

                if ($customerId) {
                    $customerModel->updatePurchaseStats($customerId, $total);
                    
                    // AUTO CREDIT DETECTION: If paid < total, automatically treat as credit sale
                    $dueAmount = $total - $paid;
                    
                    if ($dueAmount > 0) {
                        // Add due to customer's total_amount
                        $customerModel->addToTotal($customerId, $dueAmount);
                    }
                }

                $this->logActivity('sale', 'sale', 'Sale completed: ' . $invoiceNumber . ' - Total: ' . DEFAULT_CURRENCY . number_format($total, 2), 'sale', $saleId);

                echo json_encode([
                    'success' => true,
                    'invoice_number' => $invoiceNumber,
                    'sale_id' => intval($saleId),
                    'change' => floatval($change),
                    'total' => floatval($total),
                    'paid_amount' => floatval($paid),
                    'is_credit' => ($total > $paid),
                    'due_amount' => floatval($total - $paid)
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit();
        }

        $this->redirect('pos/index');
    }
}
