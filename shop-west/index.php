<?php
$page_title = "Home";
include 'includes/header.php';

// Get featured products (optimized query)
try {
    $stmt = $pdo->query("SELECT p.id, p.name, p.description, p.price, p.image, p.rating, p.reviews_count FROM products p ORDER BY p.rating DESC, p.reviews_count DESC LIMIT 8");
    $featured_products = $stmt->fetchAll();
} catch (Exception $e) {
    $featured_products = [];
}

// Get categories
try {
    $stmt = $pdo->query("SELECT id, name, description FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();
} catch (Exception $e) {
    $categories = [];
}
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Welcome to Shop West</h1>
                <p>Discover premium products from trusted brands. Experience seamless shopping with fast delivery, secure payments, and exceptional customer service.</p>
                <div class="flex gap-4 justify-center mt-6">
                    <a href="products.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i>
                        Shop Now
                    </a>
                    <a href="#categories" class="btn btn-secondary btn-lg">
                        <i class="fas fa-compass"></i>
                        Explore Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories" id="categories" style="background: var(--bg-feature);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Browse our carefully curated collection of products across different categories</p>
            </div>
            <div class="category-grid">
                <?php foreach ($categories as $category): ?>
                <div class="category-card">
                    <span class="category-icon">
                        <?php
                        $icons = [
                            'Electronics' => '📱',
                            'Clothing' => '👕',
                            'Books' => '📚',
                            'Home & Garden' => '🏠',
                            'Sports' => '⚽'
                        ];
                        echo $icons[$category['name']] ?? '🛍️';
                        ?>
                    </span>
                    <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        Browse Collection
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products" style="background: var(--bg-accent);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked products that our customers love the most</p>
            </div>
            <div class="product-grid">
                <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/uploads/<?php echo $product['image'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             loading="lazy" 
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJJbnRlciIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                        <?php if ($product['rating'] >= 4.5): ?>
                            <div class="product-badge">Top Rated</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-category">Featured</div>
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?></p>
                        <div class="product-price">₹<?php echo number_format($product['price'], 2); ?></div>
                        <div class="product-rating">
                            <div class="stars">
                                <?php
                                $rating = $product['rating'];
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<span class="star' . ($i <= $rating ? '' : ' empty') . '">★</span>';
                                }
                                ?>
                            </div>
                            <span class="rating-text">(<?php echo $product['reviews_count']; ?> reviews)</span>
                        </div>
                        <div class="product-actions">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="flex: 1;">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-success btn-sm">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-6">
                <a href="products.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-th-large"></i>
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 5rem 0; background: var(--bg-feature);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose Shop West?</h2>
                <p class="section-subtitle">We're committed to providing you with the best shopping experience</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-xl)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem;">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Fast Delivery</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Get your orders delivered quickly with our reliable shipping partners</p>
                    <a href="delivery.php" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-info-circle"></i>
                        Learn More
                    </a>
                </div>
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-xl)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--success-color), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Secure Payments</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Shop with confidence using our secure payment gateway</p>
                    <a href="payments.php" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-lock"></i>
                        Security Info
                    </a>
                </div>
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-xl)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--warning-color), #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">24/7 Support</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Our customer support team is always here to help you</p>
                    <a href="support.php" style="background: linear-gradient(135deg, var(--warning-color), #d97706); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-comments"></i>
                        Contact Us
                    </a>
                </div>
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-xl)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--info-color), #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 2rem;">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Easy Returns</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Not satisfied? Return your purchase within 30 days</p>
                    <a href="returns.php" style="background: linear-gradient(135deg, var(--info-color), #1d4ed8); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-exchange-alt"></i>
                        Return Policy
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function addToCart(productId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.style.background = 'var(--success-color)';
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            button.innerHTML = originalContent;
            button.disabled = false;
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        button.innerHTML = originalContent;
        button.disabled = false;
        alert('Error adding product to cart');
    });
}
</script>

<?php include 'includes/footer.php'; ?>