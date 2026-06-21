<?php
session_start();

// Auto-detect BASE_URL for online hosting
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
$baseDir = str_replace('/app/config', '', $baseDir);
define('BASE_URL', rtrim($protocol . $host . $baseDir, '/'));

// Database - use environment variables or defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'digital_ledger_solutions');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('SITE_NAME', 'Digital Ledger Solutions V2');
define('COMPANY_NAME', 'Digital Ledger Solutions');
define('COMPANY_PHONE', '+88 01889933520');
define('COMPANY_EMAIL', 'mdrifathossainpersonal@gmail.com');
define('COMPANY_FACEBOOK', 'https://www.facebook.com/DigitalLedgerSolutions');
define('COMPANY_ADDRESS', 'Setabgnaj Bus Station, Bochaganj, Dinajpur, Bangladesh, 5216');
define('COMPANY_LOGO', 'uploads/logos/company_logo.jpeg');

define('DEFAULT_CURRENCY', '৳');
define('DATE_FORMAT', 'Y-m-d');
define('TIME_FORMAT', 'H:i:s');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

define('LOW_STOCK_THRESHOLD', 10);
define('TAX_RATE', 0);
define('SHOW_BARCODE', true);

define('FOOTER_DEVELOP_BY', 'Digital Ledger Solutions');
define('FOOTER_DEVELOP_BY_LINK', 'https://www.facebook.com/DigitalLedgerSolutions');
define('FOOTER_DESIGN_BY', 'Md Rifat Hossain');
define('FOOTER_DESIGN_BY_LINK', 'https://www.facebook.com/mdrifathossain1999');
define('FOOTER_WHATSAPP', '+8801889933520');
define('FOOTER_COPYRIGHT', 'All rights reserved.');
define('FOOTER_CUSTOM_TEXT', 'Setabganj Business Solutions Ltd');

define('ADMIN_ROLE', 'admin');
define('CASHIER_ROLE', 'cashier');

if (getenv('APP_ENV') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
