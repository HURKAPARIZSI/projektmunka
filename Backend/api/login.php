<?php
// Beállítjuk a kapcsolatot az adatbázissal
require("../includes/db.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Az SQL lekérdezés előkészítése
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' jelzi, hogy a paraméter típus string
    $stmt->execute();
    $result = $stmt->get_result();

    // Ha találunk felhasználót az adott email címhez
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Ellenőrizzük a jelszót (feltételezzük, hogy a jelszó hash-el van tárolva az adatbázisban)
        if (password_verify($password, $user['password'])) {
            // Sikeres bejelentkezés
            $_SESSION['user_id'] = $user['user_id']; // Felhasználó azonosító mentése munkamenetbe
            header("Location: protected.php"); // Átirányítás
            echo "Sikeres bejelentkezés, üdvözlünk, " . htmlspecialchars($user['username']) . "!";
            // Itt beállíthatnál session-t is, hogy a felhasználó be legyen jelentkezve
        } else {
            // Hibás jelszó
            echo "Hibás jelszó!";
        }
    } else {
        // Nincs olyan felhasználó, aki a megadott email címmel regisztrált
        echo "Nincs felhasználó ezzel az email címmel!";
    }

    // Bezárjuk a kapcsolatot
    $stmt->close();
    $conn->close();
}
?>