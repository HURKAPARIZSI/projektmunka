<?php
include_once '../includes/db.php';
include_once 'adatbazis.php';


// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</body>
</html>