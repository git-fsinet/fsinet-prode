<?php
require_once __DIR__.'/config/db.php';
$stmt = $pdo->query("SELECT * FROM predictions ORDER BY id DESC LIMIT 5");
$res = $stmt->fetchAll();
print_r($res);
