<?php
// config/db.php
date_default_timezone_set('America/Argentina/Buenos_Aires');

// COMPLETA ESTOS DATOS CON LOS DEL SERVIDOR DE LA EMPRESA
$host = 'localhost';
$dbname = 'nombre_de_la_base_de_datos';
$username = 'usuario_de_la_bd';
$password = 'contraseña_de_la_bd';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
