<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../frontend/index.html");
    exit;
}

// Ha itt vagy, a felhasználó be van jelentkezve
echo "Üdvözlünk a védett oldalon!";
echo '<br><a href="logout.php">Kijelentkezés</a>';
?>