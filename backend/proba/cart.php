<?php
session_start();
include_once '../includes/db.php'; // Adatbázis kapcsolat betöltése

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}

$userId = $_SESSION['user_id'] ?? null; // Bejelentkezett felhasználó ID-ja

// Kosár lekérése az adatbázisból, ha van bejelentkezett felhasználó
$cartItems = [];
if ($userId) {
    $stmt = $conn->prepare("
        SELECT c.product_id, p.name, p.price, c.quantity 
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
    $stmt->close();
}

// Ha az eltávolítás gombot megnyomták
if (isset($_GET['remove']) && $userId) {
    $productId = $_GET['remove'];

    // Először eltávolítjuk a terméket a session kosarából
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            unset($_SESSION['cart'][$key]); // Eltávolítjuk a terméket a session kosarából
            break;
        }
    }

    // Újrarendezzük a session kosarát, hogy ne legyenek üres helyek
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    // Eltávolítás az adatbázisból
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $stmt->close();

    // Frissítés a PHP oldalon is
    header("Location: cart.php");
    exit;
}

// Kosár darabszámának meghatározása
$cartCount = count($cartItems);
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
    <a href="logout.php">Kijelentkezés</a>  
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
    </nav>
</header>
<main>
    <h1>Kosár</h1>
    <table>
        <thead>
            <tr>
                <th>Termék</th>
                <th>Ár</th>
                <th>Darab</th>
                <th>Művelet</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?> Ft</td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><a href="cart.php?remove=<?= htmlspecialchars($item['product_id']) ?>"><button>Eltávolítás</button></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">A kosár üres.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a id="penztargomb" href="penztar.php">Pénztár</a>
</main>
</body>
</html>
