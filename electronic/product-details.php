<?php
session_start();
require_once 'config/database.php';

$product_id = $_GET['id'] ?? 0;
$product = null;

if ($product_id) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT p.*, c.name as category_name FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.id = ? AND p.status = 'active'";
        $stmt = $db->prepare($query);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $product = null;
    }
}

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Mahadev Electronic</title>
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
        
        .product-image {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .price-display {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent-orange);
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
        
        .btn-success {
            background: var(--accent-orange);
            color: white;
        }
        
        .btn-success:hover {
            background: #ea580c;
            transform: translateY(-2px);
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
                    <li class="nav-item"><a class="nav-link active" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart me-1"></i>Cart</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5 mt-5">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <img src="assets/images/<?= $product['image'] ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="img-fluid product-image"
                     onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop'">
            </div>
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
                    </ol>
                </nav>
                
                <h1 class="fw-bold mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="text-muted mb-3">Category: <?= htmlspecialchars($product['category_name']) ?></p>
                
                <div class="price-display mb-4">₹<?= number_format($product['price']) ?></div>
                
                <p class="mb-4"><?= htmlspecialchars($product['description']) ?></p>
                
                <div class="mb-4">
                    <span class="badge bg-<?= $product['stock_quantity'] > 0 ? 'success' : 'danger' ?> fs-6">
                        <?= $product['stock_quantity'] > 0 ? 'In Stock (' . $product['stock_quantity'] . ' available)' : 'Out of Stock' ?>
                    </span>
                </div>
                
                <?php if ($product['stock_quantity'] > 0): ?>
                    <div class="d-flex gap-3 mb-4">
                        <div class="input-group" style="max-width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                            <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button class="btn btn-success btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                        <button class="btn btn-primary btn-lg" onclick="buyNow(<?= $product['id'] ?>)">
                            <i class="fas fa-bolt me-2"></i>Buy Now
                        </button>
                    </div>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-times me-2"></i>Out of Stock
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeQuantity(change) {
            const input = document.getElementById('quantity');
            const newValue = parseInt(input.value) + change;
            const max = parseInt(input.max);
            
            if (newValue >= 1 && newValue <= max) {
                input.value = newValue;
            }
        }
        
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            
            fetch('cart-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=add&product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                } else {
                    alert(data.message || 'Please login to add items to cart');
                    if (!data.logged_in) {
                        window.location.href = 'login.php';
                    }
                }
            });
        }
        
        function buyNow(productId) {
            addToCart(productId);
            setTimeout(() => {
                window.location.href = 'cart.php';
            }, 500);
        }
    </script>
</body>
</html>