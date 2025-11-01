<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Update existing categories to match the new structure
    $updates = [
        ['Smartphones', 'Mobile phones and smartphone accessories', 'Mobile Phones'],
        ['Laptops', 'Laptops, notebooks and computer accessories', 'Laptops'],
        ['Accessories', 'Electronic accessories and peripherals', 'Electronics'],
        ['Home Audio', 'Audio systems and home entertainment', 'Home Appliances']
    ];
    
    foreach($updates as $update) {
        $stmt = $db->prepare("UPDATE categories SET name = ?, description = ? WHERE name = ?");
        $stmt->execute([$update[0], $update[1], $update[2]]);
    }
    
    // Add missing categories if they don't exist
    $categories = [
        ['Smartphones', 'Mobile phones and smartphone accessories'],
        ['Laptops', 'Laptops, notebooks and computer accessories'],
        ['Accessories', 'Electronic accessories and peripherals'],
        ['Home Audio', 'Audio systems and home entertainment']
    ];
    
    foreach($categories as $category) {
        $check = $db->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
        $check->execute([$category[0]]);
        
        if($check->fetchColumn() == 0) {
            $insert = $db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $insert->execute($category);
        }
    }
    
    echo "Categories updated successfully!<br>";
    echo "<a href='index.php'>Go to Homepage</a> | <a href='products.php'>View Products</a> | <a href='admin/login.php'>Admin Panel</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>