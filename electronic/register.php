<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            if (!$db) {
                $error = 'Database connection failed. Please try again later.';
            } else {
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() > 0) {
                    $error = 'Email already registered';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
                    
                    if ($stmt->execute([$name, $email, $hashed_password, $phone])) {
                        $success = 'Registration successful! You can now login.';
                    } else {
                        $error = 'Failed to create account. Please try again.';
                    }
                }
            }
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), "Table") !== false) {
                $error = 'Database not set up. <a href="setup.php" style="color: #1e40af; font-weight: 600;">Click here to set up database</a>';
            } else {
                $error = 'Registration failed: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mahadev Electronic</title>
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
            background: linear-gradient(135deg, var(--neutral-50) 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 900px;
            margin: 2rem auto;
        }
        
        .auth-brand {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        
        .brand-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #fbbf24;
        }
        
        .form-container {
            padding: 3rem;
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
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
        }
        
        .alert {
            border: none;
            border-radius: 0.75rem;
            font-weight: 500;
        }
        
        .auth-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .auth-link:hover {
            color: var(--primary-dark);
        }
        
        .input-group-text {
            background: var(--neutral-100);
            border: 2px solid var(--neutral-200);
            border-right: none;
            color: var(--neutral-600);
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        
        .feature-list i {
            margin-right: 0.75rem;
            color: #fbbf24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="auth-brand h-100">
                        <div>
                            <img src="assets/images/logo.svg" alt="Mahadev Electronic" height="60" class="mb-3">
                            <h2 class="fw-bold mb-3">Mahadev Electronic</h2>
                            <p class="mb-4 opacity-90">Join thousands of customers who trust us for premium electronics worldwide</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Premium Quality Products</li>
                                <li><i class="fas fa-shipping-fast"></i> Global Shipping</li>
                                <li><i class="fas fa-shield-alt"></i> 2 Year Warranty</li>
                                <li><i class="fas fa-headset"></i> 24/7 Support</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-container">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-2">Create Your Account</h3>
                            <p class="text-muted">Start your journey with premium electronics</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= $_POST['name'] ?? '' ?>" placeholder="Enter your full name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= $_POST['email'] ?? '' ?>" placeholder="Enter your email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?= $_POST['phone'] ?? '' ?>" placeholder="Enter your phone number">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Create a strong password" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm your password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-2">Already have an account? 
                                <a href="login.php" class="auth-link">Sign in here</a>
                            </p>
                            <p class="mb-0">
                                <a href="index.php" class="auth-link">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Home
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>