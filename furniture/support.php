<?php
require_once 'includes/functions.php';
$page_title = 'Customer Support';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Customer Support</h1>
    
    <div style="max-width: 800px; margin: 2rem auto;">
        <div class="support-section">
            <h3>Contact Information</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div class="contact-card">
                    <i class="fas fa-phone" style="font-size: 2rem; color: #8B4513; margin-bottom: 1rem;"></i>
                    <h4>Phone Support</h4>
                    <p>+91 1800-123-4567</p>
                    <p>Mon-Sat: 9 AM - 7 PM</p>
                </div>
                <div class="contact-card">
                    <i class="fas fa-envelope" style="font-size: 2rem; color: #8B4513; margin-bottom: 1rem;"></i>
                    <h4>Email Support</h4>
                    <p>support@interno.com</p>
                    <p>Response within 24 hours</p>
                </div>
                <div class="contact-card">
                    <i class="fas fa-comments" style="font-size: 2rem; color: #8B4513; margin-bottom: 1rem;"></i>
                    <h4>Live Chat</h4>
                    <p>Available on website</p>
                    <p>Mon-Sat: 9 AM - 7 PM</p>
                </div>
            </div>
        </div>
        
        <div class="support-section">
            <h3>Quick Links</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="orders.php" class="quick-link">Track Your Order</a>
                <a href="returns.php" class="quick-link">Return Policy</a>
                <a href="shipping-info.php" class="quick-link">Shipping Info</a>
                <a href="faq.php" class="quick-link">FAQ</a>
            </div>
        </div>
    </div>
</div>

<style>
.support-section {
    margin-bottom: 3rem;
}
.support-section h3 {
    color: #8B4513;
    margin-bottom: 2rem;
    text-align: center;
}
.contact-card {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #e9ecef;
}
.contact-card h4 {
    margin: 1rem 0 0.5rem 0;
    color: #333;
}
.quick-link {
    display: block;
    padding: 1rem;
    background: #8B4513;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s ease;
}
.quick-link:hover {
    background: #A0522D;
    transform: translateY(-2px);
}
</style>

<?php include 'includes/footer.php'; ?>