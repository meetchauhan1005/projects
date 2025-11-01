<?php
session_start();
require_once 'config/database.php';

// Get cart count
$cart_count = 0;
if (isset($_SESSION['user_id']) && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cart_count = $stmt->fetchColumn() ?: 0;
    } catch (Exception $e) {
        $cart_count = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Shop West' : 'Shop West - Your Online Shopping Destination'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="assets/js/main.js" defer></script>
    <meta name="description" content="Shop West - Your premier online shopping destination for electronics, clothing, books, and more. Discover amazing products at unbeatable prices.">
    <meta name="keywords" content="online shopping, ecommerce, electronics, clothing, books, home garden">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="45" fill="url(#logoGradient)"/>
                        <path d="M25 35 L50 20 L75 35 L75 65 L25 65 Z" fill="white" opacity="0.9"/>
                        <rect x="42" y="45" width="16" height="12" fill="url(#logoGradient)"/>
                        <circle cx="38" cy="40" r="2" fill="url(#logoGradient)"/>
                        <circle cx="62" cy="40" r="2" fill="url(#logoGradient)"/>
                        <path d="M35 55 Q50 45 65 55" stroke="url(#logoGradient)" stroke-width="2" fill="none"/>
                    </svg>
                    <span>Shop West</span>
                </a>
                
                <div class="search-bar">
                    <button type="button" onclick="searchProducts()">
                        <i class="fas fa-search"></i>
                    </button>
                    <input type="text" placeholder="Search for products, brands, and more..." id="searchInput">
                </div>
                
                <nav class="nav-links">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="products.php">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Products</span>
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Cart</span>
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="admin/index.php">
                            <i class="fas fa-cog"></i>
                            <span>Admin</span>
                        </a>
                        <a href="logout.php" style="color: var(--danger-color);">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a href="register.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <script>
        function searchProducts() {
            const query = document.getElementById('searchInput').value;
            if (query.trim()) {
                window.location.href = 'products.php?search=' + encodeURIComponent(query);
            }
        }
        
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchProducts();
            }
        });
        
        // Add loading states and smooth interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Add loading animation for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('loading')) {
                        this.classList.add('loading');
                        setTimeout(() => this.classList.remove('loading'), 1000);
                    }
                });
            });
        });
    </script>