<?php
$page_title = "Shopping Cart";
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle cart updates
if ($_POST) {
    try {
        if (isset($_POST['update_cart'])) {
            foreach ($_POST['quantities'] as $cart_id => $quantity) {
                $quantity = max(1, (int)$quantity);
                $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);
            }
        } elseif (isset($_POST['remove_item'])) {
            $cart_id = (int)$_POST['cart_id'];
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $_SESSION['user_id']]);
        }
    } catch (Exception $e) {
        // Handle error silently
    }
    
    header('Location: cart.php');
    exit;
}

// Get cart items with error handling
try {
    $stmt = $pdo->prepare("
        SELECT c.id as cart_id, c.quantity, p.id, p.name, p.price, p.image, p.stock_quantity
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll();
} catch (Exception $e) {
    $cart_items = [];
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-shopping-cart"></i>
                Shopping Cart
            </h1>
            <p class="section-subtitle">Review your items and proceed to checkout</p>
        </div>
        
        <?php if ($cart_items): ?>
            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start;">
                <!-- Cart Items -->
                <div>
                    <form method="POST" id="cartForm">
                        <div style="background: var(--bg-primary); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
                            <?php foreach ($cart_items as $index => $item): ?>
                            <div class="cart-item" style="<?php echo $index === count($cart_items) - 1 ? 'border-bottom: none;' : ''; ?>">
                                <img src="assets/uploads/<?php echo $item['image'] ?: 'default.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     class="cart-item-image"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJJbnRlciIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                
                                <div class="cart-item-info">
                                    <h3 class="cart-item-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <div class="cart-item-price">₹<?php echo number_format($item['price'], 2); ?></div>
                                    <div style="color: var(--text-secondary); font-size: 0.875rem; margin: 0.5rem 0;">
                                        <?php if ($item['stock_quantity'] > 10): ?>
                                            <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                            In Stock
                                        <?php elseif ($item['stock_quantity'] > 0): ?>
                                            <i class="fas fa-exclamation-triangle" style="color: var(--warning-color);"></i>
                                            Only <?php echo $item['stock_quantity']; ?> left
                                        <?php else: ?>
                                            <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                                            Out of Stock
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, -1, <?php echo $item['stock_quantity']; ?>)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantities[<?php echo $item['cart_id']; ?>]" 
                                               id="qty_<?php echo $item['cart_id']; ?>"
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" 
                                               max="<?php echo $item['stock_quantity']; ?>"
                                               class="quantity-input"
                                               onchange="updateItemTotal(<?php echo $item['cart_id']; ?>, <?php echo $item['price']; ?>)">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_id']; ?>, 1, <?php echo $item['stock_quantity']; ?>)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div style="text-align: right; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                    <div class="cart-item-total" id="total_<?php echo $item['cart_id']; ?>" style="font-size: 1.25rem; font-weight: 700; color: var(--primary-color);">
                                        ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                    
                                    <button type="button" 
                                            onclick="removeItem(<?php echo $item['cart_id']; ?>)"
                                            class="btn btn-sm" 
                                            style="background: var(--danger-color); color: white; margin-top: 1rem;">
                                        <i class="fas fa-trash"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="cart_id" id="removeCartId" value="">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0;">
                            <button type="submit" name="update_cart" value="1" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i>
                                Update Cart
                            </button>
                            <a href="products.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Continue Shopping
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Order Summary -->
                <div style="position: sticky; top: 2rem;">
                    <div style="background: var(--bg-primary); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Order Summary</h3>
                        
                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Subtotal (<?php echo count($cart_items); ?> items)</span>
                                <span id="subtotal">₹<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-secondary);">
                                <span>Shipping</span>
                                <span>FREE</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: var(--text-secondary);">
                                <span>Tax</span>
                                <span>Calculated at checkout</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; margin-bottom: 2rem;">
                            <span>Total</span>
                            <span id="grandTotal" style="color: var(--primary-color);">₹<?php echo number_format($total, 2); ?></span>
                        </div>
                        
                        <a href="checkout.php" class="btn btn-primary w-full btn-lg">
                            <i class="fas fa-credit-card"></i>
                            Proceed to Checkout
                        </a>
                        
                        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md); text-align: center;">
                            <div style="color: var(--success-color); font-weight: 600; margin-bottom: 0.5rem;">
                                <i class="fas fa-shield-alt"></i>
                                Secure Checkout
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-secondary);">Your payment information is protected</div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <div class="text-center" style="padding: 4rem 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <div style="font-size: 5rem; color: var(--text-muted); margin-bottom: 2rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary);">Your cart is empty</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.125rem;">Looks like you haven't added any items to your cart yet.</p>
                <a href="products.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i>
                    Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function updateQuantity(cartId, change, maxStock) {
    const input = document.getElementById('qty_' + cartId);
    let newValue = parseInt(input.value) + change;
    
    if (newValue < 1) newValue = 1;
    if (newValue > maxStock) newValue = maxStock;
    
    input.value = newValue;
    
    // Trigger change event to update totals
    input.dispatchEvent(new Event('change'));
}

function updateItemTotal(cartId, price) {
    const quantity = parseInt(document.getElementById('qty_' + cartId).value);
    const itemTotal = price * quantity;
    
    document.getElementById('total_' + cartId).textContent = '₹' + itemTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    updateGrandTotal();
}

function updateGrandTotal() {
    let total = 0;
    const quantities = document.querySelectorAll('input[name^="quantities"]');
    
    quantities.forEach(input => {
        const cartId = input.name.match(/\[(\d+)\]/)[1];
        const quantity = parseInt(input.value);
        const priceText = document.getElementById('total_' + cartId).textContent;
        const price = parseFloat(priceText.replace('₹', '').replace(/,/g, ''));
        total += price;
    });
    
    document.getElementById('subtotal').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('grandTotal').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function removeItem(cartId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        document.getElementById('removeCartId').value = cartId;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="remove_item" value="1">
            <input type="hidden" name="cart_id" value="${cartId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Add loading state to update cart button
document.getElementById('cartForm').addEventListener('submit', function(e) {
    const updateBtn = document.querySelector('button[name="update_cart"]');
    const originalContent = updateBtn.innerHTML;
    
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    updateBtn.disabled = true;
    
    setTimeout(() => {
        if (updateBtn.disabled) {
            updateBtn.innerHTML = originalContent;
            updateBtn.disabled = false;
        }
    }, 3000);
});

// Auto-save cart changes
let autoSaveTimeout;
document.querySelectorAll('input[name^="quantities"]').forEach(input => {
    input.addEventListener('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-submit form after 2 seconds of inactivity
            document.querySelector('button[name="update_cart"]').click();
        }, 2000);
    });
});
</script>

<?php include 'includes/footer.php'; ?>