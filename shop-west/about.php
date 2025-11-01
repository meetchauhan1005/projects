<?php
$page_title = "About Us";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3rem; color: #232f3e; margin-bottom: 20px;">About Shop West</h1>
            <p style="font-size: 1.2rem; color: #666; max-width: 600px; margin: 0 auto;">Your trusted online shopping destination since 2024</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 80px;">
            <div>
                <h2 style="color: #232f3e; margin-bottom: 20px;">Our Story</h2>
                <p style="line-height: 1.8; color: #555; margin-bottom: 20px;">
                    Shop West was founded with a simple mission: to provide customers with an exceptional online shopping experience. We believe that shopping should be convenient, affordable, and enjoyable.
                </p>
                <p style="line-height: 1.8; color: #555;">
                    From electronics to fashion, books to home essentials, we curate the best products from trusted brands to bring you quality and value in every purchase.
                </p>
            </div>
            <div style="text-align: center;">
                <div style="width: 300px; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <i class="fas fa-store" style="font-size: 4rem; color: white;"></i>
                </div>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 60px 40px; border-radius: 20px; text-align: center;">
            <h2 style="color: #232f3e; margin-bottom: 40px;">Why Choose Shop West?</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
                <div>
                    <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #ff9900; margin-bottom: 20px;"></i>
                    <h3>Fast Shipping</h3>
                    <p>Quick and reliable delivery to your doorstep</p>
                </div>
                <div>
                    <i class="fas fa-shield-alt" style="font-size: 3rem; color: #ff9900; margin-bottom: 20px;"></i>
                    <h3>Secure Shopping</h3>
                    <p>Your data and transactions are always protected</p>
                </div>
                <div>
                    <i class="fas fa-headset" style="font-size: 3rem; color: #ff9900; margin-bottom: 20px;"></i>
                    <h3>24/7 Support</h3>
                    <p>Our customer service team is always here to help</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>