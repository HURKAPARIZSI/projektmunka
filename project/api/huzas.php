<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="kartyahuzas.css"> 
</head>
<body>
    
    <div class="overlay">
    <header>
        <h1>Webshop.kft</h1>  

        <nav>
            <a class="item-1" href="fooldal.php">Vissza a főoldalra</a>
        </nav>
    </header>
    <main>
    <h1>🎴 Húzz egy kártyát! 🎴</h1>
    <button onclick="huzzKartya()">Húzás</button>
    <div id="kartyaContainer"></div>

    <script>
        async function huzzKartya() {
        try {
            const response = await fetch('https://deckofcardsapi.com/api/deck/new/draw/?count=1');
            if (!response.ok) {
            throw new Error('Hiba történt a kártya húzásakor.');
            }
            const data = await response.json();
            const kartya = data.cards[0];

            const kartyaContainer = document.getElementById('kartyaContainer');
            kartyaContainer.innerHTML = `
            <h2>${kartya.value} of ${kartya.suit}</h2>
            <img id="kartyaKep" src="${kartya.image}" alt="${kartya.value} of ${kartya.suit}">
            `;
        } catch (error) {
            console.error('Hiba:', error);
        }
    }
  </script>
  </div>
  </main>
</body>
</html>