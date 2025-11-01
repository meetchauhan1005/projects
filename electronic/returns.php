<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns - Mahadev Electronic</title>
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
        
        .return-badge {
            background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
            color: white;
            padding: 1rem 2rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            background: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .timeline-item::after {
            content: attr(data-step);
            position: absolute;
            left: 0.5rem;
            top: 0.25rem;
            color: white;
            font-weight: 700;
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
            <h1 class="display-3 fw-bold mb-4">Returns & Refunds</h1>
            <p class="lead mb-4 opacity-90">Easy returns with hassle-free refund process</p>
            <div class="return-badge">
                <i class="fas fa-undo me-2"></i>30-Day Return Policy
            </div>
        </div>
    </section>

    <div class="container py-5">
        <!-- Return Process -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Return Process</h2>
                
                <div class="timeline-item" data-step="1">
                    <h5 class="fw-bold">Initiate Return Request</h5>
                    <p class="text-muted">Contact our support team within 30 days of purchase to start your return process.</p>
                </div>
                
                <div class="timeline-item" data-step="2">
                    <h5 class="fw-bold">Get Return Authorization</h5>
                    <p class="text-muted">Receive a Return Merchandise Authorization (RMA) number and return shipping label.</p>
                </div>
                
                <div class="timeline-item" data-step="3">
                    <h5 class="fw-bold">Package & Ship</h5>
                    <p class="text-muted">Pack the item securely with all original accessories and ship using our prepaid label.</p>
                </div>
                
                <div class="timeline-item" data-step="4">
                    <h5 class="fw-bold">Inspection & Refund</h5>
                    <p class="text-muted">We'll inspect the item and process your refund within 5-7 business days.</p>
                </div>
            </div>
        </div>

        <!-- Return Conditions -->
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h5 class="text-success mb-3"><i class="fas fa-check-circle me-2"></i>Returnable Items</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">• Items in original condition</li>
                            <li class="mb-2">• All original packaging included</li>
                            <li class="mb-2">• Accessories and manuals included</li>
                            <li class="mb-2">• Returned within 30 days</li>
                            <li class="mb-2">• Valid proof of purchase</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h5 class="text-danger mb-3"><i class="fas fa-times-circle me-2"></i>Non-Returnable Items</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">• Damaged or used items</li>
                            <li class="mb-2">• Items without original packaging</li>
                            <li class="mb-2">• Personalized or custom items</li>
                            <li class="mb-2">• Software with opened seals</li>
                            <li class="mb-2">• Items returned after 30 days</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Refund Information</h3>
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                                    <h5 class="fw-bold">Original Payment Method</h5>
                                    <p class="text-muted">Refunds are processed to your original payment method</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                    <h5 class="fw-bold">Processing Time</h5>
                                    <p class="text-muted">5-7 business days after we receive your return</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-shipping-fast fa-3x text-info mb-3"></i>
                                    <h5 class="fw-bold">Free Return Shipping</h5>
                                    <p class="text-muted">We provide prepaid return labels for eligible returns</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Refund processing times may vary depending on your bank or payment provider. International returns may take additional time.
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="contact.php" class="btn btn-primary me-3">
                                <i class="fas fa-undo me-2"></i>Start Return Process
                            </a>
                            <a href="help-center.php" class="btn btn-outline-primary">
                                <i class="fas fa-question-circle me-2"></i>Need Help?
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