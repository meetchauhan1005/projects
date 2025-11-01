<?php
$page_title = "My Profile";
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get user orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$success = '';
$error = '';

if ($_POST) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if (empty($full_name)) {
        $error = "Full name is required.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
        if ($stmt->execute([$full_name, $phone, $address, $_SESSION['user_id']])) {
            $success = "Profile updated successfully!";
            $_SESSION['full_name'] = $full_name;
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<main>
    <div class="container" style="padding: 40px 20px; max-width: 800px; margin: 0 auto;">
        <!-- Profile Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 50px 40px; border-radius: 20px; margin-bottom: 40px; text-align: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: inline-block; width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; backdrop-filter: blur(10px);">
                    <i class="fas fa-user" style="font-size: 50px;"></i>
                </div>
                <h1 style="margin: 0; font-size: 2.8rem; font-weight: 700;"><?php echo htmlspecialchars($user['full_name'] ?? 'User Profile'); ?></h1>
                <p style="margin: 15px 0 0 0; opacity: 0.9; font-size: 1.1rem;">@<?php echo htmlspecialchars($user['username'] ?? 'username'); ?></p>
            </div>
        </div>
        
        <!-- Single Column Form -->
        <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 50px; border: 1px solid #f0f0f0;">
            <div style="text-align: center; margin-bottom: 40px;">
                <i class="fas fa-user-edit" style="font-size: 3rem; color: #667eea; margin-bottom: 20px;"></i>
                <h2 style="margin: 0; color: #2c3e50; font-size: 2.2rem; font-weight: 600;">Profile Information</h2>
                <p style="color: #6c757d; margin: 10px 0 0 0;">Update your personal details below</p>
            </div>
            
            <?php if ($error): ?>
                <div style="background: linear-gradient(135deg, #ff6b6b, #ee5a52); color: white; padding: 20px 25px; border-radius: 12px; margin-bottom: 30px; display: flex; align-items: center; box-shadow: 0 8px 25px rgba(255,107,107,0.3);">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 15px; font-size: 20px;"></i>
                    <span style="font-weight: 500;"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: linear-gradient(135deg, #51cf66, #40c057); color: white; padding: 20px 25px; border-radius: 12px; margin-bottom: 30px; display: flex; align-items: center; box-shadow: 0 8px 25px rgba(81,207,102,0.3);">
                    <i class="fas fa-check-circle" style="margin-right: 15px; font-size: 20px;"></i>
                    <span style="font-weight: 500;"><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                    <div class="form-group">
                        <label for="username" style="color: #2c3e50; font-weight: 600; margin-bottom: 12px; display: block; font-size: 15px;">Username</label>
                        <div style="position: relative;">
                            <i class="fas fa-user" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #adb5bd; font-size: 16px;"></i>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" readonly style="background: #f8f9fa; padding: 18px 20px 18px 55px; border: 2px solid #e9ecef; border-radius: 12px; width: 100%; font-size: 15px; color: #6c757d;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" style="color: #2c3e50; font-weight: 600; margin-bottom: 12px; display: block; font-size: 15px;">Email Address</label>
                        <div style="position: relative;">
                            <i class="fas fa-envelope" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #adb5bd; font-size: 16px;"></i>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly style="background: #f8f9fa; padding: 18px 20px 18px 55px; border: 2px solid #e9ecef; border-radius: 12px; width: 100%; font-size: 15px; color: #6c757d;">
                        </div>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="full_name" style="color: #2c3e50; font-weight: 600; margin-bottom: 12px; display: block; font-size: 15px;">Full Name <span style="color: #e74c3c;">*</span></label>
                    <div style="position: relative;">
                        <i class="fas fa-id-card" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #667eea; font-size: 16px;"></i>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" style="padding: 18px 20px 18px 55px; border: 2px solid #e9ecef; border-radius: 12px; width: 100%; font-size: 15px; transition: all 0.3s; background: white;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="phone" style="color: #2c3e50; font-weight: 600; margin-bottom: 12px; display: block; font-size: 15px;">Phone Number</label>
                    <div style="position: relative;">
                        <i class="fas fa-phone" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #667eea; font-size: 16px;"></i>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" style="padding: 18px 20px 18px 55px; border: 2px solid #e9ecef; border-radius: 12px; width: 100%; font-size: 15px; transition: all 0.3s; background: white;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 40px;">
                    <label for="address" style="color: #2c3e50; font-weight: 600; margin-bottom: 12px; display: block; font-size: 15px;">Address</label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt" style="position: absolute; left: 20px; top: 20px; color: #667eea; font-size: 16px;"></i>
                        <textarea id="address" name="address" rows="4" style="padding: 18px 20px 18px 55px; border: 2px solid #e9ecef; border-radius: 12px; width: 100%; font-size: 15px; resize: vertical; transition: all 0.3s; background: white; min-height: 120px;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'" onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 50px; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 25px rgba(102,126,234,0.3); min-width: 200px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 35px rgba(102,126,234,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.3)'">
                        <i class="fas fa-save" style="margin-right: 12px; font-size: 16px;"></i>
                        Update Profile
                    </button>
                </div>
            </form>
            
            <!-- Quick Actions -->
            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #f8f9fa; text-align: center;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">Quick Actions</h3>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="products.php" style="background: #28a745; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-shopping-bag" style="margin-right: 8px;"></i>
                        Shop Now
                    </a>
                    <a href="cart.php" style="background: #17a2b8; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>
                        View Cart
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Order History Section -->
        <?php if ($orders): ?>
        <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); padding: 50px; border: 1px solid #f0f0f0; margin-top: 30px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <i class="fas fa-history" style="font-size: 3rem; color: #667eea; margin-bottom: 20px;"></i>
                <h2 style="margin: 0; color: #2c3e50; font-size: 2.2rem; font-weight: 600;">Order History</h2>
                <p style="color: #6c757d; margin: 10px 0 0 0;">Track your recent purchases</p>
            </div>
            
            <div style="display: grid; gap: 20px;">
                <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                <div style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border: 2px solid #e9ecef; border-radius: 15px; padding: 25px; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-receipt" style="color: #667eea; margin-right: 15px; font-size: 20px;"></i>
                            <div>
                                <strong style="color: #2c3e50; font-size: 18px;">Order #<?php echo $order['id']; ?></strong>
                                <p style="margin: 5px 0 0 0; color: #6c757d;"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></p>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="background: linear-gradient(135deg, #51cf66, #40c057); color: white; padding: 8px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">
                                <?php echo ucfirst($order['status']); ?>
                            </div>
                            <strong style="color: #28a745; font-size: 18px;">₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($orders) > 3): ?>
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #6c757d; margin-bottom: 20px;">Showing 3 of <?php echo count($orders); ?> orders</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>