<?php
// Check if admin user exists and create if not
function ensureAdminExists($conn) {
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
        $stmt->execute();
        if (!$stmt->fetch()) {
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['admin', 'admin@furniture.com', $password, 'Administrator', 'admin']);
            echo "Admin user created successfully!\n";
        }
    } catch (Exception $e) {
        echo "Error creating admin user: " . $e->getMessage() . "\n";
    }
}

// Check if category exists by name
function categoryExists($conn, $name) {
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ? LIMIT 1");
    $stmt->execute([$name]);
    return $stmt->fetch() !== false;
}

// Initialize database tables and data
function initializeDatabase($conn) {
    try {
        // Create tables
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100) NOT NULL,
                phone VARCHAR(20),
                address TEXT,
                role ENUM('user', 'admin') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_username (username),
                INDEX idx_email (email)
            )",
            "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE INDEX idx_category_name (name)
            )",
            "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(200) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                discount_price DECIMAL(10,2) DEFAULT NULL,
                category_id INT,
                stock_quantity INT DEFAULT 0,
                image VARCHAR(255),
                gallery TEXT,
                specifications TEXT,
                status ENUM('active', 'inactive') DEFAULT 'active',
                featured BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id),
                INDEX idx_category (category_id),
                INDEX idx_status (status),
                INDEX idx_featured (featured)
            )",
            "CREATE TABLE IF NOT EXISTS cart (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                product_id INT,
                quantity INT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                INDEX idx_user (user_id)
            )",
            "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                total_amount DECIMAL(10,2) NOT NULL,
                status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
                shipping_address TEXT NOT NULL,
                payment_method VARCHAR(50),
                payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                INDEX idx_user_order (user_id),
                INDEX idx_status (status)
            )",
            "CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT,
                product_id INT,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id),
                INDEX idx_order (order_id)
            )",
            "CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT,
                user_id INT,
                rating INT CHECK (rating >= 1 AND rating <= 5),
                comment TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_product (product_id),
                INDEX idx_user (user_id)
            )"
        ];

        foreach ($queries as $query) {
            $conn->exec($query);
        }

        // Create admin user
        ensureAdminExists($conn);

        // Add default categories if they don't exist
        $default_categories = [
            ['Sofas & Chairs', 'Comfortable seating furniture for your living room', 'sofa.jpg'],
            ['Tables', 'Dining tables, coffee tables, and more', 'table.jpg'],
            ['Bedroom', 'Beds, wardrobes, and bedroom furniture', 'bedroom.jpg'],
            ['Storage', 'Cabinets, shelves, and storage solutions', 'storage.jpg'],
            ['Office', 'Desks, chairs, and office furniture', 'office.jpg']
        ];

        foreach ($default_categories as $category) {
            if (!categoryExists($conn, $category[0])) {
                $stmt = $conn->prepare("INSERT INTO categories (name, description, image) VALUES (?, ?, ?)");
                $stmt->execute($category);
            }
        }

        echo "Database initialized successfully!\n";
        return true;
    } catch (Exception $e) {
        echo "Error initializing database: " . $e->getMessage() . "\n";
        return false;
    }
}

// Connect to database and initialize
require_once 'config/database.php';
try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (initializeDatabase($conn)) {
        // Import sailing products if they haven't been imported yet
        if (file_exists('sailing_products.sql')) {
            $sailing_products = file_get_contents('sailing_products.sql');
            $conn->exec($sailing_products);
            echo "Sailing products imported successfully!\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>