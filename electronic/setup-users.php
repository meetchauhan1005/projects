<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if users table exists
    $check = $db->query("SHOW TABLES LIKE 'users'");
    
    if($check->rowCount() == 0) {
        // Create users table
        $sql = "CREATE TABLE users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            profile_photo VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($sql);
        echo "Users table created successfully!<br>";
    } else {
        echo "Users table already exists.<br>";
    }
    
    // Check if orders table exists
    $check = $db->query("SHOW TABLES LIKE 'orders'");
    
    if($check->rowCount() == 0) {
        // Create orders table
        $sql = "CREATE TABLE orders (
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
        )";
        $db->exec($sql);
        echo "Orders table created successfully!<br>";
    } else {
        echo "Orders table already exists.<br>";
    }
    
    echo "<br><a href='index.php'>Go to Homepage</a> | <a href='register.php'>Register</a> | <a href='login.php'>Login</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>