<?php
$page_title = "Fast Delivery";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-shipping-fast" style="color: var(--primary-color);"></i>
                Fast Delivery
            </h1>
            <p class="section-subtitle">Get your orders delivered quickly and safely</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-clock"></i>
                    Delivery Options
                </h2>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--success-color); margin-bottom: 0.5rem;">Free Standard Delivery</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1rem;">Orders over ₹500 • 3-5 business days</p>
                    <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
                        <li>No delivery charges</li>
                        <li>Track your order online</li>
                        <li>Secure packaging</li>
                    </ul>
                </div>
                <div>
                    <h3 style="color: var(--warning-color); margin-bottom: 0.5rem;">Express Delivery</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1rem;">₹99 • Next day delivery</p>
                    <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
                        <li>Available in major cities</li>
                        <li>Order before 2 PM</li>
                        <li>Priority handling</li>
                    </ul>
                </div>
            </div>

            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-map-marker-alt"></i>
                    Coverage Areas
                </h2>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--success-color); margin-bottom: 0.5rem;">Metro Cities</h3>
                    <p style="color: var(--text-secondary);">Mumbai, Delhi, Bangalore, Chennai, Kolkata, Hyderabad, Pune, Ahmedabad</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--info-color); margin-bottom: 0.5rem;">Tier 2 Cities</h3>
                    <p style="color: var(--text-secondary);">500+ cities across India with standard delivery</p>
                </div>
                <div>
                    <h3 style="color: var(--warning-color); margin-bottom: 0.5rem;">Remote Areas</h3>
                    <p style="color: var(--text-secondary);">Extended delivery time of 7-10 days</p>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl); text-align: center;">
            <h2 style="margin-bottom: 2rem; color: var(--text-primary);">Track Your Order</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">Stay updated with real-time tracking information</p>
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 3rem;">
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-box"></i>
                    </div>
                    <h4>Order Placed</h4>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--warning-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4>Processing</h4>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--info-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h4>Shipped</h4>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; background: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4>Delivered</h4>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <button onclick="trackOrder()" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(37, 99, 235, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(37, 99, 235, 0.3)'">
                    <i class="fas fa-search"></i>
                    Track My Order
                </button>
                <button onclick="scheduleDelivery()" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.3)'">
                    <i class="fas fa-calendar-alt"></i>
                    Schedule Delivery
                </button>
                <a href="support.php" style="background: linear-gradient(135deg, var(--warning-color), #d97706); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 158, 11, 0.3)'">
                    <i class="fas fa-headset"></i>
                    Delivery Support
                </a>
            </div>
        </div>

        <script>
        function trackOrder() {
            const orderNumber = prompt('Enter your order number:');
            if (orderNumber) {
                if (window.shopWest) {
                    window.shopWest.showNotification(`Tracking order #${orderNumber}. You will receive updates via SMS and email.`, 'info', 5000);
                }
            }
        }
        
        function scheduleDelivery() {
            if (window.shopWest) {
                window.shopWest.showNotification('Delivery scheduling feature coming soon! Contact support for special delivery requests.', 'info', 5000);
            }
        }
        </script>
    </div>
</main>

<?php include 'includes/footer.php'; ?>