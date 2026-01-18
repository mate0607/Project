// simple-auth.js - Bejelentkezési rendszer

class SimpleAuth {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        console.log('🔧 SimpleAuth initializing...');
        this.loadUser();
        this.setupEventListeners();
        this.updateUI();
    }

    setupEventListeners() {
        console.log('🔧 Setting up event listeners...');
        
        // 1. Bejelentkezési gomb
        const loginBtn = document.getElementById('login-btn');
        if (loginBtn) {
            loginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('🎯 Login button clicked!');
                this.showLoginModal();
            });
        } else {
            console.error('❌ Login button not found!');
        }

        // 2. Modal bezárások
        this.setupModalClosers();

        // 3. Tab váltások
        this.setupTabs();

        // 4. Űrlapok
        this.setupForms();

        console.log('✅ Event listeners setup complete');
    }

    setupModalClosers() {
        // Login modal bezárás
        const closeLogin = document.getElementById('close-login');
        if (closeLogin) {
            closeLogin.addEventListener('click', () => {
                console.log('Closing login modal');
                this.hideLoginModal();
            });
        }

        // Forgot password modal bezárás
        const closeForgot = document.getElementById('close-forgot');
        if (closeForgot) {
            closeForgot.addEventListener('click', () => {
                console.log('Closing forgot password modal');
                this.hideForgotPasswordModal();
            });
        }

        // Kívülre kattintás
        document.addEventListener('click', (e) => {
            if (e.target.id === 'login-modal') {
                this.hideLoginModal();
            }
            if (e.target.id === 'forgot-password-modal') {
                this.hideForgotPasswordModal();
            }
        });

        // ESC billentyű
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideLoginModal();
                this.hideForgotPasswordModal();
            }
        });
    }

    setupTabs() {
        const tabs = document.querySelectorAll('.auth-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const targetTab = e.target.getAttribute('data-tab');
                console.log('Switching to tab:', targetTab);
                this.switchTab(targetTab);
            });
        });
    }

    setupForms() {
        // Login form
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Login form submitted');
                this.handleLogin(e);
            });
        }

        // Register form
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Register form submitted');
                this.handleRegister(e);
            });
        }

        // Forgot password form
        const forgotForm = document.getElementById('forgot-password-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Forgot password form submitted');
                this.handleForgotPassword(e);
            });
        }

        // Forgot password link
        const forgotLink = document.querySelector('.forgot-password');
        if (forgotLink) {
            forgotLink.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Forgot password link clicked');
                this.showForgotPasswordModal();
            });
        }

        // Back to login link
        const backToLogin = document.querySelector('.back-to-login');
        if (backToLogin) {
            backToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Back to login clicked');
                this.hideForgotPasswordModal();
                this.showLoginModal();
            });
        }

        // Logout button
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Logout clicked');
                this.logout();
            });
        }
    }

    // Modal műveletek
    showLoginModal() {
        console.log('Showing login modal');
        const modal = document.getElementById('login-modal');
        if (modal) {
            modal.style.display = 'block';
            this.resetForms();
            this.switchTab('login');
        } else {
            console.error('Login modal not found!');
        }
    }

    hideLoginModal() {
        const modal = document.getElementById('login-modal');
        if (modal) modal.style.display = 'none';
    }

    showForgotPasswordModal() {
        console.log('Showing forgot password modal');
        const modal = document.getElementById('forgot-password-modal');
        if (modal) {
            this.hideLoginModal();
            modal.style.display = 'block';
            this.resetForms();
        }
    }

    hideForgotPasswordModal() {
        const modal = document.getElementById('forgot-password-modal');
        if (modal) modal.style.display = 'none';
    }

    // Tab váltás
    switchTab(tabName) {
        console.log('Switching to tab:', tabName);
        
        // Tabs
        const tabs = document.querySelectorAll('.auth-tab');
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.getAttribute('data-tab') === tabName) {
                tab.classList.add('active');
            }
        });

        // Forms
        const forms = document.querySelectorAll('.auth-form');
        forms.forEach(form => {
            form.classList.remove('active');
            if (form.id === `${tabName}-form`) {
                form.classList.add('active');
            }
        });
    }

    // Form kezelés
    handleLogin(e) {
        const formData = new FormData(e.target);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };

        console.log('Login attempt:', data);

        // Egyszerű validáció
        if (!data.email || !data.password) {
            alert('Kérjük töltsd ki mindkét mezőt!');
            return;
        }

        // Demo bejelentkezés
        if (data.email === 'demo@example.com' && data.password === 'demo123') {
            this.login({
                id: 1,
                name: 'Demo Felhasználó',
                email: data.email,
                avatar: 'D'
            });
            this.hideLoginModal();
            alert('Sikeres bejelentkezés! Üdvözöljük!');
        } else {
            alert('Hibás e-mail vagy jelszó! Használd: demo@example.com / demo123');
        }
    }

    handleRegister(e) {
        const formData = new FormData(e.target);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword')
        };

        console.log('Register attempt:', data);

        // Egyszerű validáció
        if (!data.name || !data.email || !data.password || !data.confirmPassword) {
            alert('Kérjük töltsd ki az összes mezőt!');
            return;
        }

        if (data.password !== data.confirmPassword) {
            alert('A jelszavak nem egyeznek!');
            return;
        }

        // Regisztráció
        this.login({
            id: Date.now(),
            name: data.name,
            email: data.email,
            avatar: data.name.charAt(0).toUpperCase()
        });
        
        this.hideLoginModal();
        alert('Sikeres regisztráció! Üdvözöljük!');
    }

    handleForgotPassword(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const email = formData.get('email');

        console.log('Password reset requested for:', email);

        if (!email) {
            alert('Kérjük add meg az e-mail címed!');
            return;
        }

        this.hideForgotPasswordModal();
        alert(`Küldtünk egy jelszó-visszaállítási linket a(z) ${email} címre!`);
    }

    // Auth műveletek
    login(user) {
        console.log('Logging in user:', user);
        this.currentUser = user;
        localStorage.setItem('currentUser', JSON.stringify(user));
        this.updateUI();
    }

    logout() {
        console.log('Logging out user');
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        this.updateUI();
        alert('Sikeres kijelentkezés!');
    }

    loadUser() {
        try {
            const userData = localStorage.getItem('currentUser');
            if (userData) {
                this.currentUser = JSON.parse(userData);
                console.log('Loaded user from storage:', this.currentUser);
            }
        } catch (error) {
            console.error('Error loading user:', error);
        }
    }

    updateUI() {
        const loginBtn = document.getElementById('login-btn');
        const loginBtnText = document.getElementById('login-btn-text');
        const userMenu = document.getElementById('user-menu');

        if (!loginBtn || !loginBtnText) {
            console.error('UI elements not found!');
            return;
        }

        if (this.currentUser) {
            // Bejelentkezett állapot
            loginBtnText.textContent = this.currentUser.name.split(' ')[0];
            loginBtn.classList.add('logged-in');
            
            // User menu frissítése
            if (userMenu) {
                document.getElementById('user-name').textContent = this.currentUser.name;
                document.getElementById('user-email').textContent = this.currentUser.email;
                document.getElementById('user-avatar').textContent = this.currentUser.avatar;
            }

            console.log('UI updated: User is logged in');
        } else {
            // Kijelentkezett állapot
            loginBtnText.textContent = 'Bejelentkezés';
            loginBtn.classList.remove('logged-in');
            
            if (userMenu) {
                userMenu.style.display = 'none';
            }

            console.log('UI updated: User is logged out');
        }
    }

    resetForms() {
        // Reset all forms
        const forms = document.querySelectorAll('.auth-form');
        forms.forEach(form => form.reset());
        
        // Clear errors
        const errors = document.querySelectorAll('.error-message');
        errors.forEach(error => error.classList.remove('show'));
        
        const errorInputs = document.querySelectorAll('input.error');
        errorInputs.forEach(input => input.classList.remove('error'));
    }
}

// Azonnali inicializálás
console.log('🚀 Starting SimpleAuth system...');
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM fully loaded');
    window.simpleAuth = new SimpleAuth();
    
    // Demo információk
    console.log('📋 Demo bejelentkezési adatok:');
    console.log('   E-mail: demo@example.com');
    console.log('   Jelszó: demo123');
});