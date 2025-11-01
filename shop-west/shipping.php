<?php
$page_title = "Shipping Information";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3rem; color: #232f3e; margin-bottom: 20px;">Shipping Information</h1>
            <p style="font-size: 1.2rem; color: #666;">Fast and reliable delivery options</p>
        </div>

        <div style="max-width: 900px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 50px;">
                
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; text-align: center;">
                    <i class="fas fa-truck" style="font-size: 3rem; color: #28a745; margin-bottom: 20px;"></i>
                    <h3 style="color: #232f3e; margin-bottom: 15px;">Standard Shipping</h3>
                    <p style="color: #666; margin-bottom: 15px;">3-5 Business Days</p>
                    <p style="font-size: 1.2rem; font-weight: bold; color: #28a745;">FREE on orders $50+</p>
                    <p style="color: #666; font-size: 14px;">$5.99 for orders under $50</p>
                </div>
                
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; text-align: center;">
                    <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #ff9900; margin-bottom: 20px;"></i>
                    <h3 style="color: #232f3e; margin-bottom: 15px;">Express Shipping</h3>
                    <p style="color: #666; margin-bottom: 15px;">1-2 Business Days</p>
                    <p style="font-size: 1.2rem; font-weight: bold; color: #ff9900;">$12.99</p>
                    <p style="color: #666; font-size: 14px;">Available nationwide</p>
                </div>
                
                <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 30px; text-align: center;">
                    <i class="fas fa-clock" style="font-size: 3rem; color: #dc3545; margin-bottom: 20px;"></i>
                    <h3 style="color: #232f3e; margin-bottom: 15px;">Same Day Delivery</h3>
                    <p style="color: #666; margin-bottom: 15px;">Within 24 Hours</p>
                    <p style="font-size: 1.2rem; font-weight: bold; color: #dc3545;">$19.99</p>
                    <p style="color: #666; font-size: 14px;">Select cities only</p>
                </div>
            </div>

            <div style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px;">
                <h2 style="color: #232f3e; margin-bottom: 30px; text-align: center;">Shipping Details</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    <div>
                        <h3 style="color: #495057; margin-bottom: 20px;">
                            <i class="fas fa-map-marked-alt" style="color: #667eea; margin-right: 10px;"></i>
                            Delivery Areas
                        </h3>
                        <ul style="color: #666; line-height: 1.8;">
                            <li>All 50 US States</li>
                            <li>Washington D.C.</li>
                            <li>Puerto Rico</li>
                            <li>US Virgin Islands</li>
                        </ul>
                        
                        <h3 style="color: #495057; margin: 30px 0 20px 0;">
                            <i class="fas fa-calendar-alt" style="color: #667eea; margin-right: 10px;"></i>
                            Processing Time
                        </h3>
                        <p style="color: #666; line-height: 1.6;">Orders are processed within 1-2 business days. You'll receive a tracking number once your order ships.</p>
                    </div>
                    
                    <div>
                        <h3 style="color: #495057; margin-bottom: 20px;">
                            <i class="fas fa-box" style="color: #667eea; margin-right: 10px;"></i>
                            Package Tracking
                        </h3>
                        <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">Track your package every step of the way with real-time updates sent to your email and phone.</p>
                        
                        <h3 style="color: #495057; margin-bottom: 20px;">
                            <i class="fas fa-shield-alt" style="color: #667eea; margin-right: 10px;"></i>
                            Package Protection
                        </h3>
                        <p style="color: #666; line-height: 1.6;">All packages are insured against loss or damage during transit. We'll replace or refund any damaged items.</p>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-top: 30px; text-align: center;">
                    <h3 style="color: #232f3e; margin-bottom: 15px;">Questions about shipping?</h3>
                    <p style="color: #666; margin-bottom: 20px;">Our customer service team is here to help</p>
                    <a href="contact.php" class="btn">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>