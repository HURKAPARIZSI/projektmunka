<?php
  // adatok felvétele a kapcsolathoz
  $servername = "localhost";
  $username = "felhasznalo";
  $password = "jelszo";
  $dbname = "adatbazis";

  $conn = new mysqli("eretsegiprojekt.sql", $username, $password); // kapcsolat létrehozása

  // kapcsolódás ellenőrzése
  if ($conn->connect_error) {
    die("Sikertelen kapcsolódás: " . $conn->connect_error); // sikertelen kapcsolódás
  } else {
    echo "Sikeres kapcsolódás."; // sikeres kapcsolódás
  }
  $sql = "SELECT user_id, username FROM users";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) { // megvizsgáljuk, hogy a lekérdezésnek van-e eredménye
    // amíg van sor, addig kiíratjuk
    while($row = mysqli_fetch_assoc($result)) { // addig megy amíg van még sor, rekord
       echo "user_id: " . $row["user_id"]. ", username: " . $row["username"]. "<br>";
    }
}
?>