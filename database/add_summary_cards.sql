-- Summary Cards Table for existing databases
-- Run this SQL to add summary cards functionality

CREATE TABLE IF NOT EXISTS summary_cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    card_key VARCHAR(50) UNIQUE NOT NULL,
    card_title VARCHAR(100) NOT NULL,
    card_type ENUM('users', 'products', 'customers', 'low_stock', 'custom') DEFAULT 'custom',
    icon_class VARCHAR(50) DEFAULT 'bi bi-collection',
    color_class VARCHAR(50) DEFAULT 'blue',
    custom_query TEXT,
    display_order INT DEFAULT 0,
    is_active ENUM('yes', 'no') DEFAULT 'yes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default summary cards
INSERT IGNORE INTO summary_cards (card_key, card_title, card_type, icon_class, color_class, display_order) VALUES
('total_users', 'Total Users', 'users', 'bi bi-people', 'purple', 1),
('total_products', 'Total Products', 'products', 'bi bi-box-seam', 'green', 2),
('total_customers', 'Total Customers', 'customers', 'bi bi-person-hearts', 'orange', 3),
('low_stock', 'Low Stock Items', 'low_stock', 'bi bi-exclamation-triangle', 'red', 4);
