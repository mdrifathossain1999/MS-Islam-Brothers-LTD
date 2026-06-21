-- =====================================================
-- INVOICE MANAGEMENT SYSTEM DATABASE
-- Sumon Enterprise Ltd - Invoice Management
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- =====================================================
-- INVOICES TABLE - Main Invoice Records
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL UNIQUE,
  `invoice_prefix` varchar(20) DEFAULT 'INV',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `sales_id` int(11) DEFAULT NULL COMMENT 'Link to original sale',
  `user_id` int(11) NOT NULL,
  `cashier_name` varchar(100) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('percent','fixed') DEFAULT 'percent',
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_type` enum('percent','fixed') DEFAULT 'percent',
  `tax_percent` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) DEFAULT 0.00,
  `due_amount` decimal(12,2) DEFAULT 0.00,
  `change_amount` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid','overdue') DEFAULT 'unpaid',
  `payment_method` enum('cash','card','cheque','mobile','bank_transfer','multiple') DEFAULT 'cash',
  `mobile_type` varchar(20) DEFAULT NULL COMMENT 'bkash,nagad,rocket etc',
  `transaction_id` varchar(100) DEFAULT NULL,
  `invoice_type` enum('regular','proforma','estimate','return','credit_note') DEFAULT 'regular',
  `status` enum('draft','sent','viewed','completed','cancelled','void') DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `footer_note` text DEFAULT NULL,
  `is_recurring` enum('yes','no') DEFAULT 'no',
  `recurring_interval` varchar(20) DEFAULT NULL COMMENT 'weekly,monthly etc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  KEY `invoice_date` (`invoice_date`),
  KEY `due_date` (`due_date`),
  KEY `payment_status` (`payment_status`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE ITEMS TABLE - Line Items
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `item_name` varchar(200) NOT NULL,
  `item_description` text DEFAULT NULL,
  `hsn_code` varchar(20) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(20) DEFAULT 'piece',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_percent` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE PAYMENTS TABLE - Payment History
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_time` time DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','cheque','mobile','bank_transfer','adjustment') NOT NULL,
  `mobile_type` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `cheque_number` varchar(50) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `is_deleted` enum('yes','no') DEFAULT 'no',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `payment_date` (`payment_date`),
  KEY `payment_method` (`payment_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE ADJUSTMENTS TABLE - Discount/Adjustment
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `adjustment_type` enum('discount','refund','extra_charge','settlement') NOT NULL,
  `adjustment_reason` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `adjustment_date` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE TEMPLATES TABLE - Custom Print Templates
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(100) NOT NULL,
  `template_type` enum('thermal','a4','a5','pos','custom') DEFAULT 'a4',
  `is_default` enum('yes','no') DEFAULT 'no',
  `header_text` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `show_logo` enum('yes','no') DEFAULT 'yes',
  `show_barcode` enum('yes','no') DEFAULT 'yes',
  `show_qr_code` enum('yes','no') DEFAULT 'no',
  `show_terms` enum('yes','no') DEFAULT 'yes',
  `terms_content` text DEFAULT NULL,
  `show_customer_signature` enum('yes','no') DEFAULT 'no',
  `show_cashier_signature` enum('yes','no') DEFAULT 'no',
  `color_scheme` varchar(20) DEFAULT '#667eea',
  `font_size` enum('small','medium','large') DEFAULT 'medium',
  `is_active` enum('yes','no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE NUMBERING TABLE - Auto Increment Control
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_numbering` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) NOT NULL DEFAULT 'INV',
  `starting_number` int(11) NOT NULL DEFAULT 1,
  `current_number` int(11) NOT NULL DEFAULT 1,
  `padding` int(11) NOT NULL DEFAULT 4 COMMENT 'Zero padding count',
  `fiscal_year` varchar(10) DEFAULT NULL,
  `is_active` enum('yes','no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `prefix` (`prefix`,`fiscal_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- DEFAULT TEMPLATE INSERT
-- =====================================================

INSERT INTO `invoice_templates` (
  `template_name`, `template_type`, `is_default`, `header_text`, `footer_text`, 
  `show_logo`, `show_barcode`, `show_qr_code`, `show_terms`, `terms_content`,
  `show_customer_signature`, `show_cashier_signature`, `color_scheme`, `font_size`, `is_active`
) VALUES 
('Default Thermal', 'thermal', 'yes', 'Sumon Enterprise Ltd\n123 Business Street\nContact: 01889-933541', 'Thank you for shopping with us!', 'yes', 'yes', 'no', 'yes', 'Goods once sold cannot be returned.', 'yes', 'yes', '#667eea', 'small', 'yes'),
('Default A4', 'a4', 'no', 'Sumon Enterprise Ltd\nYour Trusted Business Partner\nAddress: As per records | Phone: 01889-933541', 'Payment is due within 30 days. Thank you for your business!', 'yes', 'yes', 'yes', 'yes', '1. Goods can be exchanged within 7 days.\n2. Original receipt required.\n3. Damage due to misuse not covered.', 'yes', 'yes', '#2c3e50', 'medium', 'yes');

-- =====================================================
-- DEFAULT INVOICE NUMBERING INSERT
-- =====================================================

INSERT INTO `invoice_numbering` (
  `prefix`, `starting_number`, `current_number`, `padding`, `fiscal_year`, `is_active`
) VALUES 
('INV', 1, 1, 4, '2026', 'yes'),
('RET', 1, 1, 4, '2026', 'yes');

-- =====================================================
-- INVOICE REMINDERS TABLE - Payment Reminders
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `reminder_type` enum('due_soon','overdue','first','second','final') DEFAULT 'due_soon',
  `reminder_date` date NOT NULL,
  `sent_via` enum('sms','email','whatsapp','none') DEFAULT 'none',
  `sent_at` timestamp NULL DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_sent` enum('yes','no') DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `reminder_date` (`reminder_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- INVOICE ACTIVITY LOG TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` enum('created','updated','viewed','sent','paid','partial_paid','cancelled','voided','reminder_sent','printed','emailed') NOT NULL,
  `description` text DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `user_id` (`user_id`),
  KEY `activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- CUSTOMER INVOICE SUMMARY (For fast loading)
-- =====================================================

CREATE TABLE IF NOT EXISTS `customer_invoice_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `total_invoices` int(11) DEFAULT 0,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `total_paid` decimal(12,2) DEFAULT 0.00,
  `total_due` decimal(12,2) DEFAULT 0.00,
  `paid_invoices` int(11) DEFAULT 0,
  `partial_invoices` int(11) DEFAULT 0,
  `unpaid_invoices` int(11) DEFAULT 0,
  `overdue_invoices` int(11) DEFAULT 0,
  `last_invoice_date` date DEFAULT NULL,
  `last_payment_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- QUOTATIONS TABLE - Proforma/Estimates
-- =====================================================

CREATE TABLE IF NOT EXISTS `quotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(50) NOT NULL UNIQUE,
  `quotation_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','sent','accepted','rejected','expired','converted') DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `converted_to_invoice_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `quotation_number` (`quotation_number`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- QUOTATION ITEMS TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS `quotation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `item_name` varchar(200) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(20) DEFAULT 'piece',
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;