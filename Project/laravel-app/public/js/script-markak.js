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
const brandsGrid = document.getElementById('brands-grid');
const brandProducts = document.getElementById('brand-products');
const filterBtns = document.querySelectorAll('.filter-btn');

// Eseményfigyelők
document.addEventListener('DOMContentLoaded', function() {
    initMarkakPage();
    
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
    
    // Szűrő gombok
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Aktív gomb beállítása
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Szűrés alkalmazása
            const brand = this.getAttribute('data-brand');
            filterByBrand(brand);
        });
    });
});

// Márkák oldal inicializálása
function initMarkakPage() {
    renderCategoriesMenu();
    renderBrands();
    renderAllBrandProducts();
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

// Márkák megjelenítése
function renderBrands() {
    brands.forEach(brand => {
        const brandCard = document.createElement('div');
        brandCard.className = 'brand-card';
        brandCard.innerHTML = `
            <div class="brand-logo-large">${brand}</div>
            <div class="brand-info">
                <h3>${brand}</h3>
                <p>Minőségi alkatrészek</p>
                <button class="view-brand-btn" data-brand="${brand.toLowerCase()}">Termékek megtekintése</button>
            </div>
        `;
        
        // Márka gomb esemény
        const viewBtn = brandCard.querySelector('.view-brand-btn');
        viewBtn.addEventListener('click', function() {
            const brandName = this.getAttribute('data-brand');
            filterByBrand(brandName);
            
            // Aktív szűrő gomb beállítása
            filterBtns.forEach(b => b.classList.remove('active'));
            const correspondingBtn = document.querySelector(`.filter-btn[data-brand="${brandName}"]`);
            if (correspondingBtn) {
                correspondingBtn.classList.add('active');
            }
        });
        
        brandsGrid.appendChild(brandCard);
    });
}

// Összes márka termék megjelenítése
function renderAllBrandProducts() {
    brandProducts.innerHTML = '';
    const allProducts = getAllProducts();
    
    allProducts.forEach(product => {
        const productCard = createProductCard(product);
        brandProducts.appendChild(productCard);
    });
}

// Szűrés márka szerint
function filterByBrand(brand) {
    const allProducts = getAllProducts();
    let filteredProducts = [];
    
    if (brand === 'all') {
        filteredProducts = allProducts;
    } else {
        // Márka alapján szűrés (egyszerűsítve)
        filteredProducts = allProducts.filter(product => 
            product.name.toLowerCase().includes(brand.toLowerCase())
        );
    }
    
    // Termékek megjelenítése
    brandProducts.innerHTML = '';
    if (filteredProducts.length === 0) {
        brandProducts.innerHTML = `
            <div class="no-results">
                <h3>Nincs találat a kiválasztott márkához</h3>
                <p>Próbálj meg másik márkát választani</p>
            </div>
        `;
        return;
    }
    
    filteredProducts.forEach(product => {
        const productCard = createProductCard(product);
        brandProducts.appendChild(productCard);
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

// Összes termék lekérése
function getAllProducts() {
    let allProducts = [];
    
    for (const category in products) {
        allProducts = allProducts.concat(products[category]);
    }
    
    return allProducts;
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

.brands-filter {
    margin-bottom: 30px;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.filter-btn {
    padding: 10px 20px;
    border: 2px solid var(--accent);
    background-color: white;
    color: var(--accent);
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 500;
}

.filter-btn:hover,
.filter-btn.active {
    background-color: var(--accent);
    color: white;
}

.brands-showcase {
    margin-bottom: 50px;
}

.brands-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.brand-card {
    background: white;
    border-radius: 8px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid #eee;
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.brand-logo-large {
    font-size: 24px;
    font-weight: bold;
    color: var(--primary);
    margin-bottom: 15px;
    padding: 15px;
    background: var(--light);
    border-radius: 8px;
}

.brand-info h3 {
    color: var(--primary);
    margin-bottom: 10px;
}

.brand-info p {
    color: var(--gray);
    margin-bottom: 15px;
}

.view-brand-btn {
    background: var(--accent);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.view-brand-btn:hover {
    background: #2980b9;
}

.brand-products {
    margin-bottom: 50px;
}

.brand-products h2 {
    text-align: center;
    margin-bottom: 30px;
    color: var(--primary);
    font-size: 28px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: var(--gray);
}

.no-results h3 {
    margin-bottom: 10px;
    color: var(--primary);
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
    .brands-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-options {
        flex-direction: column;
        align-items: center;
    }
    
    .filter-btn {
        width: 200px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
    .products-grid {
        grid-template-columns: 1fr;
    }
}
`;

// CSS hozzáadása
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);