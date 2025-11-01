<?php
require_once 'includes/functions.php';
$page_title = 'Shopping Cart';

if (!isLoggedIn()) {
    redirect('login.php?redirect=cart.php');
}

$database = new Database();
$db = $database->getConnection();

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $cart_id => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity > 0) {
                $query = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$quantity, $cart_id, $_SESSION['user_id']]);
            } else {
                $query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$cart_id, $_SESSION['user_id']]);
            }
        }
        redirect('cart.php');
    }
    
    if (isset($_POST['remove_item'])) {
        $cart_id = (int)$_POST['cart_id'];
        $query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$cart_id, $_SESSION['user_id']]);
        redirect('cart.php');
    }
}

// Get cart items
$query = "SELECT c.*, p.name, p.price, p.discount_price, p.image, p.stock_quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ? 
          ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $price = $item['discount_price'] ?: $item['price'];
    $total += $price * $item['quantity'];
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1 style="margin-bottom: 2rem;">Shopping Cart</h1>
    
    <?php if (empty($cart_items)): ?>
        <div style="text-align: center; padding: 4rem 0;">
            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
            <h3>Your cart is empty</h3>
            <p>Add some products to get started!</p>
            <a href="products.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php 
                        $price = $item['discount_price'] ?: $item['price'];
                        $subtotal = $price * $item['quantity'];
                        ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <?php 
                                    $image_path = 'assets/images/products/' . $item['image'];
                                    if (!file_exists($image_path)) {
                                        $image_path = 'assets/images/placeholder.jpg';
                                    }
                                    ?>
                                    <img src="<?php echo $image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                                    <div>
                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <?php if ($item['stock_quantity'] < $item['quantity']): ?>
                                            <small style="color: #dc3545;">Only <?php echo $item['stock_quantity']; ?> in stock</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($item['discount_price']): ?>
                                    <?php echo formatPrice($item['discount_price']); ?>
                                    <br><small style="text-decoration: line-through; color: #999;"><?php echo formatPrice($item['price']); ?></small>
                                <?php else: ?>
                                    <?php echo formatPrice($item['price']); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="number" name="quantities[<?php echo $item['id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="0" max="<?php echo $item['stock_quantity']; ?>" 
                                       class="quantity-input">
                            </td>
                            <td><strong><?php echo formatPrice($subtotal); ?></strong></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="remove_item" value="1" 
                                            onclick="return confirm('Remove this item from cart?')"
                                            style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 1.2rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0;">
                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                <div style="text-align: right;">
                    <h3>Total: <?php echo formatPrice($total); ?></h3>
                </div>
            </div>
        </form>
        
        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
            <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>