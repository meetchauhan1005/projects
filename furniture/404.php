<?php
require_once 'includes/functions.php';
$page_title = '404 - Page Not Found';

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem; text-align: center; padding: 4rem 0;">
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="font-size: 6rem; color: #8B4513; margin-bottom: 1rem;">404</h1>
        <h2 style="color: #333; margin-bottom: 2rem;">Page Not Found</h2>
        <p style="font-size: 1.2rem; color: #666; margin-bottom: 3rem;">
            Sorry, the page you are looking for doesn't exist or has been moved.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="index.php" class="btn">Go Home</a>
            <a href="products.php" class="btn btn-secondary">Browse Products</a>
            <a href="contact.php" class="btn btn-secondary">Contact Us</a>
        </div>
        
        <div style="margin-top: 3rem; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
            <h3 style="color: #8B4513; margin-bottom: 1rem;">What can you do?</h3>
            <ul style="text-align: left; max-width: 400px; margin: 0 auto;">
                <li>Check the URL for typos</li>
                <li>Go back to the previous page</li>
                <li>Visit our homepage</li>
                <li>Browse our product catalog</li>
                <li>Contact our support team</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>