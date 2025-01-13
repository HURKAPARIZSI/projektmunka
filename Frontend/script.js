function openModal(id) {
    document.getElementById(id).style.display = "flex";
}


function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

// Kosár adatok betöltése a localStorage-ból
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Termék hozzáadása a kosárhoz
function addToCart(productName, productPrice) {
    cart.push({ name: productName, price: productPrice });
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`${productName} sikeresen hozzáadva a kosárhoz!`);
}

// Kosár tartalmának betöltése és megjelenítése
function loadCart() {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = ""; // Töröljük az eddigi tartalmat

    cart.forEach((item, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.price} Ft</td>
            <td><button onclick="removeFromCart(${index})">Eltávolítás</button></td>
        `;
        tbody.appendChild(row);
    });
}

// Termék eltávolítása a kosárból
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
}

// Kosár oldal betöltéskor a kosár tartalmát megjelenítjük
if (document.querySelector("table")) {
    window.onload = loadCart;
}

function calculateTotal() {
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    document.getElementById("total").innerText = `Teljes összeg: ${total} Ft`;
}