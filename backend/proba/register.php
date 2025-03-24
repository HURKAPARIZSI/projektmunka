<?php
session_start();

// Üzenet törlése a megjelenítés után
$message = '';
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message']; // Üzenet kiolvasása
    unset($_SESSION['message']); // Üzenet törlése
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    //echo "Üdvözlünk, " . htmlspecialchars($_SESSION['username']) . "! Be vagy jelentkezve.";
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";

} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <h1>Webshop.kft</h1>  
        <a href="logout.php">Kijelentkezés</a>  
        <!--<a href="email.php">asdasd  </a>-->
        <nav>
            <a class="item-1" href="main.php">Kezdőlap</a>
            <a class="item-1" href="index.php">Termékek</a>
            <a class="item-2" href="contact.php">Kapcsolatok</a>
            <a class="icon" href="cart.php">
                <img src="img/th.jpg" alt="Kosár" title="Kosár">
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?= $cartCount ?></span>
                <?php endif; ?>
            </a> 
            <a class="icon" href="login.php"><img src="<?= htmlspecialchars($_SESSION['loggedinimg']) ?>" alt="Bejelentkezés" title="Bejelentkezés"></a> 
    </header>
    <main>
        <h1>Regisztráció</h1>
        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input type="hidden" name="action" value="register">
            <label for="username">Username:</label>
            <input type="username" id="username" name="username" required>
            

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>

            <label for="password2">Jelszó újra:</label>
            <input type="password" id="password2" name="password2" required>
            
            <button type="submit">Regisztráció</button>

            <p>Van már fiókod? <a href="login.php">Bejelentkezés</a></p>
        </form>
    </main>
</body>
</html>

<?php
require("../includes/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['password2']);

    // Alapvető validációk
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "Üres mezők!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Rossz email cím!";
    } elseif ($password !== $confirm_password) {
        $_SESSION['message'] = "Jelszavak nem egyeznek!";
    } else {
        // Ellenőrzés, hogy az e-mail vagy felhasználónév már létezik-e
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();
        echo"asdasd";
        if ($stmt->num_rows > 0) {
            $_SESSION['message'] = "Az e-mail vagy a felhasználónév már regisztrálva van!!";
        } else {
            // Jelszó titkosítása és adatok hozzáadása az adatbázishoz
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $username, $email, $password_hash);
            echo "Sikeres kapcsolódás.";
            if ($stmt->execute()) {
                $_SESSION['message'] = "Regisztrálva!";
                echo"regisztráció";
                header("Location: register.php");
            } else {
                $message = "Hiba történt a regisztráció során: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}


?>