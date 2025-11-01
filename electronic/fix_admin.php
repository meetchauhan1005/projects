<?php
// Fix admin login issues
$host = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mahadev_electronic");
    $pdo->exec("USE mahadev_electronic");
    
    // Drop and recreate admin_users table
    $pdo->exec("DROP TABLE IF EXISTS admin_users");
    $pdo->exec("CREATE TABLE admin_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create other tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category_id INT,
        image VARCHAR(255),
        stock_quantity INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert admin with correct password hash
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO admin_users (username, password, email) VALUES 
        ('admin@gmail.com', '$admin_password', 'admin@gmail.com')");
    
    // Insert sample data
    $pdo->exec("INSERT IGNORE INTO categories (name, description) VALUES 
        ('Mobile Phones', 'Latest smartphones and accessories'),
        ('Laptops', 'Computers and laptops for all needs'),
        ('Home Appliances', 'Kitchen and home electronic appliances'),
        ('Audio & Video', 'Speakers, headphones, and entertainment systems')");
    
    $pdo->exec("INSERT IGNORE INTO products (name, description, price, category_id, image, stock_quantity) VALUES 
        ('iPhone 15 Pro', 'Latest Apple iPhone with advanced features', 99999.00, 1, 'iphone15.jpg', 10),
        ('Samsung Galaxy S24', 'Premium Android smartphone', 79999.00, 1, 'galaxy-s24.jpg', 15),
        ('MacBook Air M2', 'Lightweight laptop for professionals', 119999.00, 2, 'macbook-air.jpg', 8),
        ('Dell XPS 13', 'High-performance ultrabook', 89999.00, 2, 'dell-xps13.jpg', 12),
        ('LG Refrigerator', 'Double door refrigerator with inverter', 45999.00, 3, 'lg-fridge.jpg', 5),
        ('Sony Headphones', 'Noise cancelling wireless headphones', 24999.00, 4, 'sony-headphones.jpg', 20)");
    
    echo "✅ Admin login fixed successfully!<br>";
    echo "🔐 Login: <a href='admin/login.php'>Admin Panel</a><br>";
    echo "📧 Username: admin@gmail.com<br>";
    echo "🔑 Password: admin123<br>";
    echo "🏠 Website: <a href='index.php'>Homepage</a>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>