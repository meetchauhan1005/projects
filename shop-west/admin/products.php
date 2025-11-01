<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Handle product actions
if ($_POST) {
    if (isset($_POST['add_product'])) {
        $image_name = 'default.jpg';
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image_name = time() . '_' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $image_name);
            }
        }
        
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $image_name]);
    } elseif (isset($_POST['edit_product'])) {
        $image_name = $_POST['current_image'];
        
        // Handle image upload for edit
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $image_name = time() . '_' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $image_name);
            }
        }
        
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=?, category_id=?, image=? WHERE id=?");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $image_name, $_POST['product_id']]);
    } elseif (isset($_POST['delete_product'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_POST['product_id']]);
    }
}

// Only fetch categories once
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

// Get product for editing first
$edit_product = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_product = $stmt->fetch();
}

// Fetch products with pagination for better performance
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$products = $pdo->prepare("SELECT p.id, p.name, p.price, p.stock_quantity, p.image, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT ? OFFSET ?");
$products->execute([$limit, $offset]);
$products = $products->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products Management - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav { background: #232f3e; padding: 15px 0; margin-bottom: 30px; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav h1 { color: #ff9900; margin: 0; }
        .admin-nav a { color: white; text-decoration: none; margin: 0 15px; padding: 8px 15px; border-radius: 5px; }
        .admin-nav a:hover { background: #ff9900; }
        .admin-table { background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 30px; }
        .admin-table h3 { background: #232f3e; color: white; padding: 20px; margin: 0; }
        .admin-table table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background: #f8f9fa; font-weight: 600; }
        .edit-btn { background: #28a745; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 3px; margin-right: 5px; }
        .delete-btn { background: #dc3545; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 3px; }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <h1><i class="fas fa-box"></i> Products Management</h1>
            <div>
                <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../index.php"><i class="fas fa-home"></i> View Site</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="admin-table">
            <h3><i class="fas fa-<?php echo $edit_product ? 'edit' : 'plus'; ?>"></i> <?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></h3>
            <div style="padding: 20px;">
                <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php if ($edit_product): ?>
                        <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $edit_product['image']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($edit_product && $edit_product['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" name="price" step="0.01" value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" value="<?php echo $edit_product ? $edit_product['stock_quantity'] : ''; ?>" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Product Image</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if ($edit_product && $edit_product['image']): ?>
                            <p style="margin-top: 5px; color: #666;">Current: <?php echo $edit_product['image']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Description</label>
                        <textarea name="description" rows="3" required><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                    </div>
                    <div style="grid-column: 1 / -1; display: flex; gap: 10px;">
                        <button type="submit" name="<?php echo $edit_product ? 'edit_product' : 'add_product'; ?>" class="btn"><?php echo $edit_product ? 'Update Product' : 'Add Product'; ?></button>
                        <?php if ($edit_product): ?>
                            <a href="products.php" class="btn" style="background: #6c757d;">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-table">
            <h3><i class="fas fa-list"></i> All Products</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><img src="../assets/uploads/<?php echo $product['image'] ?: 'default.jpg'; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; transition: none;" loading="lazy"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?: 'No Category'); ?></td>
                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                        <td>
                            <a href="products.php?edit=<?php echo $product['id']; ?>" class="edit-btn">Edit</a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="delete_product" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>