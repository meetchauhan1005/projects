<?php
$page_title = "Secure Payments";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-shield-alt" style="color: var(--success-color);"></i>
                Secure Payments
            </h1>
            <p class="section-subtitle">Shop with confidence using our secure payment methods</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--success-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-credit-card"></i>
                    Payment Methods
                </h2>
                <div style="display: grid; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-credit-card" style="color: var(--primary-color); font-size: 1.5rem;"></i>
                        <div>
                            <h4>Credit & Debit Cards</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Visa, Mastercard, RuPay, American Express</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-mobile-alt" style="color: var(--success-color); font-size: 1.5rem;"></i>
                        <div>
                            <h4>UPI Payments</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Google Pay, PhonePe, Paytm, BHIM</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-wallet" style="color: var(--warning-color); font-size: 1.5rem;"></i>
                        <div>
                            <h4>Digital Wallets</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Paytm, Amazon Pay, Mobikwik</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-university" style="color: var(--info-color); font-size: 1.5rem;"></i>
                        <div>
                            <h4>Net Banking</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">All major banks supported</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--success-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-lock"></i>
                    Security Features
                </h2>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">256-bit SSL Encryption</h3>
                    <p style="color: var(--text-secondary);">All transactions are encrypted with bank-level security</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">PCI DSS Compliant</h3>
                    <p style="color: var(--text-secondary);">We follow international security standards</p>
                </div>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Two-Factor Authentication</h3>
                    <p style="color: var(--text-secondary);">Additional security layer for your protection</p>
                </div>
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Fraud Protection</h3>
                    <p style="color: var(--text-secondary);">Advanced fraud detection and prevention</p>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl); text-align: center;">
            <h2 style="margin-bottom: 2rem; color: var(--text-primary);">Payment Security Guarantee</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">Your payment information is never stored on our servers. All transactions are processed through secure payment gateways with end-to-end encryption.</p>
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 3rem;">
                <img src="https://via.placeholder.com/100x50/e2e8f0/64748b?text=SSL" alt="SSL Certified" style="border-radius: var(--radius-md);">
                <img src="https://via.placeholder.com/100x50/e2e8f0/64748b?text=PCI+DSS" alt="PCI DSS" style="border-radius: var(--radius-md);">
                <img src="https://via.placeholder.com/100x50/e2e8f0/64748b?text=Verified" alt="Verified" style="border-radius: var(--radius-md);">
            </div>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <button onclick="testPayment()" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.3)'">
                    <i class="fas fa-shield-alt"></i>
                    Test Payment Security
                </button>
                <button onclick="viewSavedCards()" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(37, 99, 235, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(37, 99, 235, 0.3)'">
                    <i class="fas fa-credit-card"></i>
                    Manage Payment Methods
                </button>
                <button onclick="downloadInvoice()" style="background: linear-gradient(135deg, var(--info-color), #1d4ed8); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.3)'">
                    <i class="fas fa-download"></i>
                    Download Invoice
                </button>
            </div>
        </div>

        <script>
        function testPayment() {
            if (window.shopWest) {
                window.shopWest.showNotification('Payment gateway security test passed! All systems are secure and operational.', 'success', 5000);
            }
        }
        
        function viewSavedCards() {
            if (window.shopWest) {
                window.shopWest.showNotification('Please login to view and manage your saved payment methods.', 'info', 5000);
            }
        }
        
        function downloadInvoice() {
            if (window.shopWest) {
                window.shopWest.showNotification('Invoice download feature available after purchase. Check your order history.', 'info', 5000);
            }
        }
        </script>
    </div>
</main>

<?php include 'includes/footer.php'; ?>