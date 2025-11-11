<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Panel de Administración' ?> - Filá Mariscales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link href="<?= URL_ROOT ?>/assets/css/admin.css" rel="stylesheet">
    <!-- Personalización dinámica (CSS generado desde la base de datos) -->
    <link rel="stylesheet" href="<?= URL_ROOT ?>/aplicar-personalizacion.php?v=<?php echo isset($_SESSION['personalizacion_version']) ? $_SESSION['personalizacion_version'] : time(); ?>">
    <?php if (isset($data['styles'])): ?>
        <?php foreach ($data['styles'] as $style): ?>
            <link href="<?= $style ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">
                <h4>Filá Mariscales</h4>
                <p class="mb-0">Panel de Administración</p>
            </div>
            <div class="list-group list-group-flush">
                <!-- Principal -->
                <a href="<?= URL_ROOT ?>/admin" class="list-group-item list-group-item-action bg-dark text-white <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || $_SERVER['REQUEST_URI'] == URL_ROOT . '/admin' || $_SERVER['REQUEST_URI'] == URL_ROOT . '/admin/') ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                
                <!-- Separador: Contenido -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-file-alt me-1"></i> Contenido
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/noticias" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/noticias') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/nueva-noticia') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/editar-noticia') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-newspaper me-2"></i>Noticias / Blog
                </a>
                <a href="<?= URL_ROOT ?>/admin/eventos" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/eventos') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/events') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/nuevo-evento') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-calendar-alt me-2"></i>Eventos
                </a>
                <a href="<?= URL_ROOT ?>/admin/galeria" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/galeria') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/gallery') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-images me-2"></i>Galería
                </a>
                <a href="<?= URL_ROOT ?>/admin/videos" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/videos') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-video me-2"></i>Videos
                </a>
                <a href="<?= URL_ROOT ?>/admin/documentos" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/documentos') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-file-alt me-2"></i>Documentos
                </a>
                <a href="<?= URL_ROOT ?>/admin/flipbooks" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/flipbooks') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-book me-2"></i>Flipbooks
                </a>
                
                <!-- Separador: Usuarios -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-users me-1"></i> Usuarios
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/usuarios" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/usuarios') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/crearUsuario') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/editarUsuario') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-users me-2"></i>Gestión de Usuarios
                </a>
                <a href="<?= URL_ROOT ?>/admin/cuotas" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/cuotas') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-credit-card me-2"></i>Cuotas de Socios
                </a>
                
                <!-- Separador: Comercio -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-shopping-cart me-1"></i> Comercio
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/productos" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/productos') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/nuevo-producto') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/editar-producto') !== false || strpos($_SERVER['REQUEST_URI'], '/admin/tienda') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-shopping-cart me-2"></i>Tienda Online
                </a>
                
                <!-- Separador: Comunicación -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-comments me-1"></i> Comunicación
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/mensajes" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/mensajes') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-envelope me-2"></i>Mensajes
                    <?php if (isset($data['messagesCount']) && $data['messagesCount'] > 0): ?>
                        <span class="badge bg-danger float-end"><?= $data['messagesCount'] ?></span>
                    <?php endif; ?>
                </a>
                
                <!-- Separador: Análisis -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-chart-bar me-1"></i> Análisis
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/visitas" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/visitas') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-chart-line me-2"></i>Analíticas / Visitas
                </a>
                
                <!-- Separador: Configuración -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-cog me-1"></i> Configuración
                </div>
                
                <a href="<?= URL_ROOT ?>/admin/personalizacion" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/personalizacion') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-palette me-2"></i>Personalización
                </a>
                <a href="<?= URL_ROOT ?>/admin/settings" class="list-group-item list-group-item-action bg-dark text-white <?= (strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false) ? 'active bg-primary' : '' ?>">
                    <i class="fas fa-cog me-2"></i>Ajustes Generales
                </a>
                
                <!-- Separador: Acciones -->
                <div class="list-group-item bg-dark text-white-50 small px-3 py-2 mt-3" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-external-link-alt me-1"></i> Acciones
                </div>
                
                <a href="<?= URL_ROOT ?>/" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-home me-2"></i>Volver al Sitio
                </a>
                <a href="<?= URL_ROOT ?>/auth/logout" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-link" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown ms-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['user_name'] ?? 'Admin' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= URL_ROOT ?>/profile"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= URL_ROOT ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-3">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['flash_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php 
                    // Clear flash message after displaying
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>

                <?php 
                // Include the content view
                $content = $content ?? 'admin/dashboard';
                $this->view($content, $data);
                ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom scripts -->
    <script>
        // Toggle sidebar
        document.getElementById('menu-toggle').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('wrapper').classList.toggle('toggled');
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    <?php if (isset($data['scripts'])): ?>
        <?php foreach ($data['scripts'] as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
