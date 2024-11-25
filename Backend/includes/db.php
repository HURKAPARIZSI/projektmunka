<?php
$dsn = "mysql:host=localhost;dbname=eretsegiprojekt;charset=utf8mb4"; // Igazítsd az adatbázis adatokhoz
$username = "root"; // Az adatbázis felhasználónév
$password = "";     // Az adatbázis jelszó



try {
    $pdo = new PDO($dsn, $username, $password);
    $stmt = $pdo->("SELECT * FROM orders");
    var_dump($stmt);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
?>
