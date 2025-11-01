<?php
require_once 'includes/functions.php';
$page_title = 'Checkout';

if (!isLoggedIn()) {
    redirect('login.php?redirect=checkout.php');
}

$database = new Database();
$db = $database->getConnection();

// Get cart items
$query = "SELECT c.*, p.name, p.price, p.discount_price, p.image, p.stock_quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    redirect('cart.php');
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $price = $item['discount_price'] ?: $item['price'];
    $total += $price * $item['quantity'];
}

// Get user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';
$success = '';

// Process order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $shipping_address = sanitize($_POST['shipping_address']);
        $phone = sanitize($_POST['phone']);
        $payment_method = in_array($_POST['payment_method'], ['cod', 'card', 'upi']) ? $_POST['payment_method'] : 'cod';
        
        // Update user phone if provided
        if (!empty($phone)) {
            $query = "UPDATE users SET phone = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$phone, $_SESSION['user_id']]);
        }
        
        if (empty($shipping_address)) {
            $error = 'Shipping address is required';
        } elseif (empty($phone)) {
            $error = 'Phone number is required';
        } elseif ($payment_method == 'card') {
            $card_number = sanitize($_POST['card_number'] ?? '');
            $expiry_date = sanitize($_POST['expiry_date'] ?? '');
            $cvv = sanitize($_POST['cvv'] ?? '');
            $card_holder_name = sanitize($_POST['card_holder_name'] ?? '');
            
            if (empty($card_number) || empty($expiry_date) || empty($cvv) || empty($card_holder_name)) {
                $error = 'All card details are required';
            } elseif (strlen(str_replace(' ', '', $card_number)) < 13) {
                $error = 'Invalid card number';
            } elseif (!preg_match('/^\d{2}\/\d{2}$/', $expiry_date)) {
                $error = 'Invalid expiry date format';
            } elseif (strlen($cvv) < 3) {
                $error = 'Invalid CVV';
            }
        } elseif ($payment_method == 'upi') {
            $upi_id = sanitize($_POST['upi_id'] ?? '');
            $upi_name = sanitize($_POST['upi_name'] ?? '');
            $upi_app = sanitize($_POST['upi_app'] ?? '');
            
            if (empty($upi_id) || empty($upi_name) || empty($upi_app)) {
                $error = 'All UPI details are required';
            } elseif (!filter_var($upi_id, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid UPI ID format';
            }
        } else {
            try {
                $db->beginTransaction();
                
                // Verify stock availability
                foreach ($cart_items as $item) {
                    if ($item['stock_quantity'] < $item['quantity']) {
                        throw new Exception('Insufficient stock for ' . $item['name']);
                    }
                }
                
                // Create order
                $query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$_SESSION['user_id'], $total, $shipping_address, $payment_method]);
                $order_id = $db->lastInsertId();
                
                // Add order items and update stock
                foreach ($cart_items as $item) {
                    $price = $item['discount_price'] ?: $item['price'];
                    
                    // Insert order item
                    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $price]);
                    
                    // Update stock with validation
                    $query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?";
                    $stmt = $db->prepare($query);
                    $result = $stmt->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
                    
                    if ($stmt->rowCount() == 0) {
                        throw new Exception('Stock validation failed for ' . $item['name']);
                    }
                }
                
                // Clear cart
                $query = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$_SESSION['user_id']]);
                
                $db->commit();
                redirect('order-success.php?order_id=' . $order_id);
                
            } catch (Exception $e) {
                $db->rollBack();
                logError('Checkout error: ' . $e->getMessage(), __FILE__, __LINE__);
                $error = $e->getMessage();
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Checkout</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="checkout-grid" style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; margin-top: 2rem;">
        <!-- Order Form -->
        <div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="form-section">
                    <h3>Shipping Information</h3>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address *</label>
                        <textarea name="shipping_address" id="shipping_address" rows="4" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Payment Method</h3>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod" checked onchange="togglePaymentDetails()">
                            <span>Cash on Delivery</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card" onchange="togglePaymentDetails()">
                            <span>Credit/Debit Card</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="upi" onchange="togglePaymentDetails()">
                            <span>UPI Payment</span>
                        </label>
                    </div>
                    
                    <div id="card-details" style="display: none; margin-top: 1rem; padding: 1rem; background: #fff; border-radius: 8px; border: 1px solid #ddd;">
                        <h4>Card Details</h4>
                        <div class="form-group">
                            <label for="card_number">Card Number *</label>
                            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date *</label>
                                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_holder_name">Cardholder Name *</label>
                            <input type="text" id="card_holder_name" name="card_holder_name" placeholder="Name on card">
                        </div>
                    </div>
                    
                    <div id="upi-details" style="display: none; margin-top: 1rem; padding: 1rem; background: #fff; border-radius: 8px; border: 1px solid #ddd;">
                        <h4>UPI Payment Details</h4>
                        <div class="form-group">
                            <label for="upi_id">UPI ID *</label>
                            <input type="text" id="upi_id" name="upi_id" placeholder="yourname@paytm / yourname@gpay" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+">
                        </div>
                        <div class="form-group">
                            <label for="upi_name">Account Holder Name *</label>
                            <input type="text" id="upi_name" name="upi_name" placeholder="Name as per bank account">
                        </div>
                        <div class="form-group">
                            <label>Select UPI App</label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.5rem; margin-top: 0.5rem;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                                    <input type="radio" name="upi_app" value="gpay">
                                    <span>Google Pay</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                                    <input type="radio" name="upi_app" value="paytm">
                                    <span>Paytm</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                                    <input type="radio" name="upi_app" value="phonepe">
                                    <span>PhonePe</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                                    <input type="radio" name="upi_app" value="other">
                                    <span>Other</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="place_order" class="btn btn-primary" style="width: 100%; margin-top: 2rem;">
                    Place Order
                </button>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="order-items">
                <?php foreach ($cart_items as $item): ?>
                    <?php 
                    $price = $item['discount_price'] ?: $item['price'];
                    $subtotal = $price * $item['quantity'];
                    ?>
                    <div class="order-item">
                        <img src="<?php echo getImagePath($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Qty: <?php echo $item['quantity']; ?> × <?php echo formatPrice($price); ?></p>
                        </div>
                        <div class="item-total">
                            <?php echo formatPrice($subtotal); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-total">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span><?php echo formatPrice($total); ?></span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="total-row final-total">
                    <span>Total:</span>
                    <span><?php echo formatPrice($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.form-section h3 {
    margin-bottom: 1rem;
    color: #333;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-option:hover {
    border-color: #007bff;
}

.payment-option input[type="radio"]:checked + span {
    font-weight: 500;
}

.payment-option input[type="radio"]:checked {
    accent-color: #007bff;
}

.order-summary {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    height: fit-content;
}

.order-summary h3 {
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

.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
}

.item-details p {
    margin: 0;
    color: #666;
    font-size: 0.8rem;
}

.item-total {
    font-weight: 500;
}

.order-total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid #e9ecef;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.final-total {
    font-weight: bold;
    font-size: 1.1rem;
    color: #333;
    border-top: 1px solid #ddd;
    padding-top: 0.5rem;
    margin-top: 1rem;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script>
function togglePaymentDetails() {
    const cardDetails = document.getElementById('card-details');
    const upiDetails = document.getElementById('upi-details');
    const cardRadio = document.querySelector('input[name="payment_method"][value="card"]');
    const upiRadio = document.querySelector('input[name="payment_method"][value="upi"]');
    
    cardDetails.style.display = cardRadio.checked ? 'block' : 'none';
    upiDetails.style.display = upiRadio.checked ? 'block' : 'none';
}

// Format card number
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date
document.getElementById('expiry_date').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0,2) + '/' + value.substring(2,4);
    }
    e.target.value = value;
});

// CVV numbers only
document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
</script>

<?php include 'includes/footer.php'; ?>