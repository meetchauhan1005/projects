<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if($conn) {
        echo "Database connection successful!\n";
        
        // Test if the database exists
        $result = $conn->query("SHOW TABLES");
        echo "\nTables in the database:\n";
        while($row = $result->fetch(PDO::FETCH_COLUMN)) {
            echo "- " . $row . "\n";
        }
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>