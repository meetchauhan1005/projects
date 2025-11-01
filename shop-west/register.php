<?php
$page_title = "Register";
include 'includes/header.php';

$error = '';
$success = '';

if ($_POST) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $address])) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<main>
    <div class="form-container" style="max-width: 600px;">
        <div class="form-header">
            <div style="text-align: center; margin-bottom: 1rem;">
                <svg width="60" height="60" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="registerLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="45" fill="url(#registerLogoGradient)"/>
                    <path d="M25 35 L50 20 L75 35 L75 65 L25 65 Z" fill="white" opacity="0.9"/>
                    <rect x="42" y="45" width="16" height="12" fill="url(#registerLogoGradient)"/>
                    <circle cx="38" cy="40" r="2" fill="url(#registerLogoGradient)"/>
                    <circle cx="62" cy="40" r="2" fill="url(#registerLogoGradient)"/>
                    <path d="M35 55 Q50 45 65 55" stroke="url(#registerLogoGradient)" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <h1 class="form-title">Join Shop West</h1>
            <p class="form-subtitle">Create your account and start shopping today</p>
        </div>
        
        <?php if ($error): ?>
            <div class="form-error">
                <i class="fas fa-exclamation-circle"></i>
                <span style="margin-left: 0.5rem;"><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="form-success">
                <i class="fas fa-check-circle"></i>
                <div style="margin-left: 0.5rem;">
                    <div><?php echo htmlspecialchars($success); ?></div>
                    <a href="login.php" class="btn btn-primary btn-sm" style="margin-top: 0.5rem; display: inline-flex;">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In Now
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="registerForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i>
                        Username *
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-input" 
                           required 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                           placeholder="Choose a username">
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input" 
                           required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           placeholder="your@email.com">
                </div>
            </div>
            
            <div class="form-group">
                <label for="full_name" class="form-label">
                    <i class="fas fa-id-card"></i>
                    Full Name *
                </label>
                <input type="text" 
                       id="full_name" 
                       name="full_name" 
                       class="form-input" 
                       required 
                       value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                       placeholder="Enter your full name">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone"></i>
                        Phone Number
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                           placeholder="+1 (555) 123-4567">
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Address
                    </label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
                           placeholder="Your address">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password *
                    </label>
                    <div style="position: relative;">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input" 
                               required
                               placeholder="Create a password"
                               minlength="6">
                        <button type="button" 
                                onclick="togglePassword('password', 'passwordToggle1')" 
                                style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem;">
                            <i class="fas fa-eye" id="passwordToggle1"></i>
                        </button>
                    </div>
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-secondary);">
                        Minimum 6 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirm Password *
                    </label>
                    <div style="position: relative;">
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="form-input" 
                               required
                               placeholder="Confirm your password">
                        <button type="button" 
                                onclick="togglePassword('confirm_password', 'passwordToggle2')" 
                                style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.5rem;">
                            <i class="fas fa-eye" id="passwordToggle2"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div style="margin: 1.5rem 0;">
                <label style="display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.875rem; color: var(--text-secondary); cursor: pointer; line-height: 1.5;">
                    <input type="checkbox" name="terms" required style="width: auto; margin: 0; margin-top: 0.125rem;">
                    <span>I agree to the <a href="#" style="color: var(--primary-color); text-decoration: none;">Terms of Service</a> and <a href="#" style="color: var(--primary-color); text-decoration: none;">Privacy Policy</a></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary w-full btn-lg" id="registerBtn">
                <i class="fas fa-user-plus"></i>
                Create My Account
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Already have an account?</p>
            <a href="login.php" class="btn btn-secondary w-full">
                <i class="fas fa-sign-in-alt"></i>
                Sign In Instead
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
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'fas fa-eye';
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = getPasswordStrength(password);
    updatePasswordStrength(strength);
});

function getPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    return strength;
}

function updatePasswordStrength(strength) {
    // You can add a visual password strength indicator here
}

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const btn = document.getElementById('registerBtn');
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return;
    }
    
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
    btn.disabled = true;
    
    // Re-enable button after 5 seconds if form doesn't submit
    setTimeout(() => {
        if (btn.disabled) {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }, 5000);
});

// Real-time password confirmation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = 'var(--danger-color)';
    } else {
        this.style.borderColor = 'var(--border-color)';
    }
});
</script>

<?php include 'includes/footer.php'; ?>