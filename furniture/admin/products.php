<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Manage Products';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $db->prepare($query);
    if ($stmt->execute([$id])) {
        $success = 'Product deleted successfully!';
    } else {
        $error = 'Error deleting product';
    }
}

// Get all products
$query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Manage Products</h1>
        <div>
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
            <a href="add-product.php" class="btn">Add New Product</a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="cart-table">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                <br>
                                <small style="color: #666;"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</small>
                            </td>
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'No Category'); ?></td>
                            <td><?php echo formatPrice($product['price']); ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; 
                                             background: <?php echo $product['stock_quantity'] > 0 ? '#d4edda' : '#f8d7da'; ?>; 
                                             color: <?php echo $product['stock_quantity'] > 0 ? '#155724' : '#721c24'; ?>;">
                                    <?php echo $product['stock_quantity']; ?>
                                </span>
                            </td>
                            <td>
                                <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; 
                                             background: <?php echo $product['status'] == 'active' ? '#d4edda' : '#f8d7da'; ?>; 
                                             color: <?php echo $product['status'] == 'active' ? '#155724' : '#721c24'; ?>;">
                                    <?php echo ucfirst($product['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" 
                                       style="padding: 0.5rem; background: #007bff; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $product['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this product?')"
                                       style="padding: 0.5rem; background: #dc3545; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>