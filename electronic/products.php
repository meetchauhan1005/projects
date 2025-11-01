<?php
session_start();
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'active'";

    if($category_filter) {
        $query .= " AND p.category_id = :category_id";
    }

    $query .= " ORDER BY p.created_at DESC";

    $stmt = $db->prepare($query);
    if($category_filter) {
        $stmt->bindParam(':category_id', $category_filter);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cat_query = "SELECT * FROM categories ORDER BY name";
    $cat_stmt = $db->prepare($cat_query);
    $cat_stmt->execute();
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $products = [];
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Mahadev Electronic</title>
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
        
        footer img {
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
        
        .hero-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 120px 0 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-banner::before {
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
            z-index: 2;
        }
        
        .filter-chip {
            background: white;
            border: 2px solid var(--neutral-200);
            color: var(--neutral-600);
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0.5rem;
        }
        
        .filter-chip:hover, .filter-chip.active {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
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
        
        .price-tag {
            background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        }
        
        .category-badge {
            background: var(--neutral-100);
            color: var(--neutral-700);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
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
            transform: translateY(-3px);
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
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 1.5rem;
            border: 2px dashed var(--neutral-200);
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
        
        .social-btn:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Enhanced Mobile Styles for Products */
        @media (max-width: 768px) {
            .hero-banner { 
                padding: 80px 0 60px; 
                min-height: 50vh;
            }
            .section-title { 
                font-size: 1.8rem; 
                text-align: center;
            }
            .section-subtitle {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .filter-container {
                text-align: center;
            }
            .filter-chip {
                display: inline-block;
                margin: 0.25rem;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            .card-img-top {
                height: 200px;
            }
            .price-tag {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner {
                padding: 60px 0 40px;
                min-height: 40vh;
            }
            .hero-banner h1 {
                font-size: 1.8rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .filter-chip {
                display: block;
                margin: 0.25rem auto;
                max-width: 200px;
            }
            .card-img-top {
                height: 180px;
            }
            .card-body {
                padding: 1rem;
            }
            .btn {
                font-size: 0.85rem;
                padding: 0.6rem 1rem;
            }
            .empty-state {
                padding: 2rem 1rem;
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Personal Details</a></li>
                                <li><a class="dropdown-item" href="my-orders.php"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="place-order.php"><i class="fas fa-plus me-2"></i>Place Test Order</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="admin/login.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container text-center hero-content">
            <h1 class="display-3 fw-bold mb-4">Premium Electronics Collection</h1>
            <p class="lead mb-0 opacity-90">Discover cutting-edge technology from world's leading brands with global shipping and warranty</p>
        </div>
    </section>

    <div class="container py-5">
        <!-- Category Filters -->
        <div class="text-center mb-5">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Find exactly what you're looking for</p>
            <div class="filter-container">
                <a href="products.php" class="filter-chip <?php echo !$category_filter ? 'active' : ''; ?>">
                    <i class="fas fa-th-large me-2"></i>All Products
                </a>
                <?php foreach($categories as $category): ?>
                <a href="products.php?category=<?php echo $category['id']; ?>" 
                   class="filter-chip <?php echo $category_filter == $category['id'] ? 'active' : ''; ?>">
                    <i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($category['name']); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Products Grid -->
        <?php if(count($products) > 0): ?>
        <div class="row g-4">
            <?php foreach($products as $product): ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
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
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-tag">₹<?php echo number_format($product['price']); ?></span>
                                <small class="<?php echo $product['stock_quantity'] > 0 ? 'text-success' : 'text-danger'; ?> fw-semibold">
                                    <i class="fas fa-<?php echo $product['stock_quantity'] > 0 ? 'check-circle' : 'times-circle'; ?> me-1"></i>
                                    <?php echo $product['stock_quantity'] > 0 ? 'Available' : 'Out of Stock'; ?>
                                </small>
                            </div>
                            <a href="product-details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-search fa-4x text-muted mb-4"></i>
            <h3 class="fw-bold mb-3">No Products Found</h3>
            <p class="text-muted mb-4">We couldn't find any products matching your criteria. Try browsing other categories or check back later.</p>
            <a href="products.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>View All Products
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="py-5 mt-5">
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
                        <?php foreach($categories as $category): ?>
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
    <script src="assets/js/responsive.js"></script>
    <script>
        // Products page specific mobile optimizations
        document.addEventListener('DOMContentLoaded', function() {
            // Make filter chips more touch-friendly on mobile
            const filterChips = document.querySelectorAll('.filter-chip');
            filterChips.forEach(chip => {
                chip.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                chip.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
            
            // Optimize product grid for mobile
            function optimizeProductGrid() {
                const productGrid = document.querySelector('.row.g-4');
                if (productGrid && window.innerWidth <= 576) {
                    productGrid.classList.remove('g-4');
                    productGrid.classList.add('g-3');
                } else if (productGrid) {
                    productGrid.classList.remove('g-3');
                    productGrid.classList.add('g-4');
                }
            }
            
            optimizeProductGrid();
            window.addEventListener('resize', optimizeProductGrid);
        });
    </script>
</body>
</html>