<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Mahadev Electronic</title>
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
            position: relative;
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
        
        .faq-item {
            border: 1px solid var(--neutral-200);
            border-radius: 1rem;
            margin-bottom: 1rem;
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
            <h1 class="display-3 fw-bold mb-4">Help Center</h1>
            <p class="lead mb-0 opacity-90">Find answers to your questions and get the support you need</p>
        </div>
    </section>

    <div class="container py-5">
        <!-- Quick Help -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold mb-3">24/7 Support</h5>
                        <p class="text-muted mb-4">Get instant help from our expert team</p>
                        <a href="contact.php" class="btn btn-primary">Contact Support</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-comments fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold mb-3">Live Chat</h5>
                        <p class="text-muted mb-4">Chat with our support agents in real-time</p>
                        <button class="btn btn-success">Start Chat</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="fas fa-phone fa-3x text-info mb-3"></i>
                        <h5 class="fw-bold mb-3">Call Us</h5>
                        <p class="text-muted mb-4">Speak directly with our support team</p>
                        <a href="tel:+919876543210" class="btn btn-info">+91 98765 43210</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Frequently Asked Questions</h2>
                
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I track my order?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can track your order by logging into your account and visiting the "My Orders" section. You'll receive a tracking number via email once your order ships.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                What is your return policy?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer a 30-day return policy for all products. Items must be in original condition with all accessories and packaging. <a href="returns.php">Learn more</a>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you offer international shipping?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we ship to over 100 countries worldwide. Shipping costs and delivery times vary by location. <a href="shipping.php">View shipping details</a>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We accept all major credit cards, debit cards, PayPal, UPI, and bank transfers. All payments are processed securely with SSL encryption.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                How do I claim warranty?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To claim warranty, contact our support team with your order number and product details. We'll guide you through the warranty process. <a href="warranty.php">View warranty terms</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>