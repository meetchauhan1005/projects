<?php
session_start();
require_once 'config/database.php';

$message = '';
$message_type = '';

if($_POST) {
    $database = new Database();
    $db = $database->getConnection();
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $msg = trim($_POST['message']);
    
    if($name && $email && $msg) {
        $query = "INSERT INTO contact_messages (name, email, phone, message) VALUES (:name, :email, :phone, :message)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $msg);
        
        if($stmt->execute()) {
            $message = 'Thank you for your message. We will get back to you soon!';
            $message_type = 'success';
        } else {
            $message = 'Sorry, there was an error sending your message.';
            $message_type = 'danger';
        }
    } else {
        $message = 'Please fill in all required fields.';
        $message_type = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Mahadev Electronic</title>
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
        
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .form-control {
            border: 2px solid var(--neutral-200);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.5rem;
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
        
        .contact-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
            color: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .alert {
            border: none;
            border-radius: 0.75rem;
            font-weight: 500;
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
        
        /* Enhanced Mobile Styles for Contact */
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
            .contact-icon {
                width: 3.5rem;
                height: 3.5rem;
                font-size: 1.3rem;
            }
            .card-body {
                padding: 1.5rem;
            }
            .form-control {
                padding: 0.75rem;
            }
            .btn {
                padding: 0.75rem 1.5rem;
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
            .contact-icon {
                width: 3rem;
                height: 3rem;
                font-size: 1.2rem;
            }
            .card-body {
                padding: 1rem;
            }
            .form-control {
                padding: 0.6rem;
                font-size: 0.9rem;
            }
            .btn {
                font-size: 0.9rem;
                padding: 0.7rem 1.2rem;
            }
            .social-btn {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 0.9rem;
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
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Personal Details</a></li>
                                <li><a class="dropdown-item" href="my-orders.php"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
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
            <h1 class="display-3 fw-bold mb-4">Get In Touch</h1>
            <p class="lead mb-0 opacity-90">We're here to help you find the perfect electronics solution for your needs</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Contact Information</h2>
            <p class="section-subtitle">Multiple ways to reach our expert team</p>
        </div>
        
        <?php if($message): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show d-flex align-items-center">
                    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="contact-icon mx-auto">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Visit Our Store</h5>
                        <p class="text-muted mb-3">
                            <strong>Mahadev Electronic</strong><br>
                            123 Electronics Market<br>
                            Main Road, City Center<br>
                            Mumbai, Maharashtra 400001
                        </p>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-2">Store Hours</h6>
                            <p class="text-muted mb-0">
                                Mon - Sat: 10:00 AM - 9:00 PM<br>
                                Sunday: 11:00 AM - 8:00 PM
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="contact-icon mx-auto">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Call or Message</h5>
                        <p class="text-muted mb-3">
                            <strong>Phone:</strong> +91 98765 43210<br>
                            <strong>WhatsApp:</strong> +91 98765 43210<br>
                            <strong>Email:</strong> info@mahadevelectronic.com
                        </p>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-3">Follow Us</h6>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="social-btn">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="social-btn">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-btn">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="contact-icon mx-auto">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Expert Support</h5>
                        <p class="text-muted mb-3">
                            Get technical assistance and product recommendations from our certified experts.
                        </p>
                        <div class="mt-4">
                            <h6 class="fw-semibold mb-2">Support Hours</h6>
                            <p class="text-muted mb-0">
                                24/7 Online Support<br>
                                Live Chat Available
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-2">Send us a Message</h3>
                            <p class="text-muted">We'll get back to you within 24 hours</p>
                        </div>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Full Name *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           placeholder="Enter your full name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email Address *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="Enter your phone number">
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment me-2"></i>Message *
                                </label>
                                <textarea class="form-control" id="message" name="message" rows="5" 
                                          placeholder="Tell us how we can help you..." required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <h6 class="fw-semibold mb-3">Products</h6>
                    <ul class="list-unstyled">
                        <li><a href="products.php?category=2" class="text-decoration-none" style="color: var(--neutral-200);">Smartphones</a></li>
                        <li><a href="products.php?category=3" class="text-decoration-none" style="color: var(--neutral-200);">Laptops</a></li>
                        <li><a href="products.php" class="text-decoration-none" style="color: var(--neutral-200);">Accessories</a></li>
                        <li><a href="products.php?category=4" class="text-decoration-none" style="color: var(--neutral-200);">Home Audio</a></li>
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
                    <div class="mb-2"><i class="fas fa-phone me-2"></i>+91 98765 43210</div>
                    <div><i class="fas fa-map-marker-alt me-2"></i>Mumbai, Maharashtra, India</div>
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
        // Contact page specific optimizations
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced form validation for mobile
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                        submitBtn.disabled = true;
                    }
                });
            }
            
            // Auto-resize textarea on mobile
            const textarea = document.querySelector('textarea');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }
        });
    </script>
</body>
</html>