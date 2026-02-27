// Kosár kezelése
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// DOM elemek
const cartBtn = document.getElementById('cart-btn');
const cartModal = document.getElementById('cart-modal');
const closeModal = document.querySelector('.close');
const cartItems = document.getElementById('cart-items');
const cartCount = document.getElementById('cart-count');
const cartTotalPrice = document.getElementById('cart-total-price');
const checkoutBtn = document.getElementById('checkout-btn');

// Eseményfigyelők
document.addEventListener('DOMContentLoaded', function() {
    initApp();
    
    // Kosár gomb
    cartBtn.addEventListener('click', function() {
        cartModal.style.display = 'block';
        updateCartDisplay();
    });
    
    // Modális bezárása
    closeModal.addEventListener('click', function() {
        cartModal.style.display = 'none';
    });
    
    // Modális bezárása kívülre kattintva
    window.addEventListener('click', function(event) {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    });
    
    // Fizetés gomb
    checkoutBtn.addEventListener('click', function() {
        if (cart.length === 0) {
            alert('A kosár üres!');
            return;
        }
        alert('Köszönjük a vásárlást! Összesen: ' + calculateTotal() + ' Ft');
        cart = [];
        saveCart();
        updateCartCount();
        cartModal.style.display = 'none';
    });
});

// Eseményfigyelők
document.addEventListener('DOMContentLoaded', function() {
    initApp();
    
    // Kosár gomb
    cartBtn.addEventListener('click', function() {
        cartModal.style.display = 'block';
        updateCartDisplay();
    });
    
    // Modális bezárása
    closeModal.addEventListener('click', function() {
        cartModal.style.display = 'none';
    });
    
    // Modális bezárása kívülre kattintva
    window.addEventListener('click', function(event) {
        if (event.target === cartModal) {
            cartModal.style.display = 'none';
        }
    });
    
    // Fizetés gomb
    checkoutBtn.addEventListener('click', function() {
        // Bejelentkezés ellenőrzése
        if (!window.authSystem || !window.authSystem.currentUser) {
            alert('A vásárláshoz be kell jelentkezned!');
            window.authSystem.showLoginModal();
            return;
        }
        
        if (cart.length === 0) {
            alert('A kosár üres!');
            return;
        }
        
        alert('Köszönjük a vásárlást! Összesen: ' + calculateTotal() + ' Ft');
        cart = [];
        saveCart();
        updateCartCount();
        cartModal.style.display = 'none';
    });
});

// Alkalmazás inicializálása
function initApp() {
    renderCategories();
    renderSpecialDeals();
    updateCartCount();
}

// Alkalmazás inicializálása
function initApp() {
    renderCategories();
    renderSpecialDeals();
    updateCartCount();
}

// Kategóriák megjelenítése
function renderCategories() {
    const categoriesGrid = document.getElementById('categories-grid');
    const categoriesMenu = document.getElementById('categories-menu');
    
    categories.forEach(category => {
        // Főoldali kategória kártyák
        const categoryCard = document.createElement('div');
        categoryCard.className = 'category-card';
        categoryCard.innerHTML = `
            <div class="category-image">${category.icon}</div>
            <div class="category-info">
                <h3>${category.name}</h3>
                <p class="category-count">${category.count} termék</p>
            </div>
        `;
        categoryCard.addEventListener('click', function() {
            window.location.href = `alkatreszek.html?category=${encodeURIComponent(category.name)}`;
        });
        categoriesGrid.appendChild(categoryCard);
        
        // Navigációs menü
        const menuItem = document.createElement('li');
        menuItem.innerHTML = `<a href="alkatreszek.html" data-category="${category.name}">${category.name}</a>`;
        categoriesMenu.appendChild(menuItem);
    });
}

// Speciális akciók megjelenítése
function renderSpecialDeals() {
    const specialDeals = document.getElementById('special-deals');
    
    products.specialDeals.forEach(product => {
        const productCard = createProductCard(product);
        specialDeals.appendChild(productCard);
    });
}

// Termékkártya létrehozása
function createProductCard(product) {
    const productCard = document.createElement('div');
    productCard.className = 'product-card';
    
    productCard.innerHTML = `
        <div class="product-image">${product.icon}</div>
        <div class="product-info">
            <div class="product-name">${product.name}</div>
            <div class="product-price">
                ${product.originalPrice ? `<span class="original-price">${product.originalPrice} Ft</span>` : ''}
                ${product.price} Ft
            </div>
            <button class="add-to-cart" data-id="${product.id}">Kosárba</button>
        </div>
    `;
    
    // Kosárba helyezés esemény
    const addToCartBtn = productCard.querySelector('.add-to-cart');
    addToCartBtn.addEventListener('click', function() {
        addToCart(product);
    });
    
    return productCard;
}

// Termék hozzáadása a kosárhoz
function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1
        });
    }
    
    saveCart();
    updateCartCount();
    
    // Értesítés
    showNotification(`${product.name} hozzáadva a kosárhoz!`);
}

// Kosár mentése localStorage-ba
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Kosár szám frissítése
function updateCartCount() {
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = totalItems;
}

// Kosár megjelenítésének frissítése
function updateCartDisplay() {
    cartItems.innerHTML = '';
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p>A kosár üres</p>';
        cartTotalPrice.textContent = '0';
        return;
    }
    
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${item.price} Ft/db</div>
            </div>
            <div class="cart-item-controls">
                <button class="quantity-btn minus" data-id="${item.id}">-</button>
                <span class="cart-item-quantity">${item.quantity} db</span>
                <button class="quantity-btn plus" data-id="${item.id}">+</button>
                <button class="remove-btn" data-id="${item.id}">🗑️</button>
            </div>
            <div class="cart-item-total">${item.quantity * item.price} Ft</div>
        `;
        cartItems.appendChild(cartItem);
    });
    
    // Mennyiség gombok eseményei
    document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            updateCartItemQuantity(id, -1);
        });
    });
    
    document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            updateCartItemQuantity(id, 1);
        });
    });
    
    // Törlés gombok eseményei
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            removeFromCart(id);
        });
    });
    
    cartTotalPrice.textContent = calculateTotal();
}

// Kosár tétel mennyiségének frissítése
function updateCartItemQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    
    if (item) {
        item.quantity += change;
        
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            saveCart();
            updateCartCount();
            updateCartDisplay();
        }
    }
}

// Termék eltávolítása a kosárból
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartCount();
    updateCartDisplay();
}

// Összesített ár számítása
function calculateTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Értesítés megjelenítése
function showNotification(message) {
    // Értesítés elem létrehozása
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    
    // Stílus beállítása
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: var(--success);
        color: white;
        padding: 15px 20px;
        border-radius: 4px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 1000;
        transition: transform 0.3s, opacity 0.3s;
        transform: translateX(100%);
        opacity: 0;
    `;
    
    document.body.appendChild(notification);
    
    // Animáció
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
    // Eltűnés
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// CSS bővítés a stílusokhoz
const additionalCSS = `
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.cart-item-info {
    flex: 1;
}

.cart-item-name {
    font-weight: bold;
    margin-bottom: 5px;
}

.cart-item-price {
    color: var(--gray);
    font-size: 14px;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-btn {
    background-color: var(--accent);
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    margin-left: 10px;
}

.cart-item-total {
    font-weight: bold;
    min-width: 100px;
    text-align: right;
}

.cart-total {
    margin-top: 20px;
    text-align: right;
    font-weight: bold;
    font-size: 18px;
    padding-top: 20px;
    border-top: 2px solid var(--primary);
}

#checkout-btn {
    background-color: var(--success);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 16px;
    transition: background-color 0.3s;
}

#checkout-btn:hover {
    background-color: #27ae60;
}

@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .cart-item-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .cart-item-total {
        text-align: left;
        width: 100%;
    }
}
`;

// CSS hozzáadása
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);

function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartEmpty = document.getElementById('cart-empty');
    const checkoutBtn = document.getElementById('checkout-btn');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart.length === 0) {
        cartItems.innerHTML = '';
        cartEmpty.style.display = 'block';
        checkoutBtn.disabled = true;
        return;
    }
    
    cartEmpty.style.display = 'none';
    checkoutBtn.disabled = false;
    
    let itemsHTML = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        itemsHTML += `
            <div class="cart-item">
                <div class="cart-item-image">
                    ${item.icon || '⚙️'}
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-category">${item.category || 'Alkatrész'}</div>
                    <div class="cart-item-price">${item.price.toLocaleString()} Ft/db</div>
                </div>
                <div class="cart-item-controls">
                    <button class="quantity-btn minus" data-id="${item.id}">-</button>
                    <span class="cart-item-quantity">${item.quantity}</span>
                    <button class="quantity-btn plus" data-id="${item.id}">+</button>
                </div>
                <div class="cart-item-total">${itemTotal.toLocaleString()} Ft</div>
                <button class="remove-btn" data-id="${item.id}" title="Eltávolítás">
                    🗑️
                </button>
            </div>
        `;
    });
    
    cartItems.innerHTML = itemsHTML;
    
    // Összesítők frissítése
    document.getElementById('cart-subtotal').textContent = subtotal.toLocaleString() + ' Ft';
    document.getElementById('cart-total-price').textContent = subtotal.toLocaleString() + ' Ft';
    document.getElementById('cart-total-amount').textContent = subtotal.toLocaleString() + ' Ft';
    
    // Eseménykezelők újrakötése
    bindCartEvents();
}

function bindCartEvents() {
    // Mennyiség gombok
    document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            updateCartItemQuantity(id, -1);
        });
    });
    
    document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            updateCartItemQuantity(id, 1);
        });
    });
    
    // Törlés gombok
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.getAttribute('data-id'));
            removeFromCart(id);
        });
    });
    
    // Folytatás gomb
    document.querySelector('.continue-shopping').addEventListener('click', function() {
        hideCartModal();
    });
}