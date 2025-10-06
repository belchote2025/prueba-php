// ===== CONFIGURACIÓN GLOBAL =====
const CONFIG = {
    API_BASE_URL: 'api/',
    UPLOAD_URL: 'uploads/',
    SITE_NAME: 'Filá Mariscales de Caballeros Templarios',
    VERSION: '2.0.0'
};

// ===== CLASE PRINCIPAL DE LA APLICACIÓN =====
class FilaMariscalesApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialData();
        this.setupSmoothScrolling();
        this.setupNavbarScroll();
        this.setupFormHandlers();
        this.setupGallery();
        this.setupAnimations();
    }

    // ===== CONFIGURACIÓN DE EVENTOS =====
    setupEventListeners() {
        // Navegación suave
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', this.handleSmoothScroll.bind(this));
        });

        // Formulario de contacto
        const contactoForm = document.getElementById('contacto-form');
        if (contactoForm) {
            contactoForm.addEventListener('submit', this.handleContactForm.bind(this));
        }

        // Botones de navegación
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', this.handleNavClick.bind(this));
        });

        // Eventos de scroll
        window.addEventListener('scroll', this.handleScroll.bind(this));
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    // ===== CARGA DE DATOS INICIALES =====
    async loadInitialData() {
        try {
            await Promise.all([
                this.loadNoticias(),
                this.loadEventos(),
                this.loadGaleria(),
                this.loadProductos()
            ]);
        } catch (error) {
            console.error('Error cargando datos iniciales:', error);
            this.showError('Error al cargar los datos. Por favor, recarga la página.');
        }
    }

    // ===== CARGA DE NOTICIAS =====
    async loadNoticias() {
        const container = document.getElementById('noticias-container');
        if (!container) return;

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}noticias.php`);
            const noticias = await response.json();

            if (noticias.success && noticias.data.length > 0) {
                this.renderNoticias(noticias.data, container);
            } else {
                this.renderNoticiasDefault(container);
            }
        } catch (error) {
            console.error('Error cargando noticias:', error);
            this.renderNoticiasDefault(container);
        }
    }

    renderNoticias(noticias, container) {
        container.innerHTML = noticias.map(noticia => `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="${noticia.imagen_url || 'assets/images/default-news.jpg'}" 
                         class="card-img-top" alt="${noticia.titulo}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${noticia.titulo}</h5>
                        <p class="card-text flex-grow-1">${noticia.resumen || noticia.contenido.substring(0, 150) + '...'}</p>
                        <div class="card-footer bg-transparent border-0 p-0 mt-auto">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                ${this.formatDate(noticia.fecha_publicacion)}
                            </small>
                            <button class="btn btn-primary btn-sm float-end" 
                                    onclick="app.showNoticiaModal(${noticia.id})">
                                Leer más
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderNoticiasDefault(container) {
        const noticiasDefault = [
            {
                titulo: "Presentación de la Filá 2024",
                contenido: "La Filá Mariscales se presenta oficialmente para las fiestas de Moros y Cristianos 2024 con nuevas incorporaciones y actividades.",
                fecha: "2024-10-15",
                imagen: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            },
            {
                titulo: "Cena de Hermandad",
                contenido: "Celebramos nuestra tradicional cena de hermandad donde todos los miembros de la filá se reúnen para compartir momentos especiales.",
                fecha: "2024-10-20",
                imagen: "https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            },
            {
                titulo: "Ensayo General",
                contenido: "Preparación final para el desfile de Moros y Cristianos con el ensayo general en el punto de encuentro oficial.",
                fecha: "2024-10-25",
                imagen: "https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            }
        ];

        container.innerHTML = noticiasDefault.map(noticia => `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="${noticia.imagen}" class="card-img-top" alt="${noticia.titulo}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${noticia.titulo}</h5>
                        <p class="card-text flex-grow-1">${noticia.contenido}</p>
                        <div class="card-footer bg-transparent border-0 p-0 mt-auto">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                ${this.formatDate(noticia.fecha)}
                            </small>
                            <button class="btn btn-primary btn-sm float-end" 
                                    onclick="app.showNoticiaModal('${noticia.titulo}', '${noticia.contenido}')">
                                Leer más
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ===== CARGA DE EVENTOS =====
    async loadEventos() {
        const container = document.getElementById('eventos-container');
        if (!container) return;

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}eventos.php`);
            const eventos = await response.json();

            if (eventos.success && eventos.data.length > 0) {
                this.renderEventos(eventos.data, container);
            } else {
                this.renderEventosDefault(container);
            }
        } catch (error) {
            console.error('Error cargando eventos:', error);
            this.renderEventosDefault(container);
        }
    }

    renderEventos(eventos, container) {
        container.innerHTML = eventos.map(evento => `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${evento.titulo}</h5>
                        <p class="card-text">${evento.descripcion}</p>
                        <div class="event-details">
                            <div class="mb-2">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                <strong>Fecha:</strong> ${this.formatDate(evento.fecha)}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Hora:</strong> ${evento.hora || 'Por determinar'}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Lugar:</strong> ${evento.lugar || 'Por determinar'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderEventosDefault(container) {
        const eventosDefault = [
            {
                titulo: "Presentación de la Filá",
                descripcion: "Presentación oficial de la Filá Mariscales para las fiestas 2024",
                fecha: "2024-10-15",
                hora: "20:00",
                lugar: "Sede Social"
            },
            {
                titulo: "Cena de Hermandad",
                descripcion: "Cena de hermandad para todos los miembros de la filá",
                fecha: "2024-10-20",
                hora: "21:00",
                lugar: "Restaurante El Rincón"
            },
            {
                titulo: "Ensayo General",
                descripcion: "Ensayo general del desfile de Moros y Cristianos",
                fecha: "2024-10-25",
                hora: "18:00",
                lugar: "Punto de encuentro: Ayuntamiento"
            }
        ];

        container.innerHTML = eventosDefault.map(evento => `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${evento.titulo}</h5>
                        <p class="card-text">${evento.descripcion}</p>
                        <div class="event-details">
                            <div class="mb-2">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                <strong>Fecha:</strong> ${this.formatDate(evento.fecha)}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Hora:</strong> ${evento.hora}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Lugar:</strong> ${evento.lugar}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ===== CARGA DE GALERÍA =====
    async loadGaleria() {
        const container = document.getElementById('galeria-container');
        if (!container) return;

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}galeria.php`);
            const galeria = await response.json();

            if (galeria.success && galeria.data.length > 0) {
                this.renderGaleria(galeria.data, container);
            } else {
                this.renderGaleriaDefault(container);
            }
        } catch (error) {
            console.error('Error cargando galería:', error);
            this.renderGaleriaDefault(container);
        }
    }

    renderGaleria(imagenes, container) {
        container.innerHTML = imagenes.map(imagen => `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="gallery-item" onclick="app.showImageModal('${imagen.url}', '${imagen.titulo || 'Imagen de la Filá'}')">
                    <img src="${imagen.thumb || imagen.url}" alt="${imagen.titulo || 'Galería'}">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderGaleriaDefault(container) {
        const imagenesDefault = [
            {
                url: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                titulo: "Desfile de Moros y Cristianos 2023"
            },
            {
                url: "https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                titulo: "Cena de Hermandad"
            },
            {
                url: "https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                titulo: "Presentación de la Filá"
            },
            {
                url: "https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                titulo: "Actuación Musical"
            }
        ];

        container.innerHTML = imagenesDefault.map(imagen => `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="gallery-item" onclick="app.showImageModal('${imagen.url}', '${imagen.titulo}')">
                    <img src="${imagen.url}" alt="${imagen.titulo}">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus"></i>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ===== CARGA DE PRODUCTOS =====
    async loadProductos() {
        const container = document.getElementById('productos-container');
        if (!container) return;

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}productos.php`);
            const productos = await response.json();

            if (productos.success && productos.data.length > 0) {
                this.renderProductos(productos.data, container);
            } else {
                this.renderProductosDefault(container);
            }
        } catch (error) {
            console.error('Error cargando productos:', error);
            this.renderProductosDefault(container);
        }
    }

    renderProductos(productos, container) {
        container.innerHTML = productos.map(producto => `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="${producto.imagen_url || 'assets/images/default-product.jpg'}" 
                         class="card-img-top" alt="${producto.nombre}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${producto.nombre}</h5>
                        <p class="card-text flex-grow-1">${producto.descripcion}</p>
                        <div class="product-price mb-3">
                            <span class="h5 text-primary">${producto.precio}€</span>
                        </div>
                        <button class="btn btn-primary w-100" 
                                onclick="app.addToCart(${producto.id}, '${producto.nombre}', ${producto.precio})">
                            <i class="fas fa-shopping-cart me-2"></i>Añadir al carrito
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    renderProductosDefault(container) {
        const productosDefault = [
            {
                id: 1,
                nombre: "Camiseta Oficial",
                descripcion: "Camiseta oficial de la Filá Mariscales",
                precio: 25.00,
                imagen: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            },
            {
                id: 2,
                nombre: "Gorra Templaria",
                descripcion: "Gorra con el escudo de los Caballeros Templarios",
                precio: 18.00,
                imagen: "https://images.unsplash.com/photo-1588850561407-ed78c282e89b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            },
            {
                id: 3,
                nombre: "Bandera de la Filá",
                descripcion: "Bandera oficial de la Filá Mariscales",
                precio: 35.00,
                imagen: "https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            },
            {
                id: 4,
                nombre: "Insignia Dorada",
                descripcion: "Insignia dorada de los Caballeros Templarios",
                precio: 12.00,
                imagen: "https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
            }
        ];

        container.innerHTML = productosDefault.map(producto => `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="${producto.imagen}" class="card-img-top" alt="${producto.nombre}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${producto.nombre}</h5>
                        <p class="card-text flex-grow-1">${producto.descripcion}</p>
                        <div class="product-price mb-3">
                            <span class="h5 text-primary">${producto.precio}€</span>
                        </div>
                        <button class="btn btn-primary w-100" 
                                onclick="app.addToCart(${producto.id}, '${producto.nombre}', ${producto.precio})">
                            <i class="fas fa-shopping-cart me-2"></i>Añadir al carrito
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ===== MANEJO DE FORMULARIOS =====
    async handleContactForm(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const data = {
            nombre: formData.get('nombre') || document.getElementById('nombre').value,
            email: formData.get('email') || document.getElementById('email').value,
            mensaje: formData.get('mensaje') || document.getElementById('mensaje').value
        };

        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}contacto.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Mensaje enviado correctamente. Te responderemos pronto.');
                event.target.reset();
            } else {
                this.showError(result.message || 'Error al enviar el mensaje.');
            }
        } catch (error) {
            console.error('Error enviando mensaje:', error);
            this.showError('Error al enviar el mensaje. Por favor, inténtalo de nuevo.');
        }
    }

    // ===== FUNCIONES DE UTILIDAD =====
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    setupNavbarScroll() {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        }
    }

    setupFormHandlers() {
        // Validación en tiempo real
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('blur', this.validateField.bind(this));
            input.addEventListener('input', this.clearFieldError.bind(this));
        });
    }

    setupGallery() {
        // Configuración de la galería de imágenes
        this.galleryModal = null;
    }

    setupAnimations() {
        // Configuración de animaciones
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        });

        document.querySelectorAll('.card, .gallery-item').forEach(el => {
            this.observer.observe(el);
        });
    }

    // ===== MANEJO DE EVENTOS =====
    handleSmoothScroll(event) {
        event.preventDefault();
        const targetId = event.target.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    handleNavClick(event) {
        // Cerrar menú móvil si está abierto
        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse.classList.contains('show')) {
            const navbarToggler = document.querySelector('.navbar-toggler');
            navbarToggler.click();
        }
    }

    handleScroll() {
        // Efectos de scroll
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.hero-section');
        
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    }

    handleResize() {
        // Manejo de redimensionamiento
        this.setupAnimations();
    }

    // ===== VALIDACIÓN DE FORMULARIOS =====
    validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        // Limpiar errores previos
        this.clearFieldError(event);
        
        // Validaciones específicas
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'Este campo es obligatorio');
            return false;
        }
        
        if (field.type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'Email no válido');
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
        errorElement.className = 'field-error text-danger small mt-1';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // ===== MODALES =====
    showNoticiaModal(id, titulo = null, contenido = null) {
        if (titulo && contenido) {
            // Modal con datos directos
            this.createModal('Noticia', `
                <h4>${titulo}</h4>
                <p>${contenido}</p>
            `);
        } else {
            // Cargar noticia desde API
            this.loadNoticiaModal(id);
        }
    }

    async loadNoticiaModal(id) {
        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}noticia.php?id=${id}`);
            const result = await response.json();
            
            if (result.success) {
                const noticia = result.data;
                this.createModal('Noticia', `
                    <h4>${noticia.titulo}</h4>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            ${this.formatDate(noticia.fecha_publicacion)}
                        </small>
                    </div>
                    <div>${noticia.contenido}</div>
                `);
            } else {
                this.showError('Error al cargar la noticia');
            }
        } catch (error) {
            console.error('Error cargando noticia:', error);
            this.showError('Error al cargar la noticia');
        }
    }

    showImageModal(imageUrl, title) {
        this.createModal('Galería', `
            <div class="text-center">
                <img src="${imageUrl}" class="img-fluid" alt="${title}">
                <h5 class="mt-3">${title}</h5>
            </div>
        `);
    }

    createModal(title, content) {
        // Crear modal dinámicamente
        const modalHtml = `
            <div class="modal fade" id="dynamicModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${content}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remover modal anterior si existe
        const existingModal = document.getElementById('dynamicModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Añadir nuevo modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('dynamicModal'));
        modal.show();

        // Limpiar modal cuando se cierre
        document.getElementById('dynamicModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // ===== CARRITO DE COMPRAS =====
    addToCart(productId, productName, price) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: price,
                quantity: 1
            });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        this.showSuccess(`${productName} añadido al carrito`);
        this.updateCartBadge();
    }

    updateCartBadge() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        let badge = document.querySelector('.cart-badge');
        if (!badge) {
            // Crear badge si no existe
            const cartLink = document.querySelector('a[href*="cart"]');
            if (cartLink) {
                badge = document.createElement('span');
                badge.className = 'cart-badge badge bg-danger position-absolute top-0 start-100 translate-middle';
                cartLink.appendChild(badge);
            }
        }
        
        if (badge) {
            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'block' : 'none';
        }
    }

    // ===== NOTIFICACIONES =====
    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'danger');
    }

    showInfo(message) {
        this.showNotification(message, 'info');
    }

    showNotification(message, type) {
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

// ===== INICIALIZACIÓN =====
let app;

document.addEventListener('DOMContentLoaded', function() {
    app = new FilaMariscalesApp();
    
    // Actualizar badge del carrito al cargar
    app.updateCartBadge();
    
    // Configurar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// ===== FUNCIONES GLOBALES =====
window.app = app;