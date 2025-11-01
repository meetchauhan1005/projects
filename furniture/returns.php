<?php
require_once 'includes/functions.php';
$page_title = 'Returns & Refunds';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Returns & Refunds</h1>
    
    <div style="max-width: 800px; margin: 2rem auto;">
        <div class="info-section">
            <h3>Return Policy</h3>
            <p>We offer 30-day return policy for all furniture items in original condition.</p>
        </div>
        
        <div class="info-section">
            <h3>Return Process</h3>
            <ol>
                <li>Contact our support team</li>
                <li>Schedule pickup</li>
                <li>Pack items securely</li>
                <li>Refund processed within 7-10 days</li>
            </ol>
        </div>
        
        <div class="info-section">
            <h3>Refund Policy</h3>
            <ul>
                <li>Full refund for defective items</li>
                <li>Return shipping charges may apply</li>
                <li>Refunds processed to original payment method</li>
            </ul>
        </div>
    </div>
</div>

<style>
.info-section {
    background: #f8f9fa;
    padding: 2rem;
    margin-bottom: 2rem;
    border-radius: 8px;
}
.info-section h3 {
    color: #8B4513;
    margin-bottom: 1rem;
}
</style>

<?php include 'includes/footer.php'; ?>