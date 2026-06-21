-- Fix customers table - add customer_type column if not exists
ALTER TABLE `customers` ADD COLUMN `customer_type` varchar(50) DEFAULT 'Retailer' AFTER `phone`;

-- Update existing customers
UPDATE `customers` SET `customer_type` = 'Local' WHERE `phone` = 'WALKIN';
