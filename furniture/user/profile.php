<?php
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$page_title = 'My Profile';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Get user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    
    if (empty($full_name)) {
        $error = 'Full name is required';
    } else {
        $query = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$full_name, $phone, $address, $_SESSION['user_id']])) {
            $success = 'Profile updated successfully!';
            // Refresh user data
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Error updating profile';
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'All password fields are required';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters';
    } elseif (!password_verify($current_password, $user['password'])) {
        $error = 'Current password is incorrect';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
            $success = 'Password changed successfully!';
        } else {
            $error = 'Error changing password';
        }
    }
}

// Get user orders
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Adjust paths for includes
$base_path = '../';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="container">
    <div class="profile-container">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p style="color: #666;"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <nav style="list-style: none;">
                <a href="#profile" onclick="showTab('profile')" class="profile-tab active" style="display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; border-radius: 5px; margin-bottom: 0.5rem;">
                    <i class="fas fa-user"></i> Profile Info
                </a>
                <a href="#password" onclick="showTab('password')" class="profile-tab" style="display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; border-radius: 5px; margin-bottom: 0.5rem;">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="#orders" onclick="showTab('orders')" class="profile-tab" style="display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; border-radius: 5px; margin-bottom: 0.5rem;">
                    <i class="fas fa-shopping-bag"></i> My Orders
                </a>
                <a href="../cart.php" style="display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; border-radius: 5px; margin-bottom: 0.5rem;">
                    <i class="fas fa-shopping-cart"></i> Shopping Cart
                </a>
                <a href="../logout.php" style="display: block; padding: 0.75rem 1rem; color: #dc3545; text-decoration: none; border-radius: 5px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Profile Info Tab -->
            <div id="profile-tab" class="tab-content">
                <div class="profile-content">
                    <h2 style="color: #8B4513; margin-bottom: 2rem;">Profile Information</h2>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background: #f8f9fa;">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: #f8f9fa;">
                        </div>
                        
                        <div class="form-group">
                            <label for="full_name">Full Name: *</label>
                            <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password Tab -->
            <div id="password-tab" class="tab-content" style="display: none;">
                <div class="profile-content">
                    <h2 style="color: #8B4513; margin-bottom: 2rem;">Change Password</h2>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="current_password">Current Password: *</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password: *</label>
                            <input type="password" id="new_password" name="new_password" required>
                            <small>Minimum 6 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password: *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn">Change Password</button>
                    </form>
                </div>
            </div>
            
            <!-- Orders Tab -->
            <div id="orders-tab" class="tab-content" style="display: none;">
                <div class="profile-content">
                    <h2 style="color: #8B4513; margin-bottom: 2rem;">Recent Orders</h2>
                    
                    <div style="margin-bottom: 1rem;">
                        <a href="../orders.php" class="btn btn-secondary">View All Orders</a>
                    </div>
                    
                    <?php if (empty($recent_orders)): ?>
                        <div style="text-align: center; padding: 3rem 0;">
                            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <h3>No orders yet</h3>
                            <p>Start shopping to see your orders here!</p>
                            <a href="../products.php" class="btn">Browse Products</a>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td>
                                                <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                                             background: <?php echo $order['status'] == 'delivered' ? '#28a745' : ($order['status'] == 'pending' ? '#ffc107' : '#6c757d'); ?>; 
                                                             color: white;">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                                             background: <?php echo $order['payment_status'] == 'paid' ? '#28a745' : ($order['payment_status'] == 'pending' ? '#ffc107' : '#dc3545'); ?>; 
                                                             color: white;">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-tab:hover, .profile-tab.active {
    background: #8B4513;
    color: white !important;
}

.tab-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.profile-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.profile-sidebar {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    height: fit-content;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: #8B4513;
    border-radius: 50%;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: bold;
}

.profile-content {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .profile-container {
        grid-template-columns: 1fr !important;
        gap: 1rem;
    }
    
    .profile-sidebar {
        order: 2;
    }
}
</style>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.profile-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').style.display = 'block';
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>