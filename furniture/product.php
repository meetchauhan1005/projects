<?php
require_once 'includes/functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    redirect('products.php');
}

$database = new Database();
$db = $database->getConnection();

// Get product details
$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ? AND p.status = 'active'";
$stmt = $db->prepare($query);
$stmt->execute([$product_id]);

if ($stmt->rowCount() == 0) {
    redirect('products.php');
}

$product = $stmt->fetch(PDO::FETCH_ASSOC);
$page_title = $product['name'];

// Get product reviews
$query = "SELECT r.*, u.username FROM reviews r 
          JOIN users u ON r.user_id = u.id 
          WHERE r.product_id = ? 
          ORDER BY r.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$avg_rating = 0;
if (!empty($reviews)) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $avg_rating = round($total_rating / count($reviews), 1);
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        redirect('login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $quantity <= $product['stock_quantity']) {
        if (addToCart($product_id, $quantity)) {
            $success = 'Product added to cart successfully!';
        } else {
            $error = 'Error adding product to cart.';
        }
    } else {
        $error = 'Invalid quantity selected.';
    }
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    if (!isLoggedIn()) {
        redirect('login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    
    $rating = (int)$_POST['rating'];
    $comment = sanitize($_POST['comment']);
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $query = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$product_id, $_SESSION['user_id'], $rating, $comment])) {
            redirect($_SERVER['REQUEST_URI']);
        }
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Product Image -->
        <div>
            <?php 
            $image_path = 'assets/images/products/' . $product['image'];
            if (!file_exists($image_path)) {
                $image_path = 'assets/images/placeholder.jpg';
            }
            ?>
            <img src="<?php echo $image_path; ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 style="width: 100%; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        </div>
        
        <!-- Product Details -->
        <div>
            <nav style="margin-bottom: 1rem; color: #666;">
                <a href="index.php">Home</a> > 
                <a href="products.php">Products</a> > 
                <a href="products.php?category=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> > 
                <?php echo htmlspecialchars($product['name']); ?>
            </nav>
            
            <h1 style="margin-bottom: 1rem;"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div style="margin-bottom: 1rem;">
                <?php if ($avg_rating > 0): ?>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span style="color: <?php echo $i <= $avg_rating ? '#ffc107' : '#ddd'; ?>;">★</span>
                            <?php endfor; ?>
                        </div>
                        <span>(<?php echo count($reviews); ?> reviews)</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="product-price" style="font-size: 1.5rem; margin-bottom: 1rem;">
                <?php if ($product['discount_price']): ?>
                    <?php echo formatPrice($product['discount_price']); ?>
                    <span class="price-original" style="font-size: 1.2rem;"><?php echo formatPrice($product['price']); ?></span>
                    <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; margin-left: 0.5rem;">
                        SAVE <?php echo round((($product['price'] - $product['discount_price']) / $product['price']) * 100); ?>%
                    </span>
                <?php else: ?>
                    <?php echo formatPrice($product['price']); ?>
                <?php endif; ?>
            </div>
            
            <p style="margin-bottom: 2rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <?php if ($product['stock_quantity'] > 0): ?>
                <p style="color: #28a745; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?> available)
                </p>
                
                <?php if (isLoggedIn()): ?>
                    <form method="POST" style="margin-bottom: 2rem;">
                        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                   style="width: 80px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <button type="submit" name="add_to_cart" class="btn" style="padding: 1rem 2rem;">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </form>
                <?php else: ?>
                    <p style="margin-bottom: 2rem;">
                        <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn">Login to Purchase</a>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <p style="color: #dc3545; margin-bottom: 2rem;">
                    <i class="fas fa-times-circle"></i> Out of Stock
                </p>
            <?php endif; ?>
            
            <?php if ($product['specifications']): ?>
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 5px;">
                    <h3 style="margin-bottom: 1rem;">Specifications</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['specifications'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div style="border-top: 1px solid #ddd; padding-top: 3rem;">
        <h2 style="margin-bottom: 2rem;">Customer Reviews</h2>
        
        <?php if (isLoggedIn()): ?>
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; margin-bottom: 3rem;">
                <h3 style="margin-bottom: 1rem;">Write a Review</h3>
                <form method="POST">
                    <div style="margin-bottom: 1rem;">
                        <label>Rating:</label>
                        <div style="margin-top: 0.5rem;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                <label for="star<?php echo $i; ?>" style="color: #ffc107; font-size: 1.5rem; cursor: pointer;">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="comment">Comment:</label>
                        <textarea name="comment" id="comment" rows="4" required 
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; margin-top: 0.5rem;"></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="btn">Submit Review</button>
                </form>
            </div>
        <?php endif; ?>
        
        <?php if (empty($reviews)): ?>
            <p>No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <div style="display: grid; gap: 2rem;">
                <?php foreach ($reviews as $review): ?>
                    <div style="border: 1px solid #ddd; padding: 1.5rem; border-radius: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color: <?php echo $i <= $review['rating'] ? '#ffc107' : '#ddd'; ?>;">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <small style="color: #666;"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>