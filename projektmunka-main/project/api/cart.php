<?php
include_once("adatbazis.php");

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
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
//print_r($_SESSION['cart']);
if (isset($_GET['remove'])) {
    if(isset($cartProducts) && !empty($cartProducts)){
    $productId = $_GET['remove'];
    $cart_id = $_SESSION['cart'][0]['cart_id'];

    // Ellenőrizzük, hogy a termék létezik a kosárban
    foreach ($cartProducts as $key => $value) {
        if ($value['product_id'] == $productId) {
            $database->deleteCartItem($cart_id, $productId);
            // Töröljük a terméket a kosárból
            unset($cartProducts[$key]);
            break;  // Kilépünk a ciklusból, mert megtaláltuk a törlendő elemet
        }
    }

    // Újrarendezni a kosarat, hogy az eltávolított elem ne hagyjon üres helyet
    $_SESSION['cart'] = array_values($cartProducts);
}
}
//print_r($cartProducts);




$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

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
            <a class="item-4" href="huzas.php">Húzz egy lapot!!!</a>
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
                        echo '<td><a href="cart.php?remove=' . htmlspecialchars($item['product_id']) . '"><button>Eltávolítás</button></a></td>';
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