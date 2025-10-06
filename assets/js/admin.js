// ===== CONFIGURACIÓN GLOBAL =====
const ADMIN_CONFIG = {
    API_BASE_URL: '../api/',
    UPLOAD_URL: '../uploads/',
    SITE_NAME: 'Filá Mariscales de Caballeros Templarios',
    VERSION: '2.0.0'
};

// ===== CLASE PRINCIPAL DEL ADMIN =====
class AdminManager {
    constructor() {
        this.currentSection = 'dashboard';
        this.charts = {};
        this.data = {
            noticias: [],
            eventos: [],
            galeria: [],
            productos: [],
            pedidos: [],
            contactos: [],
            usuarios: []
        };
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkAuth();
        this.loadDashboardData();
        this.setupCharts();
    }

    // ===== CONFIGURACIÓN DE EVENTOS =====
    setupEventListeners() {
        // Navegación del sidebar
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const section = link.getAttribute('href').substring(1);
                this.showSection(section);
            });
        });

        // Botones de acción
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action]')) {
                this.handleAction(e.target.dataset.action, e.target.dataset.id);
            }
        });

        // Formularios
        document.addEventListener('submit', (e) => {
            if (e.target.matches('form')) {
                e.preventDefault();
                this.handleFormSubmit(e.target);
            }
        });

        // Filtros
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-filter]')) {
                this.handleFilter(e.target.dataset.filter, e.target.value);
            }
        });
    }

    // ===== AUTENTICACIÓN =====
    checkAuth() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '../login.html';
            return;
        }

        // Verificar token con el servidor
        this.verifyToken(token);
    }

    async verifyToken(token) {
        try {
            const response = await fetch(`${ADMIN_CONFIG.API_BASE_URL}auth/verify.php`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!response.ok) {
                throw new Error('Token inválido');
            }

            const result = await response.json();
            if (result.success) {
                this.user = result.data;
                this.updateUserInfo();
            } else {
                this.logout();
            }
        } catch (error) {
            console.error('Error verificando token:', error);
            this.logout();
        }
    }

    updateUserInfo() {
        const userName = document.getElementById('user-name');
        if (userName && this.user) {
            userName.textContent = this.user.nombre;
        }
    }

    logout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_data');
        window.location.href = '../login.html';
    }

    // ===== NAVEGACIÓN =====
    showSection(section) {
        // Ocultar todas las secciones
        document.querySelectorAll('.content-section').forEach(sec => {
            sec.classList.remove('active');
        });

        // Mostrar la sección seleccionada
        const targetSection = document.getElementById(`${section}-section`);
        if (targetSection) {
            targetSection.classList.add('active');
            this.currentSection = section;
            this.updatePageTitle(section);
            this.updateSidebarActive(section);
            this.loadSectionData(section);
        }
    }

    updatePageTitle(section) {
        const titles = {
            'dashboard': 'Dashboard',
            'noticias': 'Noticias',
            'eventos': 'Eventos',
            'galeria': 'Galería',
            'productos': 'Productos',
            'pedidos': 'Pedidos',
            'contactos': 'Contactos',
            'usuarios': 'Usuarios',
            'configuracion': 'Configuración'
        };

        const pageTitle = document.getElementById('page-title');
        if (pageTitle) {
            pageTitle.textContent = titles[section] || 'Admin';
        }
    }

    updateSidebarActive(section) {
        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.classList.remove('active');
        });

        const activeLink = document.querySelector(`.sidebar-nav .nav-link[href="#${section}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }

    // ===== CARGA DE DATOS =====
    async loadDashboardData() {
        try {
            const [stats, noticias, eventos, contactos] = await Promise.all([
                this.getStats(),
                this.getNoticias(5),
                this.getEventos(5),
                this.getContactos(5)
            ]);

            this.updateStatsCards(stats);
            this.updateRecentActivity(noticias, eventos, contactos);
        } catch (error) {
            console.error('Error cargando datos del dashboard:', error);
            this.showError('Error al cargar los datos del dashboard');
        }
    }

    async loadSectionData(section) {
        switch (section) {
            case 'noticias':
                await this.loadNoticias();
                break;
            case 'eventos':
                await this.loadEventos();
                break;
            case 'galeria':
                await this.loadGaleria();
                break;
            case 'productos':
                await this.loadProductos();
                break;
            case 'pedidos':
                await this.loadPedidos();
                break;
            case 'contactos':
                await this.loadContactos();
                break;
            case 'usuarios':
                await this.loadUsuarios();
                break;
            case 'configuracion':
                await this.loadConfiguracion();
                break;
        }
    }

    // ===== ESTADÍSTICAS =====
    async getStats() {
        try {
            const response = await fetch(`${ADMIN_CONFIG.API_BASE_URL}admin/stats.php`);
            const result = await response.json();
            return result.success ? result.data : {};
        } catch (error) {
            console.error('Error obteniendo estadísticas:', error);
            return {};
        }
    }

    updateStatsCards(stats) {
        const elements = {
            'total-noticias': stats.total_noticias || 0,
            'total-eventos': stats.total_eventos || 0,
            'total-pedidos': stats.total_pedidos || 0,
            'total-contactos': stats.total_contactos || 0
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        });
    }

    // ===== NOTICIAS =====
    async loadNoticias() {
        try {
            const noticias = await this.getNoticias();
            this.data.noticias = noticias;
            this.renderNoticiasTable(noticias);
        } catch (error) {
            console.error('Error cargando noticias:', error);
            this.showError('Error al cargar las noticias');
        }
    }

    async getNoticias(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}noticias.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}noticias.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.noticias : [];
        } catch (error) {
            console.error('Error obteniendo noticias:', error);
            return [];
        }
    }

    renderNoticiasTable(noticias) {
        const tbody = document.querySelector('#noticias-table tbody');
        if (!tbody) return;

        tbody.innerHTML = noticias.map(noticia => `
            <tr>
                <td>${noticia.titulo}</td>
                <td>${noticia.autor_nombre || 'N/A'}</td>
                <td>${this.formatDate(noticia.fecha_publicacion)}</td>
                <td>
                    <span class="badge ${noticia.activa ? 'badge-success' : 'badge-secondary'}">
                        ${noticia.activa ? 'Activa' : 'Inactiva'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.editNoticia(${noticia.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="admin.deleteNoticia(${noticia.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== EVENTOS =====
    async loadEventos() {
        try {
            const eventos = await this.getEventos();
            this.data.eventos = eventos;
            this.renderEventosTable(eventos);
        } catch (error) {
            console.error('Error cargando eventos:', error);
            this.showError('Error al cargar los eventos');
        }
    }

    async getEventos(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}eventos.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}eventos.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.eventos : [];
        } catch (error) {
            console.error('Error obteniendo eventos:', error);
            return [];
        }
    }

    renderEventosTable(eventos) {
        const tbody = document.querySelector('#eventos-table tbody');
        if (!tbody) return;

        tbody.innerHTML = eventos.map(evento => `
            <tr>
                <td>${evento.titulo}</td>
                <td>${this.formatDate(evento.fecha)}</td>
                <td>${evento.hora || 'N/A'}</td>
                <td>${evento.lugar || 'N/A'}</td>
                <td>
                    <span class="badge badge-info">${evento.tipo}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.editEvento(${evento.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="admin.deleteEvento(${evento.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== GALERÍA =====
    async loadGaleria() {
        try {
            const galeria = await this.getGaleria();
            this.data.galeria = galeria;
            this.renderGaleriaGrid(galeria);
        } catch (error) {
            console.error('Error cargando galería:', error);
            this.showError('Error al cargar la galería');
        }
    }

    async getGaleria(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}galeria.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}galeria.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.imagenes : [];
        } catch (error) {
            console.error('Error obteniendo galería:', error);
            return [];
        }
    }

    renderGaleriaGrid(imagenes) {
        const grid = document.getElementById('galeria-grid');
        if (!grid) return;

        grid.innerHTML = imagenes.map(imagen => `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="gallery-item">
                    <img src="${imagen.imagen_url}" alt="${imagen.titulo || 'Imagen'}" class="img-fluid">
                    <div class="gallery-overlay">
                        <button class="btn btn-sm btn-primary" onclick="admin.editImagen(${imagen.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="admin.deleteImagen(${imagen.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ===== PRODUCTOS =====
    async loadProductos() {
        try {
            const productos = await this.getProductos();
            this.data.productos = productos;
            this.renderProductosTable(productos);
        } catch (error) {
            console.error('Error cargando productos:', error);
            this.showError('Error al cargar los productos');
        }
    }

    async getProductos(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}productos.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}productos.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.productos : [];
        } catch (error) {
            console.error('Error obteniendo productos:', error);
            return [];
        }
    }

    renderProductosTable(productos) {
        const tbody = document.querySelector('#productos-table tbody');
        if (!tbody) return;

        tbody.innerHTML = productos.map(producto => `
            <tr>
                <td>
                    <img src="${producto.imagen_url || '../assets/images/default-product.jpg'}" 
                         alt="${producto.nombre}" 
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                </td>
                <td>${producto.nombre}</td>
                <td>${producto.precio}€</td>
                <td>${producto.categoria}</td>
                <td>
                    <span class="badge ${producto.stock > 0 ? 'badge-success' : 'badge-danger'}">
                        ${producto.stock}
                    </span>
                </td>
                <td>
                    <span class="badge ${producto.activo ? 'badge-success' : 'badge-secondary'}">
                        ${producto.activo ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.editProducto(${producto.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="admin.deleteProducto(${producto.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== PEDIDOS =====
    async loadPedidos() {
        try {
            const pedidos = await this.getPedidos();
            this.data.pedidos = pedidos;
            this.renderPedidosTable(pedidos);
        } catch (error) {
            console.error('Error cargando pedidos:', error);
            this.showError('Error al cargar los pedidos');
        }
    }

    async getPedidos(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}pedidos.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}pedidos.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.pedidos : [];
        } catch (error) {
            console.error('Error obteniendo pedidos:', error);
            return [];
        }
    }

    renderPedidosTable(pedidos) {
        const tbody = document.querySelector('#pedidos-table tbody');
        if (!tbody) return;

        tbody.innerHTML = pedidos.map(pedido => `
            <tr>
                <td>${pedido.numero_pedido}</td>
                <td>${pedido.email || 'N/A'}</td>
                <td>${pedido.total}€</td>
                <td>
                    <span class="badge badge-${this.getEstadoBadgeClass(pedido.estado)}">
                        ${pedido.estado}
                    </span>
                </td>
                <td>${this.formatDate(pedido.fecha_pedido)}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.viewPedido(${pedido.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success" onclick="admin.updatePedidoEstado(${pedido.id})">
                        <i class="fas fa-check"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== CONTACTOS =====
    async loadContactos() {
        try {
            const contactos = await this.getContactos();
            this.data.contactos = contactos;
            this.renderContactosTable(contactos);
        } catch (error) {
            console.error('Error cargando contactos:', error);
            this.showError('Error al cargar los contactos');
        }
    }

    async getContactos(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}contacto.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}contacto.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.contactos : [];
        } catch (error) {
            console.error('Error obteniendo contactos:', error);
            return [];
        }
    }

    renderContactosTable(contactos) {
        const tbody = document.querySelector('#contactos-table tbody');
        if (!tbody) return;

        tbody.innerHTML = contactos.map(contacto => `
            <tr>
                <td>${contacto.nombre}</td>
                <td>${contacto.email}</td>
                <td>${contacto.asunto || 'Sin asunto'}</td>
                <td>${this.formatDate(contacto.fecha_envio)}</td>
                <td>
                    <span class="badge ${contacto.leido ? 'badge-success' : 'badge-warning'}">
                        ${contacto.leido ? 'Leído' : 'No leído'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.viewContacto(${contacto.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success" onclick="admin.markAsRead(${contacto.id})">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="admin.deleteContacto(${contacto.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== USUARIOS =====
    async loadUsuarios() {
        try {
            const usuarios = await this.getUsuarios();
            this.data.usuarios = usuarios;
            this.renderUsuariosTable(usuarios);
        } catch (error) {
            console.error('Error cargando usuarios:', error);
            this.showError('Error al cargar los usuarios');
        }
    }

    async getUsuarios(limit = null) {
        try {
            const url = limit ? 
                `${ADMIN_CONFIG.API_BASE_URL}usuarios.php?limit=${limit}` : 
                `${ADMIN_CONFIG.API_BASE_URL}usuarios.php`;
            
            const response = await fetch(url);
            const result = await response.json();
            return result.success ? result.data.usuarios : [];
        } catch (error) {
            console.error('Error obteniendo usuarios:', error);
            return [];
        }
    }

    renderUsuariosTable(usuarios) {
        const tbody = document.querySelector('#usuarios-table tbody');
        if (!tbody) return;

        tbody.innerHTML = usuarios.map(usuario => `
            <tr>
                <td>${usuario.nombre}</td>
                <td>${usuario.email}</td>
                <td>
                    <span class="badge ${usuario.rol === 'admin' ? 'badge-danger' : 'badge-info'}">
                        ${usuario.rol}
                    </span>
                </td>
                <td>
                    <span class="badge ${usuario.activo ? 'badge-success' : 'badge-secondary'}">
                        ${usuario.activo ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>${usuario.ultimo_acceso ? this.formatDate(usuario.ultimo_acceso) : 'Nunca'}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="admin.editUsuario(${usuario.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="admin.deleteUsuario(${usuario.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // ===== CONFIGURACIÓN =====
    async loadConfiguracion() {
        try {
            const config = await this.getConfiguracion();
            this.populateConfigForm(config);
        } catch (error) {
            console.error('Error cargando configuración:', error);
            this.showError('Error al cargar la configuración');
        }
    }

    async getConfiguracion() {
        try {
            const response = await fetch(`${ADMIN_CONFIG.API_BASE_URL}admin/config.php`);
            const result = await response.json();
            return result.success ? result.data : {};
        } catch (error) {
            console.error('Error obteniendo configuración:', error);
            return {};
        }
    }

    populateConfigForm(config) {
        Object.entries(config).forEach(([key, value]) => {
            const element = document.getElementById(key);
            if (element) {
                element.value = value;
            }
        });
    }

    // ===== GRÁFICOS =====
    setupCharts() {
        this.setupActivityChart();
        this.setupOrdersChart();
    }

    setupActivityChart() {
        const ctx = document.getElementById('activityChart');
        if (!ctx) return;

        this.charts.activity = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Visitas',
                    data: [12, 19, 3, 5, 2, 3, 9],
                    borderColor: '#8B4513',
                    backgroundColor: 'rgba(139, 69, 19, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    setupOrdersChart() {
        const ctx = document.getElementById('ordersChart');
        if (!ctx) return;

        this.charts.orders = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'Procesando', 'Enviados', 'Entregados'],
                datasets: [{
                    data: [12, 19, 3, 5],
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#007bff',
                        '#28a745'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // ===== ACTIVIDAD RECIENTE =====
    updateRecentActivity(noticias, eventos, contactos) {
        const container = document.getElementById('recent-activity');
        if (!container) return;

        const activities = [
            ...noticias.slice(0, 2).map(n => ({
                icon: 'fas fa-plus text-success',
                text: `Nueva noticia: ${n.titulo}`,
                time: this.getTimeAgo(n.fecha_publicacion)
            })),
            ...eventos.slice(0, 2).map(e => ({
                icon: 'fas fa-calendar text-primary',
                text: `Nuevo evento: ${e.titulo}`,
                time: this.getTimeAgo(e.fecha_creacion)
            })),
            ...contactos.slice(0, 2).map(c => ({
                icon: 'fas fa-envelope text-warning',
                text: `Nuevo mensaje de ${c.nombre}`,
                time: this.getTimeAgo(c.fecha_envio)
            }))
        ].sort((a, b) => new Date(b.time) - new Date(a.time)).slice(0, 5);

        container.innerHTML = activities.map(activity => `
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="activity-content">
                    <p class="mb-1">${activity.text}</p>
                    <small class="text-muted">${activity.time}</small>
                </div>
            </div>
        `).join('');
    }

    // ===== UTILIDADES =====
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(hours / 24);

        if (days > 0) {
            return `Hace ${days} día${days > 1 ? 's' : ''}`;
        } else if (hours > 0) {
            return `Hace ${hours} hora${hours > 1 ? 's' : ''}`;
        } else {
            return 'Hace unos minutos';
        }
    }

    getEstadoBadgeClass(estado) {
        const classes = {
            'pendiente': 'warning',
            'procesando': 'info',
            'enviado': 'primary',
            'entregado': 'success',
            'cancelado': 'danger'
        };
        return classes[estado] || 'secondary';
    }

    // ===== MODALES =====
    showModal(title, content, size = 'lg') {
        const modalHtml = `
            <div class="modal fade" id="adminModal" tabindex="-1">
                <div class="modal-dialog modal-${size}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${content}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remover modal anterior si existe
        const existingModal = document.getElementById('adminModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Añadir nuevo modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('adminModal'));
        modal.show();
    }

    // ===== NOTIFICACIONES =====
    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'danger');
    }

    showWarning(message) {
        this.showNotification(message, 'warning');
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

    // ===== ACCIONES =====
    async handleAction(action, id) {
        switch (action) {
            case 'edit':
                await this.editItem(id);
                break;
            case 'delete':
                await this.deleteItem(id);
                break;
            case 'view':
                await this.viewItem(id);
                break;
        }
    }

    async editItem(id) {
        // Implementar edición según la sección actual
        this.showInfo('Función de edición en desarrollo');
    }

    async deleteItem(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            // Implementar eliminación según la sección actual
            this.showInfo('Función de eliminación en desarrollo');
        }
    }

    async viewItem(id) {
        // Implementar visualización según la sección actual
        this.showInfo('Función de visualización en desarrollo');
    }

    // ===== FILTROS =====
    handleFilter(type, value) {
        // Implementar filtros según el tipo
        this.showInfo('Función de filtros en desarrollo');
    }

    // ===== FORMULARIOS =====
    async handleFormSubmit(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            // Implementar envío de formulario según el tipo
            this.showSuccess('Formulario enviado correctamente');
        } catch (error) {
            console.error('Error enviando formulario:', error);
            this.showError('Error al enviar el formulario');
        }
    }
}

// ===== FUNCIONES GLOBALES =====
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

function showSection(section) {
    if (window.admin) {
        window.admin.showSection(section);
    }
}

function logout() {
    if (window.admin) {
        window.admin.logout();
    }
}

// ===== INICIALIZACIÓN =====
let admin;

document.addEventListener('DOMContentLoaded', function() {
    admin = new AdminManager();
    window.admin = admin;
});