<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart_items = [];
$total = 0;

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT c.*, p.name, p.price FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = ? AND p.status = 'active'";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    if (empty($cart_items)) {
        header('Location: cart.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: cart.php');
    exit;
}

$shipping = $total >= 5000 ? 0 : 199;
$final_total = $total + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-dark: #1e3a8a;
            --accent-orange: #f97316;
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-600: #475569;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--neutral-800);
            background: var(--neutral-50);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--neutral-200);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-blue) !important;
        }
        
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-blue);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .payment-method {
            border: 2px solid var(--neutral-200);
            border-radius: 1rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover, .payment-method.selected {
            border-color: var(--primary-blue);
            background: rgba(30, 64, 175, 0.05);
        }
        
        .form-control {
            border: 2px solid var(--neutral-200);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-bolt text-warning me-2"></i>Mahadev Electronic
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Checkout</h1>
        
        <div class="row">
            <div class="col-lg-8">
                <form id="checkoutForm">
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Shipping Address</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_SESSION['user_name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="3" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PIN Code</label>
                                    <input type="text" class="form-control" name="pincode" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Payment Method</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="payment-method" onclick="selectPayment('cod')">
                                        <input type="radio" name="payment_method" value="cod" id="cod" class="form-check-input me-3">
                                        <label for="cod" class="form-check-label">
                                            <i class="fas fa-money-bill-wave fa-2x text-success mb-2 d-block"></i>
                                            <strong>Cash on Delivery</strong><br>
                                            <small class="text-muted">Pay when you receive</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="payment-method" onclick="selectPayment('upi')">
                                        <input type="radio" name="payment_method" value="upi" id="upi" class="form-check-input me-3">
                                        <label for="upi" class="form-check-label">
                                            <i class="fas fa-mobile-alt fa-2x text-primary mb-2 d-block"></i>
                                            <strong>UPI Payment</strong><br>
                                            <small class="text-muted">PhonePe, GPay, Paytm</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="payment-method" onclick="selectPayment('card')">
                                        <input type="radio" name="payment_method" value="card" id="card" class="form-check-input me-3">
                                        <label for="card" class="form-check-label">
                                            <i class="fas fa-credit-card fa-2x text-info mb-2 d-block"></i>
                                            <strong>Credit/Debit Card</strong><br>
                                            <small class="text-muted">Visa, Mastercard, RuPay</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="payment-method" onclick="selectPayment('netbanking')">
                                        <input type="radio" name="payment_method" value="netbanking" id="netbanking" class="form-check-input me-3">
                                        <label for="netbanking" class="form-check-label">
                                            <i class="fas fa-university fa-2x text-warning mb-2 d-block"></i>
                                            <strong>Net Banking</strong><br>
                                            <small class="text-muted">All major banks</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- UPI Details -->
                            <div id="upi-details" class="mt-4" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    You will be redirected to your UPI app to complete the payment
                                </div>
                            </div>
                            
                            <!-- Card Details -->
                            <div id="card-details" class="mt-4" style="display: none;">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Card Number</label>
                                        <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control" placeholder="123" maxlength="3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Month</label>
                                        <select class="form-control">
                                            <option>01</option>
                                            <option>02</option>
                                            <option>03</option>
                                            <option>04</option>
                                            <option>05</option>
                                            <option>06</option>
                                            <option>07</option>
                                            <option>08</option>
                                            <option>09</option>
                                            <option>10</option>
                                            <option>11</option>
                                            <option>12</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Year</label>
                                        <select class="form-control">
                                            <option>2024</option>
                                            <option>2025</option>
                                            <option>2026</option>
                                            <option>2027</option>
                                            <option>2028</option>
                                            <option>2029</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        
                        <?php foreach ($cart_items as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                                <span>₹<?= number_format($item['price'] * $item['quantity']) ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹<?= number_format($total) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span><?= $shipping == 0 ? 'FREE' : '₹' . number_format($shipping) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>₹<?= number_format($final_total) ?></strong>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100" onclick="placeOrder()">
                            <i class="fas fa-lock me-2"></i>Place Order
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secure checkout with SSL encryption
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPayment(method) {
            document.getElementById(method).checked = true;
            
            // Hide all payment details
            document.getElementById('upi-details').style.display = 'none';
            document.getElementById('card-details').style.display = 'none';
            
            // Show relevant payment details
            if (method === 'upi') {
                document.getElementById('upi-details').style.display = 'block';
            } else if (method === 'card') {
                document.getElementById('card-details').style.display = 'block';
            }
            
            // Update selected styling
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
        }
        
        function placeOrder() {
            const form = document.getElementById('checkoutForm');
            const formData = new FormData(form);
            
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Check if payment method is selected
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Simulate order placement
            alert('Order placed successfully! You will receive a confirmation email shortly.');
            
            // Clear cart and redirect
            fetch('cart-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=clear'
            })
            .then(() => {
                window.location.href = 'index.php';
            });
        }
    </script>
</body>
</html>