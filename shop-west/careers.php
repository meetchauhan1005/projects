<?php
$page_title = "Careers";
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 40px 20px;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 style="font-size: 3rem; color: #232f3e; margin-bottom: 20px;">Join Our Team</h1>
            <p style="font-size: 1.2rem; color: #666;">Build your career with Shop West</p>
        </div>

        <div style="margin-bottom: 60px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 50px; border-radius: 20px; text-align: center;">
                <h2 style="margin-bottom: 20px;">Why Work With Us?</h2>
                <p style="font-size: 1.1rem; opacity: 0.9;">Join a dynamic team that's revolutionizing online shopping</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #232f3e; margin-bottom: 15px;">Software Developer</h3>
                <p style="color: #666; margin-bottom: 15px;">Full-time • Remote</p>
                <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">Join our development team to build innovative e-commerce solutions using modern technologies.</p>
                <a href="contact.php" class="btn">Apply Now</a>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #232f3e; margin-bottom: 15px;">Customer Service Rep</h3>
                <p style="color: #666; margin-bottom: 15px;">Full-time • On-site</p>
                <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">Help customers with their shopping experience and resolve any issues they may have.</p>
                <a href="contact.php" class="btn">Apply Now</a>
            </div>
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #232f3e; margin-bottom: 15px;">Marketing Specialist</h3>
                <p style="color: #666; margin-bottom: 15px;">Full-time • Hybrid</p>
                <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">Drive our marketing campaigns and help grow our customer base through creative strategies.</p>
                <a href="contact.php" class="btn">Apply Now</a>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 40px; border-radius: 15px; text-align: center;">
            <h2 style="color: #232f3e; margin-bottom: 20px;">Don't see the right position?</h2>
            <p style="color: #666; margin-bottom: 25px;">We're always looking for talented individuals to join our team.</p>
            <a href="contact.php" class="btn">Send Us Your Resume</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>