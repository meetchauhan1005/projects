<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get user stats
    $stats = $db->prepare("SELECT COUNT(*) as orders, COALESCE(SUM(total_amount), 0) as spent FROM orders WHERE user_id = ? OR customer_email = ?");
    $stats->execute([$_SESSION['user_id'], $_SESSION['user_email']]);
    $user_stats = $stats->fetch(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $user_stats = ['orders' => 0, 'spent' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .welcome-card { background: white; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .user-avatar { width: 80px; height: 80px; background: linear-gradient(45deg, #1e40af, #7c3aed); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600; }
        .stat-card { background: #f8fafc; border-radius: 1rem; padding: 1.5rem; text-align: center; }
        .stat-number { font-size: 1.5rem; font-weight: 700; color: #1e40af; }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="welcome-card p-5 text-center" style="max-width: 600px;">
            <div class="user-avatar mx-auto mb-4">
                <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
            </div>
            
            <h2 class="mb-2">Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>! 🎉</h2>
            <p class="text-muted mb-4">Login successful! Here's your profile information:</p>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="stat-card">
                        <i class="fas fa-user text-primary mb-2"></i>
                        <div class="fw-bold"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                        <small class="text-muted">Full Name</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <i class="fas fa-envelope text-primary mb-2"></i>
                        <div class="fw-bold"><?= htmlspecialchars($_SESSION['user_email']) ?></div>
                        <small class="text-muted">Email Address</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <i class="fas fa-shopping-bag text-primary mb-2"></i>
                        <div class="stat-number"><?= $user_stats['orders'] ?></div>
                        <small class="text-muted">Total Orders</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <i class="fas fa-rupee-sign text-primary mb-2"></i>
                        <div class="stat-number">₹<?= number_format($user_stats['spent']) ?></div>
                        <small class="text-muted">Total Spent</small>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-3 justify-content-center">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Continue Shopping
                </a>
                <a href="profile.php" class="btn btn-success">
                    <i class="fas fa-user-circle me-2"></i>Profile Section
                </a>
                <a href="my-orders.php" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Auto redirect after 10 seconds
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 10000);
    </script>
</body>
</html>