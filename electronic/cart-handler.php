<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'logged_in' => false, 'message' => 'Please login first']);
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Create cart table if not exists
    $db->exec("CREATE TABLE IF NOT EXISTS cart (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_product (user_id, product_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
    
    switch ($action) {
        case 'add':
            $product_id = $_POST['product_id'] ?? 0;
            $quantity = max(1, intval($_POST['quantity'] ?? 1));
            
            // Check if product exists and is in stock
            $stmt = $db->prepare("SELECT stock_quantity FROM products WHERE id = ? AND status = 'active'");
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
            
            // Add or update cart item
            $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) 
                                 ON DUPLICATE KEY UPDATE quantity = quantity + ?");
            $stmt->execute([$user_id, $product_id, $quantity, $quantity]);
            
            echo json_encode(['success' => true, 'message' => 'Product added to cart']);
            break;
            
        case 'update':
            $product_id = $_POST['product_id'] ?? 0;
            $quantity = max(0, intval($_POST['quantity'] ?? 0));
            
            if ($quantity == 0) {
                $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
            } else {
                $stmt = $db->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$quantity, $user_id, $product_id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
            break;
            
        case 'remove':
            $product_id = $_POST['product_id'] ?? 0;
            
            $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
            break;
            
        case 'clear':
            $stmt = $db->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            echo json_encode(['success' => true, 'message' => 'Cart cleared']);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>