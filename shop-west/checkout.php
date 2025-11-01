<?php
$page_title = "Checkout";
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.quantity, p.id, p.name, p.price, p.stock_quantity
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

if (!$cart_items) {
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$error = '';
$success = '';

if ($_POST) {
    $shipping_address = trim($_POST['shipping_address']);
    
    if (empty($shipping_address)) {
        $error = "Please provide a shipping address.";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $total, $shipping_address]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items and update stock
            foreach ($cart_items as $item) {
                // Check stock again
                $stmt = $pdo->prepare("SELECT stock_quantity FROM products WHERE id = ?");
                $stmt->execute([$item['id']]);
                $current_stock = $stmt->fetchColumn();
                
                if ($current_stock < $item['quantity']) {
                    throw new Exception("Not enough stock for " . $item['name']);
                }
                
                // Add order item
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
                
                // Update stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['id']]);
            }
            
            // Clear cart
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            
            $pdo->commit();
            
            header('Location: order_success.php?order_id=' . $order_id);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    }
}
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <h1>Checkout</h1>
        
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 40px;">
            <!-- Order Form -->
            <div>
                <h2>Shipping Information</h2>
                
                <?php if ($error): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required 
                                  style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    
                    <h3>Payment Method</h3>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                        <label style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                            <input type="radio" name="payment" value="cod" checked style="margin-right: 10px;">
                            <i class="fas fa-money-bill-wave" style="margin-right: 8px; color: #28a745;"></i>
                            Cash on Delivery
                        </label>
                        
                        <label style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                            <input type="radio" name="payment" value="upi" style="margin-right: 10px;">
                            <i class="fab fa-google-pay" style="margin-right: 8px; color: #4285f4;"></i>
                            UPI (Google Pay, PhonePe, Paytm)
                        </label>
                        
                        <label style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                            <input type="radio" name="payment" value="card" style="margin-right: 10px;">
                            <i class="fas fa-credit-card" style="margin-right: 8px; color: #ff6b35;"></i>
                            Credit/Debit Card
                        </label>
                        
                        <label style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                            <input type="radio" name="payment" value="netbanking" style="margin-right: 10px;">
                            <i class="fas fa-university" style="margin-right: 8px; color: #6f42c1;"></i>
                            Net Banking
                        </label>
                        
                        <label style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                            <input type="radio" name="payment" value="wallet" style="margin-right: 10px;">
                            <i class="fas fa-wallet" style="margin-right: 8px; color: #fd7e14;"></i>
                            Digital Wallet (Paytm, Amazon Pay)
                        </label>
                        
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="payment" value="emi" style="margin-right: 10px;">
                            <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #20c997;"></i>
                            EMI (Easy Monthly Installments)
                        </label>
                    </div>
                    
                    <button type="submit" class="btn" style="background: #28a745; font-size: 1.1rem; padding: 15px 30px;">
                        Place Order
                    </button>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div>
                <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px;">
                    <h3>Order Summary</h3>
                    
                    <?php foreach ($cart_items as $item): ?>
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
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 1.1rem; font-weight: bold; border-top: 2px solid #232f3e; margin-top: 15px;">
                        <span>Total:</span>
                        <span>₹<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>