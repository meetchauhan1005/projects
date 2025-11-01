<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if profile_photo column exists
    $check = $db->query("SHOW COLUMNS FROM users LIKE 'profile_photo'");
    
    if($check->rowCount() == 0) {
        // Add profile_photo column
        $db->exec("ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255)");
        echo "Added profile_photo column to users table.<br>";
    } else {
        echo "Profile_photo column already exists.<br>";
    }
    
    // Create profiles directory
    $dir = "assets/images/profiles/";
    if(!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created profiles directory.<br>";
    } else {
        echo "Profiles directory already exists.<br>";
    }
    
    echo "<br><strong>Profile photo system is ready!</strong><br>";
    echo "<a href='profile.php'>Go to Profile</a>";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>