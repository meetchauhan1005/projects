<?php
require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if admin_users table exists
    $check_table = $db->query("SHOW TABLES LIKE 'admin_users'");
    
    if($check_table->rowCount() == 0) {
        // Create admin_users table
        $create_table = "CREATE TABLE admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $db->exec($create_table);
        echo "Admin users table created successfully.<br>";
    }
    
    // Check if admin user exists
    $check_admin = $db->prepare("SELECT COUNT(*) FROM admin_users WHERE email = ?");
    $check_admin->execute(['admin@mahadev.com']);
    
    if($check_admin->fetchColumn() == 0) {
        // Create default admin user
        $username = 'admin';
        $email = 'admin@mahadev.com';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        
        $insert_admin = $db->prepare("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)");
        $insert_admin->execute([$username, $email, $password]);
        
        echo "Default admin user created successfully.<br>";
        echo "Email: admin@mahadev.com<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Admin user already exists.<br>";
    }
    
    echo "<br><a href='login.php'>Go to Login</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>