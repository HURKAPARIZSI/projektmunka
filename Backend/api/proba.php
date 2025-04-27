<?php
session_start();
require("../includes/db.php");

if (isset($_SESSION['message'])) {
    echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
    unset($_SESSION['message']); // Töröljük az üzenetet, hogy csak egyszer jelenjen meg
} else {
    echo "<p>Nincs üzenet.</p>";
}
?>
