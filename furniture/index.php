<?php
require_once 'includes/functions.php';
$page_title = 'Home';

// Get featured products
$featured_products = getProducts(8, null, true);
$categories = getCategories();

// Debug: Check if products exist
if (empty($featured_products)) {
    $featured_products = getProducts(8); // Get any products if no featured ones
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 80vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1.5" fill="white" opacity="0.1"/></svg>'); opacity: 0.3;"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div style="max-width: 600px; color: white; text-align: left;">
            <h1 style="font-size: 4rem; font-weight: 900; margin-bottom: 1.5rem; line-height: 1.1; letter-spacing: -2px;">Make Your Home Beautiful</h1>
            <p style="font-size: 1.3rem; margin-bottom: 2.5rem; opacity: 0.95; line-height: 1.6;">Transform your living space with our premium collection of modern furniture designed for Indian homes</p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="products.php" style="padding: 1rem 2.5rem; background: white; color: #2d3748; text-decoration: none; border-radius: 50px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                    <i class="fas fa-shopping-bag"></i> Shop Collection
                </a>
                <a href="#features" style="padding: 1rem 2.5rem; background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-play"></i> Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section style="padding: 6rem 0; background: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 3rem; font-weight: 800; color: #2d3748; margin-bottom: 1rem;">Shop by Category</h2>
            <p style="font-size: 1.2rem; color: #718096; max-width: 600px; margin: 0 auto;">Discover our carefully curated furniture collections for every room in your home</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <?php foreach ($categories as $category): ?>
                <div style="background: white; padding: 2.5rem; border-radius: 20px; text-align: center; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); position: relative; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #4299e1, #3182ce); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; box-shadow: 0 4px 20px rgba(66, 153, 225, 0.3);">
                        <?php
                        $icons = [
                            'Sofas & Chairs' => 'fas fa-couch',
                            'Tables' => 'fas fa-table', 
                            'Bedroom' => 'fas fa-bed',
                            'Storage' => 'fas fa-archive',
                            'Office' => 'fas fa-chair'
                        ];
                        echo '<i class="' . ($icons[$category['name']] ?? 'fas fa-home') . '" style="font-size: 2rem;"></i>';
                        ?>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1rem;"><?php echo htmlspecialchars($category['name']); ?></h3>
                    <p style="color: #718096; margin-bottom: 2rem; line-height: 1.6;"><?php echo htmlspecialchars($category['description']); ?></p>
                    <a href="products.php?category=<?php echo $category['id']; ?>" style="padding: 0.75rem 2rem; background: linear-gradient(135deg, #4299e1, #3182ce); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; transition: all 0.3s ease; display: inline-block;">
                        View Products <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section style="padding: 6rem 0; background: #f8fafc;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 3rem; font-weight: 800; color: #2d3748; margin-bottom: 1rem;">Featured Products</h2>
            <p style="font-size: 1.2rem; color: #718096; max-width: 600px; margin: 0 auto;">Handpicked furniture pieces that combine style, comfort, and quality craftsmanship</p>
        </div>
        <?php if (empty($featured_products)): ?>
            <div style="text-align: center; padding: 3rem; color: #666;">
                <p>No products found. Please add products from the admin panel.</p>
            </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;">
            <?php foreach ($featured_products as $product): ?>
                <div style="background: white; border-radius: 24px; overflow: hidden; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); position: relative;">
                    <?php 
                    $image_path = 'assets/images/products/' . $product['image'];
                    if (!file_exists($image_path)) {
                        $image_path = 'assets/images/placeholder.jpg';
                    }
                    ?>
                    <div style="position: relative; overflow: hidden;">
                        <img src="<?php echo $image_path; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             style="width: 100%; height: 280px; object-fit: cover; transition: transform 0.3s ease;"
                             loading="lazy">
                        <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.9); padding: 0.5rem; border-radius: 50%; backdrop-filter: blur(10px);">
                            <i class="fas fa-heart" style="color: #e53e3e;"></i>
                        </div>
                    </div>
                    <div style="padding: 2rem;">
                        <h3 style="font-size: 1.4rem; font-weight: 700; color: #2d3748; margin-bottom: 0.75rem; line-height: 1.3;"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div style="margin-bottom: 1rem;">
                            <span style="font-size: 1.8rem; font-weight: 800; color: #3182ce;">
                                <?php if ($product['discount_price']): ?>
                                    <?php echo formatPrice($product['discount_price']); ?>
                                    <span style="font-size: 1rem; color: #a0aec0; text-decoration: line-through; margin-left: 0.5rem;"><?php echo formatPrice($product['price']); ?></span>
                                <?php else: ?>
                                    <?php echo formatPrice($product['price']); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <p style="color: #718096; margin-bottom: 2rem; line-height: 1.6;"><?php echo substr(htmlspecialchars($product['description']), 0, 80) . '...'; ?></p>
                        <div style="display: flex; gap: 1rem;">
                            <a href="product.php?id=<?php echo $product['id']; ?>" style="flex: 1; padding: 0.75rem; background: linear-gradient(135deg, #4299e1, #3182ce); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; text-align: center; transition: all 0.3s ease;">
                                View Details
                            </a>
                            <?php if (isLoggedIn()): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" style="padding: 0.75rem 1rem; background: #f7fafc; color: #2d3748; border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 4rem;">
            <a href="products.php" style="padding: 1rem 3rem; background: linear-gradient(135deg, #2d3748, #1a202c); color: white; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; display: inline-block;">
                View All Products <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" style="padding: 6rem 0; background: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 3rem; font-weight: 800; color: #2d3748; margin-bottom: 1rem;">Why Choose Interno</h2>
            <p style="font-size: 1.2rem; color: #718096; max-width: 600px; margin: 0 auto;">We're committed to providing the best furniture shopping experience in India</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem;">
            <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, #f0fff4, #e6fffa); border-radius: 20px; border: 1px solid rgba(72, 187, 120, 0.1);">
                <div style="background: linear-gradient(135deg, #48bb78, #38a169); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; box-shadow: 0 4px 20px rgba(72, 187, 120, 0.3);">
                    <i class="fas fa-truck" style="font-size: 2rem;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1rem;">Free Delivery</h3>
                <p style="color: #718096; line-height: 1.6;">Free delivery across India on orders over ₹25,000</p>
            </div>
            <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, #fef5e7, #fed7aa); border-radius: 20px; border: 1px solid rgba(237, 137, 54, 0.1);">
                <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; box-shadow: 0 4px 20px rgba(237, 137, 54, 0.3);">
                    <i class="fas fa-shield-alt" style="font-size: 2rem;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1rem;">Quality Guarantee</h3>
                <p style="color: #718096; line-height: 1.6;">Premium quality furniture with 2-year warranty</p>
            </div>
            <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, #ebf8ff, #bee3f8); border-radius: 20px; border: 1px solid rgba(66, 153, 225, 0.1);">
                <div style="background: linear-gradient(135deg, #4299e1, #3182ce); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; box-shadow: 0 4px 20px rgba(66, 153, 225, 0.3);">
                    <i class="fas fa-headset" style="font-size: 2rem;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1rem;">Expert Support</h3>
                <p style="color: #718096; line-height: 1.6;">Dedicated customer support in Hindi & English</p>
            </div>
            <div style="text-align: center; padding: 2.5rem; background: linear-gradient(135deg, #faf5ff, #e9d8fd); border-radius: 20px; border: 1px solid rgba(159, 122, 234, 0.1);">
                <div style="background: linear-gradient(135deg, #9f7aea, #805ad5); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; box-shadow: 0 4px 20px rgba(159, 122, 234, 0.3);">
                    <i class="fas fa-undo" style="font-size: 2rem;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-bottom: 1rem;">Easy Returns</h3>
                <p style="color: #718096; line-height: 1.6;">Hassle-free 30-day return & exchange policy</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>