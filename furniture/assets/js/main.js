// Main JavaScript functionality for Furniture Store

// Add to cart function
function addToCart(productId, quantity = 1) {
    if (!isLoggedIn()) {
        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
        return;
    }
    
    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Product added to cart successfully!', 'success');
            updateCartCount();
        } else {
            showAlert(data.message || 'Error adding product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error adding product to cart', 'error');
    });
}

// Update cart count in header
function updateCartCount() {
    fetch('ajax/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const cartCount = document.querySelector('.cart-count');
        if (data.count > 0) {
            if (cartCount) {
                cartCount.textContent = data.count;
            } else {
                const cartIcon = document.querySelector('.cart-icon');
                if (cartIcon) {
                    const countSpan = document.createElement('span');
                    countSpan.className = 'cart-count';
                    countSpan.textContent = data.count;
                    cartIcon.appendChild(countSpan);
                }
            }
        } else if (cartCount) {
            cartCount.remove();
        }
    });
}

// Show alert messages
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '100px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Check if user is logged in
function isLoggedIn() {
    return document.body.dataset.loggedIn === 'true';
}

// Search functionality
function performSearch() {
    const searchInput = document.querySelector('.search-box');
    if (searchInput && searchInput.value.trim()) {
        window.location.href = 'products.php?search=' + encodeURIComponent(searchInput.value.trim());
    }
}

// Handle search form submission
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action="search.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }
    
    // Set logged in status for JavaScript
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        document.body.dataset.loggedIn = 'true';
    }
    
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('nav');
    
    if (mobileMenuBtn && nav) {
        mobileMenuBtn.addEventListener('click', function() {
            nav.classList.toggle('mobile-open');
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showAlert('Please fill in all required fields', 'error');
            }
        });
    });
    
    // Quantity input validation
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const min = parseInt(this.min) || 1;
            const max = parseInt(this.max) || 999;
            let value = parseInt(this.value) || min;
            
            if (value < min) value = min;
            if (value > max) value = max;
            
            this.value = value;
        });
    });
    
    // Image lazy loading
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// Product image gallery
function changeProductImage(src) {
    const mainImage = document.querySelector('.main-product-image');
    if (mainImage) {
        mainImage.src = src;
    }
}

// Price range filter
function updatePriceRange() {
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const priceDisplay = document.getElementById('priceDisplay');
    
    if (minPrice && maxPrice && priceDisplay) {
        priceDisplay.textContent = `$${minPrice.value} - $${maxPrice.value}`;
    }
}

// Newsletter subscription
function subscribeNewsletter() {
    const emailInput = document.querySelector('#newsletter-email');
    if (!emailInput || !emailInput.value.trim()) {
        showAlert('Please enter your email address', 'error');
        return;
    }
    
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        showAlert('Please enter a valid email address', 'error');
        return;
    }
    
    // Here you would typically send the email to your backend
    showAlert('Thank you for subscribing to our newsletter!', 'success');
    emailInput.value = '';
}

// Back to top button
function addBackToTopButton() {
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #8B4513;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        cursor: pointer;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    `;
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Initialize back to top button
document.addEventListener('DOMContentLoaded', addBackToTopButton);