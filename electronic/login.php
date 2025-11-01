<?php
session_start();
require_once 'config/database.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if($email && $password) {
        try {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$email]);
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_phone'] = $user['phone'];
                    $_SESSION['user_created'] = $user['created_at'];
                    header('Location: index.php');
                    exit();
                } else {
                    $error = 'Invalid password';
                }
            } else {
                $error = 'User not found';
            }
        } catch(Exception $e) {
            $error = 'Login error. Please try again.';
        }
    } else {
        $error = 'Please fill all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mahadev Electronic</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        
        .login-header {
            background: white;
            padding: 3rem 3rem 2rem;
            text-align: center;
            border-bottom: 1px solid var(--neutral-100);
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: var(--neutral-600);
            font-size: 1rem;
        }
        
        .login-body {
            padding: 2.5rem 3rem 3rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.75rem;
        }
        
        .form-control {
            border: 2px solid var(--neutral-200);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
            background: white;
        }
        
        .btn {
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            border: none;
            transition: all 0.3s ease;
            font-size: 1rem;
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
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            background: transparent;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
            transform: translateY(-2px);
        }
        
        .alert {
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .demo-info {
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .demo-title {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .demo-credentials {
            background: white;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--neutral-700);
            border: 1px solid var(--neutral-200);
        }
        
        @media (max-width: 768px) {
            .login-header, .login-body {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="login-card">
                    <div class="login-header">
                        <img src="assets/images/logo.svg" alt="Mahadev Electronic" height="60" class="mb-3">
                        <h2 class="login-title">Welcome Back</h2>
                        <p class="login-subtitle">Sign in to your account</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>
                        

                        

                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>