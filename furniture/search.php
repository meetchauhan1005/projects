<?php
require_once 'includes/functions.php';

$search_query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$page_title = 'Search Results';

if (empty($search_query)) {
    redirect('products.php');
}

$database = new Database();
$db = $database->getConnection();

// Search products
$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.status = 'active' AND (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?)
          ORDER BY p.name ASC";

$search_term = "%$search_query%";
$stmt = $db->prepare($query);
$stmt->execute([$search_term, $search_term, $search_term]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Search Results</h1>
    <p style="margin-bottom: 2rem;">
        Showing results for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>" 
        (<?php echo count($products); ?> products found)
    </p>
    
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 4rem 0;">
            <i class="fas fa-search" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
            <h3>No products found</h3>
            <p>Try different keywords or browse our categories.</p>
            <a href="products.php" class="btn">View All Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <?php 
                    $image_path = 'assets/images/products/' . $product['image'];
                    if (!file_exists($image_path)) {
                        $image_path = 'assets/images/placeholder.jpg';
                    }
                    ?>
                    <img src="<?php echo $image_path; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 0.5rem;">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </p>
                        <div class="product-price">
                            <?php if ($product['discount_price']): ?>
                                <?php echo formatPrice($product['discount_price']); ?>
                                <span class="price-original"><?php echo formatPrice($product['price']); ?></span>
                            <?php else: ?>
                                <?php echo formatPrice($product['price']); ?>
                            <?php endif; ?>
                        </div>
                        <p><?php echo substr(htmlspecialchars($product['description']), 0, 100) . '...'; ?></p>
                        <div style="margin-top: 1rem;">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                            <?php if (isLoggedIn() && $product['stock_quantity'] > 0): ?>
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-secondary">Add to Cart</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>