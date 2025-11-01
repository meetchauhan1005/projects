<?php
require_once 'config/database.php';

try {
    // Read the SQL file
    $sql = file_get_contents('sailing_products.sql');
    
    // Split into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each query
    foreach ($queries as $query) {
        if (!empty($query)) {
            $conn->query($query);
        }
    }
    
    echo "Successfully imported sailing-themed products!";
} catch (Exception $e) {
    echo "Error importing products: " . $e->getMessage();
}
?>