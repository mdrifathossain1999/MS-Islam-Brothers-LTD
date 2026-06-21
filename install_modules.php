<?php
// Database Migration - Add new tables for Purchase, Expense, Accounting, HRM, Supplier

require_once 'app/config/config.php';

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

// Purchase Table
$db->exec("CREATE TABLE IF NOT EXISTS purchases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    purchase_date DATE NOT NULL,
    invoice_no VARCHAR(100) UNIQUE,
    supplier_id INT,
    total_amount DECIMAL(12,2) DEFAULT 0,
    paid_amount DECIMAL(12,2) DEFAULT 0,
    due_amount DECIMAL(12,2) DEFAULT 0,
    status ENUM('pending','received','returned','cancelled') DEFAULT 'pending',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Purchase Items Table
$db->exec("CREATE TABLE IF NOT EXISTS purchase_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    purchase_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Purchase Return Table
$db->exec("CREATE TABLE IF NOT EXISTS purchase_returns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    purchase_id INT,
    return_date DATE NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    reason TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Sale Return Table
$db->exec("CREATE TABLE IF NOT EXISTS sale_returns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sale_id INT,
    return_date DATE NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    reason TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Expense Categories Table
$db->exec("CREATE TABLE IF NOT EXISTS expense_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    is_active ENUM('yes','no') DEFAULT 'yes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Expense Table
$db->exec("CREATE TABLE IF NOT EXISTS expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    expense_date DATE NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description TEXT,
    reference_no VARCHAR(100),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Accounts Table
$db->exec("CREATE TABLE IF NOT EXISTS accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(100),
    account_type ENUM('cash','bank','mobile_banking') DEFAULT 'cash',
    opening_balance DECIMAL(12,2) DEFAULT 0,
    current_balance DECIMAL(12,2) DEFAULT 0,
    is_default ENUM('yes','no') DEFAULT 'no',
    is_active ENUM('yes','no') DEFAULT 'yes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Transactions Table (Deposit/Withdraw)
$db->exec("CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    transaction_date DATE NOT NULL,
    transaction_type ENUM('deposit','withdraw','transfer') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description TEXT,
    reference_no VARCHAR(100),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// HRM Tables
$db->exec("CREATE TABLE IF NOT EXISTS hrm_departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    is_active ENUM('yes','no') DEFAULT 'yes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS hrm_employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id VARCHAR(50) UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50),
    department_id INT,
    designation VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    salary DECIMAL(12,2) DEFAULT 0,
    join_date DATE,
    status ENUM('active','inactive','terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS hrm_attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present','absent','leave','late') DEFAULT 'present',
    check_in TIME,
    check_out TIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (employee_id, attendance_date)
)");

$db->exec("CREATE TABLE IF NOT EXISTS hrm_holidays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    holiday_name VARCHAR(100) NOT NULL,
    holiday_date DATE NOT NULL,
    description TEXT,
    is_recurring ENUM('yes','no') DEFAULT 'no',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Suppliers Table
$db->exec("CREATE TABLE IF NOT EXISTS suppliers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(100) NOT NULL,
    company_name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    balance DECIMAL(12,2) DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Role Permissions Table
$db->exec("CREATE TABLE IF NOT EXISTS role_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL,
    module VARCHAR(50) NOT NULL,
    can_view ENUM('yes','no') DEFAULT 'no',
    can_create ENUM('yes','no') DEFAULT 'no',
    can_edit ENUM('yes','no') DEFAULT 'no',
    can_delete ENUM('yes','no') DEFAULT 'no',
    UNIQUE KEY unique_permission (role_name, module)
)");

echo "All tables created successfully!";