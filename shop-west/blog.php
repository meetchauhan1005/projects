<?php
$page_title = "Blog";
include 'includes/header.php';

$blog_posts = [
    [
        'id' => 1,
        'title' => '10 Must-Have Tech Gadgets for 2024',
        'excerpt' => 'Discover the latest technology trends and gadgets that are revolutionizing our daily lives.',
        'image' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?w=400&h=250&fit=crop&crop=center',
        'author' => 'Tech Team',
        'date' => '2024-01-15',
        'category' => 'Technology',
        'read_time' => '5 min read'
    ],
    [
        'id' => 2,
        'title' => 'Fashion Trends: Spring Collection 2024',
        'excerpt' => 'Explore the hottest fashion trends and styling tips for the upcoming spring season.',
        'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=250&fit=crop&crop=center',
        'author' => 'Style Team',
        'date' => '2024-01-12',
        'category' => 'Fashion',
        'read_time' => '4 min read'
    ],
    [
        'id' => 3,
        'title' => 'Home Decor Ideas on a Budget',
        'excerpt' => 'Transform your living space with these affordable and creative home decoration ideas.',
        'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=250&fit=crop&crop=center',
        'author' => 'Home Team',
        'date' => '2024-01-10',
        'category' => 'Home & Garden',
        'read_time' => '6 min read'
    ],
    [
        'id' => 4,
        'title' => 'Fitness Equipment for Home Workouts',
        'excerpt' => 'Build your perfect home gym with these essential fitness equipment recommendations.',
        'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=250&fit=crop&crop=center',
        'author' => 'Fitness Team',
        'date' => '2024-01-08',
        'category' => 'Sports & Fitness',
        'read_time' => '7 min read'
    ],
    [
        'id' => 5,
        'title' => 'Book Recommendations: Best Reads of 2024',
        'excerpt' => 'Discover amazing books across different genres that you should add to your reading list.',
        'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=250&fit=crop&crop=center',
        'author' => 'Literary Team',
        'date' => '2024-01-05',
        'category' => 'Books',
        'read_time' => '3 min read'
    ],
    [
        'id' => 6,
        'title' => 'Sustainable Shopping: Eco-Friendly Products',
        'excerpt' => 'Learn about sustainable shopping practices and discover eco-friendly product alternatives.',
        'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=400&h=250&fit=crop&crop=center',
        'author' => 'Sustainability Team',
        'date' => '2024-01-03',
        'category' => 'Lifestyle',
        'read_time' => '5 min read'
    ]
];
?>

<main>
    <div class="container" style="padding: 2rem 0;">
        <div class="section-header mb-6">
            <h1 class="section-title">
                <i class="fas fa-blog" style="color: var(--primary-color);"></i>
                Shop West Blog
            </h1>
            <p class="section-subtitle">Stay updated with the latest trends, tips, and product insights</p>
        </div>

        <!-- Featured Post -->
        <div style="background: var(--bg-primary); border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 3rem; box-shadow: var(--shadow-xl); border: 1px solid var(--border-color); position: relative;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; align-items: center;">
                <div style="padding: 3rem;">
                    <div style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-lg); display: inline-block; font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem;">
                        Featured Post
                    </div>
                    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem; color: var(--text-primary);"><?php echo $blog_posts[0]['title']; ?></h2>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.1rem; line-height: 1.6;"><?php echo $blog_posts[0]['excerpt']; ?></p>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; color: var(--text-secondary); font-size: 0.9rem;">
                        <span><i class="fas fa-user"></i> <?php echo $blog_posts[0]['author']; ?></span>
                        <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($blog_posts[0]['date'])); ?></span>
                        <span><i class="fas fa-clock"></i> <?php echo $blog_posts[0]['read_time']; ?></span>
                    </div>
                    <a href="article.php?id=<?php echo $blog_posts[0]['id']; ?>" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1.25rem 2.5rem; border-radius: var(--radius-xl); font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); transition: all 0.3s ease; text-decoration: none; font-size: 1.1rem; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 12px 35px rgba(37, 99, 235, 0.5)'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 6px 20px rgba(37, 99, 235, 0.4)'">
                        <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease;"></div>
                        <i class="fas fa-book-open" style="font-size: 1.2rem;"></i>
                        Read Full Article
                        <i class="fas fa-arrow-right" style="transition: transform 0.3s ease;"></i>
                    </a>
                </div>
                <div style="position: relative; overflow: hidden;">
                    <img src="<?php echo $blog_posts[0]['image']; ?>" alt="<?php echo $blog_posts[0]['title']; ?>" style="width: 100%; height: 400px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="position: absolute; inset: 0; background: linear-gradient(45deg, rgba(37, 99, 235, 0.1), rgba(16, 185, 129, 0.1));"></div>
                </div>
            </div>
        </div>

        <!-- Blog Categories -->
        <div style="background: var(--bg-feature); padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 3rem;">
            <h3 style="text-align: center; margin-bottom: 2rem; color: var(--text-primary);">Browse by Category</h3>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <button onclick="filterCategory('all')" style="background: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">All Posts</button>
                <button onclick="filterCategory('technology')" style="background: var(--info-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Technology</button>
                <button onclick="filterCategory('fashion')" style="background: var(--success-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Fashion</button>
                <button onclick="filterCategory('home')" style="background: var(--warning-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Home & Garden</button>
                <button onclick="filterCategory('lifestyle')" style="background: var(--danger-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Lifestyle</button>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            <?php foreach (array_slice($blog_posts, 1) as $post): ?>
            <article style="background: var(--bg-primary); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-xl)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'">
                <div style="position: relative;">
                    <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="position: absolute; top: 1rem; left: 1rem; background: rgba(0,0,0,0.7); color: white; padding: 0.5rem 1rem; border-radius: var(--radius-lg); font-size: 0.875rem; font-weight: 600;">
                        <?php echo $post['category']; ?>
                    </div>
                </div>
                <div style="padding: 2rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary); line-height: 1.4;">
                        <?php echo $post['title']; ?>
                    </h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                        <?php echo $post['excerpt']; ?>
                    </p>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; color: var(--text-secondary); font-size: 0.875rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span><i class="fas fa-user"></i> <?php echo $post['author']; ?></span>
                            <span><i class="fas fa-clock"></i> <?php echo $post['read_time']; ?></span>
                        </div>
                        <span><?php echo date('M j', strtotime($post['date'])); ?></span>
                    </div>
                    <a href="article.php?id=<?php echo $post['id']; ?>" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border: none; padding: 1rem 1.5rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; width: 100%; justify-content: center; transition: all 0.3s ease; text-decoration: none; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(37, 99, 235, 0.4)'; this.querySelector('.arrow').style.transform='translateX(5px)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''; this.querySelector('.arrow').style.transform='translateX(0)'">
                        <div style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease;"></div>
                        <i class="fas fa-book-open"></i>
                        Read Article
                        <i class="fas fa-arrow-right arrow" style="transition: transform 0.3s ease;"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Newsletter Subscription -->
        <div style="background: var(--bg-feature); padding: 3rem; border-radius: var(--radius-xl); text-align: center; margin-top: 4rem;">
            <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary);">Stay Updated</h3>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">Subscribe to our newsletter and never miss the latest trends, tips, and exclusive offers.</p>
            <div style="display: flex; justify-content: center; gap: 1rem; max-width: 400px; margin: 0 auto;">
                <input type="email" placeholder="Enter your email" style="flex: 1; padding: 1rem; border: 2px solid var(--border-color); border-radius: var(--radius-lg); font-size: 1rem;" id="newsletterEmail">
                <button onclick="subscribeNewsletter()" style="background: linear-gradient(135deg, var(--success-color), #059669); color: white; border: none; padding: 1rem 2rem; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-paper-plane"></i>
                    Subscribe
                </button>
            </div>
        </div>
    </div>
</main>

<script>
function readPost(postId) {
    if (window.shopWest) {
        window.shopWest.showNotification(`Opening blog post #${postId}. Full article feature coming soon!`, 'info', 4000);
    }
}

function filterCategory(category) {
    if (window.shopWest) {
        window.shopWest.showNotification(`Filtering posts by category: ${category}. Filter feature coming soon!`, 'info', 3000);
    }
}

function subscribeNewsletter() {
    const email = document.getElementById('newsletterEmail').value;
    if (email && email.includes('@')) {
        if (window.shopWest) {
            window.shopWest.showNotification(`Thank you for subscribing with ${email}! You'll receive our latest updates.`, 'success', 5000);
            document.getElementById('newsletterEmail').value = '';
        }
    } else {
        if (window.shopWest) {
            window.shopWest.showNotification('Please enter a valid email address.', 'error', 3000);
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>