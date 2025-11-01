<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get or create demo user
    $user_check = $db->prepare("SELECT id FROM users WHERE email = ?");
    $user_check->execute(['demo@user.com']);
    $user_id = $user_check->fetchColumn();
    
    if(!$user_id) {
        // Create demo user
        $insert_user = $db->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $insert_user->execute(['Demo User', 'demo@user.com', '9876543210', password_hash('demo123', PASSWORD_DEFAULT)]);
        $user_id = $db->lastInsertId();
        echo "Created demo user with ID: $user_id<br>";
    }
    
    // Delete existing orders for demo user
    $delete = $db->prepare("DELETE FROM orders WHERE user_id = ? OR customer_email = ?");
    $delete->execute([$user_id, 'demo@user.com']);
    echo "Deleted existing orders<br>";
    
    // Create new test orders
    $orders = [
        [$user_id, 'Demo User', 'demo@user.com', '9876543210', '123 Main St, Mumbai', 25000.00, 'pending', 'pending'],
        [$user_id, 'Demo User', 'demo@user.com', '9876543210', '456 Oak Ave, Mumbai', 15000.00, 'processing', 'paid'],
        [$user_id, 'Demo User', 'demo@user.com', '9876543210', '789 Pine Rd, Mumbai', 35000.00, 'delivered', 'paid']
    ];
    
    $insert = $db->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach($orders as $order) {
        $insert->execute($order);
        echo "Created order with ID: " . $db->lastInsertId() . "<br>";
    }
    
    echo "<br><strong>Test orders created successfully!</strong><br>";
    echo "<a href='login.php'>Login with demo@user.com / demo123</a><br>";
    echo "<a href='my-orders.php'>View My Orders</a><br>";
    echo "<a href='my-orders.php?debug=1'>Debug My Orders</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>