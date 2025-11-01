<?php
require_once 'includes/functions.php';
$page_title = 'Login';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!checkRateLimit('login', 5, 300)) {
        $error = 'Too many login attempts. Please try again later.';
    } else {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $username]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                redirect(isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php');
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'User not found';
        }
    }
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 2rem;">Login to Your Account</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn" style="width: 100%;">Login</button>
            </div>
        </form>
        
        <div style="text-align: center; margin-top: 2rem;">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="forgot-password.php">Forgot your password?</a></p>
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee;">
                <p style="color: #666; font-size: 0.9rem;">Administrator?</p>
                <a href="admin/login.php" style="color: #8B4513; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-shield-alt"></i> Admin Login
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>