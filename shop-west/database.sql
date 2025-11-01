-- Shop West Database Structure
CREATE DATABASE IF NOT EXISTS shop_west;
USE shop_west;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
    rating DECIMAL(2,1) DEFAULT 0,
    reviews_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Cart table
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Electronics', 'Latest gadgets and electronics', 'electronics.jpg'),
('Clothing', 'Fashion and apparel', 'clothing.jpg'),
('Books', 'Books and literature', 'books.jpg'),
('Home & Garden', 'Home improvement and garden supplies', 'home.jpg'),
('Sports', 'Sports and outdoor equipment', 'sports.jpg');

-- Insert sample products
INSERT INTO products (name, description, price, stock_quantity, category_id, image, rating) VALUES
('iPhone 15 Pro', 'Latest Apple smartphone with advanced features', 82999.00, 50, 1, 'iphone15.jpg', 4.8),
('Samsung Galaxy S24', 'Premium Android smartphone', 74999.00, 30, 1, 'galaxy-s24.jpg', 4.7),
('MacBook Air M3', 'Lightweight laptop with M3 chip', 108999.00, 25, 1, 'macbook-air.jpg', 4.9),
('Nike Air Max', 'Comfortable running shoes', 10999.00, 100, 2, 'nike-airmax.jpg', 4.5),
('Adidas Hoodie', 'Comfortable cotton hoodie', 4999.00, 75, 2, 'adidas-hoodie.jpg', 4.3),
('The Great Gatsby', 'Classic American novel', 999.00, 200, 3, 'gatsby.jpg', 4.6),
('Coffee Maker', 'Automatic drip coffee maker', 7499.00, 40, 4, 'coffee-maker.jpg', 4.4),
('Yoga Mat', 'Non-slip exercise mat', 2499.00, 80, 5, 'yoga-mat.jpg', 4.2);