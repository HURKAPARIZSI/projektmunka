<?php
session_start();
require("../includes/db.php");
//unset($_SESSION['cart']);
    
// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}

// Termékek lekérdezése
$query = "SELECT product_id, name, price, stock, image_default, image_ver1, image_ver2, image_ver3 FROM products";
$result = $conn->query($query);

if (!$result) {
    die("SQL hiba: " . $conn->error);
}

// Termékek listájának tárolása
$products = [];
while ($row = $result->fetch_assoc()) {
    // Képek hozzáadása a termékhez
    $row['images'] = [
        'default' => $row['image_default'],
        'ver1' => $row['image_ver1'],
        'ver2' => $row['image_ver2'],
        'ver3' => $row['image_ver3']
    ];
    $products[] = $row;
}

// Kosárkezelés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // POST változók inicializálása
    $productName = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? 0;
    $selectedImage = $_POST['selected_image'] ?? ''; // Kiválasztott kép

    // Ellenőrzés, hogy van-e kiválasztott kép
    if (empty($selectedImage)) {
        echo "<p>Hiba: Nem választottál ki képet a termékhez!</p>";
    } else {
        // Kosár inicializálása, ha még nem létezik
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kosár frissítése vagy új elem hozzáadása
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if (isset($item['name']) && isset($item['image']) && $item['name'] === $productName && $item['image'] === $selectedImage) {
                $item['quantity']++;
                $found = true;
                break;
            }


            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'name' => htmlspecialchars($productName),
                'price' => (float)$productPrice,
                'image' => htmlspecialchars($selectedImage),
                'quantity' => 1,
            ];
        }
    }

$conn->close();
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
        <a href="logout.php">Kijelentkezés</a>  
        <nav>
            <a class="item-1" href="index.php">Kezdőlap</a>
            <a class="item-2" href="contact.php">Kapcsolatok</a>
            <a class="icon item-3" href="cart.php"><img src="img/th.jpg" alt="Kosár" title="Kosár"></a>
            
            <a class="icon" href="login.php"><img src="<?= htmlspecialchars($_SESSION['loggedinimg']) ?>" alt="Bejelentkezés" title="Bejelentkezés"></a> 
    </header>
    <main>
        
        <section class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                    <p>Ár: <?php echo htmlspecialchars($product['price']); ?> Ft</p>

                    <form method="POST">
                        <label for="image-select-<?php echo $product['product_id']; ?>">Képek:</label>
                        <select id="image-select-<?php echo $product['product_id']; ?>" name="selected_image">
                            <?php 
                            // Képek legördülő menüje
                            foreach ($product['images'] as $version => $imagePath): ?>
                                <option value="<?php echo htmlspecialchars($imagePath); ?>">
                                    <?php echo "Verzió " . htmlspecialchars($version); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <button type="submit" name="add_to_cart">Kosárba</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
