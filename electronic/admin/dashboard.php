<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Get statistics with error handling
$stats = ['products' => 0, 'categories' => 0, 'orders' => 0, 'new_messages' => 0];
$recent_products = [];

try {
    $query = "SELECT COUNT(*) as count FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['products'] = $result ? (int)$result['count'] : 0;

    $query = "SELECT COUNT(*) as count FROM categories";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['categories'] = $result ? (int)$result['count'] : 0;

    $query = "SELECT COUNT(*) as count FROM orders";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['orders'] = $result ? (int)$result['count'] : 0;

    $query = "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'new'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['new_messages'] = $result ? (int)$result['count'] : 0;

    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.created_at DESC LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recent_products = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch(Exception $e) {
    error_log('Dashboard stats error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mahadev Electronic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { 
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); 
            min-height: 100vh; 
            position: fixed; 
            width: 280px; 
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .main-content { 
            margin-left: 280px; 
            padding: 20px; 
            transition: margin-left 0.3s ease;
        }
        .nav-link { 
            color: #bdc3c7; 
            padding: 15px 25px; 
            border-radius: 0; 
            transition: all 0.3s; 
            display: flex;
            align-items: center;
        }
        .nav-link:hover, .nav-link.active { 
            background: rgba(52, 152, 219, 0.2); 
            color: #fff; 
        }
        .stats-card { 
            background: linear-gradient(135deg, #3498db, #2980b9); 
            color: white; 
            border-radius: 15px; 
            transition: transform 0.3s; 
            margin-bottom: 1rem;
        }
        .stats-card:hover { transform: translateY(-5px); }
        .card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08); 
        }
        .btn-primary { 
            background: linear-gradient(45deg, #3498db, #2980b9); 
            border: none; 
            border-radius: 25px; 
        }
        .navbar-brand { font-size: 1.5rem; font-weight: bold; }
        
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
            
            .stats-card .card-body {
                padding: 1.5rem;
                text-align: center;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
                vertical-align: middle;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }
            
            .d-flex.gap-2 {
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 10px;
            }
            
            .stats-card h2 {
                font-size: 1.5rem;
            }
            
            .card-header h5 {
                font-size: 1rem;
            }
            
            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
            }
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
            <a class="nav-link active" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
            <a class="nav-link" href="products.php">
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
                <?php if($stats['new_messages'] > 0): ?>
                <span class="badge bg-danger ms-2"><?php echo $stats['new_messages']; ?></span>
                <?php endif; ?>
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
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Dashboard Overview</h1>
                <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?>!</p>
            </div>
            <div class="d-flex gap-2">
                <a href="products.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-box fa-3x mb-3 opacity-75"></i>
                        <h2 class="mb-1"><?php echo (int)$stats['products']; ?></h2>
                        <p class="mb-0">Total Products</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-tags fa-3x mb-3 opacity-75"></i>
                        <h2 class="mb-1"><?php echo (int)$stats['categories']; ?></h2>
                        <p class="mb-0">Categories</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3 opacity-75"></i>
                        <h2 class="mb-1"><?php echo (int)$stats['orders']; ?></h2>
                        <p class="mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-bell fa-3x mb-3 opacity-75"></i>
                        <h2 class="mb-1"><?php echo (int)$stats['new_messages']; ?></h2>
                        <p class="mb-0">New Messages</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="fas fa-clock text-primary me-2"></i>Recent Products</h5>
                <a href="products.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                <?php if(count($recent_products) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Product</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Stock</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_products as $product): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="../assets/images/<?php echo htmlspecialchars($product['image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($product['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 40), ENT_QUOTES, 'UTF-8'); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($product['category_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td class="fw-bold text-success">₹<?php echo number_format((float)($product['price'] ?? 0)); ?></td>
                                <td><?php echo (int)($product['stock_quantity'] ?? 0); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($product['status'] ?? '') == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($product['status'] ?? 'inactive'), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-muted"><?php echo $product['created_at'] ? htmlspecialchars(date('M j, Y', strtotime($product['created_at'])), ENT_QUOTES, 'UTF-8') : 'N/A'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No products found.</p>
                </div>
                <?php endif; ?>
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
    </script>
</body>
</html>