<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Add Product';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Get categories
$query = "SELECT * FROM categories ORDER BY name";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $stock_quantity = (int)$_POST['stock_quantity'];
    $status = sanitize($_POST['status']);
    $image = sanitize($_POST['image']);
    
    // Handle image upload
    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image_upload']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed) && $_FILES['image_upload']['size'] < 5000000) {
            $new_filename = time() . '_' . $filename;
            $upload_path = '../assets/images/products/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $upload_path)) {
                $image = $new_filename;
            }
        }
    }
    
    if (empty($name) || empty($description) || $price <= 0) {
        $error = 'Please fill in all required fields';
    } else {
        $query = "INSERT INTO products (name, description, price, category_id, stock_quantity, status, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$name, $description, $price, $category_id, $stock_quantity, $status, $image])) {
            $success = 'Product added successfully!';
            $_POST = [];
        } else {
            $error = 'Error adding product';
        }
    }
}

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Add New Product</h1>
        <div>
            <a href="products.php" class="btn btn-secondary">Back to Products</a>
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 800px;">
        <form method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div class="form-group">
                        <label for="name">Product Name: *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (₹): *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required 
                               value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>" placeholder="Enter price in rupees">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category:</label>
                        <select id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity:</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" 
                               value="<?php echo isset($_POST['stock_quantity']) ? $_POST['stock_quantity'] : '0'; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image_upload">Upload Image:</label>
                        <input type="file" id="image_upload" name="image_upload" accept="image/*">
                        <small style="color: #666;">Or enter filename manually:</small>
                        <input type="text" name="image" placeholder="e.g., sofa1.jpg" style="margin-top: 0.5rem;"
                               value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image']) : ''; ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Description: *</label>
                <textarea id="description" name="description" rows="4" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn" style="width: 100%;">Add Product</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>