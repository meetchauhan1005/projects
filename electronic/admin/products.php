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

// Handle file upload
function uploadImage($file) {
    $target_dir = "../assets/images/";
    
    // Validate file
    if(!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    // Check file size (2MB max)
    if ($file["size"] > 2097152) {
        return false;
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if(!in_array($mime_type, $allowed_types)) {
        return false;
    }
    
    // Generate secure filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if(!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    $new_filename = bin2hex(random_bytes(16)) . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Ensure directory exists
    if(!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    }
    return false;
}

// Handle product actions
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if(isset($_POST['action'])) {
            // Input validation and sanitization
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
            $category_id = filter_var($_POST['category_id'] ?? 0, FILTER_VALIDATE_INT);
            $stock_quantity = filter_var($_POST['stock_quantity'] ?? 0, FILTER_VALIDATE_INT);
            $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
            
            switch($_POST['action']) {
                case 'add':
                    // Validate required fields
                    if(empty($name) || $price === false || $category_id === false) {
                        $message = 'Please fill all required fields with valid data.';
                        $message_type = 'danger';
                        break;
                    }
                    
                    $image_name = '';
                    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $image_name = uploadImage($_FILES['image']);
                        if(!$image_name) {
                            $message = 'Error uploading image. Please check file format and size.';
                            $message_type = 'danger';
                            break;
                        }
                    }
                    
                    $query = "INSERT INTO products (name, description, price, category_id, image, stock_quantity, status) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    
                    if($stmt->execute([$name, $description, $price, $category_id, $image_name, $stock_quantity, $status])) {
                        $message = 'Product added successfully!';
                        $message_type = 'success';
                    } else {
                        $message = 'Error adding product.';
                        $message_type = 'danger';
                    }
                    break;
                    
                case 'edit':
                    $product_id = filter_var($_POST['product_id'] ?? 0, FILTER_VALIDATE_INT);
                    
                    // Validate required fields
                    if(empty($name) || $price === false || $category_id === false || !$product_id) {
                        $message = 'Please fill all required fields with valid data.';
                        $message_type = 'danger';
                        break;
                    }
                    
                    $image_name = $_POST['current_image'] ?? '';
                    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $new_image = uploadImage($_FILES['image']);
                        if($new_image) {
                            // Delete old image safely
                            if($image_name && file_exists("../assets/images/" . basename($image_name))) {
                                unlink("../assets/images/" . basename($image_name));
                            }
                            $image_name = $new_image;
                        }
                    }
                    
                    $query = "UPDATE products SET name = ?, description = ?, price = ?, 
                             category_id = ?, image = ?, stock_quantity = ?, status = ? WHERE id = ?";
                    $stmt = $db->prepare($query);
                    
                    if($stmt->execute([$name, $description, $price, $category_id, $image_name, $stock_quantity, $status, $product_id])) {
                        $message = 'Product updated successfully!';
                        $message_type = 'success';
                    } else {
                        $message = 'Error updating product.';
                        $message_type = 'danger';
                    }
                    break;
                    
                case 'delete':
                    $product_id = filter_var($_POST['product_id'] ?? 0, FILTER_VALIDATE_INT);
                    
                    if(!$product_id) {
                        $message = 'Invalid product ID.';
                        $message_type = 'danger';
                        break;
                    }
                    
                    // Get image name to delete file
                    $query = "SELECT image FROM products WHERE id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if($product) {
                        // Delete product
                        $query = "DELETE FROM products WHERE id = ?";
                        $stmt = $db->prepare($query);
                        
                        if($stmt->execute([$product_id])) {
                            // Delete image file safely
                            if($product['image'] && file_exists("../assets/images/" . basename($product['image']))) {
                                unlink("../assets/images/" . basename($product['image']));
                            }
                            $message = 'Product deleted successfully!';
                            $message_type = 'success';
                        } else {
                            $message = 'Error deleting product.';
                            $message_type = 'danger';
                        }
                    } else {
                        $message = 'Product not found.';
                        $message_type = 'danger';
                    }
                    break;
            }
        }
    } catch(PDOException $e) {
        error_log('Products error: ' . $e->getMessage());
        $message = 'Database error occurred.';
        $message_type = 'danger';
    } catch(Exception $e) {
        error_log('Products error: ' . $e->getMessage());
        $message = 'An error occurred. Please try again.';
        $message_type = 'danger';
    }
}

// Get products
try {
    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $products = [];
}

// Get categories for dropdown
try {
    $cat_query = "SELECT * FROM categories ORDER BY name";
    $cat_stmt = $db->prepare($cat_query);
    $cat_stmt->execute();
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-gradient: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        
        .sidebar { 
            background: var(--sidebar-gradient); 
            min-height: 100vh; 
            position: fixed; 
            width: 280px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .main-content { 
            margin-left: 280px; 
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
                padding-top: 80px;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
                vertical-align: middle;
            }
            
            .product-image {
                width: 40px;
                height: 40px;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-lg {
                max-width: calc(100% - 1rem);
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 10px;
            }
            
            .card-body {
                padding: 0.5rem;
            }
            
            .table th,
            .table td {
                padding: 0.25rem;
                font-size: 0.8rem;
            }
            
            .product-image {
                width: 30px;
                height: 30px;
            }
            
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .form-control {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .nav-link { 
            color: #bdc3c7; 
            padding: 15px 25px; 
            transition: background-color 0.2s ease;
            border-radius: 0;
        }
        
        .nav-link:hover, .nav-link.active { 
            background: rgba(52, 152, 219, 0.2); 
            color: #fff;
        }
        
        .card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .btn-primary { 
            background: var(--primary-gradient); 
            border: none; 
            border-radius: 25px;
            padding: 12px 25px;
            font-weight: 600;
        }
        
        .modal-content { border-radius: 20px; }
        .form-control { border-radius: 15px; }
        .table th { border: none; background: #f8f9fa; }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 15px;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 15px;
            margin-top: 10px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .modal {
            backdrop-filter: blur(5px);
        }
        
        .btn {
            transition: background-color 0.2s ease;
        }
        
        .alert {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4 text-white border-bottom">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <img src="../assets/images/logo.svg" alt="Mahadev Electronic" height="35" class="me-2">
                    <h5 class="mb-0">Mahadev Electronic</h5>
                </div>
                <button class="btn btn-sm btn-outline-light d-md-none" onclick="closeSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <small class="text-light">Admin Dashboard</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link active" href="products.php">
                <i class="fas fa-box me-3"></i> Products
            </a>
            <a class="nav-link" href="categories.php">
                <i class="fas fa-tags me-3"></i> Categories
            </a>
            <a class="nav-link" href="orders.php">
                <i class="fas fa-shopping-cart me-3"></i> Orders
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
                <h1 class="h3 mb-0">Products Management</h1>
                <p class="text-muted">Manage your product inventory</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-2"></i>Add Product
            </button>
        </div>

        <?php if($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
            <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Image</th>
                                <th>Product Details</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($products) > 0): ?>
                                <?php foreach($products as $product): ?>
                                <tr>
                                    <td class="ps-4">
                                        <img src="../assets/images/<?php echo $product['image']; ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                             class="product-image"
                                             onerror="this.src='https://via.placeholder.com/60x60/f8f9fa/6c757d?text=No+Image'">
                                    </td>
                                    <td>
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8'); ?>...</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td class="fw-bold text-success">₹<?php echo number_format((float)$product['price']); ?></td>
                                    <td><?php echo (int)$product['stock_quantity']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $product['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($product['status']), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-1" 
                                                onclick="editProduct(<?php echo htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No products found. Add your first product!</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus text-primary me-2"></i>Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="price" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock_quantity" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this, 'addPreview')">
                            <img id="addPreview" class="image-preview" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit text-primary me-2"></i>Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <input type="hidden" name="current_image" id="edit_current_image">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_category_id" class="form-label">Category</label>
                                <select class="form-control" name="category_id" id="edit_category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_price" class="form-label">Price (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="edit_price" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-control" name="status" id="edit_status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this, 'editPreview')">
                            <img id="editPreview" class="image-preview">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        // Close sidebar when clicking on nav links on mobile
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
        function previewImage(input, previewId) {
            try {
                const preview = document.getElementById(previewId);
                if (!preview) return;
                
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    if (file.size > 2097152) {
                        alert('File size must be less than 2MB');
                        input.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.onerror = function() {
                        alert('Error reading file');
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            } catch(error) {
                console.error('Error previewing image:', error);
            }
        }
        
        function editProduct(product) {
            try {
                document.getElementById('edit_product_id').value = product.id || '';
                document.getElementById('edit_name').value = product.name || '';
                document.getElementById('edit_description').value = product.description || '';
                document.getElementById('edit_price').value = product.price || '';
                document.getElementById('edit_category_id').value = product.category_id || '';
                document.getElementById('edit_stock_quantity').value = product.stock_quantity || '';
                document.getElementById('edit_status').value = product.status || 'active';
                document.getElementById('edit_current_image').value = product.image || '';
                
                const preview = document.getElementById('editPreview');
                if(product.image) {
                    preview.src = '../assets/images/' + product.image;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }
                
                const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                modal.show();
            } catch(error) {
                console.error('Error opening edit modal:', error);
            }
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        setTimeout(function() {
                            submitBtn.disabled = false;
                        }, 3000);
                    }
                });
            });
        });
    </script>
</body>
</html>