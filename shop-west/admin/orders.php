<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Handle order status update
if ($_POST && isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
}

$orders = $pdo->query("SELECT o.*, u.username, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav { background: #232f3e; padding: 15px 0; margin-bottom: 30px; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav h1 { color: #ff9900; margin: 0; }
        .admin-nav a { color: white; text-decoration: none; margin: 0 15px; padding: 8px 15px; border-radius: 5px; }
        .admin-nav a:hover { background: #ff9900; }
        .admin-table { background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px; }
        .admin-table h3 { background: #232f3e; color: white; padding: 20px; margin: 0; }
        .admin-table table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Orders Management</h1>
            <div>
                <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="products.php"><i class="fas fa-box"></i> Products</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../index.php"><i class="fas fa-home"></i> View Site</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="admin-table">
            <h3><i class="fas fa-list"></i> All Orders</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <span style="background: <?php echo $order['status'] == 'pending' ? '#ffc107' : '#28a745'; ?>; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>