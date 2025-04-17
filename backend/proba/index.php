<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../includes/db.php';
include_once 'adatbazis.php';

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
    $_SESSION['iconLink'] = "logout.php";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
    $_SESSION['iconLink'] = "login.php";
}

// Termékek betöltése
$products = $_SESSION['products'] ?? [];


// Kosárba helyezés
/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['products'] ?? '';

    $kivalasztott = null;
    foreach ($products as $elem) {
        if ($elem['name'] === $productName) { 
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
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])){
    $productName = $_POST['products'] ?? '';
    $quantity = 1;
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT product_id FROM products WHERE name = ?");
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
        } else {
            echo "A termék nem található.";
            exit;
        }


        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $cart_id = $row['cart_id'];
        } else {
            // Ha nincs kosár, létrehozunk egyet
            $stmt = $conn->prepare("INSERT INTO cart (user_id) VALUES (?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
        }

        $stmt = $conn->prepare("SELECT item_id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Már van ilyen termék, frissítjük a mennyiséget
            $new_quantity = $row['quantity'] + 1;
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE item_id = ?");
            $stmt->bind_param("ii", $new_quantity, $row['item_id']);
            $stmt->execute();
        } else {
            // Új terméket adunk a kosárhoz
            $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            $stmt->execute();
        }


    }else{
        echo"Nem vagy bejelentkezve";
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
            <a class="icon" href="<?php echo $_SESSION['iconLink']?>"><img src="<?php echo $_SESSION['loggedinimg']?>" alt="Bejelentkezés/Kijelentkezés" title="Bejelentkezés"></a> 
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
