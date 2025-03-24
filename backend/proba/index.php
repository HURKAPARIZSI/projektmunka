<?php
include_once '../includes/db.php';
include_once 'adatbazis.php';

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}

// Termékek betöltése
$products = $_SESSION['products'] ?? [];


// Kosárba helyezés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['products'] ?? '';

    $kivalasztott = null;
    foreach ($products as $elem) {
        if ($elem['name'] === $productName) { // Helyes összehasonlítás
            $kivalasztott = $elem;
            break;
        }
    }

    if ($kivalasztott) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $productName) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'name' => htmlspecialchars($productName),
                'price' => (float)$kivalasztott['price'],
                'image' => htmlspecialchars($kivalasztott['image_default']),
                'quantity' => 1,
            ];
        }
    }
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

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
        <h1>Webshop.kft</h1>  
<<<<<<< HEAD
        <!--<a href="logout.php">Kijelentkezés</a>  
        <a href="email.php">asdasd  </a> -->
=======
        
        <nav>
            <a class="item-1" href="fooldal.php">Főoldal</a>
            <a class="item-2" href="index.php">Termékek</a>
            <a class="item-3" href="contact.php">Kapcsolatok</a>
            <a class="icon" href="cart.php">
                <img src="img/th.jpg" alt="Kosár" title="Kosár">
                <?php if ($cartCount > 0): ?>
                    <span class="cart-count"><?= $cartCount ?></span>
                <?php endif; ?>
            </a> 
            <a class="icon" href="login.php"><img src="<?= htmlspecialchars($_SESSION['loggedinimg']) ?>" alt="Bejelentkezés" title="Bejelentkezés"></a> 
    </header>
    <main>
        <section class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                    <p>Ár: <?php echo htmlspecialchars($product['price']); ?> $</p>
                    <form method="POST">
                        <img src="<?php echo htmlspecialchars($product['image_default']) ?>" width="150px">
                        <input type="hidden" name="products" value="<?= htmlspecialchars($product['name']) ?>">
                        <button type="submit" name="add_to_cart">Kosárba</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
