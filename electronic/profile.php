<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$message = '';
$message_type = '';

// Handle profile update
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        if($name && $email) {
            $query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            
            if($stmt->execute([$name, $email, $phone, $_SESSION['user_id']])) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $message = 'Profile updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error updating profile.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Name and email are required.';
            $message_type = 'warning';
        }
    } catch(Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $message_type = 'danger';
    }
}

// Get user data
try {
    // Check if profile_photo column exists
    $check_column = $db->query("SHOW COLUMNS FROM users LIKE 'profile_photo'");
    $has_photo_column = $check_column->rowCount() > 0;
    
    if($has_photo_column) {
        $query = "SELECT * FROM users WHERE id = ?";
    } else {
        $query = "SELECT id, name, email, phone, created_at FROM users WHERE id = ?";
    }
    
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$has_photo_column) {
        $user['profile_photo'] = null;
    }
} catch(Exception $e) {
    $user = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Details - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--neutral-200);
        }
        
        .profile-container {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            position: relative;
            z-index: 2;
        }
        
        .profile-content {
            position: relative;
            z-index: 2;
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
            background: linear-gradient(45deg, var(--primary-blue), var(--primary-dark));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
        }
        
        .info-card {
            background: var(--neutral-50);
            border-radius: 1rem;
            padding: 1.5rem;
            border-left: 4px solid var(--accent-orange);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid var(--neutral-200);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }
        
        .stat-label {
            color: var(--neutral-600);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logo.svg" alt="Mahadev Electronic" height="45" class="me-2">
                <span class="fw-bold">Mahadev Electronic</span>
            </a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
                <a href="my-orders.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 120px; margin-bottom: 60px;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-content">
                            <div class="profile-avatar">
                                <?php if($user['profile_photo'] ?? false): ?>
                                    <img src="assets/images/profiles/<?php echo htmlspecialchars($user['profile_photo']); ?>" 
                                         alt="Profile Photo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="mb-1"><?php echo htmlspecialchars($user['name'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="mb-0 opacity-90"><?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                    <div class="p-4">
                        <?php if($message): ?>
                            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
                                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Account Stats -->
                        <div class="stats-grid">
                            <?php
                            try {
                                $order_count = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? OR customer_email = ?");
                                $order_count->execute([$_SESSION['user_id'], $_SESSION['user_email']]);
                                $total_orders = $order_count->fetchColumn();
                                
                                $total_spent = $db->prepare("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE (user_id = ? OR customer_email = ?) AND payment_status = 'paid'");
                                $total_spent->execute([$_SESSION['user_id'], $_SESSION['user_email']]);
                                $spent = $total_spent->fetchColumn();
                            } catch(Exception $e) {
                                $total_orders = 0;
                                $spent = 0;
                            }
                            ?>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $total_orders; ?></div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">₹<?php echo number_format($spent); ?></div>
                                <div class="stat-label">Total Spent</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $user['created_at'] ? date('M Y', strtotime($user['created_at'])) : 'N/A'; ?></div>
                                <div class="stat-label">Member Since</div>
                            </div>
                        </div>
                        
                        <!-- Profile Form -->
                        <div class="info-card mb-4">
                            <h5 class="mb-3"><i class="fas fa-edit me-2"></i>Edit Profile Information</h5>
                            <form method="POST">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                                        <input type="text" class="form-control" name="name" 
                                               value="<?php echo htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-phone me-2"></i>Phone Number</label>
                                        <input type="tel" class="form-control" name="phone" 
                                               value="<?php echo htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fas fa-calendar me-2"></i>Account Created</label>
                                        <input type="text" class="form-control" 
                                               value="<?php echo $user['created_at'] ? date('M j, Y g:i A', strtotime($user['created_at'])) : 'N/A'; ?>" readonly>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>