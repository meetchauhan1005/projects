<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_POST) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = $_SESSION['user_id'];
    
    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
        exit;
    }
    
    // Check if product exists and has enough stock
    $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit;
    }
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch();
    
    if ($cart_item) {
        // Update quantity
        $new_quantity = $cart_item['quantity'] + $quantity;
        if ($new_quantity > $product['stock_quantity']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $success = $stmt->execute([$new_quantity, $cart_item['id']]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $success = $stmt->execute([$user_id, $product_id, $quantity]);
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>