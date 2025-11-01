    </main>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About FurnitureStore</h3>
                    <p>We provide high-quality furniture for your home and office. Our collection features modern, classic, and contemporary designs to suit every taste and budget.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="categories.php">Categories</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="shipping-info.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="support.php">Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Furniture Street, City, State 12345</p>
                    <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> info@furniturestore.com</p>
                    
                    <div class="social-links" style="margin-top: 1rem;">
                        <a href="#" style="display: inline-block; margin-right: 1rem; color: #8B4513; font-size: 1.5rem; transition: color 0.3s; text-decoration: none;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="display: inline-block; margin-right: 1rem; color: #8B4513; font-size: 1.5rem; transition: color 0.3s; text-decoration: none;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="display: inline-block; margin-right: 1rem; color: #8B4513; font-size: 1.5rem; transition: color 0.3s; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="display: inline-block; color: #8B4513; font-size: 1.5rem; transition: color 0.3s; text-decoration: none;"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> FurnitureStore. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo isset($base_path) ? $base_path : ''; ?>assets/js/main.js"></script>
    <script>
        function toggleMobileMenu() {
            const nav = document.getElementById('main-nav');
            nav.classList.toggle('mobile-open');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const nav = document.getElementById('main-nav');
            const menuBtn = document.querySelector('.mobile-menu');
            if (!nav.contains(e.target) && !menuBtn.contains(e.target)) {
                nav.classList.remove('mobile-open');
            }
        });
    </script>
</body>
</html>