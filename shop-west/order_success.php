<?php
$page_title = "Order Confirmation";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = (int)$_GET['order_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.full_name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>

<main>
    <div class="container" style="padding: 40px 20px; text-align: center;">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="background: #d4edda; color: #155724; padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 20px;"></i>
                <h1>Order Placed Successfully!</h1>
                <p>Thank you for your order. We'll send you a confirmation email shortly.</p>
            </div>
            
            <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; text-align: left;">
                <h2>Order Details</h2>
                
                <div style="margin-bottom: 20px;">
                    <strong>Order ID:</strong> #<?php echo $order_id; ?><br>
                    <strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?><br>
                    <strong>Status:</strong> <span style="color: #28a745;">Confirmed</span>
                </div>
                
                <h3>Items Ordered:</h3>
                <?php foreach ($order_items as $item): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                        <small>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?></small>
                    </div>
                    <div>
                        ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 1.2rem; font-weight: bold; border-top: 2px solid #232f3e; margin-top: 15px;">
                    <span>Total:</span>
                    <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                
                <div style="margin-top: 20px;">
                    <strong>Shipping Address:</strong><br>
                    <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                </div>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="products.php" class="btn" style="margin-right: 15px;">Continue Shopping</a>
                <a href="profile.php" class="btn" style="background: #17a2b8;">View Orders</a>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>