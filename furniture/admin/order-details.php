<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Order Details';
$order_id = (int)$_GET['id'];

$database = new Database();
$db = $database->getConnection();

// Get order details
$query = "SELECT o.*, u.username, u.email, u.full_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    redirect('orders.php');
}

// Get order items
$query = "SELECT oi.*, p.name as product_name, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Order #<?php echo $order['id']; ?></h1>
        <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Order Items -->
        <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="color: #8B4513; margin-bottom: 1.5rem;">Order Items</h2>
            <div style="overflow-x: auto;">
                <table class="cart-table">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <img src="../assets/images/products/<?php echo $item['image']; ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Info -->
        <div>
            <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 2rem;">
                <h3 style="color: #8B4513; margin-bottom: 1rem;">Order Information</h3>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Order ID:</strong> #<?php echo $order['id']; ?></div>
                    <div><strong>Date:</strong> <?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></div>
                    <div><strong>Status:</strong> 
                        <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; 
                                     background: <?php echo $order['status'] == 'delivered' ? '#d4edda' : ($order['status'] == 'pending' ? '#fff3cd' : '#f8d7da'); ?>; 
                                     color: <?php echo $order['status'] == 'delivered' ? '#155724' : ($order['status'] == 'pending' ? '#856404' : '#721c24'); ?>;">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <div><strong>Payment:</strong> 
                        <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; 
                                     background: <?php echo $order['payment_status'] == 'paid' ? '#d4edda' : '#fff3cd'; ?>; 
                                     color: <?php echo $order['payment_status'] == 'paid' ? '#155724' : '#856404'; ?>;">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                    <div><strong>Total:</strong> <span style="font-size: 1.2rem; color: #8B4513;"><?php echo formatPrice($order['total_amount']); ?></span></div>
                </div>
            </div>

            <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <h3 style="color: #8B4513; margin-bottom: 1rem;">Customer Information</h3>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?></div>
                    <div><strong>Username:</strong> <?php echo htmlspecialchars($order['username'] ?? 'Guest'); ?></div>
                    <div><strong>Email:</strong> <?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?></div>
                    <div><strong>Address:</strong> <?php echo htmlspecialchars($order['shipping_address'] ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>