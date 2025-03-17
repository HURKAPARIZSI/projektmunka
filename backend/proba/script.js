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
        ver1: 'img/kep_szerencskerek2.jpg',
        ver2: 'img/kep_szerencskerek3.jpg',
        ver3: 'img/kep_szerencskerek4.jpg',
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


let slideIndex = 0;

function showSlides() {
    let slides = document.querySelectorAll(".slide");
    slides.forEach(slide => slide.style.display = "none"); 

    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1; }

    slides[slideIndex - 1].style.display = "block";
    setTimeout(showSlides, 3000); // 3 másodpercenként vált
}

// Indítás
showSlides();
