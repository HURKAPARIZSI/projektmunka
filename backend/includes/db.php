<?php
$servername = "localhost";
$username = "felhasznalo";
$password = "jelszo"; 
$dbname = "projektdb";

// Adatbázis kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Hibaellenőrzés
if ($conn->connect_error) {
    die("Kapcsolati hiba: " . $conn->connect_error);
}
?>