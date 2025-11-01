<?php
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$page_title = 'Admin Dashboard';

$database = new Database();
$db = $database->getConnection();

// Get statistics with error handling
$stats = [];
try {
    $stats['users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0;
} catch(Exception $e) {
    $stats['users'] = 0;
}

try {
    $stats['products'] = $db->query("SELECT COUNT(*) FROM products")->fetchColumn() ?: 0;
} catch(Exception $e) {
    $stats['products'] = 0;
}

try {
    $stats['orders'] = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?: 0;
} catch(Exception $e) {
    $stats['orders'] = 0;
}

try {
    $stats['categories'] = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn() ?: 0;
} catch(Exception $e) {
    $stats['categories'] = 0;
}

include 'includes/admin_header.php';
?>

<div class="container" style="padding: 2rem;">
    <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); margin-bottom: 3rem; border: 1px solid rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: #2d3748; margin-bottom: 0.5rem; font-size: 2.5rem; font-weight: 800;">Dashboard</h1>
                <p style="color: #718096; font-size: 1.1rem;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong></p>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="../index.php" style="padding: 0.75rem 1.5rem; background: #e2e8f0; color: #2d3748; text-decoration: none; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <a href="../logout.php" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #e53e3e, #c53030); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div style="background: linear-gradient(135deg, #4299e1, #3182ce); color: white; padding: 2.5rem; border-radius: 20px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 4px 20px rgba(66, 153, 225, 0.3);">
            <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-users" style="font-size: 2rem;"></i>
            </div>
            <h3 style="font-size: 3rem; margin-bottom: 0.5rem; font-weight: 800;"><?php echo $stats['users']; ?></h3>
            <p style="font-size: 1.1rem; opacity: 0.9;">Total Users</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; padding: 2.5rem; border-radius: 20px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 4px 20px rgba(72, 187, 120, 0.3);">
            <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-box" style="font-size: 2rem;"></i>
            </div>
            <h3 style="font-size: 3rem; margin-bottom: 0.5rem; font-weight: 800;"><?php echo $stats['products']; ?></h3>
            <p style="font-size: 1.1rem; opacity: 0.9;">Total Products</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; padding: 2.5rem; border-radius: 20px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 4px 20px rgba(237, 137, 54, 0.3);">
            <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-shopping-cart" style="font-size: 2rem;"></i>
            </div>
            <h3 style="font-size: 3rem; margin-bottom: 0.5rem; font-weight: 800;"><?php echo $stats['orders']; ?></h3>
            <p style="font-size: 1.1rem; opacity: 0.9;">Total Orders</p>
        </div>
        
        <div style="background: linear-gradient(135deg, #9f7aea, #805ad5); color: white; padding: 2.5rem; border-radius: 20px; text-align: center; transition: transform 0.3s ease; box-shadow: 0 4px 20px rgba(159, 122, 234, 0.3);">
            <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-tags" style="font-size: 2rem;"></i>
            </div>
            <h3 style="font-size: 3rem; margin-bottom: 0.5rem; font-weight: 800;"><?php echo $stats['categories']; ?></h3>
            <p style="font-size: 1.1rem; opacity: 0.9;">Categories</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
        <h2 style="color: #2d3748; margin-bottom: 2rem; font-size: 1.8rem; font-weight: 700;">Quick Actions</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <a href="products.php" style="display: block; padding: 2rem; background: linear-gradient(135deg, #f7fafc, #edf2f7); border-radius: 16px; text-decoration: none; color: #2d3748; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);">
                <div style="background: linear-gradient(135deg, #48bb78, #38a169); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                    <i class="fas fa-box" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="margin-bottom: 0.5rem; font-weight: 600;">Manage Products</h4>
                <p style="color: #718096; font-size: 0.9rem;">Add, edit, delete products</p>
            </a>
            
            <a href="categories.php" style="display: block; padding: 2rem; background: linear-gradient(135deg, #f7fafc, #edf2f7); border-radius: 16px; text-decoration: none; color: #2d3748; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);">
                <div style="background: linear-gradient(135deg, #9f7aea, #805ad5); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                    <i class="fas fa-tags" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="margin-bottom: 0.5rem; font-weight: 600;">Manage Categories</h4>
                <p style="color: #718096; font-size: 0.9rem;">Organize product categories</p>
            </a>
            
            <a href="orders.php" style="display: block; padding: 2rem; background: linear-gradient(135deg, #f7fafc, #edf2f7); border-radius: 16px; text-decoration: none; color: #2d3748; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);">
                <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                    <i class="fas fa-shopping-cart" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="margin-bottom: 0.5rem; font-weight: 600;">View Orders</h4>
                <p style="color: #718096; font-size: 0.9rem;">Process customer orders</p>
            </a>
            
            <a href="users.php" style="display: block; padding: 2rem; background: linear-gradient(135deg, #f7fafc, #edf2f7); border-radius: 16px; text-decoration: none; color: #2d3748; text-align: center; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);">
                <div style="background: linear-gradient(135deg, #4299e1, #3182ce); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                    <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="margin-bottom: 0.5rem; font-weight: 600;">Manage Users</h4>
                <p style="color: #718096; font-size: 0.9rem;">View and manage users</p>
            </a>
        </div>
    </div>
</div>

<style>
.admin-action:hover {
    background: #e9ecef !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column !important;
        text-align: center !important;
    }
    
    .admin-header > div:first-child {
        margin-bottom: 1rem;
    }
    
    .admin-header > div:last-child {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
}
</style>

<?php include 'includes/admin_footer.php'; ?>