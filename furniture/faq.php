<?php
require_once 'includes/functions.php';
$page_title = 'FAQ';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Frequently Asked Questions</h1>
    
    <div style="max-width: 800px; margin: 2rem auto;">
        <div class="faq-item">
            <h3>How do I place an order?</h3>
            <p>Browse products, add to cart, and proceed to checkout with your details.</p>
        </div>
        
        <div class="faq-item">
            <h3>What payment methods do you accept?</h3>
            <p>We accept Cash on Delivery, Credit/Debit Cards, and UPI payments.</p>
        </div>
        
        <div class="faq-item">
            <h3>How long does delivery take?</h3>
            <p>Standard delivery takes 5-7 business days, express delivery 2-3 days.</p>
        </div>
        
        <div class="faq-item">
            <h3>Can I return my order?</h3>
            <p>Yes, we offer 30-day returns for items in original condition.</p>
        </div>
        
        <div class="faq-item">
            <h3>Do you provide assembly service?</h3>
            <p>Yes, we provide free assembly service for most furniture items.</p>
        </div>
    </div>
</div>

<style>
.faq-item {
    background: #f8f9fa;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-radius: 8px;
    border-left: 4px solid #8B4513;
}
.faq-item h3 {
    color: #8B4513;
    margin-bottom: 0.5rem;
}
</style>

<?php include 'includes/footer.php'; ?>