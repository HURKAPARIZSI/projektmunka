<?php
  $servername = "localhost";
  $username = "felhasznalo";
  $password = "jelszo"; 
  $dbname = "projektdb";

  $conn = new mysqli($servername, $username, $password, $dbname); // kapcsolat létrehozása




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['password2']);

    // Alapvető validációk
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Minden mezőt ki kell tölteni!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Érvényes e-mail címet adj meg!";
    } elseif ($password !== $confirm_password) {
        $error = "A jelszavak nem egyeznek!";
    } else {
        // Ellenőrzés, hogy az e-mail vagy felhasználónév már létezik-e
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Az e-mail vagy a felhasználónév már regisztrálva van!";
            echo "email vagy felhasználó.";
        } else {
            // Jelszó titkosítása és adatok hozzáadása az adatbázishoz
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $username, $email, $password_hash);
            echo "Sikeres kapcsolódás.";
            if ($stmt->execute()) {
                $success = "Sikeres regisztráció!";
            } else {
                $error = "Hiba történt a regisztráció során: " . $stmt->error;
            }
        }
        //  $stmt->close();
    }
}



















$sql = "SELECT user_id, username FROM users";

$result = $conn->query($sql);

// eredmény kiíratása
if ($result->num_rows > 0) { // ha a lekérdezésnek van eredménye, akkor belépünk az if-be
  while($row = $result->fetch_assoc()) { // amíg van rekord, addig kiírom őket
    echo "id: " . $row["user_id"]. ", név: " . $row["username"]. "<br>";
  }
} else {
  echo "A lekérdezésnek nincs eredménye."; // nincs eredmény
}

$conn->close();
?>