// Modern JavaScript for Shop West
class ShopWest {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateCartCount();
    }

    setupEventListeners() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.performSearch();
            });
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    }

    async addToCart(productId, quantity = 1) {
        const button = event?.target?.closest('button');
        const originalContent = button?.innerHTML;
        
        try {
            if (button) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
            }

            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&quantity=${quantity}`
            });

            const data = await response.json();

            if (data.success) {
                if (button) {
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    button.style.background = 'var(--success-color)';
                }
                this.showNotification('Product added to cart!', 'success');
                await this.updateCartCount();
                
                setTimeout(() => {
                    if (button && originalContent) {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                        button.style.background = '';
                    }
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add product');
            }
        } catch (error) {
            if (button && originalContent) {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
            this.showNotification(error.message || 'Error adding product', 'error');
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch('get_cart_count.php');
            const data = await response.json();
            
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count || 0;
                cartCount.style.display = data.count > 0 ? 'flex' : 'none';
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    showNotification(message, type = 'info', duration = 4000) {
        const notification = document.createElement('div');
        const colors = {
            success: 'var(--success-color)',
            error: 'var(--danger-color)',
            info: 'var(--info-color)'
        };

        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; padding: 1rem 1.5rem;
            border-radius: var(--radius-lg); color: white; font-weight: 600;
            z-index: 10000; transform: translateX(100%); transition: all 0.3s ease;
            background: ${colors[type] || colors.info}; display: flex; align-items: center; gap: 0.75rem;
        `;

        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; padding: 0.25rem;">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(notification);
        setTimeout(() => notification.style.transform = 'translateX(0)', 100);
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    performSearch() {
        const query = document.getElementById('searchInput')?.value?.trim();
        if (query) window.location.href = `products.php?search=${encodeURIComponent(query)}`;
    }
}

// Global functions
function addToCart(productId, quantity = 1) {
    if (window.shopWest) window.shopWest.addToCart(productId, quantity);
}

function showNotification(message, type = 'info') {
    if (window.shopWest) window.shopWest.showNotification(message, type);
}

function searchProducts() {
    if (window.shopWest) window.shopWest.performSearch();
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.shopWest = new ShopWest();
    document.body.classList.add('loaded');
});

function showFeatureDetails(feature) {
    const details = {
        delivery: 'Free delivery on orders over ₹500. Express delivery available in 24 hours for major cities.',
        payments: 'We use 256-bit SSL encryption and support all major payment methods including UPI, cards, and wallets.',
        support: 'Our support team is available 24/7 via chat, email, and phone to assist you with any queries.',
        returns: 'Easy 30-day return policy. No questions asked for unused items in original packaging.'
    };
    
    if (window.shopWest) {
        window.shopWest.showNotification(details[feature] || 'Feature information not available.', 'info', 6000);
    }
}