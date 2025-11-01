<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Edit Product';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

$product_id = (int)$_GET['id'];

// Get product
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    redirect('products.php');
}

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
    
    if (empty($name) || empty($description) || $price <= 0) {
        $error = 'Please fill in all required fields';
    } else {
        $query = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, stock_quantity = ?, status = ?, image = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$name, $description, $price, $category_id, $stock_quantity, $status, $image, $product_id])) {
            $success = 'Product updated successfully!';
            // Refresh product data
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Error updating product';
        }
    }
}

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Edit Product</h1>
        <div>
            <a href="products.php" class="btn btn-secondary">Back to Products</a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 800px;">
        <form method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div class="form-group">
                        <label for="name">Product Name: *</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (₹): *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo $product['price']; ?>" placeholder="Enter price in rupees">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category:</label>
                        <select id="category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity:</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo $product['stock_quantity']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image Filename:</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Description: *</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn" style="width: 100%;">Update Product</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>