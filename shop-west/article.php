<?php
$article_id = $_GET['id'] ?? 1;

$articles = [
    1 => [
        'title' => '10 Must-Have Tech Gadgets for 2024',
        'author' => 'Tech Team',
        'date' => '2024-01-15',
        'category' => 'Technology',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?w=800&h=400&fit=crop&crop=center',
        'content' => [
            'intro' => 'Technology continues to evolve at an unprecedented pace, bringing us innovative gadgets that transform how we work, play, and live. As we navigate through 2024, several groundbreaking devices have emerged as must-have items for tech enthusiasts and everyday users alike.',
            'sections' => [
                [
                    'heading' => '1. Smart Home Hub with AI Integration',
                    'content' => 'The latest smart home hubs now feature advanced AI capabilities that learn your daily routines and preferences. These devices can automatically adjust lighting, temperature, and security settings based on your behavior patterns. With voice recognition technology, they can distinguish between different family members and provide personalized responses.',
                    'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=300&fit=crop&crop=center'
                ],
                [
                    'heading' => '2. Wireless Charging Stations with Fast Charging',
                    'content' => 'Gone are the days of tangled cables and multiple chargers. Modern wireless charging stations can power up to 5 devices simultaneously, including smartphones, earbuds, smartwatches, and tablets. The latest models support 50W fast charging, reducing charging time by up to 60%.',
                    'image' => 'https://images.unsplash.com/photo-1609592806596-4d8b5b1d7e7a?w=600&h=300&fit=crop&crop=center'
                ],
                [
                    'heading' => '3. AR Glasses for Enhanced Reality',
                    'content' => 'Augmented Reality glasses have finally reached consumer-friendly prices and functionality. These lightweight devices overlay digital information onto the real world, perfect for navigation, translation, and productivity tasks. Battery life has improved to 8+ hours of continuous use.',
                    'image' => 'https://images.unsplash.com/photo-1592478411213-6153e4ebc696?w=600&h=300&fit=crop&crop=center'
                ],
                [
                    'heading' => '4. Portable Air Purifiers with UV-C Technology',
                    'content' => 'Health-conscious consumers are investing in portable air purifiers that combine HEPA filtration with UV-C sterilization. These compact devices can clean the air in small to medium rooms and are perfect for travel, offering protection against airborne particles and pathogens.',
                    'image' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=600&h=300&fit=crop&crop=center'
                ],
                [
                    'heading' => '5. Smart Fitness Mirrors',
                    'content' => 'Transform any room into a personal gym with smart fitness mirrors. These devices display workout routines, track your form using AI, and provide real-time feedback. With thousands of classes available on-demand, they offer the convenience of a personal trainer at home.',
                    'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600&h=300&fit=crop&crop=center'
                ]
            ],
            'conclusion' => 'These innovative gadgets represent the cutting edge of consumer technology in 2024. While each serves different needs, they all share common themes: enhanced connectivity, improved user experience, and seamless integration into our daily lives. As prices continue to become more accessible, these technologies will likely become standard in most households within the next few years.'
        ]
    ],
    2 => [
        'title' => 'Fashion Trends: Spring Collection 2024',
        'author' => 'Style Team',
        'date' => '2024-01-12',
        'category' => 'Fashion',
        'read_time' => '4 min read',
        'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=800&h=400&fit=crop&crop=center',
        'content' => [
            'intro' => 'Spring 2024 brings a refreshing blend of classic elegance and bold innovation. This season\'s fashion trends celebrate both sustainability and self-expression, offering something for every style preference.',
            'sections' => [
                [
                    'heading' => 'Sustainable Fashion Takes Center Stage',
                    'content' => 'Eco-friendly materials and ethical production methods are no longer just trends—they\'re becoming the standard. Brands are embracing recycled fabrics, organic cotton, and innovative materials made from ocean plastic.',
                    'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=300&fit=crop&crop=center'
                ],
                [
                    'heading' => 'Bold Colors and Patterns',
                    'content' => 'This spring is all about making a statement with vibrant colors and eye-catching patterns. From electric blues to sunset oranges, fashion enthusiasts are embracing bold choices that reflect optimism and creativity.',
                    'image' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=600&h=300&fit=crop&crop=center'
                ]
            ],
            'conclusion' => 'Spring 2024 fashion is about expressing individuality while being mindful of our environmental impact. The trends this season encourage us to invest in quality pieces that will last beyond the current season.'
        ]
    ]
];

$article = $articles[$article_id] ?? $articles[1];
$page_title = $article['title'];
include 'includes/header.php';
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                <a href="index.php" style="color: var(--primary-color); text-decoration: none;">Home</a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <a href="blog.php" style="color: var(--primary-color); text-decoration: none;">Blog</a>
                <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                <span style="color: var(--text-primary);"><?php echo $article['category']; ?></span>
            </div>
        </nav>

        <!-- Article Header -->
        <article style="max-width: 800px; margin: 0 auto;">
            <header style="margin-bottom: 3rem;">
                <div style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-lg); display: inline-block; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">
                    <?php echo $article['category']; ?>
                </div>
                
                <h1 style="font-size: 3rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.5rem; color: var(--text-primary);">
                    <?php echo $article['title']; ?>
                </h1>
                
                <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem; color: var(--text-secondary); font-size: 0.95rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user"></i>
                        <span>By <?php echo $article['author']; ?></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-calendar"></i>
                        <span><?php echo date('M j, Y', strtotime($article['date'])); ?></span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $article['read_time']; ?></span>
                    </div>
                </div>
                

            </header>

            <!-- Article Content -->
            <div style="background: var(--bg-primary); padding: 3rem; border-radius: var(--radius-xl); border: 1px solid var(--border-color); box-shadow: var(--shadow-lg);">
                <!-- Introduction -->
                <div style="font-size: 1.2rem; line-height: 1.8; color: var(--text-secondary); margin-bottom: 3rem; padding: 2rem; background: var(--bg-feature); border-radius: var(--radius-lg); border-left: 4px solid var(--primary-color);">
                    <?php echo $article['content']['intro']; ?>
                </div>

                <!-- Article Sections -->
                <?php foreach ($article['content']['sections'] as $section): ?>
                <section style="margin-bottom: 3rem;">
                    <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">
                        <?php echo $section['heading']; ?>
                    </h2>
                    
                    <?php if (isset($section['image'])): ?>
                    <div style="margin-bottom: 2rem; border-radius: var(--radius-lg); overflow: hidden;">
                        <img src="<?php echo $section['image']; ?>" alt="<?php echo $section['heading']; ?>" style="width: 100%; height: 300px; object-fit: cover;">
                    </div>
                    <?php endif; ?>
                    
                    <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary); margin-bottom: 2rem;">
                        <?php echo $section['content']; ?>
                    </p>
                </section>
                <?php endforeach; ?>

                <!-- Conclusion -->
                <div style="background: var(--bg-feature); padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--success-color); margin-top: 3rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary);">
                        <i class="fas fa-lightbulb" style="color: var(--success-color);"></i>
                        Key Takeaways
                    </h3>
                    <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary);">
                        <?php echo $article['content']['conclusion']; ?>
                    </p>
                </div>
            </div>

            <!-- Article Actions -->
            <div style="margin-top: 3rem; padding: 2rem; background: var(--bg-feature); border-radius: var(--radius-xl); text-align: center;">
                <h3 style="margin-bottom: 2rem; color: var(--text-primary);">Enjoyed this article?</h3>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <button onclick="shareArticle()" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-share-alt"></i>
                        Share Article
                    </button>
                    <a href="blog.php" style="background: var(--bg-primary); color: var(--text-primary); border: 2px solid var(--border-color); padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary-color)'; this.style.color='var(--primary-color)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-arrow-left"></i>
                        Back to Blog
                    </a>
                </div>
            </div>
        </article>
    </div>
</main>

<script>
function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($article['title']); ?>',
            text: 'Check out this article from Shop West Blog',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            if (window.shopWest) {
                window.shopWest.showNotification('Article link copied to clipboard!', 'success', 3000);
            }
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>