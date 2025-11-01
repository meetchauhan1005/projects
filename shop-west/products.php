<?php
// Error handling to prevent blinking
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

$page_title = "Products";
include 'includes/header.php';

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'name';

// Build query
$where_conditions = [];
$params = [];

if ($search) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_id) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_id;
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

$order_clause = match($sort) {
    'price_low' => 'ORDER BY p.price ASC',
    'price_high' => 'ORDER BY p.price DESC',
    'rating' => 'ORDER BY p.rating DESC',
    'newest' => 'ORDER BY p.created_at DESC',
    default => 'ORDER BY p.name ASC'
};

try {
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            $where_clause $order_clause";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
    // Get categories for filter
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();
} catch (Exception $e) {
    $products = [];
    $categories = [];
}
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">Our Products</h1>
            <p class="section-subtitle">Discover our complete collection of premium products</p>
        </div>
        
        <!-- Modern Filter Section -->
        <div class="filter-section">
            <form method="GET" class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Products</label>
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name, brand, or description...">
                    </div>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select name="sort" class="filter-select">
                        <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name (A-Z)</option>
                        <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Clear All
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Results Summary -->
        <?php if ($search || $category_id): ?>
            <div style="background: var(--bg-primary); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; border: 1px solid var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.9rem;">Showing results for:</span>
                        <?php if ($search): ?>
                            <span style="background: var(--primary-color); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-left: 0.5rem;">"<?php echo htmlspecialchars($search); ?>"</span>
                        <?php endif; ?>
                        <?php if ($category_id): ?>
                            <?php 
                            $selected_category = array_filter($categories, fn($c) => $c['id'] == $category_id);
                            if ($selected_category): 
                                $selected_category = reset($selected_category);
                            ?>
                                <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.875rem; margin-left: 0.5rem;"><?php echo htmlspecialchars($selected_category['name']); ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <span style="color: var(--text-secondary); font-size: 0.9rem;"><?php echo count($products); ?> products found</span>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Products Grid -->
        <?php if ($products): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="assets/uploads/<?php echo $product['image'] ?: 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             loading="lazy" 
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjNmNGY2Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJJbnRlciIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzZiNzI4MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                        <?php if ($product['rating'] >= 4.5): ?>
                            <div class="product-badge">Top Rated</div>
                        <?php elseif (isset($product['created_at']) && strtotime($product['created_at']) > strtotime('-30 days')): ?>
                            <div class="product-badge" style="background: var(--info-color);">New</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-category"><?php echo htmlspecialchars($product['category_name'] ?? 'Product'); ?></div>
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
                                <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-success btn-sm" title="Add to Cart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-secondary btn-sm" title="Login to Add to Cart">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center" style="padding: 4rem 2rem; background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <div style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    <i class="fas fa-search"></i>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary);">No products found</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">We couldn't find any products matching your criteria. Try adjusting your search terms or browse all products.</p>
                <div class="flex gap-3 justify-center">
                    <a href="products.php" class="btn btn-primary">
                        <i class="fas fa-th-large"></i>
                        View All Products
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function addToCart(productId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    // Show loading state
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
            // Show success state
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.style.background = 'var(--success-color)';
            
            // Show success notification
            showNotification('Product added to cart successfully!', 'success');
            
            // Update cart count in header
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            button.innerHTML = originalContent;
            button.disabled = false;
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        button.innerHTML = originalContent;
        button.disabled = false;
        showNotification('Error adding product to cart', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        color: white;
        font-weight: 600;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        ${type === 'success' ? 'background: var(--success-color);' : 'background: var(--danger-color);'}
    `;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span style="margin-left: 0.5rem;">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>