<?php
session_start();

// Üzenet törlése a megjelenítés után
$message = '';
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message']; // Üzenet kiolvasása
    unset($_SESSION['message']); // Üzenet törlése
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
    //echo "Üdvözlünk, " . htmlspecialchars($_SESSION['username']) . "! Be vagy jelentkezve.";
    //header("Location: logout.php");

} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Webshop.kft</h1>
        <nav>
            <a href="index.php">Kezdőlap</a>
            <a href="contact.php">Kapcsolatok</a>
            <a class="icon" href="cart.php"><img src="img/th.jpg" alt="Kosár" title="Kosár"></a>
            <a class="icon" href="login.php"><img src="<?= htmlspecialchars($_SESSION['loggedinimg']) ?>" alt="Bejelentkezés" title="Bejelentkezés"></a> 
        </nav>
    </header>
    <main>
        <!-- Üzenet megjelenítése -->
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <h1>Bejelentkezés</h1>



        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Bejelentkezés</button>
            <p><a href="register.php">Regisztráció</a></p>
        </form>
    </main>
</body>
</html>



<?php
require("../includes/db.php"); // Adatbázis kapcsolat betöltése
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Email mező
    $password = $_POST['password']; // Jelszó mező
    $_SESSION['error'] = "4";
    // SQL lekérdezés előkészítése
    $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error'] = "4";
        die("SQL előkészítési hiba: " . $conn->error);
    }

    // Paraméterek kötése
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $_SESSION['error'] = "1";
    if ($result->num_rows === 1) {
        // Felhasználó megtalálva
        $user = $result->fetch_assoc();
        $_SESSION['error'] = "2";
        // Jelszó ellenőrzése
        if (password_verify($password, $user['password'])) {
            // Sikeres bejelentkezés
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['message'] = "Bejelentkezve!";
            header("Location: login.php");
            exit;   
            
        } else {
            $_SESSION['message'] = "Hibás Jelszó!";
            echo "Hibás jelszó.";
        }
    } else {
        $_SESSION['message'] = "Hibés email-cím!";
        echo "Hibás email-cím.";
    }

    $stmt->close();
}
else{
    
}
$conn->close(); // Kapcsolat lezárása
?>