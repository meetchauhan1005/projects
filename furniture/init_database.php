<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Read the schema SQL
    $schema_sql = file_get_contents('database.sql');
    $conn->exec($schema_sql);
    echo "Database schema created successfully!\n";
    
    // Read the sailing products SQL
    $products_sql = file_get_contents('sailing_products.sql');
    $conn->exec($products_sql);
    echo "Sailing products imported successfully!\n";
    
    // Create default admin user if not exists
    $check_admin = $conn->query("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
    if (!$check_admin->fetch()) {
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->exec("INSERT INTO users (username, email, password, full_name, role) 
                    VALUES ('admin', 'admin@furniture.com', '$admin_password', 'Administrator', 'admin')");
        echo "Default admin user created (username: admin, password: admin123)\n";
    }
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString();
}
?>