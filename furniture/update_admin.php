<?php
require_once 'includes/functions.php';

$database = new Database();
$db = $database->getConnection();

// Hash the new password
$password = password_hash('admin123', PASSWORD_DEFAULT);

// Update admin password
$query = "UPDATE users SET password = ? WHERE email = 'admin@gmail.com'";
$stmt = $db->prepare($query);

if ($stmt->execute([$password])) {
    echo "Admin password updated successfully!<br>";
    echo "Email: admin@gmail.com<br>";
    echo "New Password: admin123<br>";
    echo "<a href='admin/login.php'>Login to Admin Panel</a>";
} else {
    echo "Error updating password!";
}
?>