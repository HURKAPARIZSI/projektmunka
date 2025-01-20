// Képek definiálása
const images = {
    blackjack: {
        ver1: 'img/kep_blackjack2.jpg',
        ver2: 'img/kep_blackjack3.jpg',
        ver3: 'img/kep_blackjack4.jpg',
    },
    roulett: {
        ver1: 'img/kep_roulett2.jpg',
        ver2: 'img/kep_roulett3.jpg',
        ver3: 'img/kep_roulett4.jpg',
    },
    slot: {
        ver1: 'img/kep_slot2.jpg',
        ver2: 'img/kep_slot3.jpg',
        ver3: 'img/kep_slot4.jpg',
    },
    kartya: {
        ver1: 'img/kep_kartya2.jpg',
        ver2: 'img/kep_kartya3.jpg',
        ver3: 'img/kep_kartya4.jpg',
    },
    poker: {
        ver1: 'img/kep_poker2.jpg',
        ver2: 'img/kep_poker3.jpg',
        ver3: 'img/kep_poker4.jpg',
    },
    szerencsekerek: {
        ver1: 'img/kep_szerencsekerek2.jpg',
        ver2: 'img/kep_szerencsekerek3.jpg',
        ver3: 'img/kep_szerencsekerek4.jpg',
    },
};

// Kosár inicializálása
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Kép frissítése
function updateImage(imageId, version, productId) {
    const imageElement = document.getElementById(imageId);

    if (!imageElement) {
        console.error(`Image element with id "${imageId}" not found.`);
        return;
    }

    const newSrc = images[productId][version];

    if (newSrc) {
        imageElement.src = newSrc;
    } else {
        console.error(`No image found for product "${productId}" with version "${version}".`);
    }
}

// Kosár elem osztály
class CartItem {
    constructor(name, price, quantity = 1) {
        this.name = name;
        this.price = price;
        this.quantity = quantity;
    }
}

// Termék hozzáadása a kosárhoz
function addToCart(productName, price) {
    const existingItem = cart.find(item => item.name === productName);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        const newItem = new CartItem(productName, price);
        cart.push(newItem);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartView();
}

// Kosár nézet frissítése
function updateCartView() {
    const tableBody = document.querySelector("table tbody");
    tableBody.innerHTML = "";

    if (cart.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="3">A kosár üres.</td></tr>`;
        return;
    }

    cart.forEach((item, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.price} Ft (x${item.quantity})</td>
            <td>
                <button onclick="incrementQuantity(${index})">+</button>
                <button onclick="decrementQuantity(${index})">-</button>
                <button onclick="removeFromCart(${index})">Eltávolítás</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Mennyiség növelése
function incrementQuantity(index) {
    cart[index].quantity++;
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartView();
}

// Mennyiség csökkentése
function decrementQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
    } else {
        removeFromCart(index);
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartView();
}

// Termék eltávolítása a kosárból
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    update 
    CartView();
}

// Oldal betöltésekor frissítés
document.addEventListener("DOMContentLoaded", () => {
    updateCartView();
});
