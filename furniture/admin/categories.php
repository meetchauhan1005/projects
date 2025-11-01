<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Manage Categories';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Handle category deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = $db->prepare($query);
    if ($stmt->execute([$id])) {
        $success = 'Category deleted successfully!';
    } else {
        $error = 'Error deleting category';
    }
}

// Handle add category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    
    if (empty($name)) {
        $error = 'Category name is required';
    } else {
        $query = "INSERT INTO categories (name, description, created_at) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$name, $description])) {
            $success = 'Category added successfully!';
        } else {
            $error = 'Error adding category';
        }
    }
}

// Get all categories
$query = "SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.name";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Manage Categories</h1>
        <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Add Category Form -->
    <div style="background: white; padding: 2rem; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 2rem;">
        <h2 style="color: #8B4513; margin-bottom: 1.5rem;">Add New Category</h2>
        <form method="POST" style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="name">Category Name: *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description">
            </div>
            <button type="submit" name="add_category" class="btn">Add Category</button>
        </form>
    </div>

    <!-- Categories List -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="cart-table">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($category['description']); ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; background: #d4edda; color: #155724;">
                                    <?php echo $category['product_count']; ?> products
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($category['created_at'])); ?></td>
                            <td>
                                <a href="?delete=<?php echo $category['id']; ?>" 
                                   onclick="return confirm('Are you sure? This will affect <?php echo $category['product_count']; ?> products.')"
                                   style="padding: 0.5rem; background: #dc3545; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>