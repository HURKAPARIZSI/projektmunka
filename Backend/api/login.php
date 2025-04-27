<?php
session_start();
require("../includes/db.php"); // Adatbázis kapcsolat betöltése

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Email mező
    $password = $_POST['password']; // Jelszó mező
    
    // SQL lekérdezés előkészítése
    $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("SQL előkészítési hiba: " . $conn->error);
    }

    // Paraméterek kötése
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Felhasználó megtalálva
        $user = $result->fetch_assoc();
        echo("kakifejasd");
        // Jelszó ellenőrzése
        if (password_verify($password, $user['password'])) {
            // Sikeres bejelentkezés
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['message'] = "Teszt üzenet!";
            header("Location: ../../frontend/login.html");
            echo("kakifej");
            exit;
            
        } else {
            echo "Hibás jelszó.";
        }
    } else {
        echo "Hibás email-cím.";
    }

    $stmt->close();
}
else{
    echo("asdef");
}
$conn->close(); // Kapcsolat lezárása
?>