<?php
$page_title = "24/7 Support";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-headset" style="color: var(--warning-color);"></i>
                24/7 Customer Support
            </h1>
            <p class="section-subtitle">We're here to help you anytime, anywhere</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--warning-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-comments"></i>
                    Contact Options
                </h2>
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-comment-dots" style="color: var(--success-color); font-size: 2rem;"></i>
                        <div>
                            <h4>Live Chat</h4>
                            <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">Instant support available 24/7</p>
                            <a href="chat.php" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.3)'">
                                <i class="fas fa-comments"></i>
                                Start Chat
                            </a>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-phone" style="color: var(--primary-color); font-size: 2rem;"></i>
                        <div>
                            <h4>Phone Support</h4>
                            <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">Call us at: <strong>1800-123-4567</strong></p>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Toll-free • Available 24/7</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-md);">
                        <i class="fas fa-envelope" style="color: var(--info-color); font-size: 2rem;"></i>
                        <div>
                            <h4>Email Support</h4>
                            <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">support@shopwest.com</p>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Response within 2 hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                <h2 style="color: var(--warning-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-question-circle"></i>
                    Quick Help
                </h2>
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Frequently Asked Questions</h3>
                    <div style="display: grid; gap: 1rem;">
                        <details style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md);">
                            <summary style="cursor: pointer; font-weight: 600;">How do I track my order?</summary>
                            <p style="color: var(--text-secondary); margin-top: 0.5rem;">You can track your order using the tracking number sent to your email or by logging into your account.</p>
                        </details>
                        <details style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md);">
                            <summary style="cursor: pointer; font-weight: 600;">What is your return policy?</summary>
                            <p style="color: var(--text-secondary); margin-top: 0.5rem;">We offer a 30-day return policy for unused items in original packaging.</p>
                        </details>
                        <details style="background: var(--bg-secondary); padding: 1rem; border-radius: var(--radius-md);">
                            <summary style="cursor: pointer; font-weight: 600;">How can I change my order?</summary>
                            <p style="color: var(--text-secondary); margin-top: 0.5rem;">Orders can be modified within 1 hour of placement. Contact our support team immediately.</p>
                        </details>
                    </div>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl); text-align: center;">
            <h2 style="margin-bottom: 2rem; color: var(--text-primary);">Our Support Promise</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div>
                    <div style="width: 60px; height: 60px; background: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>24/7 Availability</h4>
                    <p style="color: var(--text-secondary);">Round the clock support</p>
                </div>
                <div>
                    <div style="width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Quick Response</h4>
                    <p style="color: var(--text-secondary);">Average response time: 2 minutes</p>
                </div>
                <div>
                    <div style="width: 60px; height: 60px; background: var(--warning-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>Expert Team</h4>
                    <p style="color: var(--text-secondary);">Trained professionals</p>
                </div>
                <div>
                    <div style="width: 60px; height: 60px; background: var(--info-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white;">
                        <i class="fas fa-language"></i>
                    </div>
                    <h4>Multi-Language</h4>
                    <p style="color: var(--text-secondary);">Support in 8 languages</p>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <button onclick="requestCallback()" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(37, 99, 235, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(37, 99, 235, 0.3)'">
                    <i class="fas fa-phone-alt"></i>
                    Request Callback
                </button>
                <button onclick="submitTicket()" style="background: linear-gradient(135deg, var(--warning-color), #d97706); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 158, 11, 0.3)'">
                    <i class="fas fa-ticket-alt"></i>
                    Submit Support Ticket
                </button>
                <button onclick="viewFAQ()" style="background: linear-gradient(135deg, var(--info-color), #1d4ed8); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.3)'">
                    <i class="fas fa-question-circle"></i>
                    View All FAQs
                </button>
            </div>
        </div>

        <script>
        function startLiveChat() {
            if (window.shopWest) {
                window.shopWest.showNotification('Live chat is starting... Please wait while we connect you to an agent.', 'info', 5000);
            }
        }
        
        function requestCallback() {
            const phone = prompt('Enter your phone number for callback:');
            if (phone) {
                if (window.shopWest) {
                    window.shopWest.showNotification(`Callback requested for ${phone}. Our team will call you within 15 minutes.`, 'success', 5000);
                }
            }
        }
        
        function submitTicket() {
            if (window.shopWest) {
                window.shopWest.showNotification('Support ticket form will open shortly. Describe your issue in detail.', 'info', 5000);
            }
        }
        
        function viewFAQ() {
            if (window.shopWest) {
                window.shopWest.showNotification('Redirecting to comprehensive FAQ section...', 'info', 3000);
            }
        }
        </script>
    </div>
</main>

<?php include 'includes/footer.php'; ?>