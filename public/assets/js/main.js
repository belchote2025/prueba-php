// Main JavaScript for Filá Mariscales website
document.addEventListener('DOMContentLoaded', function() {
    initBootstrapComponents();
    initSmoothScrolling();
    initFormValidation();
    initFlashMessages();
    initMobileMenu();
    setActiveNavItem();
    initNavbarScroll();
    initLazyLoading();
});

function initBootstrapComponents() {
    // Tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(el => new bootstrap.Tooltip(el));
    
    // Popovers
    var popovers = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popovers.map(el => new bootstrap.Popover(el));
}

function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
}

function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}

function initFlashMessages() {
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

function initMobileMenu() {
    const menu = document.getElementById('navbarNav');
    if (!menu) return;
    
    const bsCollapse = new bootstrap.Collapse(menu, { toggle: false });
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                bsCollapse.hide();
            }
        });
    });
}

function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/') {
            link.classList.add('active');
        }
    });
}

function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Lazy Loading de imágenes para mejor performance
function initLazyLoading() {
    // Si el navegador soporta loading="lazy" nativo, agregarlo a todas las imágenes
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach(img => {
            // No aplicar a imágenes del carousel o hero (carga inmediata)
            if (!img.closest('.carousel') && !img.closest('.hero')) {
                img.loading = 'lazy';
            }
        });
    } else {
        // Fallback para navegadores antiguos usando Intersection Observer
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    observer.unobserve(img);
                }
            });
        });
        
        // Observar imágenes con data-src
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}
