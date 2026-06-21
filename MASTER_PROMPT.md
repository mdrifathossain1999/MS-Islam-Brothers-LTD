# Sumon Enterprise V2 — Complete System Master Prompt

## Overview

PHP MySQL-based POS & Business Management System for "Digital Ledger Solutions" (Setabganj, Dinajpur, Bangladesh). Custom MVC framework. Dark/light theme. Bengali/English i18n. Role-based auth (admin/cashier).

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x, Custom MVC |
| Database | MySQL 5.7+ (InnoDB, utf8mb4_general_ci) |
| Frontend | HTML5, CSS3, Vanilla JS |
| CSS Framework | Bootstrap 5.3.2 (limited — mostly custom CSS variables) |
| Icons | Bootstrap Icons 1.11, Font Awesome 6.5.1 |
| Charts | ApexCharts |
| Fonts | Inter, Noto Sans Bengali |
| Build | None (pure PHP) |
| Frontend (separate) | React + Vite + Tailwind in `/frontend/` (not integrated) |
| URL Rewriting | Apache mod_rewrite → `index.php?url=$1` |

---

## Project Structure

```
/
├── index.php                     # Entry point + all route definitions
├── .htaccess                     # Apache URL rewriting
├── app/
│   ├── config/config.php         # Constants: DB, company info, roles
│   ├── core/
│   │   ├── Database.php          # PDO wrapper (FETCH_ASSOC, utf8mb4)
│   │   └── Controller.php        # Base controller: model(), view(), redirect(), auth helpers
│   ├── routes/router.php         # Custom Router: GET/POST, {param} regex matching
│   ├── controllers/              # 15 controllers
│   ├── models/                   # 15 models
│   └── views/                    # ~67 view files across 16 subdirectories
├── public/
│   └── css/
│       ├── style.css             # 962 lines — CSS variables, dark mode, badges, tables
│       ├── header-menu.css       # 67 lines — User dropdown, nav dark mode
│       ├── page-header.css       # Page header component styles
│       └── admin.css             # 271 lines — Admin panel styles (cards, tables, badges)
├── backups/                      # SQL dump: backup_2026-04-12_201648.sql
├── uploads/logos/                # Company logo storage
└── frontend/                     # React+Vite app (separate, not integrated)
```

---

## Database Schema (21 tables, 192 columns)

### Core Tables
| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `users` | id, username, password(bcrypt), full_name, email, role(admin/cashier), status | Authentication & staff |
| `products` | id, barcode(unique), product_name, category, cost_price, sell_price, stock_quantity, low_stock_threshold, unit, status | Inventory items |
| `categories` | id, category_name(unique), size_type, size_options | Product categories |
| `units` | id, unit_name(unique) | Units of measurement (pcs, kg, etc.) |
| `size_types` | id, size_type_name(unique), size_type_options | Product size variants |
| `category_unit_mapping` | id, category(unique), unit_name | Maps categories to units |
| `customers` | id, customer_name, phone(unique), email, address, total_purchases, total_amount, paid_amount, status | Customer management |
| `customer_types` | id, type_name(unique), discount_percent, display_order, is_active | Customer classification |

### Sales & Invoicing
| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `sales` | id, invoice_number(unique), customer_id(FK), user_id(FK), subtotal, discount, tax, total, paid_amount, change, payment_method, sale_date, status | Sales transactions |
| `sale_items` | id, sale_id(FK), product_id(FK), barcode, product_name, quantity, unit_price, total_price | Line items per sale |
| `payments` | id, sale_id(FK CASCADE), payment_method(cash/card/mobile), amount, transaction_id | Payment records |
| `invoices` | id, invoice_number, customer_id, sales_id, subtotal, total, paid_amount, due_amount, payment_status(unpaid/partial/paid/overdue), status(draft/sent/completed/cancelled) | Invoice system |
| `invoice_items` | id, invoice_id(FK), product_id, barcode, item_name, quantity, unit_price, total_price | Invoice line items |
| `invoice_payments` | id, invoice_id(FK), amount, payment_method(cash/card/cheque/mobile/bank_transfer), transaction_id, notes | Invoice payments |
| `invoice_templates` | id, template_name, template_type(thermal/a4/a5/pos/custom), show_logo, show_barcode, show_terms, color_scheme, font_size, custom_html | Invoice design templates |
| `invoice_numbering` | id, prefix, starting_number, current_number, padding, fiscal_year | Auto-invoice numbering |
| `invoice_activities` | id, invoice_id, user_id, activity_type(enum), description | Invoice audit log |

### HRM
| Table | Key Columns | Purpose |
|-------|-------------|---------|
| (HRM tables exist in controller but no DDL found in backup — likely departments, employees, attendance, holidays) |

### Audit & Settings
| Table | Key Columns | Purpose |
|-------|-------------|---------|
| `activity_logs` | id, user_id, action, module, description, reference_type, reference_id, ip_address | Full activity audit trail |
| `stock_logs` | id, product_id, previous_quantity, new_quantity, change_type(add/remove/adjust/sale), reference_type, user_id | Stock movement tracking |
| `summary_cards` | id, card_key(unique), card_title, card_type(users/products/customers/low_stock/custom), icon_class, color_class, custom_query, display_order, is_active | Customizable dashboard cards |
| `payment_history` | id, customer_id, amount, payment_method, transaction_id, user_id | Customer payment history |

---

## Authentication & Roles

- **Session-based auth** with bcrypt password hashing
- **2 roles**: `admin` (full access) and `cashier` (limited POS/sales access)
- `requireLogin()` / `requireAdmin()` guards in base Controller
- Login page at `/auth/login`, logout at `/auth/logout`
- Profile edit at `/auth/profile`
- `$_SESSION['user_id']`, `$_SESSION['role']`, `$_SESSION['username']`, `$_SESSION['full_name']`

---

## All Routes & Features (100+ routes)

### Auth (5 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `/` | GET | Redirects to `/auth/login` |
| `auth/login` | GET/POST | Login form & authentication |
| `auth/logout` | GET | Destroy session, redirect to login |
| `auth/profile` | GET/POST | View & update user profile |

### Dashboard (2 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `dashboard` | GET | Main dashboard: summary cards (total products, customers, sales, due), revenue chart (area, 7/14/28 day toggle), donut chart (profit/loss/monthly sales/due), table of recent 10 sales, low stock alerts |
| `dashboard/chartData` | GET | JSON endpoint for chart data |

### Products (8 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `product/index` | GET | Product list with category filter, search, table display |
| `product/create` | GET/POST | Add new product (barcode, name, category, prices, stock) |
| `product/edit/{id}` | GET/POST | Edit product form |
| `product/delete/{id}` | GET | Delete product |
| `product/search` | GET | JSON search by name/barcode for POS autocomplete |
| `product/barcode/{barcode}` | GET | Lookup product by barcode |

### POS (9 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `pos` | GET | POS interface: product grid, cart, customer select, payment |
| `pos/index` | GET | Alias for pos |
| `pos/createSale` | POST | Process sale (legacy route name) |
| `pos/processSale` | POST | Process sale: create sale record, sale_items, payments, update stock, generate invoice |
| `pos/holdSale` | POST | Hold/save current cart for later |
| `pos/getHeldSales` | GET | JSON: retrieve all held sales |
| `pos/resumeHeldSale/{id}` | GET | JSON: load a held sale into cart |
| `pos/deleteHeldSale/{id}` | GET | Delete a held sale |

### Purchases (5 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `purchase/index` | GET | Purchase list (all purchase records) |
| `purchase/create` | GET/POST | New purchase: add items, update stock, record cost |
| `purchase/return` | GET/POST | Purchase return processing |

### Sale Returns (2 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `sale/return` | GET/POST | Process sale return (reverse stock, refund) |

### Expenses (3 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `expense/index` | GET | Expense list with search/filter |
| `expense/create` | GET/POST | Add new expense |

### Accounting (11 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `account/index` | GET | Accounting dashboard: cash flow overview, transactions, accounts list |
| `account/create` | GET/POST | Create new account |
| `account/edit/{id}` | GET/POST | Edit account |
| `account/delete/{id}` | GET | Delete account |
| `account/deposit` | GET/POST | Deposit money to account |
| `account/withdraw` | GET/POST | Withdraw money from account |

### Customers (16 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `customer/index` | GET | Customer list with search |
| `customer/create` | GET/POST | Add customer form |
| `customer/edit/{id}` | GET/POST | Edit customer |
| `customer/delete/{id}` | GET | Delete customer |
| `customer/search` | GET | JSON search for POS |
| `customer/quickAdd` | POST | Quick customer creation (inline from POS) |
| `customer/history/{id}` | GET | Customer purchase history page |
| `customer/historyData/{id}` | GET | JSON: purchase history data |
| `customer/profile/{id}` | GET | Customer profile (details, stats, history) |
| `customer/receivePayment/{id}` | GET/POST | Receive payment from customer |
| `customer/payment/{id}` | POST | Record payment |

### Suppliers (6 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `supplier/index` | GET | Supplier list with search |
| `supplier/create` | GET/POST | Add supplier |
| `supplier/edit/{id}` | GET/POST | Edit supplier |
| `supplier/delete/{id}` | GET | Delete supplier |

### Invoices (22 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `invoice/index` | GET | All invoices list |
| `invoice/today` | GET | Today's invoices |
| `invoice/dateRange` | GET | Filter invoices by date range |
| `invoice/view/{id}` | GET | Invoice detail view |
| `invoice/print/{id}` | GET | Printable invoice view |
| `invoice/search` | GET | Search invoices |
| `invoice/due` | GET | Due invoices list |
| `invoice/unpaid` | GET | Unpaid invoices list |
| `invoice/templates` | GET | Invoice template management page |
| `invoice/saveTemplate` | POST | Create new template |
| `invoice/editTemplate/{id}` | GET/POST | Edit template |
| `invoice/deleteTemplate/{id}` | GET | Delete template |
| `invoice/setDefaultTemplate/{id}` | GET | Set as default |
| `invoice/toggleTemplateStatus/{id}` | GET | Enable/disable template |
| `invoice/numbering` | GET | Invoice numbering configuration |
| `invoice/saveNumbering` | POST | Save numbering format |
| `invoice/deleteNumbering/{id}` | GET | Delete numbering config |
| `invoice/createFromSales` | GET | Create invoices from existing sales |

### Reports (7 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `report/index` | GET | Reports landing/index |
| `report/daily` | GET | Daily sales report (with optional format param) |
| `report/monthly` | GET | Monthly sales report |
| `report/productSales` | GET | Per-product sales report |
| `report/stock` | GET | Stock/inventory report |
| `report/customRange` | GET | Custom date range report |
| `report/profit` | GET | Profit calculation report |

### HRM (15 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `hrm/department` | GET/POST | Department list & create |
| `hrm/editDepartment/{id}` | GET/POST | Edit department |
| `hrm/deleteDepartment/{id}` | GET | Delete department |
| `hrm/employee` | GET/POST | Employee list & create |
| `hrm/editEmployee/{id}` | GET/POST | Edit employee |
| `hrm/deleteEmployee/{id}` | GET | Delete employee |
| `hrm/attendance` | GET/POST | Attendance management |
| `hrm/holiday` | GET/POST | Holiday management |

### Admin (52 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `admin` / `admin/index` | GET | Admin dashboard with summary stats |
| `admin/settings` | GET | System settings (company info, logo, currency, tax) |
| `admin/updateSettings` | POST | Save settings |
| `admin/users` | GET | User management list |
| `admin/createUser` | GET/POST | Create new user |
| `admin/editUser/{id}` | GET/POST | Edit user |
| `admin/deleteUser/{id}` | GET | Delete user |
| `admin/categories` | GET | Category management |
| `admin/addCategory` | POST | Create category |
| `admin/editCategory/{id}` | GET/POST | Edit category |
| `admin/updateCategory` | POST | Update category |
| `admin/deleteCategory/{id}` | GET/POST | Delete category |
| `admin/addSizeType` | POST | Add size type |
| `admin/deleteSizeType` | POST | Delete size type |
| `admin/units` | GET | Unit management |
| `admin/addUnit` | POST | Add unit |
| `admin/updateUnit` | POST | Update unit |
| `admin/deleteUnit/{id}` | GET | Delete unit |
| `admin/database` | GET | Database management (backup/restore UI) |
| `admin/uploads` | GET | File upload manager |
| `admin/deleteUpload` | POST | Delete upload |
| `admin/backup` | GET | Create database backup |
| `admin/downloadBackup` | GET | Download backup file |
| `admin/exportCustomers` | GET | Export customers CSV |
| `admin/summaryCards` | GET | Summary card management (CRUD + toggle) |
| `admin/createSummaryCard` | POST | Create card |
| `admin/editSummaryCard/{id}` | GET/POST | Edit card |
| `admin/deleteSummaryCard/{id}` | GET | Delete card |
| `admin/toggleSummaryCard/{id}` | GET | Enable/disable card |
| `admin/productImportExport` | GET | Bulk product import/export UI |
| `admin/exportProducts` | GET | Export products as CSV |
| `admin/importProducts` | POST | Import products from CSV |
| `admin/downloadSample` | POST | Download sample CSV |
| `admin/customerTypes` | GET | Customer type management |
| `admin/createCustomerType` | POST | Create type |
| `admin/editCustomerType/{id}` | GET/POST | Edit type |
| `admin/deleteCustomerType/{id}` | GET | Delete type |
| `admin/toggleCustomerType/{id}` | GET | Enable/disable type |
| `admin/activityLogs` | GET | Activity log viewer (with filters) |
| `admin/clearActivityLogs` | POST | Clear old logs |
| `admin/categoryUnit` | GET | Category-to-unit mapping |
| `admin/saveCategoryUnit` | POST | Save mapping |
| `admin/deleteCategoryUnit` | POST | Delete mapping |
| `admin/createCategoryUnitTable` | GET | Create mapping DB table |

### Settings (2 routes)
| Route | Method | Description |
|-------|--------|-------------|
| `settings/rolePermission` | GET/POST | Role-based permission configuration |

---

## Layout & UI System

### Layout (`app/views/layouts/main.php`, 910 lines)
- HTML lang="bn" (Bengali)
- Fixed top-bar: 60px height, offset by sidebar-width (250px)
- Sidebar: collapsible, nav menu with submenu sections
- Content area: flex layout
- Footer: copyright with dynamic year
- Modal for notifications
- Loads: Bootstrap 5.3.2, Bootstrap Icons, Font Awesome 6.5.1, ApexCharts, Google Fonts (Inter + Noto Sans Bengali)

### CSS Theme System
- **Light mode**: CSS variables in `:root` (`style.css`)
- **Dark mode**: `body.dark-mode` class toggles CSS variables (`style.css`, `header-menu.css`, `admin.css`)
- Theme saved to `localStorage('darkMode')`, also via `$_COOKIE['darkMode']`
- `initTheme()` at DOMContentLoaded applies saved theme
- ApexCharts: `chartColors()` function returns dark/light palettes, `MutationObserver` watches `body` class changes → `updateChartsTheme()` redeploys colors

### Dark Mode Coverage
| Area | File | Status |
|------|------|--------|
| Global variables | `style.css` | ✅ Full — `body.dark-mode` overrides all `--text-*`, `--bg-*`, `--border-*` |
| Badges | `style.css` lines 915-962 | ✅ Full — active/danger/warning/info/overdue/unpaid/partial + stock statuses |
| Tables | `style.css` | ✅ Table rows, headers, hover states |
| User menu | `header-menu.css` | ✅ Dropdown, nav items, borders |
| Admin panel | `admin.css` | ✅ Cards, tables, badges, buttons, icon buttons |
| Admin pages | `index.php`, `users.php` | ✅ Local `:root` dark overrides |
| Dashboard | `modern.php` | ✅ Summary cards, charts, recent sales table |
| Login page | `login.php` | ✅ Logo URL handling |

### JavaScript Features
- **Sidebar toggle**: `toggleSidebar()`, `closeMobileSidebar()`, `initSidebar()` — responsive, auto-close on mobile nav click
- **Theme toggle**: `toggleTheme()` — class toggle + localStorage
- **Bengali translation**: `translatePage()` — dictionary-based DOM text replacement, `toBengaliNum()` for numbers
- **Notifications**: `showNotification(title, message, type)` — floating toast
- **Charts**: ApexCharts with `chartColors`, `MutationObserver`, `updateChartsTheme`
- **Language**: localStorage `language` (en/bn), Bengali dictionary with `setTimeout` retry pattern

---

## Models (15 models)

| Model | Key Methods |
|-------|-------------|
| `User` | login(bcrypt), getAll, getById, create, update, delete, getRecent |
| `Product` | getAll, getActive, getById, getByBarcode, search, create, update, updateStock, delete, getLowStock, getCategories, getUnits, addCategory, addUnit |
| `Customer` | getAll, getActive, getById, getByPhone, search, create, update, updatePurchaseStats, addPayment, getPaymentHistory, getDueAmount, delete, getTotalDue |
| `Sale` | getAll, getById, getByInvoice, getByDate, getByDateRange, getTodaySales, create, update, cancel, generateInvoiceNumber, getTodayTotal, getMonthlyTotal, getDailySalesData, getRecentSales |
| `SaleItem` | getBySaleId, create, createMultiple, delete, getProductSalesReport |
| `Payment` | getBySaleId, create, delete |
| `Invoice` | getAll, getById, getByCustomer, getByDateRange, getTodayInvoices, getDueInvoices, getUnpaidInvoices, create, update, updatePaymentStatus, addItem, getItems, addPayment, getPayments, getSummary, search, getTemplates, logActivity |
| `InvoiceTemplate` | getAll, getById, getActive, getDefault, create, update, delete, setDefault, toggleStatus |
| `HeldSale` | getAll, getById, create, update, delete, deleteAll, getCount |
| `StockLog` | create, getByProduct, getAll |
| `ActivityLog` | log, getAll, getTotalCount, getModules, getActions, getRecent, clearOld |
| `SummaryCard` | getAll, getActive, getById, create, update, delete, toggleStatus, getCardValue |
| `CustomerType` | getAll, getActive, getById, getByName, create, update, delete, toggleStatus |
| `SizeType` | getAll, create, delete, exists |
| `CategoryUnitMapping` | getAll, getByCategory, getUnitByCategory, create, update, delete, exists |

---

## View Files (67 files)

### Auth
- `auth/login.php` — Login form
- `auth/profile.php` — User profile edit

### Dashboard
- `dashboard/index.php` — Legacy dashboard
- `dashboard/modern.php` — Modern dashboard: ApexCharts (revenue area chart, profit donut), summary cards, recent sales table, low stock alerts, period selectors (7/14/28 days)

### Products
- `products/index.php` — Product table with category filter, search, status badges
- `products/create.php` — Product create form (barcode, name, category dropdown, cost/sell price, stock, unit, image upload)
- `products/edit.php` — Product edit form

### Purchases
- `purchase/index.php` — Purchase records list
- `purchase/create.php` — Purchase form with product line items
- `purchase/return.php` — Purchase return processing

### Sale Returns
- `sale/return.php` — Sale return form

### Expenses
- `expense/index.php` — Expense list with search/filter
- `expense/create.php` — Expense entry form

### Accounting
- `account/index.php` — Accounting overview: cash flow, transactions, accounts
- `account/create.php` — Create account form
- `account/edit.php` — Edit account form
- `account/deposit.php` — Deposit form
- `account/withdraw.php` — Withdrawal form

### HRM
- `hrm/department.php` — Department CRUD
- `hrm/department-edit.php` — Edit department
- `hrm/employee.php` — Employee CRUD
- `hrm/employee-edit.php` — Edit employee
- `hrm/attendance.php` — Attendance tracking
- `hrm/holiday.php` — Holiday management

### Suppliers
- `supplier/index.php` — Supplier list with search

### POS
- `pos/index.php` — Full POS interface: barcode/product search, cart, customer selection, held sales, payment processing

### Invoices
- `invoices/index.php` — Invoice list
- `invoices/today.php` — Today's invoices
- `invoices/date_range.php` — Date range filter
- `invoices/view.php`, `view_new.php` — Invoice details (legacy & new)
- `invoices/print.php`, `print_new.php` — Printable invoice (legacy & new)
- `invoices/due.php` — Due invoices
- `invoices/unpaid.php` — Unpaid invoices
- `invoices/templates.php` — Template management
- `invoices/edit_template.php` — Template editor
- `invoices/numbering.php` — Numbering config

### Customers
- `customers/index.php` — Customer list
- `customers/create.php` — Create customer
- `customers/edit.php` — Edit customer
- `customers/history.php` — Purchase history
- `customers/profile.php` — Customer profile
- `customers/receive_payment.php` — Receive payment

### Reports
- `reports/index.php` — Reports landing
- `reports/daily.php` — Daily report (table + chart)
- `reports/monthly.php` — Monthly report (table + chart)
- `reports/product_sales.php` — Per-product sales
- `reports/stock.php` — Stock/inventory report
- `reports/custom_range.php` — Custom date range
- `reports/profit.php` — Profit/loss report

### Admin
- `admin/index.php` — Admin dashboard (stats cards, activity)
- `admin/settings.php` — Company settings (name, logo, address, currency, tax)
- `admin/users.php` — User management table
- `admin/user-form.php` — User create/edit form
- `admin/categories.php` — Category cards with CRUD modal
- `admin/category-edit.php` — Edit category
- `admin/units.php` — Unit CRUD
- `admin/database.php` — Database manager (backup list + download)
- `admin/uploads.php` — File upload manager
- `admin/summary_cards.php` — Summary card CRUD with preview
- `admin/summary_card_form.php` — Card edit form
- `admin/customer_types.php` — Customer type management
- `admin/customer_type_form.php` — Customer type edit form
- `admin/product_import_export.php` — Bulk product CSV import/export UI
- `admin/activity_logs.php` — Activity log viewer with filters
- `admin/category_unit.php` — Category→Unit mapping table

### Settings
- `settings/rolePermission.php` — Role-based permission UI

---

## Key Config Constants (`config.php`)

```php
BASE_URL                 // Auto-detected from server
SITE_NAME = 'Digital Ledger Solutions'
COMPANY_NAME / PHONE / EMAIL / FACEBOOK / ADDRESS / LOGO
DEFAULT_CURRENCY = '৳'
LOW_STOCK_THRESHOLD = 10
TAX_RATE = 0
SHOW_BARCODE = true
ADMIN_ROLE = 'admin'
CASHIER_ROLE = 'cashier'
FOOTER_* // Developer/designer credits, WhatsApp, copyright
```

---

## Known Issues / Recently Fixed

- ✅ Categories page: SQL missing `c.id` → added `c.id` to SELECT + `?? ''` in view
- ✅ Categories page: `$category['product_count']` → `$category['count']` (column alias mismatch)
- ✅ ApexCharts dark flicker: `chartColors()` now also checks `localStorage` (not only `body.classList`)
- ✅ admin.css: Added `body.dark-mode` variable overrides + badge dark colors
- ✅ admin/index.php, users.php: Added `body.dark-mode` variable overrides
- ✅ summary_cards.php: `background: #fff` → `var(--admin-card, #fff)`
- ✅ settings.php: Hardcoded alert borders → CSS variables
- ✅ users.php: All `$user[...]` accesses now have `?? ''` null coalescing
- ⚠️ `frontend/` React app exists but not integrated with PHP backend

---

## Development Notes

- No npm/composer build step — pure PHP include-based MVC
- Apache mod_rewrite required (`.htaccess` routes all to `index.php`)
- Session-based auth with cookies; dark mode via localStorage + server-side cookie
- PHP 8.x with strict error reporting (`E_ALL`)
- MySQL utf8mb4 charset with `utf8mb4_unicode_ci` or `utf8mb4_general_ci`
- `skipRoutes` in index.php bypasses Router for `setup.php`, `create_db.php`
- View rendering: `ob_start()` → `ob_get_clean()` → `require` layout
- All model `__construct($db)` receives PDO connection from Controller
