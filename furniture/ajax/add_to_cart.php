<?php
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!$product_id || $quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Check if product exists and is in stock
$query = "SELECT * FROM products WHERE id = ? AND status = 'active'";
$stmt = $db->prepare($query);
$stmt->execute([$product_id]);

if ($stmt->rowCount() == 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product['stock_quantity'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
    exit;
}

// Add to cart
if (addToCart($product_id, $quantity)) {
    echo json_encode(['success' => true, 'message' => 'Product added to cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding product to cart']);
}
?>