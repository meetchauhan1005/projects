<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart_items = [];
$total = 0;

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT c.*, p.name, p.price, p.image, p.stock_quantity 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = ? AND p.status = 'active'";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} catch (Exception $e) {
    $cart_items = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-orange: #f97316;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--neutral-800);
            background: var(--neutral-50);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--neutral-200);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-blue) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--neutral-600) !important;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-blue) !important;
        }
        
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-blue);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .cart-item {
            border-bottom: 1px solid var(--neutral-200);
            padding: 1.5rem 0;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .quantity-input {
            width: 80px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-bolt text-warning me-2"></i>Mahadev Electronic
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link active" href="cart.php"><i class="fas fa-shopping-cart me-1"></i>Cart</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5 mt-5">
        <h1 class="mb-4">Shopping Cart</h1>
        
        <?php if (empty($cart_items)): ?>
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted mb-4">Add some products to get started</p>
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="assets/images/<?= $item['image'] ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="img-fluid rounded"
                                                 onerror="this.src='https://via.placeholder.com/100x100'">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                            <p class="text-muted mb-0">₹<?= number_format($item['price']) ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                                                <input type="number" class="form-control quantity-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock_quantity'] ?>" onchange="updateQuantity(<?= $item['product_id'] ?>, this.value)">
                                                <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p class="fw-bold mb-0">₹<?= number_format($item['price'] * $item['quantity']) ?></p>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-outline-danger btn-sm" onclick="removeItem(<?= $item['product_id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹<?= number_format($total) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span><?= $total >= 5000 ? 'FREE' : '₹199' ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong>₹<?= number_format($total + ($total >= 5000 ? 0 : 199)) ?></strong>
                            </div>
                            
                            <a href="checkout.php" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                            <a href="products.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, quantity) {
            if (quantity < 1) {
                removeItem(productId);
                return;
            }
            
            fetch('cart-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
        
        function removeItem(productId) {
            if (confirm('Remove this item from cart?')) {
                fetch('cart-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    </script>
</body>
</html>