<?php
require_once 'includes/functions.php';
$page_title = 'Categories';

$categories = getCategories();

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1 style="text-align: center; margin-bottom: 3rem;">Product Categories</h1>
    
    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
            <div class="category-card">
                <div class="category-icon">
                    <?php
                    $icons = [
                        'Sofas & Chairs' => 'fas fa-couch',
                        'Tables' => 'fas fa-table',
                        'Bedroom' => 'fas fa-bed',
                        'Storage' => 'fas fa-archive',
                        'Office' => 'fas fa-chair'
                    ];
                    echo '<i class="' . ($icons[$category['name']] ?? 'fas fa-home') . '"></i>';
                    ?>
                </div>
                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                <p><?php echo htmlspecialchars($category['description']); ?></p>
                <a href="products.php?category=<?php echo $category['id']; ?>" class="btn">View Products</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>