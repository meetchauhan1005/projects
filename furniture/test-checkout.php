<?php
// Simple test to verify checkout functionality
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    echo "Please login first to test checkout functionality.";
    exit;
}

$database = new Database();
$db = $database->getConnection();

echo "<h2>Checkout System Test</h2>";

// Test 1: Check if user has items in cart
$query = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$cart_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

echo "<p>✓ Cart items: " . $cart_count . "</p>";

// Test 2: Check if products have stock
$query = "SELECT p.name, p.stock_quantity, c.quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Stock Validation:</h3>";
foreach ($items as $item) {
    $status = $item['stock_quantity'] >= $item['quantity'] ? "✓" : "✗";
    echo "<p>{$status} {$item['name']}: {$item['quantity']} requested, {$item['stock_quantity']} available</p>";
}

// Test 3: Check user profile completeness
$query = "SELECT full_name, email, phone, address FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>User Profile:</h3>";
echo "<p>✓ Name: " . ($user['full_name'] ? "Complete" : "Missing") . "</p>";
echo "<p>✓ Email: " . ($user['email'] ? "Complete" : "Missing") . "</p>";
echo "<p>✓ Phone: " . ($user['phone'] ? "Complete" : "Missing") . "</p>";
echo "<p>✓ Address: " . ($user['address'] ? "Complete" : "Missing") . "</p>";

echo "<br><a href='checkout.php'>Go to Checkout</a> | <a href='cart.php'>View Cart</a>";
?>