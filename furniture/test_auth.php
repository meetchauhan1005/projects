<?php
session_start();
require_once 'config/database.php';

function testAuthentication($conn) {
    try {
        // Test user registration
        $username = 'testuser_' . time();
        $email = $username . '@test.com';
        $password = 'Test123!';
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$username, $email, $password_hash, 'Test User', 'user']);
        echo $result ? "User registration test: PASSED\n" : "User registration test: FAILED\n";
        
        // Test user login
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $login_success = $user && password_verify($password, $user['password']);
        echo $login_success ? "User login test: PASSED\n" : "User login test: FAILED\n";
        
        // Test admin login
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = 'admin'");
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo $admin ? "Admin user exists: PASSED\n" : "Admin user exists: FAILED\n";
        
        // Clean up test user
        $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        return true;
    } catch (Exception $e) {
        echo "Authentication test error: " . $e->getMessage() . "\n";
        return false;
    }
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    testAuthentication($conn);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>