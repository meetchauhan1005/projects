<?php
require_once 'includes/functions.php';
$page_title = 'About Us';

include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem;">
    <!-- Hero Section -->
    <section style="text-align: center; padding: 4rem 0; background: linear-gradient(135deg, #8B4513, #A0522D); color: white; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">About FurnitureStore</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Creating beautiful, functional spaces with premium furniture since 2020</p>
    </section>

    <!-- Our Story -->
    <section style="margin-bottom: 4rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <h2 style="color: #8B4513; margin-bottom: 1.5rem;">Our Story</h2>
                <p style="margin-bottom: 1rem; line-height: 1.6;">Founded with a passion for quality craftsmanship and timeless design, FurnitureStore has been transforming homes and offices with carefully curated furniture collections.</p>
                <p style="margin-bottom: 1rem; line-height: 1.6;">We believe that furniture is more than just functional pieces – it's about creating spaces where memories are made, productivity flourishes, and comfort meets style.</p>
                <p style="line-height: 1.6;">Our commitment to excellence drives us to source only the finest materials and work with skilled artisans who share our vision for quality.</p>
            </div>
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; text-align: center;">
                <div style="font-size: 4rem; color: #8B4513; margin-bottom: 1rem;">🏠</div>
                <h3>10,000+</h3>
                <p>Happy Customers</p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section style="background: #f8f9fa; padding: 3rem; border-radius: 15px; margin-bottom: 4rem;">
        <h2 style="text-align: center; color: #8B4513; margin-bottom: 3rem;">Our Values</h2>
        <div class="category-grid">
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Quality First</h3>
                <p>We never compromise on quality. Every piece is carefully inspected to meet our high standards.</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Sustainability</h3>
                <p>Committed to eco-friendly practices and sustainable sourcing of materials.</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Customer Focus</h3>
                <p>Your satisfaction is our priority. We're here to help you create your perfect space.</p>
            </div>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Design Excellence</h3>
                <p>Curating beautiful, functional pieces that stand the test of time.</p>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section style="margin-bottom: 4rem;">
        <h2 style="text-align: center; color: #8B4513; margin-bottom: 3rem;">Meet Our Team</h2>
        <div class="category-grid">
            <div class="category-card">
                <div style="width: 80px; height: 80px; background: #8B4513; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">JD</div>
                <h3>John Davis</h3>
                <p style="color: #8B4513; font-weight: bold;">Founder & CEO</p>
                <p>20+ years in furniture design and retail</p>
            </div>
            <div class="category-card">
                <div style="width: 80px; height: 80px; background: #A0522D; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">SM</div>
                <h3>Sarah Miller</h3>
                <p style="color: #8B4513; font-weight: bold;">Head of Design</p>
                <p>Interior design expert with an eye for trends</p>
            </div>
            <div class="category-card">
                <div style="width: 80px; height: 80px; background: #8B4513; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">MJ</div>
                <h3>Mike Johnson</h3>
                <p style="color: #8B4513; font-weight: bold;">Operations Manager</p>
                <p>Ensuring quality and timely delivery</p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 3rem; border-radius: 15px;">
        <h2 style="text-align: center; color: #8B4513; margin-bottom: 3rem;">Why Choose FurnitureStore?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center;">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #8B4513; margin-bottom: 1rem;"></i>
                <h4>Fast Delivery</h4>
                <p>Quick and secure delivery to your doorstep</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-tools" style="font-size: 3rem; color: #8B4513; margin-bottom: 1rem;"></i>
                <h4>Assembly Service</h4>
                <p>Professional assembly service available</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-medal" style="font-size: 3rem; color: #8B4513; margin-bottom: 1rem;"></i>
                <h4>Warranty</h4>
                <p>Comprehensive warranty on all products</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-heart" style="font-size: 3rem; color: #8B4513; margin-bottom: 1rem;"></i>
                <h4>Customer Care</h4>
                <p>Dedicated support team for all your needs</p>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>