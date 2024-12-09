<?php
// Ellenőrizzük, hogy a kérés POST módszerrel érkezett
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Adatbázis kapcsolódási adatok
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "webshop";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Kapcsolódási hiba: " . $conn->connect_error);
    }

    if ($action === "register") {
        // Regisztrációs logika
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password2 = htmlspecialchars($_POST['password2']);

        if ($password !== $password2) {
            die("A jelszavak nem egyeznek.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Érvénytelen email cím.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Sikeres regisztráció!";
        } else {
            echo "Hiba történt: " . $stmt->error;
        }

        $stmt->close();
    } elseif ($action === "login") {
        // Bejelentkezési logika
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                session_start();
                $_SESSION['email'] = $email;
                echo "Sikeres bejelentkezés!";
            } else {
                echo "Hibás jelszó.";
            }
        } else {
            echo "A megadott email-cím nem található.";
        }

        $stmt->close();
    } else {
        echo "Érvénytelen művelet.";
    }

    $conn->close();
} else {
    echo "Helytelen kérés.";
}
?>
