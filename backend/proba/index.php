<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'] ?? null; // Bejelentkezett felhasználó ID-ja

    if (!$userId) {
        die("Hiba: Hibás felhasználó.");
    }
    if (!$productId) {
        die("Hiba: Hibás termék.");
    }

    // Ellenőrizzük, hogy a termék létezik-e
    $stmt = $conn->prepare("SELECT name, price FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        die("Hiba: A termék nem található.");
    }

    $conn->begin_transaction();
    try {
        // Megnézzük, hogy a felhasználónál már szerepel-e ez a termék a kosárban
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Ha már benne van a kosárban, növeljük a mennyiséget
            $newQuantity = $row['quantity'] + 1;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $newQuantity, $userId, $productId);
            $stmt->execute();

            // Frissítés a sessionben is
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            // Ha még nincs benne, beszúrjuk
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();

            // Hozzáadás a session kosárhoz
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }

        $stmt->close();
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Hiba az adatbázis frissítésekor: " . $e->getMessage());
    }
     
}
if(isset($_SESSION['cart'])){
    $cartCount = count($_SESSION['cart']);
}else{
    $cartCount = 0;
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
        <h1>Webshop.kft</h1>  
        <a href="logout.php">Kijelentkezés</a>  
        
        <a href="email.php">asdasd  </a>
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
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                        <button type="submit" name="add_to_cart">Kosárba</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
