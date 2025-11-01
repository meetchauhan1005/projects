<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping - Mahadev Electronic</title>
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
        
        .hero-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 140px 0 100px;
            color: white;
        }
        
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .shipping-badge {
            background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
            color: white;
            padding: 1rem 2rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .table th {
            background: var(--neutral-100);
            border: none;
            font-weight: 600;
        }
        
        .zone-badge {
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.875rem;
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
                    <?php if (isset($_SESSION['user_id'])): ?>
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

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-4">Shipping Information</h1>
            <p class="lead mb-4 opacity-90">Fast, secure, and reliable delivery worldwide</p>
            <div class="shipping-badge">
                <i class="fas fa-shipping-fast me-2"></i>Free Shipping on Orders ₹5,000+
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Shipping Options -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold mb-3">Standard Delivery</h5>
                        <p class="text-muted mb-3">5-7 business days</p>
                        <p class="fw-bold text-success">FREE on orders ₹5,000+</p>
                        <p class="text-muted">₹199 for orders below ₹5,000</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-shipping-fast fa-3x text-warning mb-3"></i>
                        <h5 class="fw-bold mb-3">Express Delivery</h5>
                        <p class="text-muted mb-3">2-3 business days</p>
                        <p class="fw-bold text-warning">₹499</p>
                        <p class="text-muted">Available in major cities</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-plane fa-3x text-info mb-3"></i>
                        <h5 class="fw-bold mb-3">International</h5>
                        <p class="text-muted mb-3">7-14 business days</p>
                        <p class="fw-bold text-info">Calculated at checkout</p>
                        <p class="text-muted">100+ countries supported</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Zones -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Shipping Zones & Rates</h2>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Zone</th>
                                        <th>Regions</th>
                                        <th>Standard Delivery</th>
                                        <th>Express Delivery</th>
                                        <th>Delivery Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="zone-badge bg-success text-white">Zone 1</span></td>
                                        <td>Mumbai, Delhi, Bangalore, Chennai</td>
                                        <td class="fw-bold text-success">FREE*</td>
                                        <td>₹299</td>
                                        <td>1-2 days</td>
                                    </tr>
                                    <tr>
                                        <td><span class="zone-badge bg-primary text-white">Zone 2</span></td>
                                        <td>Major Indian Cities</td>
                                        <td class="fw-bold text-success">FREE*</td>
                                        <td>₹499</td>
                                        <td>2-3 days</td>
                                    </tr>
                                    <tr>
                                        <td><span class="zone-badge bg-warning text-dark">Zone 3</span></td>
                                        <td>Other Indian Cities</td>
                                        <td class="fw-bold text-success">FREE*</td>
                                        <td>₹699</td>
                                        <td>3-5 days</td>
                                    </tr>
                                    <tr>
                                        <td><span class="zone-badge bg-info text-white">International</span></td>
                                        <td>Worldwide</td>
                                        <td>₹2,999+</td>
                                        <td>₹4,999+</td>
                                        <td>7-14 days</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <p class="text-muted mt-3 text-center">*Free shipping on orders above ₹5,000</p>
            </div>
        </div>

        <!-- Shipping Features -->
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Secure Packaging</h5>
                    <p class="text-muted">Professional packaging to ensure safe delivery</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <i class="fas fa-search-location fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Real-time Tracking</h5>
                    <p class="text-muted">Track your order from dispatch to delivery</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <i class="fas fa-clipboard-check fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">Signature Confirmation</h5>
                    <p class="text-muted">Proof of delivery for high-value items</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <i class="fas fa-headset fa-3x text-info mb-3"></i>
                    <h5 class="fw-bold">Delivery Support</h5>
                    <p class="text-muted">24/7 support for delivery-related queries</p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Important Shipping Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Orders placed before 2 PM are processed same day</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Weekend and holiday deliveries available in select cities</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>SMS and email notifications for order updates</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Delivery times may vary during peak seasons</li>
                                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Remote areas may require additional delivery time</li>
                                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>International orders subject to customs clearance</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="contact.php" class="btn btn-primary me-3">
                                <i class="fas fa-question-circle me-2"></i>Shipping Questions?
                            </a>
                            <a href="products.php" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>