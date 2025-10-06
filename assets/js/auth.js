// ===== CONFIGURACIÓN GLOBAL =====
const AUTH_CONFIG = {
    API_BASE_URL: 'api/',
    LOGIN_ENDPOINT: 'auth/login.php',
    REGISTER_ENDPOINT: 'auth/register.php',
    FORGOT_PASSWORD_ENDPOINT: 'auth/forgot-password.php',
    RESET_PASSWORD_ENDPOINT: 'auth/reset-password.php',
    TOKEN_KEY: 'auth_token',
    USER_KEY: 'user_data'
};

// ===== CLASE DE AUTENTICACIÓN =====
class AuthManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkAuthStatus();
        this.setupFormValidation();
    }

    // ===== CONFIGURACIÓN DE EVENTOS =====
    setupEventListeners() {
        // Formulario de login
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', this.handleLogin.bind(this));
        }

        // Formulario de registro
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', this.handleRegister.bind(this));
        }

        // Formulario de recuperación de contraseña
        const forgotForm = document.getElementById('forgot-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', this.handleForgotPassword.bind(this));
        }

        // Formulario de restablecimiento de contraseña
        const resetForm = document.getElementById('reset-form');
        if (resetForm) {
            resetForm.addEventListener('submit', this.handleResetPassword.bind(this));
        }

        // Validación en tiempo real
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('blur', this.validateField.bind(this));
            input.addEventListener('input', this.clearFieldError.bind(this));
        });
    }

    // ===== MANEJO DE LOGIN =====
    async handleLogin(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            email: formData.get('email'),
            password: formData.get('password'),
            remember: formData.get('remember') === 'on'
        };

        // Validar datos
        if (!this.validateLoginData(data)) {
            return;
        }

        // Mostrar estado de carga
        this.setLoadingState(form, true);

        try {
            const response = await fetch(`${AUTH_CONFIG.API_BASE_URL}${AUTH_CONFIG.LOGIN_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Guardar token y datos del usuario
                this.saveAuthData(result.data, data.remember);
                
                // Mostrar mensaje de éxito
                this.showNotification('¡Bienvenido! Iniciando sesión...', 'success');
                
                // Redirigir después de un breve delay
                setTimeout(() => {
                    this.redirectAfterLogin(result.data);
                }, 1500);
            } else {
                this.showNotification(result.message || 'Error al iniciar sesión', 'danger');
                this.shakeForm(form);
            }
        } catch (error) {
            console.error('Error en login:', error);
            this.showNotification('Error de conexión. Inténtalo de nuevo.', 'danger');
        } finally {
            this.setLoadingState(form, false);
        }
    }

    // ===== MANEJO DE REGISTRO =====
    async handleRegister(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            nombre: formData.get('nombre'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirm_password: formData.get('confirm_password'),
            telefono: formData.get('telefono'),
            acepta_terminos: formData.get('acepta_terminos') === 'on'
        };

        // Validar datos
        if (!this.validateRegisterData(data)) {
            return;
        }

        // Mostrar estado de carga
        this.setLoadingState(form, true);

        try {
            const response = await fetch(`${AUTH_CONFIG.API_BASE_URL}${AUTH_CONFIG.REGISTER_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('¡Registro exitoso! Revisa tu email para activar la cuenta.', 'success');
                form.reset();
                
                // Redirigir al login después de 3 segundos
                setTimeout(() => {
                    window.location.href = 'login.html';
                }, 3000);
            } else {
                this.showNotification(result.message || 'Error al registrar usuario', 'danger');
                this.shakeForm(form);
            }
        } catch (error) {
            console.error('Error en registro:', error);
            this.showNotification('Error de conexión. Inténtalo de nuevo.', 'danger');
        } finally {
            this.setLoadingState(form, false);
        }
    }

    // ===== MANEJO DE RECUPERACIÓN DE CONTRASEÑA =====
    async handleForgotPassword(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            email: formData.get('email')
        };

        if (!this.isValidEmail(data.email)) {
            this.showFieldError(form.querySelector('#email'), 'Email no válido');
            return;
        }

        this.setLoadingState(form, true);

        try {
            const response = await fetch(`${AUTH_CONFIG.API_BASE_URL}${AUTH_CONFIG.FORGOT_PASSWORD_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Se ha enviado un enlace de recuperación a tu email.', 'success');
                form.reset();
            } else {
                this.showNotification(result.message || 'Error al enviar email de recuperación', 'danger');
            }
        } catch (error) {
            console.error('Error en recuperación:', error);
            this.showNotification('Error de conexión. Inténtalo de nuevo.', 'danger');
        } finally {
            this.setLoadingState(form, false);
        }
    }

    // ===== MANEJO DE RESTABLECIMIENTO DE CONTRASEÑA =====
    async handleResetPassword(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            token: formData.get('token'),
            password: formData.get('password'),
            confirm_password: formData.get('confirm_password')
        };

        if (!this.validateResetData(data)) {
            return;
        }

        this.setLoadingState(form, true);

        try {
            const response = await fetch(`${AUTH_CONFIG.API_BASE_URL}${AUTH_CONFIG.RESET_PASSWORD_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Contraseña restablecida correctamente. Redirigiendo al login...', 'success');
                setTimeout(() => {
                    window.location.href = 'login.html';
                }, 2000);
            } else {
                this.showNotification(result.message || 'Error al restablecer contraseña', 'danger');
            }
        } catch (error) {
            console.error('Error en restablecimiento:', error);
            this.showNotification('Error de conexión. Inténtalo de nuevo.', 'danger');
        } finally {
            this.setLoadingState(form, false);
        }
    }

    // ===== VALIDACIONES =====
    validateLoginData(data) {
        let isValid = true;

        if (!data.email || !this.isValidEmail(data.email)) {
            this.showFieldError(document.getElementById('email'), 'Email no válido');
            isValid = false;
        }

        if (!data.password || data.password.length < 6) {
            this.showFieldError(document.getElementById('password'), 'La contraseña debe tener al menos 6 caracteres');
            isValid = false;
        }

        return isValid;
    }

    validateRegisterData(data) {
        let isValid = true;

        if (!data.nombre || data.nombre.length < 2) {
            this.showFieldError(document.getElementById('nombre'), 'El nombre debe tener al menos 2 caracteres');
            isValid = false;
        }

        if (!data.email || !this.isValidEmail(data.email)) {
            this.showFieldError(document.getElementById('email'), 'Email no válido');
            isValid = false;
        }

        if (!data.password || data.password.length < 8) {
            this.showFieldError(document.getElementById('password'), 'La contraseña debe tener al menos 8 caracteres');
            isValid = false;
        }

        if (data.password !== data.confirm_password) {
            this.showFieldError(document.getElementById('confirm_password'), 'Las contraseñas no coinciden');
            isValid = false;
        }

        if (!data.acepta_terminos) {
            this.showFieldError(document.getElementById('acepta_terminos'), 'Debes aceptar los términos y condiciones');
            isValid = false;
        }

        return isValid;
    }

    validateResetData(data) {
        let isValid = true;

        if (!data.token) {
            this.showNotification('Token de restablecimiento no válido', 'danger');
            isValid = false;
        }

        if (!data.password || data.password.length < 8) {
            this.showFieldError(document.getElementById('password'), 'La contraseña debe tener al menos 8 caracteres');
            isValid = false;
        }

        if (data.password !== data.confirm_password) {
            this.showFieldError(document.getElementById('confirm_password'), 'Las contraseñas no coinciden');
            isValid = false;
        }

        return isValid;
    }

    validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        this.clearFieldError(event);
        
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'Este campo es obligatorio');
            return false;
        }
        
        if (field.type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'Email no válido');
            return false;
        }
        
        if (field.type === 'password' && value && value.length < 6) {
            this.showFieldError(field, 'La contraseña debe tener al menos 6 caracteres');
            return false;
        }
        
        return true;
    }

    clearFieldError(event) {
        const field = event.target;
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
        field.classList.remove('is-invalid');
    }

    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // ===== ESTADOS DE CARGA =====
    setLoadingState(form, isLoading) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const inputs = form.querySelectorAll('input, button');
        
        if (isLoading) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            inputs.forEach(input => input.disabled = true);
        } else {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            inputs.forEach(input => input.disabled = false);
        }
    }

    // ===== EFECTOS VISUALES =====
    shakeForm(form) {
        form.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            form.style.animation = '';
        }, 500);
    }

    // ===== GESTIÓN DE AUTENTICACIÓN =====
    saveAuthData(userData, remember = false) {
        const storage = remember ? localStorage : sessionStorage;
        
        storage.setItem(AUTH_CONFIG.TOKEN_KEY, userData.token);
        storage.setItem(AUTH_CONFIG.USER_KEY, JSON.stringify(userData.user));
        
        // También guardar en el otro storage para compatibilidad
        if (remember) {
            sessionStorage.setItem(AUTH_CONFIG.TOKEN_KEY, userData.token);
            sessionStorage.setItem(AUTH_CONFIG.USER_KEY, JSON.stringify(userData.user));
        }
    }

    getAuthToken() {
        return localStorage.getItem(AUTH_CONFIG.TOKEN_KEY) || 
               sessionStorage.getItem(AUTH_CONFIG.TOKEN_KEY);
    }

    getUserData() {
        const userData = localStorage.getItem(AUTH_CONFIG.USER_KEY) || 
                        sessionStorage.getItem(AUTH_CONFIG.USER_KEY);
        return userData ? JSON.parse(userData) : null;
    }

    isAuthenticated() {
        return !!this.getAuthToken();
    }

    logout() {
        localStorage.removeItem(AUTH_CONFIG.TOKEN_KEY);
        localStorage.removeItem(AUTH_CONFIG.USER_KEY);
        sessionStorage.removeItem(AUTH_CONFIG.TOKEN_KEY);
        sessionStorage.removeItem(AUTH_CONFIG.USER_KEY);
        
        // Redirigir al login
        window.location.href = 'login.html';
    }

    checkAuthStatus() {
        if (this.isAuthenticated()) {
            // Si ya está autenticado y está en una página de auth, redirigir
            if (window.location.pathname.includes('login.html') || 
                window.location.pathname.includes('register.html')) {
                this.redirectAfterLogin(this.getUserData());
            }
        } else {
            // Si no está autenticado y está en una página protegida, redirigir al login
            if (window.location.pathname.includes('admin/') || 
                window.location.pathname.includes('dashboard')) {
                window.location.href = 'login.html';
            }
        }
    }

    redirectAfterLogin(userData) {
        // Redirigir según el tipo de usuario
        if (userData.rol === 'admin') {
            window.location.href = 'admin/dashboard.html';
        } else {
            window.location.href = 'index.html';
        }
    }

    // ===== CONFIGURACIÓN DE VALIDACIÓN =====
    setupFormValidation() {
        // Configurar validación de contraseña
        const passwordField = document.getElementById('password');
        const confirmField = document.getElementById('confirm_password');
        
        if (passwordField && confirmField) {
            confirmField.addEventListener('input', () => {
                if (confirmField.value && passwordField.value !== confirmField.value) {
                    this.showFieldError(confirmField, 'Las contraseñas no coinciden');
                } else {
                    this.clearFieldError({ target: confirmField });
                }
            });
        }

        // Configurar validación de términos
        const terminosCheckbox = document.getElementById('acepta_terminos');
        if (terminosCheckbox) {
            terminosCheckbox.addEventListener('change', (e) => {
                if (!e.target.checked) {
                    this.showFieldError(e.target, 'Debes aceptar los términos y condiciones');
                } else {
                    this.clearFieldError(e);
                }
            });
        }
    }

    // ===== NOTIFICACIONES =====
    showNotification(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
}

// ===== FUNCIÓN GLOBAL PARA TOGGLE PASSWORD =====
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// ===== INICIALIZACIÓN =====
let authManager;

document.addEventListener('DOMContentLoaded', function() {
    authManager = new AuthManager();
});

// ===== FUNCIONES GLOBALES =====
window.authManager = authManager;
window.togglePassword = togglePassword;