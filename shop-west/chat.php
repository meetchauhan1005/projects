<?php
$page_title = "Live Chat Support";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-comments" style="color: var(--success-color);"></i>
                Live Chat Support
            </h1>
            <p class="section-subtitle">Get instant help from our customer support team</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem; max-width: 1200px; margin: 0 auto;">
            <!-- Chat Window -->
            <div style="background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-xl); position: relative;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--success-color), var(--primary-color), var(--warning-color));"></div>
                <!-- Chat Header -->
                <div style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(30px, -30px);"></div>
                    <div style="position: absolute; bottom: 0; left: 0; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; transform: translate(-20px, 20px);"></div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; z-index: 1;">
                            <i class="fas fa-user-tie" style="font-size: 1.2rem;"></i>
                            <div style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #00ff88; border: 2px solid white; border-radius: 50%;"></div>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 1.1rem;">Customer Support</h3>
                            <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;" id="agentStatus">Agent is online</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button onclick="minimizeChat()" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.5rem; border-radius: var(--radius-sm); cursor: pointer;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button onclick="closeChat()" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 0.5rem; border-radius: var(--radius-sm); cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div id="chatMessages" style="height: 450px; overflow-y: auto; padding: 1.5rem; background: linear-gradient(135deg, var(--bg-secondary) 0%, #f8fafc 100%); position: relative;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.03) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.03) 0%, transparent 50%); pointer-events: none;"></div>
                    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; position: relative; z-index: 1;">
                        <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color); text-align: center; max-width: 350px; box-shadow: var(--shadow-md); position: relative; overflow: hidden;">
                            <div style="position: absolute; top: -20px; right: -20px; width: 60px; height: 60px; background: linear-gradient(135deg, var(--success-color), var(--primary-color)); border-radius: 50%; opacity: 0.1;"></div>
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--success-color), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                                <i class="fas fa-robot"></i>
                            </div>
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">Welcome to Shop West!</h4>
                            <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5;">Our AI assistant is here to help. How can we assist you today?</p>
                        </div>
                    </div>
                    
                    <div class="message agent-message" style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; position: relative; z-index: 1;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--success-color), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3); position: relative;">
                            <i class="fas fa-user-tie"></i>
                            <div style="position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; background: #00ff88; border: 2px solid white; border-radius: 50%;"></div>
                        </div>
                        <div style="background: var(--bg-primary); padding: 1.25rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color); max-width: 75%; box-shadow: var(--shadow-sm); position: relative;">
                            <div style="position: absolute; top: 15px; left: -8px; width: 0; height: 0; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-right: 8px solid var(--bg-primary);"></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600; color: var(--success-color); font-size: 0.9rem;">Sarah Johnson</span>
                                <span style="background: var(--success-color); color: white; padding: 0.125rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.7rem; font-weight: 600;">AGENT</span>
                            </div>
                            <p style="margin: 0 0 0.5rem 0; color: var(--text-primary); line-height: 1.5;">Hello! I'm Sarah from customer support. I'm here to help you with any questions about your orders, products, or account.</p>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">2:34 PM • Typically replies in minutes</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div style="padding: 1.5rem; background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%); border-top: 1px solid var(--border-color); position: relative;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--primary-color), transparent);"></div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <i class="fas fa-bolt" style="color: var(--warning-color); font-size: 1rem;"></i>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--text-primary); font-weight: 600;">Quick Actions</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <button onclick="sendQuickMessage('Track my order')" style="background: var(--bg-primary); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'">
                            <i class="fas fa-search"></i>
                            Track Order
                        </button>
                        <button onclick="sendQuickMessage('Return/Exchange')" style="background: var(--bg-primary); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);" onmouseover="this.style.background='var(--success-color)'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'">
                            <i class="fas fa-exchange-alt"></i>
                            Return/Exchange
                        </button>
                        <button onclick="sendQuickMessage('Payment issue')" style="background: var(--bg-primary); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);" onmouseover="this.style.background='var(--warning-color)'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'">
                            <i class="fas fa-credit-card"></i>
                            Payment Issue
                        </button>
                        <button onclick="sendQuickMessage('Product inquiry')" style="background: var(--bg-primary); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; box-shadow: var(--shadow-sm);" onmouseover="this.style.background='var(--info-color)'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'">
                            <i class="fas fa-info-circle"></i>
                            Product Info
                        </button>
                    </div>
                </div>

                <!-- Message Input -->
                <div style="padding: 1.5rem; background: var(--bg-primary); border-top: 1px solid var(--border-color);">
                    <div style="display: flex; gap: 1rem; align-items: flex-end;">
                        <div style="flex: 1;">
                            <textarea id="messageInput" placeholder="Type your message here..." style="width: 100%; padding: 1rem; border: 2px solid var(--border-color); border-radius: var(--radius-lg); resize: none; font-family: inherit; font-size: 0.95rem; min-height: 50px; max-height: 120px;" onkeypress="handleKeyPress(event)"></textarea>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <button onclick="attachFile()" style="background: var(--bg-secondary); border: 1px solid var(--border-color); padding: 0.75rem; border-radius: var(--radius-md); cursor: pointer; color: var(--text-secondary);" title="Attach File">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button onclick="sendMessage()" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 0.75rem 1rem; border-radius: var(--radius-md); cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-weight: 600;" title="Send Message">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Info Sidebar -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Agent Info -->
                <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color); text-align: center; box-shadow: var(--shadow-md); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 80px; height: 80px; background: linear-gradient(135deg, var(--success-color), var(--primary-color)); border-radius: 50%; opacity: 0.1;"></div>
                    <div style="width: 90px; height: 90px; background: linear-gradient(135deg, var(--success-color), #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3); position: relative; z-index: 1;">
                        <i class="fas fa-user-tie"></i>
                        <div style="position: absolute; bottom: 5px; right: 5px; width: 20px; height: 20px; background: #00ff88; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0, 255, 136, 0.4);"></div>
                    </div>
                    <h3 style="margin-bottom: 0.5rem;">Sarah Johnson</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">Senior Support Agent</p>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--success-color); font-size: 0.9rem; background: rgba(16, 185, 129, 0.1); padding: 0.5rem 1rem; border-radius: var(--radius-lg); margin-top: 1rem;">
                        <div style="width: 8px; height: 8px; background: var(--success-color); border-radius: 50%; animation: pulse 2s infinite;"></div>
                        Online Now
                    </div>
                    <style>
                    @keyframes pulse {
                        0% { opacity: 1; }
                        50% { opacity: 0.5; }
                        100% { opacity: 1; }
                    }
                    </style>
                </div>

                <!-- Chat Features -->
                <div style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color);">
                    <h4 style="margin-bottom: 1.5rem; color: var(--text-primary);">Chat Features</h4>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <i class="fas fa-clock" style="color: var(--success-color);"></i>
                            24/7 Support Available
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <i class="fas fa-file-alt" style="color: var(--info-color);"></i>
                            File Sharing Supported
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <i class="fas fa-history" style="color: var(--warning-color);"></i>
                            Chat History Saved
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <i class="fas fa-shield-alt" style="color: var(--primary-color);"></i>
                            Secure & Private
                        </div>
                    </div>
                </div>

                <!-- Contact Alternatives -->
                <div style="background: var(--bg-feature); padding: 2rem; border-radius: var(--radius-xl);">
                    <h4 style="margin-bottom: 1.5rem; color: var(--text-primary);">Other Ways to Reach Us</h4>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <a href="tel:1800-123-4567" style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); text-decoration: none; padding: 0.75rem; background: var(--bg-primary); border-radius: var(--radius-md); transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'">
                            <i class="fas fa-phone" style="color: var(--success-color);"></i>
                            <span style="font-size: 0.9rem;">Call: 1800-123-4567</span>
                        </a>
                        <a href="mailto:support@shopwest.com" style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); text-decoration: none; padding: 0.75rem; background: var(--bg-primary); border-radius: var(--radius-md); transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'">
                            <i class="fas fa-envelope" style="color: var(--info-color);"></i>
                            <span style="font-size: 0.9rem;">Email Support</span>
                        </a>
                        <a href="support.php" style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); text-decoration: none; padding: 0.75rem; background: var(--bg-primary); border-radius: var(--radius-md); transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'" onmouseout="this.style.background='var(--bg-primary)'; this.style.color='var(--text-primary)'">
                            <i class="fas fa-question-circle" style="color: var(--warning-color);"></i>
                            <span style="font-size: 0.9rem;">Help Center</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let messageCount = 0;

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (message) {
        addMessage(message, 'user');
        input.value = '';
        
        // Simulate agent response
        setTimeout(() => {
            const responses = [
                "Thank you for your message. I'm looking into that for you right now.",
                "I understand your concern. Let me check our system for more details.",
                "That's a great question! I'll be happy to help you with that.",
                "I can definitely assist you with this. Give me just a moment to pull up your information.",
                "Thanks for reaching out! I'm here to help resolve this for you."
            ];
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            addMessage(randomResponse, 'agent');
        }, 1500);
    }
}

function sendQuickMessage(message) {
    addMessage(message, 'user');
    
    setTimeout(() => {
        let response = '';
        switch(message) {
            case 'Track my order':
                response = "I'd be happy to help you track your order. Could you please provide your order number?";
                break;
            case 'Return/Exchange':
                response = "I can help you with returns and exchanges. What item would you like to return?";
                break;
            case 'Payment issue':
                response = "I'm sorry to hear about the payment issue. Can you describe what problem you're experiencing?";
                break;
            case 'Product inquiry':
                response = "I'd be glad to help with product information. Which product are you interested in?";
                break;
            default:
                response = "Thank you for your message. How can I assist you today?";
        }
        addMessage(response, 'agent');
    }, 1000);
}

function addMessage(text, sender) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    const currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; justify-content: flex-end;">
                <div style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 1rem; border-radius: var(--radius-lg); max-width: 70%;">
                    <p style="margin: 0;">${text}</p>
                    <span style="font-size: 0.75rem; opacity: 0.8;">${currentTime}</span>
                </div>
                <div style="width: 32px; height: 32px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 32px; height: 32px; background: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div style="background: var(--bg-primary); padding: 1rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); max-width: 70%;">
                    <p style="margin: 0; color: var(--text-primary);">${text}</p>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">${currentTime}</span>
                </div>
            </div>
        `;
    }
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

function attachFile() {
    if (window.shopWest) {
        window.shopWest.showNotification('File attachment feature coming soon! You can describe your issue in the chat for now.', 'info', 4000);
    }
}

function minimizeChat() {
    if (window.shopWest) {
        window.shopWest.showNotification('Chat minimized. You can continue the conversation anytime.', 'info', 3000);
    }
}

function closeChat() {
    if (confirm('Are you sure you want to end this chat session?')) {
        window.location.href = 'support.php';
    }
}

// Auto-scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>

<?php include 'includes/footer.php'; ?>