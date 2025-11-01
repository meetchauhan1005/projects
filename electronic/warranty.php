<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty - Mahadev Electronic</title>
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
        
        .warranty-badge {
            background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
            color: white;
            padding: 1rem 2rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .step-number {
            width: 3rem;
            height: 3rem;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 1rem;
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
            <h1 class="display-3 fw-bold mb-4">Warranty Information</h1>
            <p class="lead mb-4 opacity-90">Comprehensive warranty coverage for your peace of mind</p>
            <div class="warranty-badge">
                <i class="fas fa-shield-alt me-2"></i>2 Year International Warranty
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Warranty Coverage -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold mb-3">Mobile Phones</h5>
                        <p class="text-muted">2 years comprehensive warranty covering hardware defects and manufacturing issues</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-laptop fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold mb-3">Laptops</h5>
                        <p class="text-muted">2 years warranty with international service support and parts replacement</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-tv fa-3x text-info mb-3"></i>
                        <h5 class="fw-bold mb-3">Electronics</h5>
                        <p class="text-muted">1-3 years warranty depending on product category and manufacturer terms</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How to Claim -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">How to Claim Warranty</h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="step-number">1</div>
                            <div class="ms-3">
                                <h5 class="fw-bold">Contact Support</h5>
                                <p class="text-muted">Reach out to our support team with your order details and issue description</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="step-number">2</div>
                            <div class="ms-3">
                                <h5 class="fw-bold">Provide Documents</h5>
                                <p class="text-muted">Submit purchase receipt, warranty card, and product photos if required</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="step-number">3</div>
                            <div class="ms-3">
                                <h5 class="fw-bold">Assessment</h5>
                                <p class="text-muted">Our technical team will assess the issue and determine warranty coverage</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="step-number">4</div>
                            <div class="ms-3">
                                <h5 class="fw-bold">Resolution</h5>
                                <p class="text-muted">We'll repair, replace, or provide store credit based on warranty terms</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warranty Terms -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Warranty Terms & Conditions</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-success mb-3"><i class="fas fa-check-circle me-2"></i>Covered</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">• Manufacturing defects</li>
                                    <li class="mb-2">• Hardware malfunctions</li>
                                    <li class="mb-2">• Component failures</li>
                                    <li class="mb-2">• Software issues (if pre-installed)</li>
                                    <li class="mb-2">• Battery performance issues</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-danger mb-3"><i class="fas fa-times-circle me-2"></i>Not Covered</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">• Physical damage or drops</li>
                                    <li class="mb-2">• Water or liquid damage</li>
                                    <li class="mb-2">• Unauthorized repairs</li>
                                    <li class="mb-2">• Normal wear and tear</li>
                                    <li class="mb-2">• Software installed by user</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="contact.php" class="btn btn-primary me-3">
                                <i class="fas fa-headset me-2"></i>Contact Support
                            </a>
                            <a href="help-center.php" class="btn btn-outline-primary">
                                <i class="fas fa-question-circle me-2"></i>Help Center
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