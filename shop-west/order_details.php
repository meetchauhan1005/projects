<?php
// Error handling to prevent blinking
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

$page_title = "Order Details";
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: profile.php');
    exit;
}

$order_id = (int)$_GET['id'];

try {
    // Get order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch();
    
    if (!$order) {
        header('Location: profile.php');
        exit;
    }
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.*, p.name, p.image 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll();
} catch (Exception $e) {
    header('Location: profile.php');
    exit;
}
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <nav style="margin-bottom: 30px;">
            <a href="profile.php">My Profile</a> > Order Details
        </nav>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee;">
                    <div>
                        <h1>Order #<?php echo $order_id; ?></h1>
                        <p style="color: #666; margin: 5px 0;">
                            Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                        </p>
                    </div>
                    <div>
                        <span style="background: #28a745; color: white; padding: 8px 15px; border-radius: 20px; font-weight: bold;">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>
                
                <h3>Items Ordered</h3>
                <div style="margin-bottom: 30px;">
                    <?php foreach ($order_items as $item): ?>
                    <div style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                        <img src="assets/uploads/<?php echo $item['image'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 20px;"
                             onerror="this.src='https://via.placeholder.com/80x80?text=<?php echo urlencode($item['name']); ?>'">
                        
                        <div style="flex: 1;">
                            <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p style="color: #666; margin: 0;">
                                Quantity: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                            </p>
                        </div>
                        
                        <div style="font-weight: bold; font-size: 1.1rem;">
                            ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 20px 0; font-size: 1.3rem; font-weight: bold; border-top: 2px solid #232f3e;">
                    <span>Total Amount:</span>
                    <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h3>Shipping Address</h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="profile.php" class="btn">Back to Profile</a>
                    <a href="products.php" class="btn" style="background: #28a745; margin-left: 15px;">Shop Again</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>