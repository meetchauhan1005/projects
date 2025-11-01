<?php
session_start();
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'active' LIMIT 6";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for navigation
    $cat_query = "SELECT * FROM categories ORDER BY name LIMIT 4";
    $cat_stmt = $db->prepare($cat_query);
    $cat_stmt->execute();
    $nav_categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $products = [];
    $nav_categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>Mahadev Electronic - Global Electronics Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-orange: #f97316;
            --accent-green: #059669;
            --accent-purple: #7c3aed;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--neutral-800);
            overflow-x: hidden;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--neutral-200);
            padding: 0.5rem 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-blue) !important;
        }
        
        .navbar-brand img {
            object-fit: contain;
            background: transparent;
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
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 120px 0 80px;
            position: relative;
            color: white;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
        }
        
        .hero-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 2.5rem;
            letter-spacing: 0.5px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 2rem;
            letter-spacing: -0.02em;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            max-width: 700px;
            line-height: 1.6;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            position: relative;
            z-index: 10;
            pointer-events: auto;
        }
        
        .btn-primary {
            background: white;
            color: var(--primary-blue);
        }
        
        .btn-primary:hover {
            background: var(--neutral-100);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            background: transparent;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
        }
        
        .stats-section {
            background: white;
            margin-top: -40px;
            position: relative;
            z-index: 10;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            display: block;
        }
        
        .stat-label {
            color: var(--neutral-600);
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .card-img-top {
            transition: transform 0.4s ease;
            height: 280px;
            object-fit: cover;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.1);
        }
        
        .price-badge {
            background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        }
        
        .feature-card {
            text-align: center;
            padding: 3rem 2rem;
            border-radius: 1.5rem;
            background: white;
            border: 1px solid var(--neutral-200);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            border-color: var(--primary-blue);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(30, 64, 175, 0.15);
        }
        
        .feature-icon {
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
            color: white;
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
        }
        
        .section-title {
            font-weight: 800;
            color: var(--neutral-900);
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        
        .section-subtitle {
            color: var(--neutral-600);
            font-size: 1.125rem;
            margin-bottom: 4rem;
        }
        
        .category-badge {
            background: var(--neutral-100);
            color: var(--neutral-700);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .cta-section {
            background: linear-gradient(135deg, var(--neutral-900) 0%, var(--neutral-800) 100%);
            color: white;
            padding: 5rem 0;
            position: relative;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }
        
        footer {
            background: var(--neutral-900);
            color: var(--neutral-200);
        }
        
        .social-btn {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: var(--neutral-800);
            color: var(--neutral-200);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        footer img {
            object-fit: contain;
            background: transparent;
        }
        
        .social-btn:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
        }
        
        .user-avatar {
            transition: all 0.3s ease;
        }
        
        .dropdown-toggle:hover .user-avatar {
            transform: scale(1.1);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 0.75rem;
        }
        
        /* Enhanced Mobile Styles */
        @media (max-width: 768px) {
            .hero-section { 
                padding: 80px 0 60px; 
                min-height: 70vh;
            }
            .hero-title { 
                font-size: 2.2rem; 
                line-height: 1.2;
            }
            .hero-subtitle {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .section-title { 
                font-size: 1.8rem; 
                text-align: center;
            }
            .section-subtitle {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .stats-section { 
                margin-top: -30px; 
                padding: 1.5rem; 
                border-radius: 1rem;
            }
            .stat-number {
                font-size: 2rem;
            }
            .card-img-top {
                height: 200px;
            }
            .btn {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
            .hero-badge {
                font-size: 0.8rem;
                padding: 0.5rem 1.5rem;
                margin-bottom: 1.5rem;
            }
            .d-flex.gap-3 {
                flex-direction: column;
                gap: 0.75rem !important;
            }
            .d-flex.gap-3 .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 60px 0 40px;
                min-height: 60vh;
            }
            .hero-title {
                font-size: 1.8rem;
            }
            .hero-subtitle {
                font-size: 0.9rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .stats-section {
                padding: 1rem;
            }
            .stat-number {
                font-size: 1.5rem;
            }
            .card-img-top {
                height: 180px;
            }
            .feature-card {
                padding: 2rem 1rem;
            }
            .feature-icon {
                width: 4rem;
                height: 4rem;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.svg" alt="Mahadev Electronic" width="50" height="50" class="me-2">
                <span class="fw-bold">Mahadev Electronic</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="line-height: 1.2;">
                                <span style="font-size: 14px;">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span><br>
                                <span style="font-size: 14px; font-weight: bold;">Account & Lists</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 450px; padding: 0; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                <li style="background: #f3f3f3; padding: 16px; border-bottom: 1px solid #ddd;">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/50" alt="User" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 12px;">
                                        <div style="flex: 1;">
                                            <div style="font-weight: bold; font-size: 16px; color: #333;"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                                            <div style="color: #666; font-size: 14px;">Account holder</div>
                                        </div>
                                        <a href="profile.php" style="color: #0066c0; text-decoration: none; font-size: 14px;">Manage Profiles ></a>
                                    </div>
                                </li>
                                <li style="padding: 16px;">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6 style="font-weight: bold; margin-bottom: 12px; color: #333;">Your Lists</h6>
                                            <div style="margin-bottom: 8px;"><a href="shopping-list.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Shopping List</a></div>
                                            <div style="margin-bottom: 8px;"><a href="wishlist.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Create a Wish List</a></div>
                                            <div style="margin-bottom: 8px;"><a href="wishlist.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Wish from Any Website</a></div>
                                            <div style="margin-bottom: 8px;"><a href="wishlist.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Baby Wishlist</a></div>
                                        </div>
                                        <div class="col-6">
                                            <h6 style="font-weight: bold; margin-bottom: 12px; color: #333;">Your Account</h6>
                                            <div style="margin-bottom: 8px;"><a href="profile.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Your Account</a></div>
                                            <div style="margin-bottom: 8px;"><a href="my-orders.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Your Orders</a></div>
                                            <div style="margin-bottom: 8px;"><a href="wishlist.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Your Wish List</a></div>
                                            <div style="margin-bottom: 8px;"><a href="recommendations.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Your Recommendations</a></div>
                                            <div style="margin-bottom: 8px;"><a href="logout.php" style="color: #333; text-decoration: none; font-size: 14px; display: block; padding: 2px 0;">Sign Out</a></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus me-1"></i>Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- User Profile Banner -->
    <div class="container-fluid" style="background: linear-gradient(135deg, #059669, #047857); color: white; padding: 1.2rem 0; margin-top: 76px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-3" style="width: 55px; height: 55px; background: rgba(255,255,255,0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; border: 3px solid rgba(255,255,255,0.3);">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?> 👋</h4>
                            <p class="mb-0 opacity-90"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="profile.php" class="btn btn-light btn-sm me-2 fw-bold">
                        <i class="fas fa-user-circle me-1"></i>Profile Section
                    </a>
                    <a href="my-orders.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-shopping-bag me-1"></i>My Orders
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero-section" <?php if (isset($_SESSION['user_id'])): ?>style="padding-top: 80px;"<?php endif; ?>>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center hero-content">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="hero-badge">
                            <i class="fas fa-user-check me-2"></i>VIP Member • Exclusive Discounts • Priority Support
                        </div>
                        <h1 class="hero-title">
                            Your Electronics<br>
                            <span style="background: linear-gradient(45deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Journey Continues</span>
                        </h1>
                        <p class="hero-subtitle">
                            Discover new products, track your orders, and enjoy exclusive member benefits.<br>
                            Your trusted electronics partner since 2008.
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="products.php" class="btn btn-primary">
                                <i class="fas fa-laptop"></i>
                                Browse Products
                            </a>
                            <a href="my-orders.php" class="btn btn-outline-light">
                                <i class="fas fa-shopping-bag"></i>
                                My Orders
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="hero-badge">
                            <i class="fas fa-award me-2"></i>Authorized Dealer • Genuine Products • Warranty Assured
                        </div>
                        <h1 class="hero-title">
                            Professional Electronics<br>
                            <span style="background: linear-gradient(45deg, #fbbf24, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Solutions & Services</span>
                        </h1>
                        <p class="hero-subtitle">
                            Your trusted partner for authentic electronics, professional installation services,<br>
                            and comprehensive technical support. Serving businesses and consumers<br>
                            with excellence since 2008.
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="products.php" class="btn btn-primary">
                                <i class="fas fa-laptop"></i>
                                Browse Catalog
                            </a>
                            <a href="register.php" class="btn btn-outline-light">
                                <i class="fas fa-user-plus"></i>
                                Create Account
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="container">
        <div class="stats-section">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <span class="stat-number">15+</span>
                        <div class="stat-label">Years Experience</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <div class="stat-label">Quality Products</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <div class="stat-label">Customer Satisfaction</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">Technical Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked premium electronics from top global brands</p>
            </div>
            <div class="row g-4">
                <?php if(count($products) > 0): ?>
                    <?php foreach($products as $product): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="position-relative overflow-hidden">
                                <a href="product-details.php?id=<?php echo $product['id']; ?>">
                                    <img src="assets/images/<?php echo $product['image']; ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=280&fit=crop'">
                                </a>
                                <div class="product-badge">
                                    <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-2">
                                    <span class="category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                </div>
                                <h5 class="card-title fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted flex-grow-1 mb-4">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="price-badge">₹<?php echo number_format($product['price']); ?></span>
                                    <a href="product-details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info border-0">
                            <i class="fas fa-info-circle me-2"></i>Products will be available soon. Stay tuned!
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Why Choose Mahadev Electronic</h2>
                <p class="section-subtitle">Professional electronics solutions with comprehensive service support</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="shipping.php" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Fast Delivery</h5>
                            <p class="text-muted mb-0">Quick and secure delivery across India with real-time tracking and insurance coverage</p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="products.php" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Genuine Products</h5>
                            <p class="text-muted mb-0">100% authentic electronics sourced directly from authorized distributors and manufacturers</p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="contact.php" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Technical Support</h5>
                            <p class="text-muted mb-0">Professional technical assistance and installation services from certified technicians</p>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="warranty.php" class="text-decoration-none">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Warranty Service</h5>
                            <p class="text-muted mb-0">Comprehensive warranty coverage with authorized service center support nationwide</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Partner with Mahadev Electronic Today</h2>
                    <p class="lead mb-5 opacity-90">
                        Experience professional electronics solutions backed by expert service and genuine warranty. Trusted by businesses and professionals across India.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="products.php" class="btn btn-primary">
                            <i class="fas fa-laptop me-2"></i>View Products
                        </a>
                        <a href="contact.php" class="btn btn-outline-light">
                            <i class="fas fa-headset me-2"></i>Get Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/images/logo.svg" alt="Mahadev Electronic" width="50" height="50">
                        <span class="ms-2 fw-bold text-white">Mahadev Electronic</span>
                    </div>
                    <p class="mb-4">Leading global electronics retailer providing premium technology products with worldwide shipping and support.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-semibold mb-3">Categories</h6>
                    <ul class="list-unstyled">
                        <?php foreach($nav_categories as $category): ?>
                        <li><a href="products.php?category=<?php echo $category['id']; ?>" class="text-decoration-none" style="color: var(--neutral-200);"><?php echo htmlspecialchars($category['name']); ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="products.php" class="text-decoration-none" style="color: var(--neutral-200);">View All</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-semibold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="help-center.php" class="text-decoration-none" style="color: var(--neutral-200);">Help Center</a></li>
                        <li><a href="warranty.php" class="text-decoration-none" style="color: var(--neutral-200);">Warranty</a></li>
                        <li><a href="returns.php" class="text-decoration-none" style="color: var(--neutral-200);">Returns</a></li>
                        <li><a href="shipping.php" class="text-decoration-none" style="color: var(--neutral-200);">Shipping</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-semibold mb-3">Contact Info</h6>
                    <div class="mb-2"><i class="fas fa-envelope me-2"></i>info@mahadevelectronic.com</div>
                    <div class="mb-2"><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</div>
                    <div><i class="fas fa-map-marker-alt me-2"></i>Global Headquarters, Tech District</div>
                </div>
            </div>
            <hr style="border-color: var(--neutral-800);">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 Mahadev Electronic. All rights reserved worldwide.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none me-3" style="color: var(--neutral-200);">Privacy Policy</a>
                    <a href="#" class="text-decoration-none" style="color: var(--neutral-200);">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure dropdown works
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
    <script src="assets/js/responsive.js"></script>
    <script>
        // Additional mobile optimizations
        document.addEventListener('DOMContentLoaded', function() {
            // Optimize images for mobile
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=280&fit=crop';
                });
            });
            
            // Add touch feedback for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                btn.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        });
    </script>
</body>
</html>