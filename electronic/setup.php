<?php
// Database setup script
$host = "localhost";
$username = "root";
$password = "";

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mahadev_electronic");
    $pdo->exec("USE mahadev_electronic");
    
    // Create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS products (
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

    CREATE TABLE IF NOT EXISTS contact_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ";
    
    $pdo->exec($sql);
    
    // Insert default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute(['admin@gmail.com', $admin_password, 'admin@gmail.com']);
    
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
    
    echo "<!DOCTYPE html><html><head><title>Setup Complete</title><style>body{font-family:Arial,sans-serif;max-width:600px;margin:50px auto;padding:20px;}.success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}.btn{background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin:5px;}</style></head><body>";
    echo "<div class='success'>✅ Database setup completed successfully!</div>";
    echo "<p>📱 <a href='index.php' class='btn'>Visit Homepage</a></p>";
    echo "<p>🔐 <a href='admin/login.php' class='btn'>Admin Panel</a> (admin@gmail.com / admin123)</p>";
    echo "<p>👤 <a href='register.php' class='btn'>Register New User</a></p>";
    echo "</body></html>";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>