/**
 * Mahadev Electronic - Responsive JavaScript
 * Handles mobile interactions and responsive features
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile Navigation Toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
        
        // Close mobile menu when clicking on links
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    navbarCollapse.classList.remove('show');
                }
            });
        });
    }
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Image lazy loading for better performance
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Touch-friendly hover effects for mobile
    if ('ontouchstart' in window) {
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 300);
            });
        });
    }
    
    // Responsive table handling
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        if (!table.closest('.table-responsive')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }
    });
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Add floating label effect
            if (input.placeholder && !input.closest('.form-floating')) {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
                
                // Check if input has value on load
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            }
            
            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    });
    
    // Responsive font size adjustment
    function adjustFontSizes() {
        const screenWidth = window.innerWidth;
        const root = document.documentElement;
        
        if (screenWidth < 576) {
            root.style.setProperty('--base-font-size', '14px');
        } else if (screenWidth < 768) {
            root.style.setProperty('--base-font-size', '15px');
        } else {
            root.style.setProperty('--base-font-size', '16px');
        }
    }
    
    // Call on load and resize
    adjustFontSizes();
    window.addEventListener('resize', adjustFontSizes);
    
    // Loading states for buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.form && this.form.checkValidity()) {
                this.classList.add('loading');
                this.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.disabled = false;
                }, 3000);
            }
        });
    });
    
    // Responsive image handling
    function handleResponsiveImages() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (!img.style.maxWidth) {
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
            }
        });
    }
    
    handleResponsiveImages();
    
    // Viewport height fix for mobile browsers
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    setViewportHeight();
    window.addEventListener('resize', setViewportHeight);
    
    // Prevent zoom on input focus for iOS
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (parseFloat(getComputedStyle(input).fontSize) < 16) {
                input.style.fontSize = '16px';
            }
        });
    }
    
    // Enhanced touch gestures for image galleries
    let startX = 0;
    let startY = 0;
    
    document.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    });
    
    document.addEventListener('touchmove', function(e) {
        if (!startX || !startY) return;
        
        const diffX = startX - e.touches[0].clientX;
        const diffY = startY - e.touches[0].clientY;
        
        // Prevent horizontal scroll on vertical swipes
        if (Math.abs(diffY) > Math.abs(diffX)) {
            return;
        }
        
        // Handle horizontal swipes for carousels
        const carousel = e.target.closest('.carousel');
        if (carousel && Math.abs(diffX) > 50) {
            const carouselInstance = bootstrap.Carousel.getInstance(carousel);
            if (carouselInstance) {
                if (diffX > 0) {
                    carouselInstance.next();
                } else {
                    carouselInstance.prev();
                }
            }
        }
        
        startX = 0;
        startY = 0;
    });
    
    // Performance optimization: Debounce resize events
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Trigger custom resize event for components that need it
            window.dispatchEvent(new CustomEvent('optimizedResize'));
        }, 250);
    });
    
    // Accessibility improvements
    function enhanceAccessibility() {
        // Add skip links
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.textContent = 'Skip to main content';
        skipLink.className = 'skip-link visually-hidden-focusable';
        skipLink.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            z-index: 1000;
            color: white;
            background: #000;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
        `;
        skipLink.addEventListener('focus', function() {
            this.style.top = '6px';
        });
        skipLink.addEventListener('blur', function() {
            this.style.top = '-40px';
        });
        
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Add main content landmark if not exists
        const mainContent = document.querySelector('main') || document.querySelector('.main-content');
        if (mainContent && !mainContent.id) {
            mainContent.id = 'main-content';
        }
        
        // Enhance button accessibility
        const buttons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
        buttons.forEach(button => {
            if (!button.textContent.trim() && button.querySelector('i')) {
                const icon = button.querySelector('i');
                const iconClass = icon.className;
                let label = 'Button';
                
                if (iconClass.includes('fa-bars')) label = 'Menu';
                else if (iconClass.includes('fa-times')) label = 'Close';
                else if (iconClass.includes('fa-search')) label = 'Search';
                else if (iconClass.includes('fa-cart')) label = 'Cart';
                
                button.setAttribute('aria-label', label);
            }
        });
    }
    
    enhanceAccessibility();
    
    console.log('Mahadev Electronic - Responsive features loaded successfully');
});

// Utility functions
window.MahadevElectronic = {
    // Show loading state
    showLoading: function(element) {
        if (element) {
            element.classList.add('loading');
            element.disabled = true;
        }
    },
    
    // Hide loading state
    hideLoading: function(element) {
        if (element) {
            element.classList.remove('loading');
            element.disabled = false;
        }
    },
    
    // Show toast notification
    showToast: function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    },
    
    // Smooth scroll to element
    scrollTo: function(element, offset = 0) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (element) {
            const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
            const offsetPosition = elementPosition - offset;
            
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }
};