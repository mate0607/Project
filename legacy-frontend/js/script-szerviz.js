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
const appointmentForm = document.getElementById('appointment-form');

// Eseményfigyelők
document.addEventListener('DOMContentLoaded', function() {
    initSzervizPage();
    
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
    
    // Időpontfoglalás form
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAppointmentSubmit();
        });
    }
    
    // Csomag megrendelés gombok
    document.querySelectorAll('.package-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const packageName = this.closest('.package-card').querySelector('h3').textContent;
            const packagePrice = this.closest('.package-card').querySelector('.package-price').textContent;
            handlePackageOrder(packageName, packagePrice);
        });
    });
});

// Szerviz oldal inicializálása
function initSzervizPage() {
    renderCategoriesMenu();
    updateCartCount();
    
    // Dátum mező minimum értékének beállítása
    const dateInput = document.getElementById('date');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
    }
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

// Időpontfoglalás kezelése
function handleAppointmentSubmit() {
    const formData = new FormData(appointmentForm);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        carType: formData.get('car-type'),
        serviceType: formData.get('service-type'),
        date: formData.get('date'),
        message: formData.get('message')
    };
    
    // Egyszerű validáció
    if (!data.name || !data.email || !data.phone || !data.carType || !data.serviceType || !data.date) {
        alert('Kérjük, töltsd ki az összes kötelező mezőt!');
        return;
    }
    
    // Szimulált mentés
    setTimeout(() => {
        alert(`Köszönjük az időpontfoglalást, ${data.name}!\n\nHamarosan felvesszük veled a kapcsolatot a ${data.date} dátummal és a ${getServiceTypeName(data.serviceType)} szolgáltatással kapcsolatban.`);
        appointmentForm.reset();
    }, 1000);
}

// Szolgáltatás típus nevének lekérése
function getServiceTypeName(type) {
    const types = {
        'basic': 'Alap szerviz',
        'full': 'Nagy szerviz',
        'diagnostic': 'Diagnosztika',
        'repair': 'Javítás',
        'other': 'Egyéb szolgáltatás'
    };
    return types[type] || type;
}

// Csomag megrendelés kezelése
function handlePackageOrder(packageName, packagePrice) {
    const confirmation = confirm(`Biztosan meg szeretnéd rendelni a ${packageName} csomagot ára: ${packagePrice}?`);
    
    if (confirmation) {
        // Kosárhoz adás
        const packageProduct = {
            id: Date.now(), // Egyedi ID
            name: `Szerviz csomag: ${packageName}`,
            price: parseInt(packagePrice.replace(/\D/g, '')),
            quantity: 1
        };
        
        addToCart(packageProduct);
    }
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
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    
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
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
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

.services-overview {
    margin-bottom: 50px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.service-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
    border: 1px solid #eee;
}

.service-card:hover {
    transform: translateY(-5px);
}

.service-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.service-card h3 {
    color: var(--primary);
    margin-bottom: 15px;
    font-size: 24px;
}

.service-card p {
    color: var(--gray);
    margin-bottom: 20px;
    font-size: 16px;
}

.service-card ul {
    text-align: left;
    margin-bottom: 25px;
    padding-left: 20px;
}

.service-card li {
    margin-bottom: 8px;
    color: var(--dark);
}

.service-price {
    font-size: 20px;
    font-weight: bold;
    color: var(--secondary);
    background: var(--light);
    padding: 10px 20px;
    border-radius: 4px;
    display: inline-block;
}

.appointment-section {
    margin-bottom: 50px;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.appointment-form h2 {
    text-align: center;
    margin-bottom: 30px;
    color: var(--primary);
    font-size: 28px;
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
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
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

.service-packages {
    margin-bottom: 50px;
}

.service-packages h2 {
    text-align: center;
    margin-bottom: 40px;
    color: var(--primary);
    font-size: 28px;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.package-card {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    position: relative;
    transition: transform 0.3s;
    border: 2px solid transparent;
}

.package-card:hover {
    transform: translateY(-5px);
}

.package-card.recommended {
    border-color: var(--accent);
    transform: scale(1.05);
}

.recommended-badge {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent);
    color: white;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.package-card h3 {
    color: var(--primary);
    margin-bottom: 15px;
    font-size: 22px;
}

.package-price {
    font-size: 24px;
    font-weight: bold;
    color: var(--secondary);
    margin-bottom: 20px;
}

.package-card ul {
    text-align: left;
    margin-bottom: 25px;
    padding-left: 20px;
}

.package-card li {
    margin-bottom: 10px;
    color: var(--dark);
}

.package-btn {
    width: 100%;
    background: var(--success);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.package-btn:hover {
    background: #27ae60;
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
    .services-grid,
    .packages-grid {
        grid-template-columns: 1fr;
    }
    
    .package-card.recommended {
        transform: none;
    }
    
    .appointment-section {
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
    .service-card,
    .package-card {
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