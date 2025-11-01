<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Delete existing admin if exists
$delete_query = "DELETE FROM users WHERE email = 'admin@gmail.com'";
$db->prepare($delete_query)->execute();

// Create new admin user
$password = password_hash('admin123', PASSWORD_DEFAULT);
$query = "INSERT INTO users (username, email, password, full_name, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $db->prepare($query);

if ($stmt->execute(['admin', 'admin@gmail.com', $password, 'Administrator', 'admin'])) {
    echo "SUCCESS: Admin user created!<br>";
    echo "Email: admin@gmail.com<br>";
    echo "Password: admin123<br>";
    echo "Role: admin<br>";
    echo "<a href='admin/login.php'>Go to Admin Login</a>";
} else {
    echo "ERROR: Failed to create admin user";
}
?>