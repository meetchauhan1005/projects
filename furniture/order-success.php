<?php
require_once 'includes/functions.php';
$page_title = 'Order Confirmation';

if (!isLoggedIn()) {
    redirect('login.php');
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    redirect('index.php');
}

$database = new Database();
$db = $database->getConnection();

// Get order details
$query = "SELECT o.*, u.full_name, u.email 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = ? AND o.user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    redirect('index.php');
}

// Get order items
$query = "SELECT oi.*, p.name, p.image 
          FROM order_items oi 
          JOIN products p ON oi.product_id = p.id 
          WHERE oi.order_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your order. We'll send you a confirmation email shortly.</p>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <div class="detail-row">
                <span>Order ID:</span>
                <span>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="detail-row">
                <span>Order Date:</span>
                <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
            </div>
            <div class="detail-row">
                <span>Total Amount:</span>
                <span><?php echo formatPrice($order['total_amount']); ?></span>
            </div>
            <div class="detail-row">
                <span>Payment Method:</span>
                <span><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
            </div>
            <div class="detail-row">
                <span>Status:</span>
                <span class="status-badge"><?php echo ucfirst($order['status']); ?></span>
            </div>
        </div>
        
        <div class="shipping-info">
            <h3>Shipping Address</h3>
            <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
        </div>
        
        <div class="order-items">
            <h3>Items Ordered</h3>
            <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <img src="<?php echo getImagePath($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="item-info">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Price: <?php echo formatPrice($item['price']); ?></p>
                    </div>
                    <div class="item-total">
                        <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="action-buttons">
            <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="user/profile.php" class="btn">View Orders</a>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    padding: 2rem;
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.success-container h1 {
    color: #28a745;
    margin-bottom: 0.5rem;
}

.success-container > p {
    color: #666;
    margin-bottom: 2rem;
}

.order-details, .shipping-info, .order-items {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    text-align: left;
}

.order-details h3, .shipping-info h3, .order-items h3 {
    margin-bottom: 1rem;
    color: #333;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.detail-row:last-child {
    border-bottom: none;
}

.status-badge {
    background: #007bff;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    text-transform: uppercase;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.item-info {
    flex: 1;
}

.item-info h4 {
    margin: 0 0 0.5rem 0;
}

.item-info p {
    margin: 0.25rem 0;
    color: #666;
    font-size: 0.9rem;
}

.item-total {
    font-weight: bold;
    font-size: 1.1rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>