<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// CSRF token generation
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle status updates
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    try {
        // CSRF protection
        if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new Exception('Invalid CSRF token');
        }
        
        $order_id = filter_var($_POST['order_id'] ?? 0, FILTER_VALIDATE_INT);
        $status = filter_var($_POST['status'] ?? '', FILTER_SANITIZE_STRING);
        $payment_status = filter_var($_POST['payment_status'] ?? '', FILTER_SANITIZE_STRING);
        
        // Validate inputs
        $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $valid_payment_statuses = ['pending', 'paid', 'failed'];
        
        if(!$order_id || $order_id <= 0) {
            throw new Exception('Invalid order ID');
        }
        
        if(!in_array($status, $valid_statuses, true)) {
            throw new Exception('Invalid status');
        }
        
        if(!in_array($payment_status, $valid_payment_statuses, true)) {
            throw new Exception('Invalid payment status');
        }
        
        // Check if order exists
        $check = $db->prepare("SELECT id FROM orders WHERE id = ?");
        $check->execute([$order_id]);
        if($check->rowCount() === 0) {
            throw new Exception('Order not found');
        }
        
        $update = $db->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE id = ?");
        if($update->execute([$status, $payment_status, $order_id])) {
            $_SESSION['success_message'] = 'Order updated successfully!';
        } else {
            throw new Exception('Failed to update order');
        }
        
        header('Location: orders.php');
        exit();
    } catch(Exception $e) {
        error_log('Order update error: ' . $e->getMessage());
        $_SESSION['error_message'] = 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header('Location: orders.php');
        exit();
    }
}

// Get orders
try {
    $query = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    error_log('Orders fetch error: ' . $e->getMessage());
    $orders = [];
}

// Get order statistics
try {
    $stats_query = "SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
        COALESCE(SUM(total_amount), 0) as total_revenue
        FROM orders";
    $stats_stmt = $db->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    error_log('Stats fetch error: ' . $e->getMessage());
    $stats = ['total_orders' => 0, 'pending_orders' => 0, 'processing_orders' => 0, 'shipped_orders' => 0, 'delivered_orders' => 0, 'total_revenue' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); min-height: 100vh; position: fixed; width: 280px; }
        .main-content { margin-left: 280px; padding: 20px; }
        .nav-link { color: #bdc3c7; padding: 15px 25px; border-radius: 0; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(52, 152, 219, 0.2); color: #fff; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .stats-card { background: linear-gradient(135deg, #3498db, #2980b9); color: white; border-radius: 15px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-4 text-white border-bottom">
            <div class="d-flex align-items-center mb-2">
                <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="35" class="me-2">
                <h5 class="mb-0">Mahadev Electronic</h5>
            </div>
            <small class="text-light">Admin Dashboard</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link" href="products.php">
                <i class="fas fa-box me-3"></i> Products
            </a>
            <a class="nav-link" href="categories.php">
                <i class="fas fa-tags me-3"></i> Categories
            </a>
            <a class="nav-link active" href="orders.php">
                <i class="fas fa-shopping-cart me-3"></i> Orders
            </a>
            <a class="nav-link" href="messages.php">
                <i class="fas fa-envelope me-3"></i> Messages
            </a>
            <hr class="text-light mx-3">
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt me-3"></i> View Website
            </a>
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt me-3"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Orders Management</h1>
                <p class="text-muted">Manage customer orders and track deliveries</p>
            </div>
        </div>
        
        <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); endif; ?>
        
        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-shopping-cart fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo $stats['total_orders']; ?></h4>
                        <small>Total Orders</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-clock fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo $stats['pending_orders']; ?></h4>
                        <small>Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-cog fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo $stats['processing_orders']; ?></h4>
                        <small>Processing</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-truck fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo $stats['shipped_orders']; ?></h4>
                        <small>Shipped</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-check fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo $stats['delivered_orders']; ?></h4>
                        <small>Delivered</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <div class="card-body text-center p-3">
                        <i class="fas fa-rupee-sign fa-2x mb-2 opacity-75"></i>
                        <h4 class="mb-1"><?php echo number_format($stats['total_revenue']); ?></h4>
                        <small>Revenue</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="fas fa-list text-primary me-2"></i>All Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Order ID</th>
                                <th class="border-0">Customer</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Payment</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?php echo str_pad((int)$order['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($order['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['customer_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">₹<?php echo number_format(max(0, (float)($order['total_amount'] ?? 0))); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        $status = $order['status'] ?? 'pending';
                                        echo $status == 'pending' ? 'warning' : 
                                            ($status == 'processing' ? 'info' : 
                                            ($status == 'shipped' ? 'primary' : 
                                            ($status == 'delivered' ? 'success' : 'danger'))); 
                                    ?>">
                                        <?php echo htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $payment_status = $order['payment_status'] ?? 'pending'; ?>
                                    <span class="badge bg-<?php echo $payment_status == 'paid' ? 'success' : ($payment_status == 'failed' ? 'danger' : 'warning'); ?>">
                                        <?php echo htmlspecialchars(ucfirst($payment_status), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?php echo $order['created_at'] ? htmlspecialchars(date('M j, Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') : 'N/A'; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $order['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal<?php echo $order['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Order #<?php echo str_pad((int)$order['id'], 4, '0', STR_PAD_LEFT); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Order Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Status</label>
                                                    <select name="payment_status" class="form-select" required>
                                                        <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="paid" <?php echo $order['payment_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                                        <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="update_status" class="btn btn-primary">Update Order</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>