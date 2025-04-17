<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}

// Ha az eltávolítás gombot megnyomták
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];

    // Ellenőrizzük, hogy a termék létezik a kosárban
    foreach($cartProducts as $key){
        if($key['product_id'] = $productId){
            $database->deleteCartItem($_SESSION['cart']['cart_id'],$product_id);
        }
    }

    // Újrarendezni a kosarat, hogy az eltávolított elem ne hagyjon üres helyet
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
/*echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';
echo '<pre>';
print_r($_SESSION['products']);
echo '</pre>';*/

// Kosár darabszámának meghatározása
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$cartProducts = [];

if (isset($_SESSION['cart']) && isset($_SESSION['products'])) {
    foreach ($_SESSION['cart'] as $cartItem) {
        foreach ($_SESSION['products'] as $product) {
            if ($cartItem['product_id'] == $product['product_id']) {
                $cartProducts[] = [
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $product['price']
                ];
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rendeles'])){
    
}





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
                <?php
                // Ellenőrizzük, hogy van-e kosár a session-ben
                if (isset($cartProducts) && !empty($cartProducts)) {
                    foreach ($cartProducts as $item) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($item['price']) . ' Ft</td>';
                        echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                        echo '<td><a href="cart.php?remove=' . htmlspecialchars($item['name']) . '"><button>Eltávolítás</button></a></td>';
                        echo '</tr>';
                        
                    }
                } else {
                    echo '<tr><td colspan="3">A kosár üres.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <form method="post">
            <a href="penztar.php">Rendeles</a>
        </form>
    </main>
</body>
</html>