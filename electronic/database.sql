-- Mahadev Electronic Database Structure
CREATE DATABASE IF NOT EXISTS mahadev_electronic;
USE mahadev_electronic;

-- Admin users table
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@mahadevelectronic.com');

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('Mobile Phones', 'Latest smartphones and accessories'),
('Laptops', 'Computers and laptops for all needs'),
('Home Appliances', 'Kitchen and home electronic appliances'),
('Audio & Video', 'Speakers, headphones, and entertainment systems');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, image, stock_quantity) VALUES 
('iPhone 15 Pro', 'Latest Apple iPhone with advanced features', 99999.00, 1, 'iphone15.jpg', 10),
('Samsung Galaxy S24', 'Premium Android smartphone', 79999.00, 1, 'galaxy-s24.jpg', 15),
('MacBook Air M2', 'Lightweight laptop for professionals', 119999.00, 2, 'macbook-air.jpg', 8),
('Dell XPS 13', 'High-performance ultrabook', 89999.00, 2, 'dell-xps13.jpg', 12),
('LG Refrigerator', 'Double door refrigerator with inverter', 45999.00, 3, 'lg-fridge.jpg', 5),
('Sony Headphones', 'Noise cancelling wireless headphones', 24999.00, 4, 'sony-headphones.jpg', 20);