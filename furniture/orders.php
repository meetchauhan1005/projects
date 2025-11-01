<?php
require_once 'includes/functions.php';
$page_title = 'My Orders';

if (!isLoggedIn()) {
    redirect('login.php?redirect=orders.php');
}

$database = new Database();
$db = $database->getConnection();

// Get user orders
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 4rem 0;">
            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
            <h3>No orders yet</h3>
            <p>Start shopping to see your orders here!</p>
            <a href="products.php" class="btn">Shop Now</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3>Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h3>
                            <p>Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-details">
                        <div class="detail-item">
                            <strong>Total: <?php echo formatPrice($order['total_amount']); ?></strong>
                        </div>
                        <div class="detail-item">
                            Payment: <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?>
                        </div>
                        <div class="detail-item">
                            Status: <?php echo ucfirst($order['payment_status']); ?>
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

.order-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-header h3 {
    margin: 0 0 0.5rem 0;
    color: #333;
}

.order-header p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
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

.order-details {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.detail-item {
    color: #666;
    font-size: 0.9rem;
}

.order-actions {
    display: flex;
    gap: 1rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-details {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>