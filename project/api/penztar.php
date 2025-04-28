<?php
session_start();
include '../includes/db.php'; 

$address = $phone = $email = "";

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT email, phone, address FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($email, $phone, $address);
    $stmt->fetch();
    $stmt->close();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rendeles'])){
    

    
    $user_id = $_SESSION['user_id'];

    // 1. cart_id lekérdezése a user alapján
    $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($cart_id);
    $stmt->fetch();
    $stmt->close();

    if (!$cart_id) {
        die("Nincs kosár a felhasználónak.");
    }

    // 2. Megrendelés létrehozása
    $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 3. Kosár elemek lekérdezése
    $query = "SELECT ci.product_id, ci.quantity, p.price
              FROM cart_items ci
              JOIN products p ON ci.product_id = p.product_id
              WHERE ci.cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 4. Átmásolás order_items-be
    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    while ($row = $result->fetch_assoc()) {
        $insert_item->bind_param("iiid", $order_id, $row['product_id'], $row['quantity'], $row['price']);
        $insert_item->execute();
    }
    $insert_item->close();
    $stmt->close();

    // 5. Kosár törlése
    $delete = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
    $delete->bind_param("i", $cart_id);
    $delete->execute();
    $delete->close();

    echo "Sikeres megrendelés! Rendelés azonosító: #" . $order_id;

}










?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>RoulettJunior25.kft</h1>    
        <nav>
            <a class="item-1" href="index.php">Kezdőlap</a>
            <a class="item-2" href="contact.php">Kapcsolatok</a>   
        </nav>
    </header>
    <main>
    <h2>Szállítási és fizetési adatok</h2>
    <form method="POST" action="penztar.php">
        


        <label for="delivery-method">Szállítási mód:</label>
        <select id="delivery-method" name="delivery_method">
            <option value="standard">Standard szállítás (3-5 munkanap)</option>
            <option value="express">Expressz szállítás (1-2 munkanap)</option>
            <option value="pickup">Személyes átvétel</option>
        </select>

        <label for="payment-method">Fizetési mód:</label>
        <select id="payment-method" name="payment_method">
            <option value="card">Bankkártyás fizetés</option>
            <option value="paypal">Online fizetés (PayPal, Google Pay)</option>
            <option value="cod">Utánvétel</option>
        </select>

        <label for="billing-name">Számlázási név:</label>
        <input id="billing-name" type="text" name="billing_name">

        <label for="note">Megjegyzés:</label>
        <textarea id="note" name="note" rows="3"></textarea>

        <label for="coupon">Kuponkód:</label>
        <input id="coupon" type="text" name="coupon">

        <label>
            Elfogadom az ÁSZF-et és az adatkezelési tájékoztatót
            <input type="checkbox" name="terms" required>
        </label>
        <fieldset>
            <legend><strong>Szállítási adatok</strong></legend>

            <label for="email">Email:</label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>

            <label for="phone">Telefonszám:</label>
            <input id="phone" type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" readonly>

            <label for="address">Cím:</label>
            <input id="address" type="text" name="address" value="<?= htmlspecialchars($address) ?>" readonly>
        </fieldset>

        <p>Fizetendő: FEJLESZTÉS ALATT</p>

        <button type="submit" name="rendeles">Rendelés leadása</button>
    </form>
</main>

    
    <script src="script.js"></script>
</body>
</html>