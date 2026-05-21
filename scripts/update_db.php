<?php
require_once 'config/db.php';

try {
    // Add is_fan column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN is_fan TINYINT(1) DEFAULT 0 AFTER full_name");
    echo "Database updated successfully: is_fan column added.\n";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
?>
