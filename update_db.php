<?php
require_once __DIR__.'/config/db.php';

try {
    // Modify matches
    $pdo->exec("ALTER TABLE matches ADD COLUMN penalties1 INT DEFAULT NULL");
    $pdo->exec("ALTER TABLE matches ADD COLUMN penalties2 INT DEFAULT NULL");
    
    // Modify predictions
    $pdo->exec("ALTER TABLE predictions ADD COLUMN penalty_winner_team1 TINYINT(1) DEFAULT 0");
    $pdo->exec("ALTER TABLE predictions ADD COLUMN penalty_winner_team2 TINYINT(1) DEFAULT 0");
    
    echo "Database updated successfully.\n";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
