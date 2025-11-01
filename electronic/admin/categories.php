<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

$message = '';
$message_type = '';

// Handle category actions
if($_POST) {
    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'add':
                $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $_POST['name']);
                $stmt->bindParam(':description', $_POST['description']);
                if($stmt->execute()) {
                    $message = 'Category added successfully!';
                    $message_type = 'success';
                }
                break;
                
            case 'delete':
                try {
                    // First, update products to remove category reference
                    $query = "UPDATE products SET category_id = NULL WHERE category_id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':id', $_POST['category_id']);
                    $stmt->execute();
                    
                    // Then delete the category
                    $query = "DELETE FROM categories WHERE id = :id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':id', $_POST['category_id']);
                    if($stmt->execute()) {
                        $message = 'Category deleted successfully! Products moved to uncategorized.';
                        $message_type = 'success';
                    }
                } catch(Exception $e) {
                    $message = 'Error deleting category: ' . $e->getMessage();
                    $message_type = 'danger';
                }
                break;
                
            case 'edit':
                $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $_POST['name']);
                $stmt->bindParam(':description', $_POST['description']);
                $stmt->bindParam(':id', $_POST['category_id']);
                if($stmt->execute()) {
                    $message = 'Category updated successfully!';
                    $message_type = 'success';
                }
                break;
        }
    }
}

// Get categories with product count
$query = "SELECT c.*, COUNT(p.id) as product_count FROM categories c 
          LEFT JOIN products p ON c.id = p.category_id 
          GROUP BY c.id ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); min-height: 100vh; position: fixed; width: 280px; }
        .main-content { margin-left: 280px; padding: 20px; }
        .nav-link { color: #bdc3c7; padding: 15px 25px; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(52, 152, 219, 0.2); color: #fff; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .btn-primary { background: linear-gradient(45deg, #3498db, #2980b9); border: none; border-radius: 25px; }
        .category-card { transition: transform 0.3s ease; }
        .category-card:hover { transform: translateY(-5px); }
        .modal-content { border-radius: 20px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-4 text-white border-bottom">
            <div class="d-flex align-items-center mb-2">
                <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="35" class="me-2">
                <h5 class="mb-0">Mahadev Electronic</h5>
            </div>
            <small class="text-light">Admin Dashboard</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link" href="products.php">
                <i class="fas fa-box me-3"></i> Products
            </a>
            <a class="nav-link active" href="categories.php">
                <i class="fas fa-tags me-3"></i> Categories
            </a>
            <a class="nav-link" href="messages.php">
                <i class="fas fa-envelope me-3"></i> Messages
            </a>
            <hr class="text-light mx-3">
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt me-3"></i> View Website
            </a>
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt me-3"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Categories Management</h1>
                <p class="text-muted">Organize your products by categories</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-2"></i>Add Category
            </button>
        </div>

        <?php if($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
            <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Categories Grid -->
        <div class="row">
            <?php foreach($categories as $category): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card category-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-tags fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($category['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-primary"><?php echo $category['product_count']; ?> Products</span>
                            <small class="text-muted"><?php echo date('M j, Y', strtotime($category['created_at'])); ?></small>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="editCategory(<?php echo $category['id']; ?>, '<?php echo addslashes($category['name']); ?>', '<?php echo addslashes($category['description']); ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this category?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(count($categories) == 0): ?>
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                        <h4>No Categories Found</h4>
                        <p class="text-muted">Start by adding your first product category</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fas fa-plus me-2"></i>Add First Category
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus text-primary me-2"></i>Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g., Mobile Phones">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Brief description of this category"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit text-primary me-2"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="category_id" id="edit_category_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editCategory(id, name, description) {
            document.getElementById('edit_category_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
    </script>
</body>
</html>