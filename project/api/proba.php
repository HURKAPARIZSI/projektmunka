<?php
    include_once '../includes/db.php';
    include_once 'adatbazis.php';

    $adatbazis = new Database($conn);
    $termekek = $adatbazis->getProducts();

    foreach($termekek as $termek){
        echo "<div>";
        echo "<h3>" . htmlspecialchars($termek['name']) . "</h3>";
        echo "<p>Ár: " . htmlspecialchars($termek['price']) . " Ft</p>";
        
        // Alapértelmezett kép megjelenítése
        if (!empty($termek['image_default'])) {
            echo '<img src="' . htmlspecialchars($termek['image_default']) . '" alt="Termékkép" width="200">';
        }

        echo "</div><hr>";

    }




?>