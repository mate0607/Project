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
const contactForm = document.getElementById('contact-form');

// Eseményfigyelők
document.addEventListener('DOMContentLoaded', function() {
    initKapcsolatPage();
    
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
    
    // Kapcsolat form
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleContactSubmit();
        });
    }
});

// Kapcsolat oldal inicializálása
function initKapcsolatPage() {
    renderCategoriesMenu();
    updateCartCount();
}

// Kategóriák menü megjelenítése
function renderCategoriesMenu() {
    const categoriesMenu = document.getElementById('categories-menu');
    
    categories.forEach(category => {
        const menuItem = document.createElement('li');
        menuItem.innerHTML = `<a href="alkatreszek.html" data-category="${category.name}">${category.name}</a>`;
        categoriesMenu.appendChild(menuItem);
    });
}

// Kapcsolat form kezelése
function handleContactSubmit() {
    const formData = new FormData(contactForm);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        subject: formData.get('subject'),
        message: formData.get('message')
    };
    
    // Egyszerű validáció
    if (!data.name || !data.email || !data.subject || !data.message) {
        alert('Kérjük, töltsd ki az összes kötelező mezőt!');
        return;
    }
    
    // Email validáció
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        alert('Kérjük, érvényes e-mail címet adj meg!');
        return;
    }
    
    // Szimulált küldés
    setTimeout(() => {
        alert(`Köszönjük üzeneted, ${data.name}!\n\nHamarosan válaszolunk a(z) "${data.subject}" tárgyú üzenetedre.`);
        contactForm.reset();
    }, 1000);
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

// CSS bővítés a stílusokhoz
const additionalCSS = `
.page-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    color: white;
    border-radius: 8px;
}

.page-header h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.page-header p {
    font-size: 18px;
    opacity: 0.9;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 50px;
}

.contact-info-section {
    display: grid;
    gap: 20px;
}

.contact-card {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
    border: 1px solid #eee;
}

.contact-card:hover {
    transform: translateY(-3px);
}

.contact-icon {
    font-size: 36px;
    margin-bottom: 15px;
}

.contact-card h3 {
    color: var(--primary);
    margin-bottom: 15px;
    font-size: 20px;
}

.contact-card p {
    margin-bottom: 5px;
    color: var(--dark);
}

.contact-form-section {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.contact-form h2 {
    text-align: center;
    margin-bottom: 30px;
    color: var(--primary);
    font-size: 28px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--dark);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
    font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent);
}

.submit-btn {
    width: 100%;
    background: var(--accent);
    color: white;
    border: none;
    padding: 15px;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background: #2980b9;
}

.map-section {
    margin-bottom: 50px;
}

.map-section h2 {
    text-align: center;
    margin-bottom: 30px;
    color: var(--primary);
    font-size: 28px;
}

.map-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.map-placeholder {
    height: 400px;
    background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    position: relative;
}

.map-content {
    text-align: center;
    background: rgba(255,255,255,0.95);
    padding: 30px;
    border-radius: 8px;
    color: var(--dark);
    max-width: 400px;
}

.map-content h3 {
    color: var(--primary);
    margin-bottom: 15px;
    font-size: 24px;
}

.transport-info {
    margin: 20px 0;
    text-align: left;
}

.transport-option {
    margin-bottom: 8px;
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.parking-info {
    margin-top: 15px;
    padding: 10px;
    background: var(--light);
    border-radius: 4px;
    font-weight: 500;
}

.faq-section {
    margin-bottom: 50px;
}

.faq-section h2 {
    text-align: center;
    margin-bottom: 40px;
    color: var(--primary);
    font-size: 28px;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.faq-item {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid var(--accent);
}

.faq-item h3 {
    color: var(--primary);
    margin-bottom: 15px;
    font-size: 18px;
}

.faq-item p {
    color: var(--dark);
    line-height: 1.6;
}

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
    .contact-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
    
    .map-placeholder {
        height: 300px;
    }
    
    .map-content {
        margin: 20px;
        padding: 20px;
    }
    
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

@media (max-width: 480px) {
    .contact-form-section {
        padding: 20px;
    }
    
    .contact-card {
        padding: 20px;
    }
    
    .page-header h1 {
        font-size: 28px;
    }
    
    .page-header p {
        font-size: 16px;
    }
}
`;

// CSS hozzáadása
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);