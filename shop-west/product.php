<?php
require_once 'config/database.php';

// Error handling to prevent blinking
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

$product_id = $_GET['id'] ?? 0;

if (!$product_id) {
    header('Location: products.php');
    exit;
}

try {
    // Get product details
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           WHERE p.id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header('Location: products.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: products.php');
    exit;
}

$page_title = $product['name'];
include 'includes/header.php';

// Get related products
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? ORDER BY rating DESC LIMIT 4");
    $stmt->execute([$product['category_id'], $product_id]);
    $related_products = $stmt->fetchAll();
} catch (Exception $e) {
    $related_products = [];
}
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem; padding: 1rem; background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                <a href="index.php" style="color: var(--primary-color); text-decoration: none;">Home</a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <a href="products.php" style="color: var(--primary-color); text-decoration: none;">Products</a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <a href="products.php?category=<?php echo $product['category_id']; ?>" style="color: var(--primary-color); text-decoration: none;"><?php echo htmlspecialchars($product['category_name']); ?></a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <span style="color: var(--text-primary); font-weight: 500;"><?php echo htmlspecialchars($product['name']); ?></span>
            </div>
        </nav>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 4rem;">
            <!-- Product Image -->
            <div>
                <div style="background: var(--bg-primary); border-radius: var(--radius-xl); padding: 2rem; border: 1px solid var(--border-color);">
                    <img src="assets/uploads/<?php echo $product['image'] ?: 'default.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="width: 100%; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);"
                         loading="lazy" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJJbnRlciIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                </div>
            </div>
            
            <!-- Product Details -->
            <div style="background: var(--bg-primary); border-radius: var(--radius-xl); padding: 2rem; border: 1px solid var(--border-color);">
                <div style="color: var(--text-muted); font-size: 0.875rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </div>
                
                <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--text-primary); line-height: 1.2;">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                    <div class="stars" style="display: flex; gap: 0.125rem;">
                        <?php
                        $rating = $product['rating'];
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span class="star' . ($i <= $rating ? '' : ' empty') . '" style="color: ' . ($i <= $rating ? '#fbbf24' : 'var(--border-color)') . '; font-size: 1.25rem;">★</span>';
                        }
                        ?>
                    </div>
                    <span style="color: var(--text-secondary); font-size: 0.9rem;">(<?php echo $product['reviews_count']; ?> reviews)</span>
                </div>
                
                <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 2rem;">
                    ₹<?php echo number_format($product['price'], 2); ?>
                </div>
                
                <p style="margin-bottom: 2rem; line-height: 1.7; color: var(--text-secondary); font-size: 1.1rem;">
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>
                
                <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">Availability</div>
                            <div style="color: var(--text-secondary);">
                                <?php if ($product['stock_quantity'] > 10): ?>
                                    <i class="fas fa-check-circle" style="color: var(--success-color);"></i> In Stock (<?php echo $product['stock_quantity']; ?>)
                                <?php elseif ($product['stock_quantity'] > 0): ?>
                                    <i class="fas fa-exclamation-triangle" style="color: var(--warning-color);"></i> Limited Stock (<?php echo $product['stock_quantity']; ?>)
                                <?php else: ?>
                                    <i class="fas fa-times-circle" style="color: var(--danger-color);"></i> Out of Stock
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">Category</div>
                            <div style="color: var(--text-secondary);"><?php echo htmlspecialchars($product['category_name']); ?></div>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['user_id']) && $product['stock_quantity'] > 0): ?>
                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);">Quantity</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <button type="button" onclick="updateQuantity(-1)" class="quantity-btn">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                       style="width: 80px; text-align: center; padding: 0.75rem; border: 2px solid var(--border-color); border-radius: var(--radius-md); font-weight: 600;">
                                <button type="button" onclick="updateQuantity(1)" class="quantity-btn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <button onclick="addToCartFromProduct(<?php echo $product['id']; ?>)" class="btn btn-primary btn-lg" style="width: 100%;">
                                <i class="fas fa-cart-plus"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <div style="background: var(--bg-secondary); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; text-align: center;">
                        <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <h3 style="margin-bottom: 1rem; color: var(--text-primary);">Sign in to Purchase</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Please sign in to add items to your cart and make purchases.</p>
                        <div style="display: flex; gap: 1rem; justify-content: center;">
                            <a href="login.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i>
                                Sign In
                            </a>
                            <a href="register.php" class="btn btn-secondary">
                                <i class="fas fa-user-plus"></i>
                                Create Account
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="background: var(--bg-secondary); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; text-align: center;">
                        <div style="font-size: 3rem; color: var(--danger-color); margin-bottom: 1rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 style="margin-bottom: 1rem; color: var(--text-primary);">Out of Stock</h3>
                        <p style="color: var(--text-secondary);">This product is currently unavailable.</p>
                    </div>
                <?php endif; ?>
                
                <div style="display: flex; gap: 1rem;">
                    <button class="btn btn-secondary" style="flex: 1;">
                        <i class="fas fa-heart"></i>
                        Add to Wishlist
                    </button>
                    <button class="btn btn-secondary" onclick="shareProduct()">
                        <i class="fas fa-share"></i>
                        Share
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if ($related_products): ?>
        <section>
            <div class="section-header mb-6">
                <h2 class="section-title">Related Products</h2>
                <p class="section-subtitle">You might also like these products</p>
            </div>
            <div class="product-grid">
                <?php foreach ($related_products as $related): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/uploads/<?php echo $related['image'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($related['name']); ?>"
                             loading="lazy" 
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJJbnRlciIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($related['name']); ?></h3>
                        <div class="product-price">₹<?php echo number_format($related['price'], 2); ?></div>
                        <div class="product-rating">
                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<span class="star' . ($i <= $related['rating'] ? '' : ' empty') . '">★</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-primary w-full">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>

<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const newValue = Math.max(1, Math.min(currentValue + change, parseInt(input.max)));
    input.value = newValue;
}

function addToCartFromProduct(productId) {
    const quantity = document.getElementById('quantity')?.value || 1;
    if (window.shopWest) {
        window.shopWest.addToCart(productId, quantity);
    }
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            if (window.shopWest) {
                window.shopWest.showNotification('Product link copied to clipboard!', 'success');
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>