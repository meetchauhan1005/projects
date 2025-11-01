<?php
class Database {
    private $host = "localhost";
    private $db_name = "mahadev_electronic";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // First try to connect without database to create it if needed
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "`");
            
            // Now connect to the database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
            
            // Create admin_users table if it doesn't exist
            $this->createAdminTable();
            
        } catch(PDOException $exception) {
            throw new Exception("Database connection failed: " . $exception->getMessage());
        }
        return $this->conn;
    }
    
    private function createAdminTable() {
        try {
            // Create admin_users table
            $sql = "CREATE TABLE IF NOT EXISTS admin_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($sql);
            
            // Create categories table
            $sql = "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($sql);
            
            // Create products table
            $sql = "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                stock_quantity INT DEFAULT 0,
                category_id INT,
                image VARCHAR(255),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )";
            $this->conn->exec($sql);
            
            // Create contact_messages table
            $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20),
                message TEXT NOT NULL,
                status ENUM('new', 'read', 'replied') DEFAULT 'new',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($sql);
            
            // Create users table
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                phone VARCHAR(20),
                password VARCHAR(255) NOT NULL,
                profile_photo VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($sql);
            
            // Add profile_photo column if it doesn't exist
            try {
                $this->conn->exec("ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255)");
            } catch(PDOException $e) {
                // Column already exists, ignore error
            }
            
            // Create orders table
            $sql = "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                customer_name VARCHAR(100) NOT NULL,
                customer_email VARCHAR(100) NOT NULL,
                customer_phone VARCHAR(20),
                customer_address TEXT,
                total_amount DECIMAL(10,2) NOT NULL,
                status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
                payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($sql);
            
            // Add user_id column if it doesn't exist
            try {
                $this->conn->exec("ALTER TABLE orders ADD COLUMN user_id INT");
            } catch(PDOException $e) {
                // Column already exists, ignore error
            }
            
            // Create order_items table
            $sql = "CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id)
            )";
            $this->conn->exec($sql);
            
            // Insert default admin if not exists
            $check = $this->conn->prepare("SELECT COUNT(*) FROM admin_users WHERE email = ?");
            $check->execute(['admin@mahadev.com']);
            
            if($check->fetchColumn() == 0) {
                $insert = $this->conn->prepare("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)");
                $insert->execute(['admin', 'admin@mahadev.com', password_hash('admin123', PASSWORD_DEFAULT)]);
            }
            
            // Insert default categories if not exists
            $check = $this->conn->prepare("SELECT COUNT(*) FROM categories");
            $check->execute();
            
            if($check->fetchColumn() == 0) {
                $categories = [
                    ['Smartphones', 'Mobile phones and smartphone accessories'],
                    ['Laptops', 'Laptops, notebooks and computer accessories'],
                    ['Accessories', 'Electronic accessories and peripherals'],
                    ['Home Audio', 'Audio systems and home entertainment']
                ];
                
                $insert = $this->conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
                foreach($categories as $category) {
                    $insert->execute($category);
                }
            }
            
            // Insert sample products if categories exist and no products
            $prod_check = $this->conn->prepare("SELECT COUNT(*) FROM products");
            $prod_check->execute();
            
            if($prod_check->fetchColumn() == 0) {
                $products = [
                    ['iPhone 15 Pro', 'Latest Apple smartphone with advanced features', 89999.00, 10, 1, 'iphone15.jpg'],
                    ['Samsung Galaxy S24', 'Premium Android smartphone', 79999.00, 15, 1, 'galaxy-s24.jpg'],
                    ['MacBook Pro M3', 'Professional laptop for creators', 199999.00, 5, 2, 'macbook-pro.jpg'],
                    ['Dell XPS 13', 'Ultra-portable business laptop', 89999.00, 8, 2, 'dell-xps13.jpg'],
                    ['AirPods Pro', 'Wireless earbuds with noise cancellation', 24999.00, 20, 3, 'airpods-pro.jpg'],
                    ['Sony WH-1000XM5', 'Premium noise-canceling headphones', 29999.00, 12, 4, 'sony-headphones.jpg']
                ];
                
                $insert = $this->conn->prepare("INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
                foreach($products as $product) {
                    $insert->execute($product);
                }
            }
            
            // Insert sample user if not exists
            $check = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $check->execute(['demo@user.com']);
            
            if($check->fetchColumn() == 0) {
                $insert = $this->conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
                $insert->execute(['Demo User', 'demo@user.com', '9876543210', password_hash('demo123', PASSWORD_DEFAULT)]);
            }
            
            // Always ensure demo user has orders
            $user_check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $user_check->execute(['demo@user.com']);
            $demo_user_id = $user_check->fetchColumn();
            
            if($demo_user_id) {
                // Check if demo user has orders
                $order_check = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? OR customer_email = ?");
                $order_check->execute([$demo_user_id, 'demo@user.com']);
                
                if($order_check->fetchColumn() == 0) {
                    // Create orders for demo user
                    $orders = [
                        [$demo_user_id, 'Demo User', 'demo@user.com', '9876543210', '123 Main St, Mumbai', 25000.00, 'pending', 'pending'],
                        [$demo_user_id, 'Demo User', 'demo@user.com', '9876543210', '456 Oak Ave, Mumbai', 15000.00, 'processing', 'paid'],
                        [$demo_user_id, 'Demo User', 'demo@user.com', '9876543210', '789 Pine Rd, Mumbai', 35000.00, 'delivered', 'paid']
                    ];
                    
                    $insert = $this->conn->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    foreach($orders as $order) {
                        $insert->execute($order);
                    }
                }
            }
            
        } catch(PDOException $e) {
            // Table creation failed, but connection is still valid
        }
    }
}
?>