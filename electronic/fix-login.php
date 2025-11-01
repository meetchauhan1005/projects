<?php
session_start();
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Test login with demo user
    $email = 'demo@user.com';
    $password = 'demo123';
    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            echo "✅ Login successful! Demo user logged in.<br>";
            echo "User ID: " . $user['id'] . "<br>";
            echo "Name: " . $user['name'] . "<br>";
            echo "Email: " . $user['email'] . "<br>";
        } else {
            echo "❌ Password verification failed<br>";
        }
    } else {
        echo "❌ Demo user not found<br>";
    }
    
    echo "<br><a href='index.php'>Go to Homepage</a> | <a href='profile.php'>View Profile</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>