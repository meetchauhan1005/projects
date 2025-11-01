<?php
require_once 'includes/functions.php';
$page_title = 'Contact Us';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // In a real application, you would send email or save to database
        $success = 'Thank you for your message! We will get back to you within 24 hours.';
        
        // Clear form data
        $_POST = array();
    }
}

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <!-- Hero Section -->
    <section style="text-align: center; padding: 3rem 0; background: linear-gradient(135deg, #8B4513, #A0522D); color: white; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.2rem;">We'd love to hear from you. Get in touch with our team!</p>
    </section>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem;">
        <!-- Contact Form -->
        <div>
            <h2 style="color: #8B4513; margin-bottom: 2rem;">Send us a Message</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                <div class="form-group">
                    <label for="name">Full Name: *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address: *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject: *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="General Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                        <option value="Product Question" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Product Question') ? 'selected' : ''; ?>>Product Question</option>
                        <option value="Order Support" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Order Support') ? 'selected' : ''; ?>>Order Support</option>
                        <option value="Delivery Issue" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Delivery Issue') ? 'selected' : ''; ?>>Delivery Issue</option>
                        <option value="Return/Exchange" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Return/Exchange') ? 'selected' : ''; ?>>Return/Exchange</option>
                        <option value="Complaint" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'Complaint') ? 'selected' : ''; ?>>Complaint</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message: *</label>
                    <textarea id="message" name="message" rows="5" required 
                              placeholder="Please describe your inquiry in detail..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Send Message</button>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div>
            <h2 style="color: #8B4513; margin-bottom: 2rem;">Get in Touch</h2>
            
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt" style="color: #8B4513; font-size: 1.5rem; margin-right: 1rem; width: 30px;"></i>
                        <div>
                            <h4>Visit Our Showroom</h4>
                            <p>123 Furniture Street<br>Downtown District<br>City, State 12345</p>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-phone" style="color: #8B4513; font-size: 1.5rem; margin-right: 1rem; width: 30px;"></i>
                        <div>
                            <h4>Call Us</h4>
                            <p><strong>(555) 123-4567</strong><br>Mon-Fri: 9AM-7PM<br>Sat-Sun: 10AM-6PM</p>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-envelope" style="color: #8B4513; font-size: 1.5rem; margin-right: 1rem; width: 30px;"></i>
                        <div>
                            <h4>Email Us</h4>
                            <p><strong>info@furniturestore.com</strong><br>support@furniturestore.com</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <i class="fas fa-clock" style="color: #8B4513; font-size: 1.5rem; margin-right: 1rem; width: 30px;"></i>
                        <div>
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9:00 AM - 7:00 PM<br>Saturday: 10:00 AM - 6:00 PM<br>Sunday: 12:00 PM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Social Media -->
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #8B4513; margin-bottom: 1rem;">Follow Us</h3>
                <div style="display: flex; gap: 1rem;">
                    <a href="#" style="color: #8B4513; font-size: 2rem; text-decoration: none;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: #8B4513; font-size: 2rem; text-decoration: none;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: #8B4513; font-size: 2rem; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: #8B4513; font-size: 2rem; text-decoration: none;"><i class="fab fa-linkedin"></i></a>
                </div>
                <p style="margin-top: 1rem; color: #666;">Stay updated with our latest collections and offers!</p>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <section style="background: #f8f9fa; padding: 3rem; border-radius: 15px; margin-bottom: 4rem;">
        <h2 style="text-align: center; color: #8B4513; margin-bottom: 3rem;">Frequently Asked Questions</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="color: #8B4513; margin-bottom: 0.5rem;">What are your delivery options?</h4>
                <p style="margin-bottom: 1.5rem;">We offer standard delivery (5-7 days) and express delivery (2-3 days). White glove delivery service is also available.</p>
                
                <h4 style="color: #8B4513; margin-bottom: 0.5rem;">Do you offer assembly service?</h4>
                <p style="margin-bottom: 1.5rem;">Yes, we provide professional assembly service for an additional fee. Our trained technicians will set up your furniture.</p>
            </div>
            <div>
                <h4 style="color: #8B4513; margin-bottom: 0.5rem;">What is your return policy?</h4>
                <p style="margin-bottom: 1.5rem;">We offer a 30-day return policy for unused items in original packaging. Custom orders may have different terms.</p>
                
                <h4 style="color: #8B4513; margin-bottom: 0.5rem;">Do you offer financing options?</h4>
                <p style="margin-bottom: 1.5rem;">Yes, we partner with financing companies to offer flexible payment plans. Contact us for more details.</p>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section style="text-align: center;">
        <h2 style="color: #8B4513; margin-bottom: 2rem;">Find Our Showroom</h2>
        <div style="background: #e9ecef; height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #666;">
            <div>
                <i class="fas fa-map" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                <p>Interactive map would be embedded here<br>123 Furniture Street, Downtown District</p>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>