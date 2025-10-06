<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro Interactivo - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 80px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.1) 100%);
            padding: 4rem 0 2rem 0;
        }

        .text-gradient {
            background: linear-gradient(45deg, #dc143c, #8b0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Cinzel', serif;
            font-weight: 700;
        }

        .flipbook-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
        }

        .heyzine-flipbook {
            text-align: center;
        }

        /* Navbar styles */
        .navbar {
            background: linear-gradient(135deg, #dc143c, #8b0000) !important;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }

        .dropdown-menu {
            background-color: white;
            border: 1px solid rgba(0,0,0,.15);
        }

        .dropdown-item {
            color: #212529;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #dc143c;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/prueba-php/public/">
                <i class="bi bi-shield-fill me-2"></i>
                Filá Mariscales
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/prueba-php/public/">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people-fill me-1"></i>Quienes Somos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/prueba-php/public/historia">Historia</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/directiva">Directiva</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/blog">Blog</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/libro">Libro</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/noticias">Noticias</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-tools me-1"></i>Utilidades
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/prueba-php/public/calendario">Calendario</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/musica">Música</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/descargas">Descargas</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/galeria">Galería</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-collection me-1"></i>Recursos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/prueba-php/public/interactiva">Zona Interactiva</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/tienda">Tienda</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/patrocinadores">Patrocinadores</a></li>
                            <li><a class="dropdown-item" href="/prueba-php/public/hermanamientos">Hermanamientos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/prueba-php/public/socios">
                            <i class="bi bi-person-badge me-1"></i>Socios
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/prueba-php/public/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/prueba-php/public/registro">
                            <i class="bi bi-person-plus me-1"></i>Registrarse
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="text-gradient">
                        <i class="bi bi-book me-3"></i>Libro Interactivo
                    </h1>
                    <p class="lead">Descubre la historia y tradiciones de la Filá Mariscales de Caballeros Templarios</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Flipbook Container -->
    <div class="flipbook-container">
        <!-- Flipbook de Heyzine embebido -->
        <div class="heyzine-flipbook">
            <iframe 
                allowfullscreen="allowfullscreen" 
                allow="clipboard-write" 
                scrolling="no" 
                class="fp-iframe" 
                src="https://heyzine.com/flip-book/fcf3fbe7c1.html" 
                style="border: 1px solid lightgray; width: 100%; height: 800px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            </iframe>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>