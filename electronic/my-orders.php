<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-shopping-bag me-2"></i>Your Orders</h4>
                    </div>
                    <div class="card-body">
                        <?php if(count($orders) > 0): ?>
                            <?php foreach($orders as $order): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="card-title">Order #<?= $order['id'] ?></h6>
                                            <p class="card-text mb-1"><strong>Amount:</strong> ₹<?= number_format($order['total_amount']) ?></p>
                                            <p class="card-text mb-1"><strong>Date:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?></p>
                                            <p class="card-text mb-1"><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-<?= $order['order_status'] == 'delivered' ? 'success' : ($order['order_status'] == 'cancelled' ? 'danger' : 'warning') ?> mb-2">
                                                <?= ucfirst($order['order_status']) ?>
                                            </span><br>
                                            <span class="badge bg-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'failed' ? 'danger' : 'secondary') ?>">
                                                Payment: <?= ucfirst($order['payment_status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>No orders found. Start shopping to see your orders here.
                            </div>
                        <?php endif; ?>
                        <a href="products.php" class="btn btn-primary">Browse Products</a>
                        <a href="index.php" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>