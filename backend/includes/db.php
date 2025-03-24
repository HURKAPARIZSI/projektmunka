<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "projek_db";

// Adatbázis kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Hibaellenőrzés
if ($conn->connect_error) {
    die("Kapcsolati hiba: " . $conn->connect_error);
}
?>