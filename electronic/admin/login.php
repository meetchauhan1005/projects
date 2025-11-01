<?php
session_start();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

require_once '../config/database.php';

if(isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$max_attempts = 5;
$lockout_time = 900; // 15 minutes

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF protection
    if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid request';
    } else {
        // Rate limiting
        $ip = $_SERVER['REMOTE_ADDR'];
        $attempts_key = 'login_attempts_' . md5($ip);
        $last_attempt_key = 'last_attempt_' . md5($ip);
        
        if(!isset($_SESSION[$attempts_key])) {
            $_SESSION[$attempts_key] = 0;
        }
        
        if(isset($_SESSION[$last_attempt_key]) && 
           (time() - $_SESSION[$last_attempt_key]) < $lockout_time && 
           $_SESSION[$attempts_key] >= $max_attempts) {
            $error = 'Too many failed attempts. Please try again later.';
        } else {
            try {
                $database = new Database();
                $db = $database->getConnection();
                
                $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];
                
                if(empty($username) || empty($password)) {
                    $error = 'Please fill in all fields';
                } elseif(!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email format';
                } else {
                    $query = "SELECT id, username, email, password FROM admin_users WHERE email = ? LIMIT 1";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$username]);
                    
                    if($stmt->rowCount() === 1) {
                        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(password_verify($password, $admin['password'])) {
                            // Reset attempts on successful login
                            unset($_SESSION[$attempts_key]);
                            unset($_SESSION[$last_attempt_key]);
                            
                            // Regenerate session ID
                            session_regenerate_id(true);
                            
                            $_SESSION['admin_id'] = (int)$admin['id'];
                            $_SESSION['admin_username'] = htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8');
                            $_SESSION['admin_email'] = htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8');
                            $_SESSION['login_time'] = time();
                            
                            header('Location: dashboard.php');
                            exit();
                        } else {
                            $error = 'Invalid credentials';
                        }
                    } else {
                        $error = 'Invalid credentials';
                    }
                    
                    // Increment failed attempts
                    if($error === 'Invalid credentials') {
                        $_SESSION[$attempts_key]++;
                        $_SESSION[$last_attempt_key] = time();
                    }
                }
            } catch(PDOException $e) {
                error_log('Login PDO error: ' . $e->getMessage());
                $error = 'Database connection error. Please ensure XAMPP MySQL is running.';
            } catch(Exception $e) {
                error_log('Login error: ' . $e->getMessage());
                $error = $e->getMessage();
            }
        }
    }
}

// Generate CSRF token
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-orange: #f97316;
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
            background: linear-gradient(135deg, var(--neutral-900) 0%, var(--neutral-800) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }
        
        .auth-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            max-width: 900px;
            margin: 2rem auto;
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .auth-container {
                margin: 1rem;
                border-radius: 1rem;
            }
            
            .auth-brand {
                padding: 2rem 1.5rem;
                text-align: center;
            }
            
            .form-container {
                padding: 2rem 1.5rem;
            }
            
            .brand-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
            
            .auth-brand h2 {
                font-size: 1.5rem;
            }
            
            .admin-features {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .auth-container {
                margin: 0.5rem;
            }
            
            .auth-brand {
                padding: 1.5rem 1rem;
            }
            
            .form-container {
                padding: 1.5rem 1rem;
            }
            
            .brand-icon {
                font-size: 2.5rem;
            }
            
            .auth-brand h2 {
                font-size: 1.25rem;
            }
            
            .form-control {
                padding: 0.75rem;
            }
            
            .btn {
                padding: 0.75rem 1.5rem;
            }
            
            .quick-access {
                padding: 1rem;
                margin-top: 1rem;
            }
        }
        
        .auth-brand {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        
        .auth-brand::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="admin-pattern" width="50" height="50" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23admin-pattern)"/></svg>');
        }
        
        .brand-content {
            position: relative;
            z-index: 2;
        }
        
        .brand-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
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
        
        .btn-success {
            background: var(--accent-orange);
            color: white;
        }
        
        .btn-success:hover {
            background: #ea580c;
            transform: translateY(-2px);
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
        
        .admin-features {
            list-style: none;
            padding: 0;
        }
        
        .admin-features li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }
        
        .admin-features i {
            margin-right: 0.75rem;
            color: #fbbf24;
        }
        
        .quick-access {
            background: var(--neutral-50);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .password-toggle {
            background: transparent;
            border: none;
            color: var(--neutral-600);
            padding: 0.5rem;
        }
        
        .password-toggle:hover {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-brand h-100">
                        <div class="brand-content">
                            <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="60" class="mb-3">
                            <h2 class="fw-bold mb-3">Admin Portal</h2>
                            <p class="mb-4 opacity-90">Secure access to Mahadev Electronic management dashboard with comprehensive control over your business operations.</p>
                            <ul class="admin-features">
                                <li><i class="fas fa-chart-line"></i> Analytics & Reports</li>
                                <li><i class="fas fa-box"></i> Product Management</li>
                                <li><i class="fas fa-users"></i> Customer Support</li>
                                <li><i class="fas fa-cog"></i> System Settings</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="form-container">
                        <div class="text-center mb-4">
                            <div class="d-lg-none mb-3">
                                <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="50" class="mb-2">
                                <h4 class="fw-bold text-primary">Mahadev Electronic</h4>
                            </div>
                            <h3 class="fw-bold mb-2">Administrator Login</h3>
                            <p class="text-muted">Enter your credentials to access the dashboard</p>
                        </div>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="loginForm">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="username" name="username" 
                                       placeholder="Enter admin email" required autocomplete="username">
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter admin password" required autocomplete="current-password">
                                    <button type="button" class="password-toggle position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Access Dashboard
                            </button>
                        </form>
                        
                        <div class="quick-access">
                            <h6 class="fw-semibold mb-3">Default Credentials</h6>
                            <div class="text-muted small">
                                <p class="mb-1"><strong>Email:</strong> admin@mahadev.com</p>
                                <p class="mb-0"><strong>Password:</strong> admin123</p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                <a href="../index.php" class="auth-link">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Website
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                password.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if(!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if(!isValidEmail(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
        });
        
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
    </script>
</body>
</html>