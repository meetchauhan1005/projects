<?php
session_start();
require_once 'config/database.php';

echo "<h2>Login System Test</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if users table exists and has data
    $users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Users in database:</h3>";
    foreach($users as $user) {
        echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}<br>";
    }
    
    // Check current session
    echo "<h3>Current Session:</h3>";
    if(isset($_SESSION['user_id'])) {
        echo "Logged in as: " . $_SESSION['user_name'] . " (" . $_SESSION['user_email'] . ")";
        echo "<br><a href='logout.php'>Logout</a>";
    } else {
        echo "Not logged in";
        echo "<br><a href='login.php'>Login</a>";
    }
    
    echo "<br><br><a href='index.php'>Back to Home</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>