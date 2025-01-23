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
    <title>Kosár</title>
    <link rel="stylesheet" href="styles.css">
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
        <h1>Kosár</h1>
        <table>
            <thead>
                <tr>
                    <th>Termék</th>
                    <th>Ár</th>
                    <th>Művelet</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <td></td>
                    <td></td>
                    <td><button>Eltávolítás</button></td>
                </tr>
            </tbody>
        </table>
        <a id="penztargomb" href="penztar.html" >Pénztár</a>
    </main>
    <script src="script.js"></script>
</body>
</html>