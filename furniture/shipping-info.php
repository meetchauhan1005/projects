<?php
require_once 'includes/functions.php';
$page_title = 'Shipping Information';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <h1>Shipping Information</h1>
    
    <div style="max-width: 800px; margin: 2rem auto;">
        <div class="info-section">
            <h3>Delivery Areas</h3>
            <p>We deliver across India to all major cities and towns.</p>
        </div>
        
        <div class="info-section">
            <h3>Shipping Charges</h3>
            <ul>
                <li>Free shipping on orders above ₹5,000</li>
                <li>Standard delivery: ₹200</li>
                <li>Express delivery: ₹500</li>
            </ul>
        </div>
        
        <div class="info-section">
            <h3>Delivery Time</h3>
            <ul>
                <li>Standard delivery: 5-7 business days</li>
                <li>Express delivery: 2-3 business days</li>
                <li>Metro cities: 3-5 business days</li>
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