<?php
include_once '../includes/db.php';
include_once 'adatbazis.php';

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedinimg'] = "img/pipa_icon.png";
} else {
    $_SESSION['loggedinimg'] = "img/5580993.png";
}


$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="styless.css"> 
</head>
<body>
    <header>
        <h1>Webshop.kft</h1>  

        <nav>
            <a class="item-1" href="fooldal.php">Főoldal</a>
            <a class="item-2" href="index.php">Termékek</a>
            <a class="item-3" href="contact.php">Kapcsolatok</a>
            <a class="item-4" href="huzas.php">Húzz egy lapot!</a>
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
    <div class="slideshow-container">
    <div class="slide fade">
        <img src="img/asd.jpg" alt="Kép 1">
        <a class="text-overlay" href="index.php">Termékeink</a>
    </div>
    <div class="slide fade">
        <img src="img/asd2.jpg" alt="Kép 2">
        <a class="text-overlay" href="contact.php">Kapcsolatok</a>
    </div>
    <div class="slide fade">
        <img src="img/asd3.jpg" alt="Kép 3">
        <a class="text-overlay" href="https://vegas.hu" target="_blank">Vegas.hu</a>
    </div>

    <!-- Előző és következő gombok -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>


    <script>
        let slideIndex = 0;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            let slides = document.querySelectorAll(".slide");

            if (n >= slides.length) { slideIndex = 0; }
            if (n < 0) { slideIndex = slides.length - 1; }

            slides.forEach(slide => slide.style.display = "none");
            slides[slideIndex].style.display = "block";
        }

        // Automatikus váltás (opcionális)
        setInterval(() => {
            plusSlides(1);
        }, 5000);
    </script>
    </main>
</body>
</html>
