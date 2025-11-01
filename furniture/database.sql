CREATE DATABASE IF NOT EXISTS furniture_store;
USE furniture_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) DEFAULT NULL,
    category_id INT,
    stock_quantity INT DEFAULT 0,
    image VARCHAR(255),
    gallery TEXT,
    specifications TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO categories (name, description, image) VALUES 
('Sofas & Chairs', 'Comfortable seating furniture for your living room', 'sofa.jpg'),
('Tables', 'Dining tables, coffee tables, and more', 'table.jpg'),
('Bedroom', 'Beds, wardrobes, and bedroom furniture', 'bedroom.jpg'),
('Storage', 'Cabinets, shelves, and storage solutions', 'storage.jpg'),
('Office', 'Desks, chairs, and office furniture', 'office.jpg');

-- INSERT INTO products (name, description, price, discount_price, category_id, stock_quantity, image, featured) VALUES 
-- ('Modern Sectional Sofa', 'Comfortable L-shaped sectional sofa perfect for modern living rooms', 1299.99, 999.99, 1, 15, 'sofa1.jpg', TRUE),
-- ('Dining Table Set', '6-seater wooden dining table with chairs', 899.99, NULL, 2, 8, 'table1.jpg', TRUE),
-- ('King Size Bed', 'Luxury king size bed with upholstered headboard', 1599.99, 1299.99, 3, 5, 'bed1.jpg', FALSE),
-- ('Office Chair', 'Ergonomic office chair with lumbar support', 299.99, 249.99, 5, 25, 'chair1.jpg', TRUE),
-- ('Coffee Table', 'Modern glass coffee table with metal legs', 399.99, NULL, 2, 12, 'coffee_table.jpg', FALSE);

INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@gmail.com', '$2y$10$7wgKzJZQZQZQZQZQZQZQZOe7wgKzJZQZQZQZQZQZQZQZOe7wgKzJZQ', 'Administrator', 'admin');

