-- Database optimization for Shop West
USE shop_west;

-- Add indexes for better performance
ALTER TABLE products ADD INDEX idx_category_id (category_id);
ALTER TABLE products ADD INDEX idx_created_at (created_at);
ALTER TABLE cart ADD INDEX idx_user_id (user_id);
ALTER TABLE cart ADD INDEX idx_product_id (product_id);
ALTER TABLE orders ADD INDEX idx_user_id (user_id);
ALTER TABLE orders ADD INDEX idx_status (status);
ALTER TABLE order_items ADD INDEX idx_order_id (order_id);
ALTER TABLE order_items ADD INDEX idx_product_id (product_id);

-- Optimize tables
OPTIMIZE TABLE products;
OPTIMIZE TABLE categories;
OPTIMIZE TABLE users;
OPTIMIZE TABLE cart;
OPTIMIZE TABLE orders;
OPTIMIZE TABLE order_items;