<?php
$page_title = "Easy Returns";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-undo-alt" style="color: var(--info-color);"></i>
                Easy Returns
            </h1>
            <p class="section-subtitle">Hassle-free returns within 30 days</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--info-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-clipboard-list"></i>
                    Return Policy
                </h2>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--success-color); margin-bottom: 0.5rem;">30-Day Return Window</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1rem;">Return any item within 30 days of delivery</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Condition Requirements</h3>
                    <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
                        <li>Item must be unused and in original condition</li>
                        <li>Original packaging and tags must be intact</li>
                        <li>Include all accessories and documentation</li>
                    </ul>
                </div>
                <div>
                    <h3 style="color: var(--warning-color); margin-bottom: 0.5rem;">Non-Returnable Items</h3>
                    <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
                        <li>Personal care items</li>
                        <li>Customized products</li>
                        <li>Perishable goods</li>
                    </ul>
                </div>
            </div>

            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--info-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-exchange-alt"></i>
                    Return Process
                </h2>
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">1</div>
                        <div>
                            <h4>Initiate Return</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Login to your account and select the item to return</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <div style="width: 40px; height: 40px; background: var(--warning-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">2</div>
                        <div>
                            <h4>Schedule Pickup</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Choose a convenient time for free pickup</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <div style="width: 40px; height: 40px; background: var(--info-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">3</div>
                        <div>
                            <h4>Quality Check</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">We inspect the returned item within 2-3 days</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <div style="width: 40px; height: 40px; background: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">4</div>
                        <div>
                            <h4>Refund Processed</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Refund credited to original payment method</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl);">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem; color: var(--text-primary);">Refund Timeline</h2>
                <p style="color: var(--text-secondary);">Get your money back quickly with our streamlined process</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                    <i class="fas fa-credit-card" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Credit/Debit Cards</h3>
                    <p style="color: var(--text-secondary);">5-7 business days</p>
                </div>
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                    <i class="fas fa-mobile-alt" style="font-size: 2.5rem; color: var(--success-color); margin-bottom: 1rem;"></i>
                    <h3>UPI/Wallets</h3>
                    <p style="color: var(--text-secondary);">1-2 business days</p>
                </div>
                <div style="text-align: center; padding: 2rem; background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                    <i class="fas fa-university" style="font-size: 2.5rem; color: var(--info-color); margin-bottom: 1rem;"></i>
                    <h3>Net Banking</h3>
                    <p style="color: var(--text-secondary);">3-5 business days</p>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl); text-align: center; margin-top: 3rem;">
            <h3 style="margin-bottom: 1rem;">Need Help with Returns?</h3>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">Our customer support team is here to assist you</p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
                <button onclick="initiateReturn()" style="background: linear-gradient(135deg, var(--info-color), #1d4ed8); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.3)'">
                    <i class="fas fa-undo-alt"></i>
                    Start Return Process
                </button>
                <button onclick="checkReturnStatus()" style="background: linear-gradient(135deg, var(--warning-color), #d97706); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 158, 11, 0.3)'">
                    <i class="fas fa-search"></i>
                    Check Return Status
                </button>
                <a href="support.php" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(37, 99, 235, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(37, 99, 235, 0.3)'">
                    <i class="fas fa-headset"></i>
                    Contact Support
                </a>
                <button onclick="window.open('tel:1800-123-4567')" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.3)'">
                    <i class="fas fa-phone"></i>
                    Call Us Now
                </button>
            </div>
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                <h4 style="color: var(--success-color); margin-bottom: 1rem;">
                    <i class="fas fa-shield-check"></i>
                    Return Guarantee
                </h4>
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">We stand behind our return policy. If you're not satisfied, we'll make it right.</p>
                <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; font-size: 0.9rem; color: var(--text-secondary);">
                    <span><i class="fas fa-check" style="color: var(--success-color);"></i> Free Return Pickup</span>
                    <span><i class="fas fa-check" style="color: var(--success-color);"></i> Quick Refund Process</span>
                    <span><i class="fas fa-check" style="color: var(--success-color);"></i> No Questions Asked</span>
                </div>
            </div>
        </div>

        <script>
        function initiateReturn() {
            if (window.shopWest) {
                window.shopWest.showNotification('Please login to your account to start the return process for your orders.', 'info', 5000);
            }
        }
        
        function checkReturnStatus() {
            const returnId = prompt('Enter your return ID or order number:');
            if (returnId) {
                if (window.shopWest) {
                    window.shopWest.showNotification(`Checking return status for ${returnId}. You will receive an update shortly.`, 'info', 5000);
                }
            }
        }
        </script>
    </div>
</main>

<?php include 'includes/footer.php'; ?>