<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Handle user deletion
if ($_POST && isset($_POST['delete_user'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['user_id']]);
}

$users = $pdo->query("SELECT u.*, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id = o.user_id GROUP BY u.id ORDER BY u.created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users Management - Admin</title>
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
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <h1><i class="fas fa-users"></i> Users Management</h1>
            <div>
                <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="products.php"><i class="fas fa-box"></i> Products</a>
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="../index.php"><i class="fas fa-home"></i> View Site</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="admin-table">
            <h3><i class="fas fa-list"></i> All Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone'] ?: 'N/A'); ?></td>
                        <td><?php echo $user['order_count']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn" style="background: #dc3545; padding: 5px 10px; font-size: 12px;" onclick="return confirm('Delete this user?')">Delete</button>
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