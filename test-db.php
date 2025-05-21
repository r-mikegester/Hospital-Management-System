<?php
require_once './config/config.php'; // Ensure this points to your config file

try {
    // Test the connection
    $stmt = $conn->query("SELECT 1");
    if ($stmt) {
        echo "Connection to the database was successful!";
    } else {
        echo "Connection test failed.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
