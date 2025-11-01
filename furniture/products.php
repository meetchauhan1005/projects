<?php
require_once 'includes/functions.php';
$page_title = 'Products';

// Get filter parameters
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Get products based on filters
$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, c.name as category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.status = 'active'";
$params = [];

if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($search) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Sorting
switch ($sort) {
    case 'price_low':
        $query .= " ORDER BY COALESCE(p.discount_price, p.price) ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY COALESCE(p.discount_price, p.price) DESC";
        break;
    case 'name':
        $query .= " ORDER BY p.name ASC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = getCategories();

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Products</h1>
        
        <div style="display: flex; gap: 1rem; align-items: center;">
            <!-- Category Filter -->
            <select onchange="filterProducts()" id="categoryFilter" style="padding: 0.5rem;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" 
                            <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <!-- Sort Filter -->
            <select onchange="sortProducts()" id="sortFilter" style="padding: 0.5rem;">
                <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
            </select>
        </div>
    </div>
    
    <?php if ($search): ?>
        <div class="alert alert-info">
            Showing results for: "<strong><?php echo htmlspecialchars($search); ?></strong>" 
            (<?php echo count($products); ?> products found)
        </div>
    <?php endif; ?>
    
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 4rem 0;">
            <h3>No products found</h3>
            <p>Try adjusting your search criteria or browse all categories.</p>
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
                        
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <p style="color: #28a745; font-size: 0.9rem;">In Stock (<?php echo $product['stock_quantity']; ?> available)</p>
                        <?php else: ?>
                            <p style="color: #dc3545; font-size: 0.9rem;">Out of Stock</p>
                        <?php endif; ?>
                        
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

<script>
function filterProducts() {
    const category = document.getElementById('categoryFilter').value;
    const sort = document.getElementById('sortFilter').value;
    const search = new URLSearchParams(window.location.search).get('search') || '';
    
    let url = 'products.php?';
    if (category) url += 'category=' + category + '&';
    if (sort) url += 'sort=' + sort + '&';
    if (search) url += 'search=' + encodeURIComponent(search);
    
    window.location.href = url;
}

function sortProducts() {
    filterProducts();
}
</script>

<?php include 'includes/footer.php'; ?>