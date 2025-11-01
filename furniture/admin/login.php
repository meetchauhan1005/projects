<?php
require_once '../includes/functions.php';
$page_title = 'Admin Login';

if (isLoggedIn() && isAdmin()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        if ($email === 'admin@gmail.com' && $password === 'admin123') {
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';
            redirect('dashboard.php');
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Interno</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .admin-login-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .admin-logo {
            font-size: 3rem;
            color: #8B4513;
            margin-bottom: 1rem;
        }
        .admin-title {
            color: #333;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: opacity 0.3s;
        }
        .back-link:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <a href="../index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Site
    </a>

    <div class="admin-login-container">
        <div class="admin-logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="admin-title">Admin Panel</h1>
        <p style="color: #666; margin-bottom: 2rem;">Please login to access the admin dashboard</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom: 2rem;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group" style="text-align: left;">
                <label for="email">Admin Email:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="admin@gmail.com">
            </div>
            
            <div class="form-group" style="text-align: left;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter admin password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                    <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                </button>
            </div>
        </form>
        
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #eee;">
            <p style="color: #666; font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Admin access only
            </p>
            <a href="../login.php" style="color: #8B4513; text-decoration: none;">
                Regular user login
            </a>
        </div>
    </div>
</body>
</html>