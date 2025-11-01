<?php
session_start();
require_once '../config/database.php';

// Handle logout first
if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Simple admin check
if (!isset($_SESSION['admin']) && (!isset($_GET['admin']) || $_GET['admin'] !== 'login')) {
    if ($_POST && $_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body style="background: #f8f9fa;">
        <div class="form-container">
            <h2 style="text-align: center;">Admin Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn" style="width: 100%;">Login</button>
            </form>
            <p style="text-align: center; margin-top: 20px; color: #666;">
                Default: admin / admin123
            </p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Get dashboard stats
$stats = [];
try {
    $stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0;
    $stats['total_products'] = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0;
    $stats['total_orders'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?: 0;
    $stats['total_revenue'] = $pdo->query("SELECT SUM(total_amount) FROM orders")->fetchColumn() ?: 0;
} catch (Exception $e) {
    $stats = ['total_users' => 0, 'total_products' => 0, 'total_orders' => 0, 'total_revenue' => 0];
}

// Get recent orders
try {
    $recent_orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll() ?: [];
} catch (Exception $e) {
    $recent_orders = [];
}

// Get low stock products
try {
    $low_stock = $pdo->query("SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC LIMIT 5")->fetchAll() ?: [];
} catch (Exception $e) {
    $low_stock = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Shop West</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav {
            background: #232f3e;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .admin-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav h1 {
            color: #ff9900;
            margin: 0;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .admin-nav a:hover {
            background: #ff9900;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        .admin-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .admin-table h3 {
            background: #232f3e;
            color: white;
            padding: 20px;
            margin: 0;
        }
        .admin-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .admin-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <div>
                <a href="products.php"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="users.php"><i class="fas fa-users"></i> Users</a>
                <a href="../index.php"><i class="fas fa-home"></i> View Site</a>
                <a href="?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users" style="color: #28a745;"></i>
                <h3><?php echo number_format($stats['total_users']); ?></h3>
                <p>Total Users</p>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-box" style="color: #17a2b8;"></i>
                <h3><?php echo number_format($stats['total_products']); ?></h3>
                <p>Total Products</p>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-shopping-cart" style="color: #ffc107;"></i>
                <h3><?php echo number_format($stats['total_orders']); ?></h3>
                <p>Total Orders</p>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-rupee-sign" style="color: #dc3545;"></i>
                <h3>₹<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="admin-table">
                <h3><i class="fas fa-clock"></i> Recent Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;"><?php echo ucfirst($order['status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-table">
                <h3><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><span style="color: #dc3545; font-weight: bold;"><?php echo $product['stock_quantity']; ?></span></td>
                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>