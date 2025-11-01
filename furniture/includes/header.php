<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Interno</title>
    <link rel="stylesheet" href="<?php echo isset($base_path) ? $base_path : ''; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="logo">
                    Interno
                </a>
                
                <button class="mobile-menu" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <nav id="main-nav">
                    <ul>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">Home</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>products.php">Products</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>categories.php">Categories</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>about.php">About</a></li>
                        <li><a href="<?php echo isset($base_path) ? $base_path : ''; ?>contact.php">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <form action="<?php echo isset($base_path) ? $base_path : ''; ?>search.php" method="GET" style="display: flex; align-items: center;">
                        <input type="text" name="q" placeholder="Search furniture..." class="search-box" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit" style="background: #8B4513; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0 5px 5px 0; cursor: pointer; margin-left: -1px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>cart.php" class="cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <?php 
                            $cart_count = getCartCount();
                            if ($cart_count > 0): 
                            ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                            <span style="color: #333; font-weight: 500;">Hello, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                            <div style="display: flex; gap: 0.5rem;">
                                <?php if (isAdmin()): ?>
                                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/dashboard.php" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Admin</a>
                                <?php endif; ?>
                                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>orders.php" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Orders</a>
                                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>user/profile.php" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Profile</a>
                                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>logout.php" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>login.php" class="btn">Login</a>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>register.php" class="btn btn-secondary">Register</a>
                        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/login.php" style="color: #8B4513; text-decoration: none; font-size: 0.9rem; margin-left: 1rem;">
                            <i class="fas fa-shield-alt"></i> Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <main>