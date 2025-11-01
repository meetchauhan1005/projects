<?php
$page_title = "Login";
include 'includes/header.php';

$error = '';

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<main>
    <div class="form-container">
        <div class="form-header">
            <div style="text-align: center; margin-bottom: 1rem;">
                <svg width="60" height="60" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="loginLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="45" fill="url(#loginLogoGradient)"/>
                    <path d="M25 35 L50 20 L75 35 L75 65 L25 65 Z" fill="white" opacity="0.9"/>
                    <rect x="42" y="45" width="16" height="12" fill="url(#loginLogoGradient)"/>
                    <circle cx="38" cy="40" r="2" fill="url(#loginLogoGradient)"/>
                    <circle cx="62" cy="40" r="2" fill="url(#loginLogoGradient)"/>
                    <path d="M35 55 Q50 45 65 55" stroke="url(#loginLogoGradient)" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <h1 class="form-title">Welcome Back</h1>
            <p class="form-subtitle">Sign in to your Shop West account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="form-error">
                <i class="fas fa-exclamation-circle"></i>
                <span style="margin-left: 0.5rem;"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i>
                    Username or Email
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-input" 
                       required 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       placeholder="Enter your username or email">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <div style="position: relative;">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input" 
                           required
                           placeholder="Enter your password">
                    <button type="button" 
                            onclick="togglePassword()" 
                            style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem;">
                        <i class="fas fa-eye" id="passwordToggle"></i>
                    </button>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); cursor: pointer;">
                    <input type="checkbox" name="remember" style="width: auto; margin: 0;">
                    Remember me
                </label>
                <a href="#" style="font-size: 0.875rem; color: var(--primary-color); text-decoration: none;">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary w-full" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Don't have an account?</p>
            <a href="register.php" class="btn btn-secondary w-full">
                <i class="fas fa-user-plus"></i>
                Create New Account
            </a>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
                <i class="fas fa-arrow-left"></i>
                Back to Home
            </a>
        </div>
    </div>
</main>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('passwordToggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Add form validation and loading states
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('loginBtn');
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
    btn.disabled = true;
    
    // Re-enable button after 3 seconds if form doesn't submit
    setTimeout(() => {
        if (btn.disabled) {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }, 3000);
});

// Add input focus effects
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});
</script>

<?php include 'includes/footer.php'; ?>