function openModal(id) {
    document.getElementById(id).style.display = "flex";
}


function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

fetch('/backend/api/login.php')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('status');
                if (data.loggedIn) {
                    statusDiv.textContent = "Be vagy jelentkezve.";
                } else {
                    statusDiv.textContent = "Nem vagy bejelentkezve.";
                }
            })
            .catch(error => console.error('Hiba a bejelentkezés ellenőrzése során:', error));

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

// script.js
document.addEventListener("DOMContentLoaded", () => {
    const checkoutButton = document.getElementById("penztargomb");
    const tableBody = document.querySelector("table tbody");

    checkoutButton.addEventListener("click", (event) => {
        // Ellenőrizzük, hogy van-e legalább egy termék a kosárban
        const rows = tableBody.querySelectorAll("tr");
        const hasItems = Array.from(rows).some(row => row.querySelector("td").textContent.trim() !== "");

        if (!hasItems) {
            event.preventDefault(); // Megakadályozza a továbbirányítást
            alert("A kosár üres! Kérjük, adjon hozzá terméket a folytatáshoz.");
        }
    });
});
