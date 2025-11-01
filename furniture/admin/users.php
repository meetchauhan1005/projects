<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Manage Users';
$success = '';
$error = '';

$database = new Database();
$db = $database->getConnection();

// Handle user role update
if (isset($_POST['update_role'])) {
    $user_id = (int)$_POST['user_id'];
    $role = sanitize($_POST['role']);
    
    $query = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    if ($stmt->execute([$role, $user_id])) {
        $success = 'User role updated successfully!';
    } else {
        $error = 'Error updating user role';
    }
}

// Get all users
$query = "SELECT * FROM users ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/admin_header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #8B4513;">Manage Users</h1>
        <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
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
                        <th>ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <select name="role" onchange="this.form.submit()" 
                                            style="padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; border: 1px solid #ddd;
                                                   background: <?php echo $user['role'] == 'admin' ? '#d4edda' : '#f8f9fa'; ?>; 
                                                   color: <?php echo $user['role'] == 'admin' ? '#155724' : '#333'; ?>;">
                                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <input type="hidden" name="update_role" value="1">
                                </form>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="../user/profile.php?id=<?php echo $user['id']; ?>" 
                                       style="padding: 0.5rem; background: #007bff; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="?delete=<?php echo $user['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this user?')"
                                           style="padding: 0.5rem; background: #dc3545; color: white; border-radius: 6px; text-decoration: none; font-size: 0.8rem;">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
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