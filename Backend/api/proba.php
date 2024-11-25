<?php
require '../includes/db.php';
try {
    // SELECT lekérdezés a Users táblára
    $stmt = $pdo->query("SELECT user_id, username, email, created_at, is_admin FROM Users");

    // Eredmények bejárása
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Felhasználó ID: " . $row['user_id'] . "<br>";
        echo "Felhasználónév: " . $row['username'] . "<br>";
        echo "E-mail: " . $row['email'] . "<br>";
        echo "Regisztráció dátuma: " . $row['created_at'] . "<br>";
        echo "Adminisztrátor: " . ($row['is_admin'] ? "Igen" : "Nem") . "<br><hr>";
    }
} catch (PDOException $e) {
    echo "Hiba történt: " . $e->getMessage();
}