<?php
session_start(); // Session indítása

// Minden session adat törlése
$_SESSION = [];

// Session cookie törlése, ha létezik
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session lezárása
session_destroy();

// Átirányítás a bejelentkezési oldalra
header("Location: login.php");
exit;