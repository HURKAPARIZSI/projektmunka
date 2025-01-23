<?php
session_start();
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
    <title>Kapcsolatok</title>
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
        <h1>Kapcsolatok</h1>
        <form>
            <label for="name">Név:</label>
            <input type="text" id="name" name="name">
            
            <label for="message">Üzenet:</label>
            <textarea id="message" name="message"></textarea>
            
            <button type="submit">Küldés</button>
        </form>
        <address>
            <p>Email: info@webshop.hu</p>
            <p>Telefon: +36 30 123 4567</p>
        </address>
    </main>

</body>
</html>