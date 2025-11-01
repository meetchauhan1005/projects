<?php
$page_title = "Contact Us";
include 'includes/header.php';

$success = '';
if ($_POST) {
    $success = "Thank you for your message! We'll get back to you soon.";
}
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3rem; color: #232f3e; margin-bottom: 20px;">Contact Us</h1>
            <p style="font-size: 1.2rem; color: #666;">We'd love to hear from you. Send us a message!</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
            <div>
                <h2 style="color: #232f3e; margin-bottom: 30px;">Get in Touch</h2>
                
                <?php if ($success): ?>
                    <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
            
            <div>
                <h2 style="color: #232f3e; margin-bottom: 30px;">Contact Information</h2>
                
                <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div style="margin-bottom: 25px;">
                        <i class="fas fa-map-marker-alt" style="color: #ff9900; margin-right: 15px; width: 20px;"></i>
                        <strong>Address:</strong><br>
                        <span style="margin-left: 35px; color: #666;">123 Shopping Street, Commerce City, CC 12345</span>
                    </div>
                    
                    <div style="margin-bottom: 25px;">
                        <i class="fas fa-phone" style="color: #ff9900; margin-right: 15px; width: 20px;"></i>
                        <strong>Phone:</strong><br>
                        <span style="margin-left: 35px; color: #666;">+1 (555) 123-4567</span>
                    </div>
                    
                    <div style="margin-bottom: 25px;">
                        <i class="fas fa-envelope" style="color: #ff9900; margin-right: 15px; width: 20px;"></i>
                        <strong>Email:</strong><br>
                        <span style="margin-left: 35px; color: #666;">support@shopwest.com</span>
                    </div>
                    
                    <div>
                        <i class="fas fa-clock" style="color: #ff9900; margin-right: 15px; width: 20px;"></i>
                        <strong>Hours:</strong><br>
                        <span style="margin-left: 35px; color: #666;">Mon-Fri: 9AM-6PM<br>
                        <span style="margin-left: 35px; color: #666;">Sat-Sun: 10AM-4PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>