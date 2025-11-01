<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Interno Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .admin-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .admin-logo::before {
            content: '🛡️';
            font-size: 1.5rem;
        }
        .admin-menu {
            display: flex;
            gap: 1rem;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }
        .admin-menu a {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        .admin-menu a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .admin-user span {
            font-weight: 600;
        }
        .admin-user a {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .admin-user a:hover {
            background: rgba(255,255,255,0.1);
        }
        .admin-body {
            padding-top: 0 !important;
            background: #f8fafc;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        @media (max-width: 768px) {
            .admin-nav {
                flex-direction: column;
                text-align: center;
            }
            .admin-menu {
                justify-content: center;
            }
            .admin-user {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <a href="dashboard.php" class="admin-logo">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </a>
                
                <ul class="admin-menu">
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                </ul>
                
                <div class="admin-user">
                    <span>Hello, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    <a href="../index.php" style="color: white; text-decoration: none;">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                    <a href="../logout.php" style="color: #e74c3c; text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <main>