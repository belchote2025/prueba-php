/**
 * Sistema de Tema y Personalización
 * Maneja el modo oscuro, personalización de colores y preferencias del usuario
 */

class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.customColors = JSON.parse(localStorage.getItem('customColors') || '{}');
        this.init();
    }

    init() {
        // Aplicar tema guardado
        this.applyTheme(this.theme);
        
        // Aplicar colores personalizados
        this.applyCustomColors();
        
        // Crear toggle button
        this.createThemeToggle();
        
        // Escuchar cambios de preferencia del sistema
        this.watchSystemPreference();
    }

    applyTheme(theme) {
        this.theme = theme;
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Actualizar icono del toggle
        const toggle = document.querySelector('.theme-toggle');
        if (toggle) {
            toggle.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        }
    }

    toggleTheme() {
        const newTheme = this.theme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
    }

    createThemeToggle() {
        // Verificar si ya existe
        if (document.querySelector('.theme-toggle')) {
            return;
        }

        const toggle = document.createElement('button');
        toggle.className = 'theme-toggle';
        toggle.setAttribute('aria-label', 'Cambiar tema');
        toggle.innerHTML = this.theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        toggle.addEventListener('click', () => this.toggleTheme());
        
        document.body.appendChild(toggle);
    }

    watchSystemPreference() {
        // Si no hay preferencia guardada, usar la del sistema
        if (!localStorage.getItem('theme')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
            if (prefersDark.matches) {
                this.applyTheme('dark');
            }
            
            // Escuchar cambios en la preferencia del sistema
            prefersDark.addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    this.applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }

    setCustomColor(colorName, colorValue) {
        this.customColors[colorName] = colorValue;
        localStorage.setItem('customColors', JSON.stringify(this.customColors));
        this.applyCustomColors();
    }

    applyCustomColors() {
        const root = document.documentElement;
        Object.keys(this.customColors).forEach(colorName => {
            root.style.setProperty(`--${colorName}`, this.customColors[colorName]);
        });
    }

    resetColors() {
        this.customColors = {};
        localStorage.removeItem('customColors');
        location.reload();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
    
    // Añadir animaciones a elementos al cargar
    const animatedElements = document.querySelectorAll('.card, .btn, .alert');
    animatedElements.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add('animate-fade-in');
        }, index * 50);
    });
});

// Añadir efectos hover mejorados
document.addEventListener('DOMContentLoaded', () => {
    // Efecto ripple en botones
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Añadir estilos para ripple
const style = document.createElement('style');
style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

