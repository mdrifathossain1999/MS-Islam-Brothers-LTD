-- D1-compatible SQLite schema
-- Converted from MySQL

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS activity_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER DEFAULT NULL,
  action varchar(50) NOT NULL,
  module varchar(50) NOT NULL,
  description text DEFAULT NULL,
  reference_type varchar(50) DEFAULT NULL,
  reference_id INTEGER DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent varchar(255) DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_name varchar(100) NOT NULL,
  phone varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  address text DEFAULT NULL,
  total_purchases REAL DEFAULT 0.00,
  loyalty_points INTEGER DEFAULT 0,
  total_amount REAL DEFAULT 0.00,
  paid_amount REAL DEFAULT 0.00,
  status TEXT DEFAULT 'active',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customer_types (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  type_name varchar(50) NOT NULL,
  description varchar(255) DEFAULT NULL,
  discount_percent REAL DEFAULT 0.00,
  display_order INTEGER DEFAULT 0,
  is_active TEXT DEFAULT 'yes',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sale_id INTEGER NOT NULL,
  payment_method TEXT DEFAULT 'cash',
  amount REAL NOT NULL,
  transaction_id varchar(100) DEFAULT NULL,
  notes text DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  barcode varchar(50) DEFAULT NULL,
  product_name varchar(200) NOT NULL,
  category varchar(100) DEFAULT NULL,
  description text DEFAULT NULL,
  cost_price REAL DEFAULT 0.00,
  sell_price REAL NOT NULL,
  stock_quantity INTEGER DEFAULT 0,
  low_stock_threshold INTEGER DEFAULT 10,
  unit varchar(20) DEFAULT 'piece',
  image varchar(255) DEFAULT NULL,
  status TEXT DEFAULT 'active',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sales (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  invoice_number varchar(50) NOT NULL,
  customer_id INTEGER DEFAULT NULL,
  user_id INTEGER NOT NULL,
  subtotal REAL NOT NULL,
  discount_amount REAL DEFAULT 0.00,
  tax_amount REAL DEFAULT 0.00,
  total_amount REAL NOT NULL,
  payment_method TEXT DEFAULT 'cash',
  mobile_type varchar(20) DEFAULT NULL,
  paid_amount REAL NOT NULL,
  change_amount REAL DEFAULT 0.00,
  sale_date TEXT NOT NULL,
  sale_time TEXT NOT NULL,
  status TEXT DEFAULT 'completed',
  notes text DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sale_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  sale_id INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  barcode varchar(50) DEFAULT NULL,
  product_name varchar(200) NOT NULL,
  quantity INTEGER NOT NULL,
  unit_price REAL NOT NULL,
  total_price REAL NOT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS stock_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  product_id INTEGER NOT NULL,
  previous_quantity INTEGER NOT NULL,
  new_quantity INTEGER NOT NULL,
  change_type TEXT NOT NULL,
  reference_type varchar(50) DEFAULT NULL,
  reference_id INTEGER DEFAULT NULL,
  notes text DEFAULT NULL,
  user_id INTEGER NOT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS category_unit_mapping (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  category varchar(100) NOT NULL,
  unit_name varchar(50) NOT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS summary_cards (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  card_key varchar(50) NOT NULL,
  card_title varchar(100) NOT NULL,
  card_type TEXT DEFAULT 'custom',
  icon_class varchar(50) DEFAULT 'bi bi-collection',
  color_class varchar(50) DEFAULT 'blue',
  custom_query text DEFAULT NULL,
  display_order INTEGER DEFAULT 0,
  is_active TEXT DEFAULT 'yes',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payment_history (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_id INTEGER NOT NULL,
  amount REAL NOT NULL,
  payment_method varchar(50) DEFAULT 'cash',
  transaction_id varchar(100) DEFAULT NULL,
  notes text DEFAULT NULL,
  user_id INTEGER DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS size_types (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  size_type_name varchar(100) NOT NULL,
  size_type_options text DEFAULT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS units (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  unit_name varchar(50) NOT NULL,
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  full_name varchar(100) NOT NULL,
  email varchar(100) DEFAULT NULL,
  role TEXT DEFAULT 'cashier',
  status TEXT DEFAULT 'active',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX IF NOT EXISTS idx_payment_history_customer_id ON payment_history (customer_id);
CREATE INDEX IF NOT EXISTS idx_payment_history_user_id ON payment_history (user_id);
CREATE INDEX IF NOT EXISTS idx_payments_sale_id ON payments (sale_id);
CREATE INDEX IF NOT EXISTS idx_sale_items_product_id ON sale_items (product_id);
CREATE INDEX IF NOT EXISTS idx_sale_items_sale_id ON sale_items (sale_id);
CREATE INDEX IF NOT EXISTS idx_sales_customer_id ON sales (customer_id);
CREATE INDEX IF NOT EXISTS idx_sales_user_id ON sales (user_id);
CREATE INDEX IF NOT EXISTS idx_stock_logs_product_id ON stock_logs (product_id);
CREATE INDEX IF NOT EXISTS idx_stock_logs_user_id ON stock_logs (user_id);
CREATE UNIQUE INDEX IF NOT EXISTS idx_category_unit_mapping_category ON category_unit_mapping (category);
CREATE UNIQUE INDEX IF NOT EXISTS idx_customer_types_type_name ON customer_types (type_name);
CREATE UNIQUE INDEX IF NOT EXISTS idx_customers_phone ON customers (phone);
CREATE UNIQUE INDEX IF NOT EXISTS idx_products_barcode ON products (barcode);
CREATE UNIQUE INDEX IF NOT EXISTS idx_sales_invoice_number ON sales (invoice_number);
CREATE UNIQUE INDEX IF NOT EXISTS idx_size_types_size_type_name ON size_types (size_type_name);
CREATE UNIQUE INDEX IF NOT EXISTS idx_summary_cards_card_key ON summary_cards (card_key);
CREATE UNIQUE INDEX IF NOT EXISTS idx_units_unit_name ON units (unit_name);
CREATE UNIQUE INDEX IF NOT EXISTS idx_users_username ON users (username);

