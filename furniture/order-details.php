<?php
require_once 'includes/functions.php';
$page_title = 'Order Details';

if (!isLoggedIn()) {
    redirect('login.php');
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$order_id) {
    redirect('orders.php');
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
    redirect('orders.php');
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
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <a href="orders.php" class="btn btn-secondary">← Back to Orders</a>
        <h1>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h1>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Order Items -->
        <div>
            <div class="order-section">
                <h3>Order Items</h3>
                <div class="items-list">
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
            </div>
            
            <div class="order-section">
                <h3>Shipping Address</h3>
                <div class="address-box">
                    <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div>
            <div class="order-section">
                <h3>Order Summary</h3>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Order Date:</span>
                        <span><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Status:</span>
                        <span class="status-badge status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <div class="summary-row">
                        <span>Payment Method:</span>
                        <span><?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Payment Status:</span>
                        <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                            <?php echo ucfirst($order['payment_status']); ?>
                        </span>
                    </div>
                    <div class="summary-row total-row">
                        <span>Total Amount:</span>
                        <span><?php echo formatPrice($order['total_amount']); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="order-section">
                <h3>Order Status</h3>
                <div class="status-timeline">
                    <div class="status-step <?php echo in_array($order['status'], ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                        <div class="step-icon">1</div>
                        <div class="step-text">Order Placed</div>
                    </div>
                    <div class="status-step <?php echo in_array($order['status'], ['processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                        <div class="step-icon">2</div>
                        <div class="step-text">Processing</div>
                    </div>
                    <div class="status-step <?php echo in_array($order['status'], ['shipped', 'delivered']) ? 'completed' : ''; ?>">
                        <div class="step-icon">3</div>
                        <div class="step-text">Shipped</div>
                    </div>
                    <div class="status-step <?php echo $order['status'] == 'delivered' ? 'completed' : ''; ?>">
                        <div class="step-icon">4</div>
                        <div class="step-text">Delivered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.order-section h3 {
    margin-bottom: 1rem;
    color: #333;
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

.address-box {
    background: white;
    padding: 1rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.summary-details {
    background: white;
    padding: 1rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.summary-row:last-child {
    border-bottom: none;
}

.total-row {
    font-weight: bold;
    font-size: 1.1rem;
    border-top: 2px solid #e9ecef;
    margin-top: 0.5rem;
    padding-top: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #d1ecf1; color: #0c5460; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.payment-status {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.payment-pending { background: #fff3cd; color: #856404; }
.payment-paid { background: #d4edda; color: #155724; }
.payment-failed { background: #f8d7da; color: #721c24; }

.status-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-step {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.status-step.completed .step-icon {
    background: #28a745;
    color: white;
}

.step-text {
    font-weight: 500;
}

.status-step.completed .step-text {
    color: #28a745;
}

@media (max-width: 768px) {
    .container > div:first-child {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .summary-row {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>