<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Manage Orders';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    if ($stmt->execute([$status, $order_id])) {
        $success = 'Order status updated successfully!';
    } else {
        $error = 'Error updating order status';
    }
}

// Get all orders
$query = "SELECT o.*, u.username, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Manage Orders</h1>
        <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="cart-table">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                            <td>
                                <strong><?php echo htmlspecialchars($order['username'] ?? 'Guest'); ?></strong>
                                <br>
                                <small style="color: #666;"><?php echo htmlspecialchars($order['email'] ?? ''); ?></small>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" 
                                            style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; border: 1px solid #ddd;
                                                   background: <?php echo $order['status'] == 'delivered' ? '#d4edda' : ($order['status'] == 'pending' ? '#fff3cd' : '#f8d7da'); ?>; 
                                                   color: <?php echo $order['status'] == 'delivered' ? '#155724' : ($order['status'] == 'pending' ? '#856404' : '#721c24'); ?>;">
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; 
                                             background: <?php echo $order['payment_status'] == 'paid' ? '#d4edda' : ($order['payment_status'] == 'pending' ? '#fff3cd' : '#f8d7da'); ?>; 
                                             color: <?php echo $order['payment_status'] == 'paid' ? '#155724' : ($order['payment_status'] == 'pending' ? '#856404' : '#721c24'); ?>;">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="order-details.php?id=<?php echo $order['id']; ?>" 
                                   style="padding: 0.5rem 1rem; background: #007bff; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>