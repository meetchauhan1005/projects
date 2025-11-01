<?php
session_start();
require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    return strlen($password) >= 6;
}

function checkRateLimit($action, $limit = 5, $window = 300) {
    $key = $action . '_' . $_SERVER['REMOTE_ADDR'];
    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = ['count' => 0, 'time' => time()];
    }
    
    $data = $_SESSION['rate_limit'][$key];
    if (time() - $data['time'] > $window) {
        $_SESSION['rate_limit'][$key] = ['count' => 1, 'time' => time()];
        return true;
    }
    
    if ($data['count'] >= $limit) {
        return false;
    }
    
    $_SESSION['rate_limit'][$key]['count']++;
    return true;
}

function logError($message, $file = '', $line = '') {
    $log = date('Y-m-d H:i:s') . ' - ' . $message;
    if ($file) $log .= ' in ' . $file;
    if ($line) $log .= ' on line ' . $line;
    error_log($log);
}

function getCartCount() {
    if (!isLoggedIn()) return 0;
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['total'] ?? 0;
}

function addToCart($product_id, $quantity = 1) {
    if (!isLoggedIn()) return false;
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if item already in cart
    $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    
    if ($stmt->rowCount() > 0) {
        // Update quantity
        $query = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$quantity, $_SESSION['user_id'], $product_id]);
    } else {
        // Insert new item
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
    }
}

function getProducts($limit = null, $category_id = null, $featured = null) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'active'";
    
    $params = [];
    
    if ($category_id) {
        $query .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($featured) {
        $query .= " AND p.featured = 1";
    }
    
    $query .= " ORDER BY p.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT " . (int)$limit;
    }
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCategories() {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM categories ORDER BY name";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getImagePath($image_name, $folder = 'products') {
    $image_path = "assets/images/{$folder}/{$image_name}";
    if (file_exists($image_path)) {
        return $image_path;
    }
    return 'assets/images/placeholder.jpg';
}
?>