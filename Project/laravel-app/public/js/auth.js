// auth.js - Teljesen újraírt, működő bejelentkezési rendszer

class AuthSystem {
    constructor() {
        this.currentUser = null;
        this.isInitialized = false;
    }

    async init() {
        if (this.isInitialized) return;
        
        try {
            await this.loadUser();
            this.bindEvents();
            this.updateUI();
            this.isInitialized = true;
            console.log('Auth system initialized');
        } catch (error) {
            console.error('Auth initialization failed:', error);
        }
    }

    // Eseménykezelők
    bindEvents() {
        // Bejelentkezési gomb
        const loginBtn = document.getElementById('login-btn');
        if (loginBtn) {
            loginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('Login button clicked');
                
                if (this.currentUser) {
                    this.toggleUserMenu();
                } else {
                    this.showLoginModal();
                }
            });
        } else {
            console.error('Login button not found!');
        }

        // Modal bezárások
        const closeLogin = document.getElementById('close-login');
        const closeForgot = document.getElementById('close-forgot');
        
        if (closeLogin) closeLogin.addEventListener('click', () => this.hideLoginModal());
        if (closeForgot) closeForgot.addEventListener('click', () => this.hideForgotPasswordModal());

        // Tab váltás
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchTab(e.target);
            });
        });

        // Űrlapok
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const forgotForm = document.getElementById('forgot-password-form');
        
        if (loginForm) loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        if (registerForm) registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        if (forgotForm) forgotForm.addEventListener('submit', (e) => this.handleForgotPassword(e));

        // Elfelejtett jelszó link
        const forgotLink = document.querySelector('.forgot-password');
        if (forgotLink) {
            forgotLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.showForgotPasswordModal();
            });
        }

        // Vissza a bejelentkezéshez
        const backToLogin = document.querySelector('.back-to-login');
        if (backToLogin) {
            backToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                this.hideForgotPasswordModal();
                this.showLoginModal();
            });
        }

        // Kijelentkezés
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        }

        // Kívülre kattintás
        document.addEventListener('click', (e) => this.handleOutsideClick(e));
        
        // ESC billentyű
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideModals();
            }
        });

        console.log('All events bound successfully');
    }

    // Modal kezelés
    showLoginModal() {
        console.log('Showing login modal');
        const modal = document.getElementById('login-modal');
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add('active');
            this.resetForms();
            this.switchToTab('login');
            
            // Fókusz az első inputra
            setTimeout(() => {
                const emailInput = document.getElementById('login-email');
                if (emailInput) emailInput.focus();
            }, 100);
        }
    }

    hideLoginModal() {
        const modal = document.getElementById('login-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('active');
        }
    }

    showForgotPasswordModal() {
        console.log('Showing forgot password modal');
        const modal = document.getElementById('forgot-password-modal');
        if (modal) {
            this.hideLoginModal();
            modal.style.display = 'block';
            modal.classList.add('active');
            this.resetForms();
            
            // Fókusz az inputra
            setTimeout(() => {
                const emailInput = document.getElementById('forgot-email');
                if (emailInput) emailInput.focus();
            }, 100);
        }
    }

    hideForgotPasswordModal() {
        const modal = document.getElementById('forgot-password-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('active');
        }
    }

    hideModals() {
        this.hideLoginModal();
        this.hideForgotPasswordModal();
        this.hideUserMenu();
    }

    // Tab váltás
    switchTab(clickedTab) {
        const tabs = document.querySelectorAll('.auth-tab');
        const forms = document.querySelectorAll('.auth-form');
        const targetTab = clickedTab.getAttribute('data-tab');

        tabs.forEach(tab => tab.classList.remove('active'));
        forms.forEach(form => form.classList.remove('active'));

        clickedTab.classList.add('active');
        
        const targetForm = document.getElementById(`${targetTab}-form`);
        if (targetForm) {
            targetForm.classList.add('active');
        }
    }

    switchToTab(tabName) {
        const tab = document.querySelector(`.auth-tab[data-tab="${tabName}"]`);
        if (tab) this.switchTab(tab);
    }

    // Űrlap kezelés
    async handleLogin(e) {
        e.preventDefault();
        console.log('Login form submitted');
        
        const formData = new FormData(e.target);
        const data = {
            email: formData.get('email'),
            password: formData.get('password'),
            remember: document.getElementById('remember-me')?.checked || false
        };

        if (this.validateLogin(data)) {
            await this.performLogin(data);
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        console.log('Register form submitted');
        
        const formData = new FormData(e.target);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword'),
            acceptTerms: document.getElementById('accept-terms')?.checked || false
        };

        if (this.validateRegister(data)) {
            await this.performRegister(data);
        }
    }

    async handleForgotPassword(e) {
        e.preventDefault();
        console.log('Forgot password form submitted');
        
        const formData = new FormData(e.target);
        const email = formData.get('email');

        if (this.validateEmail(email)) {
            await this.performPasswordReset(email);
        }
    }

    // Validáció
    validateLogin(data) {
        let isValid = true;
        this.clearErrors('login');

        if (!data.email || data.email.trim() === '') {
            this.showError('login-email', 'Kérjük add meg az e-mail címed');
            isValid = false;
        } else if (!this.validateEmailFormat(data.email)) {
            this.showError('login-email', 'Érvényes e-mail címet adj meg');
            isValid = false;
        }

        if (!data.password || data.password.trim() === '') {
            this.showError('login-password', 'Kérjük add meg a jelszavad');
            isValid = false;
        } else if (data.password.length < 4) {
            this.showError('login-password', 'A jelszó túl rövid');
            isValid = false;
        }

        return isValid;
    }

    validateRegister(data) {
        let isValid = true;
        this.clearErrors('register');

        if (!data.name || data.name.trim() === '') {
            this.showError('register-name', 'Kérjük add meg a neved');
            isValid = false;
        } else if (data.name.length < 2) {
            this.showError('register-name', 'A név túl rövid');
            isValid = false;
        }

        if (!data.email || data.email.trim() === '') {
            this.showError('register-email', 'Kérjük add meg az e-mail címed');
            isValid = false;
        } else if (!this.validateEmailFormat(data.email)) {
            this.showError('register-email', 'Érvényes e-mail címet adj meg');
            isValid = false;
        }

        if (!data.password || data.password.trim() === '') {
            this.showError('register-password', 'Kérjük add meg a jelszavad');
            isValid = false;
        } else if (data.password.length < 6) {
            this.showError('register-password', 'A jelszónak legalább 6 karakter hosszúnak kell lennie');
            isValid = false;
        }

        if (!data.confirmPassword || data.confirmPassword.trim() === '') {
            this.showError('register-confirm-password', 'Kérjük erősítsd meg a jelszavad');
            isValid = false;
        } else if (data.password !== data.confirmPassword) {
            this.showError('register-confirm-password', 'A jelszavak nem egyeznek');
            isValid = false;
        }

        if (!data.acceptTerms) {
            this.showError('terms', 'El kell fogadnod a felhasználási feltételeket');
            isValid = false;
        }

        return isValid;
    }

    validateEmail(email) {
        this.clearErrors('forgot');
        
        if (!email || email.trim() === '') {
            this.showError('forgot-email', 'Kérjük add meg az e-mail címed');
            return false;
        }
        
        if (!this.validateEmailFormat(email)) {
            this.showError('forgot-email', 'Érvényes e-mail címet adj meg');
            return false;
        }

        return true;
    }

    validateEmailFormat(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // API hívások (szimulált)
    async performLogin(data) {
        const btn = document.querySelector('#login-form button[type="submit"]');
        this.setLoading(btn, true);

        try {
            // Szimulált API hívás
            await new Promise(resolve => setTimeout(resolve, 1000 + Math.random() * 1000));

            // Demo felhasználók
            const demoUsers = [
                { email: 'demo@example.com', password: 'demo123', name: 'Demo Felhasználó', avatar: 'D' },
                { email: 'test@example.com', password: 'test123', name: 'Teszt Felhasználó', avatar: 'T' },
                { email: 'user@example.com', password: 'user123', name: 'User Felhasználó', avatar: 'U' }
            ];

            const user = demoUsers.find(u => 
                u.email === data.email && u.password === data.password
            );

            if (user) {
                const userData = {
                    id: Date.now(),
                    name: user.name,
                    email: user.email,
                    avatar: user.avatar
                };
                
                this.login(userData, data.remember);
                this.showSuccess('Sikeres bejelentkezés!');
                this.hideLoginModal();
            } else {
                throw new Error('Hibás e-mail cím vagy jelszó');
            }
        } catch (error) {
            this.showError('login-password', error.message);
        } finally {
            this.setLoading(btn, false);
        }
    }

    async performRegister(data) {
        const btn = document.querySelector('#register-form button[type="submit"]');
        this.setLoading(btn, true);

        try {
            // Szimulált API hívás
            await new Promise(resolve => setTimeout(resolve, 1000 + Math.random() * 1000));

            // Ellenőrizzük, hogy létezik-e már a felhasználó
            const existingUsers = this.getStoredUsers();
            if (existingUsers.some(user => user.email === data.email)) {
                throw new Error('Ez az e-mail cím már foglalt');
            }

            const userData = {
                id: Date.now(),
                name: data.name,
                email: data.email,
                avatar: data.name.charAt(0).toUpperCase(),
                createdAt: new Date().toISOString()
            };

            // Mentés a localStorage-ba (demo)
            existingUsers.push(userData);
            localStorage.setItem('registeredUsers', JSON.stringify(existingUsers));

            this.login(userData, true);
            this.showSuccess('Sikeres regisztráció! Üdvözöljük!');
            this.hideLoginModal();
        } catch (error) {
            this.showError('register-email', error.message);
        } finally {
            this.setLoading(btn, false);
        }
    }

    async performPasswordReset(email) {
        const btn = document.querySelector('#forgot-password-form button[type="submit"]');
        this.setLoading(btn, true);

        try {
            // Szimulált API hívás
            await new Promise(resolve => setTimeout(resolve, 1000 + Math.random() * 1000));
            
            this.showSuccess('Küldtünk egy e-mailt a jelszó visszaállítási linkkel!');
            this.hideForgotPasswordModal();
        } catch (error) {
            this.showError('forgot-email', 'Hiba történt a kérés feldolgozása során');
        } finally {
            this.setLoading(btn, false);
        }
    }

    // Felhasználó kezelés
    login(user, remember) {
        this.currentUser = user;
        this.saveUser(user, remember);
        this.updateUI();
        console.log('User logged in:', user.name);
    }

    logout() {
        console.log('User logging out');
        this.currentUser = null;
        this.clearUser();
        this.updateUI();
        this.hideUserMenu();
        this.showSuccess('Sikeres kijelentkezés! Viszlát!');
    }

    // Adat mentés
    saveUser(user, remember) {
        try {
            const storage = remember ? localStorage : sessionStorage;
            storage.setItem('currentUser', JSON.stringify(user));
            console.log('User saved to storage');
        } catch (error) {
            console.error('Error saving user:', error);
        }
    }

    loadUser() {
        try {
            let user = localStorage.getItem('currentUser') || sessionStorage.getItem('currentUser');
            if (user) {
                this.currentUser = JSON.parse(user);
                console.log('User loaded from storage:', this.currentUser.name);
            } else {
                console.log('No user found in storage');
            }
        } catch (error) {
            console.error('Error loading user:', error);
            this.currentUser = null;
        }
    }

    getStoredUsers() {
        try {
            return JSON.parse(localStorage.getItem('registeredUsers') || '[]');
        } catch (error) {
            return [];
        }
    }

    clearUser() {
        try {
            localStorage.removeItem('currentUser');
            sessionStorage.removeItem('currentUser');
            console.log('User cleared from storage');
        } catch (error) {
            console.error('Error clearing user:', error);
        }
    }

    // UI frissítés
    updateUI() {
        const loginBtn = document.getElementById('login-btn');
        const loginBtnText = document.getElementById('login-btn-text');
        
        if (!loginBtn || !loginBtnText) {
            console.error('Login button elements not found');
            return;
        }

        if (this.currentUser) {
            const shortName = this.currentUser.name.split(' ')[0];
            loginBtnText.textContent = shortName;
            loginBtn.classList.add('logged-in');
            
            // Felhasználói adatok frissítése
            const userName = document.getElementById('user-name');
            const userEmail = document.getElementById('user-email');
            const userAvatar = document.getElementById('user-avatar');
            
            if (userName) userName.textContent = this.currentUser.name;
            if (userEmail) userEmail.textContent = this.currentUser.email;
            if (userAvatar) userAvatar.textContent = this.currentUser.avatar;
            
            console.log('UI updated: User is logged in');
        } else {
            loginBtnText.textContent = 'Bejelentkezés';
            loginBtn.classList.remove('logged-in');
            this.hideUserMenu();
            console.log('UI updated: User is logged out');
        }
    }

    toggleUserMenu() {
        const userMenu = document.getElementById('user-menu');
        if (!userMenu) {
            console.error('User menu not found');
            return;
        }

        const isVisible = userMenu.style.display === 'block';
        userMenu.style.display = isVisible ? 'none' : 'block';
        console.log('User menu toggled:', userMenu.style.display);
    }

    hideUserMenu() {
        const userMenu = document.getElementById('user-menu');
        if (userMenu) {
            userMenu.style.display = 'none';
        }
    }

    // Segéd funkciók
    showError(fieldId, message) {
        const errorElement = document.getElementById(`${fieldId}-error`);
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement && inputElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('error');
            
            // Fókusz a hibás mezőre
            inputElement.focus();
        }
    }

    clearErrors(formType) {
        const errors = document.querySelectorAll(`#${formType}-form .error-message`);
        const inputs = document.querySelectorAll(`#${formType}-form input`);
        
        errors.forEach(error => {
            error.classList.remove('show');
            error.textContent = '';
        });
        
        inputs.forEach(input => input.classList.remove('error'));
    }

    showSuccess(message) {
        // Egyszerű success üzenet - valós alkalmazásban modal vagy toast lenne
        const existingAlert = document.querySelector('.success-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = 'success-alert';
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        alert.textContent = message;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 3000);
    }

    setLoading(button, isLoading) {
        if (!button) return;
        
        if (isLoading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }

    resetForms() {
        document.querySelectorAll('.auth-form').forEach(form => {
            if (form) form.reset();
        });
        
        document.querySelectorAll('.error-message').forEach(error => {
            error.classList.remove('show');
        });
        
        document.querySelectorAll('input.error').forEach(input => {
            input.classList.remove('error');
        });
    }

    handleOutsideClick(e) {
        // User menu bezárása
        const userMenu = document.getElementById('user-menu');
        const loginBtn = document.getElementById('login-btn');
        
        if (userMenu && userMenu.style.display === 'block' && 
            !userMenu.contains(e.target) && 
            !loginBtn.contains(e.target)) {
            this.hideUserMenu();
        }
        
        // Modal bezárás kívülre kattintva
        const loginModal = document.getElementById('login-modal');
        const forgotModal = document.getElementById('forgot-password-modal');
        
        if (loginModal && e.target === loginModal) {
            this.hideLoginModal();
        }
        
        if (forgotModal && e.target === forgotModal) {
            this.hideForgotPasswordModal();
        }
    }
}

// Inicializálás és globális hozzáférés
document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM loaded, initializing auth system...');
    
    // Várjunk egy kicsit, hogy minden betöltődjön
    setTimeout(async () => {
        window.authSystem = new AuthSystem();
        await window.authSystem.init();
        
        // Demo információk
        console.log('Demo bejelentkezési adatok:');
        console.log('E-mail: demo@example.com, Jelszó: demo123');
        console.log('E-mail: test@example.com, Jelszó: test123');
        console.log('E-mail: user@example.com, Jelszó: user123');
    }, 100);
});

// CSS animációk hozzáadása
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .modal.active {
        display: block !important;
    }
`;
document.head.appendChild(style);