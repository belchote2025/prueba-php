<?php
// Set the current directory
chdir(dirname(__DIR__));

// Load configuration
require_once 'src/config/config.php';
require_once 'src/config/helpers.php';
require_once 'src/config/admin_credentials.php';

// Aplicar headers de seguridad (si SecurityHelper está disponible)
if (file_exists('src/helpers/SecurityHelper.php')) {
    require_once 'src/helpers/SecurityHelper.php';
    if (class_exists('SecurityHelper')) {
        SecurityHelper::setSecurityHeaders();
    }
}

// SOLUCIÓN DEFINITIVA: Extraer siempre la URL del REQUEST_URI
// Esto garantiza que funcione incluso si el .htaccess falla
if (!isset($_GET['url']) || empty($_GET['url'])) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $requestUri = strtok($requestUri, '?'); // Limpiar query string
    
    // Si REQUEST_URI es /public/index.php o /public/, es la raíz
    if (preg_match('#^/public/index\.php$#', $requestUri) || 
        preg_match('#^/public/?$#', $requestUri)) {
        $_GET['url'] = '';
    }
    // Si REQUEST_URI contiene /public/ seguido de algo
    elseif (preg_match('#/public/(.+)$#', $requestUri, $matches)) {
        $path = $matches[1];
        // Si es index.php, es la raíz
        if ($path === 'index.php') {
            $_GET['url'] = '';
        } 
        // Si es un archivo PHP directo (como debug-url.php), no es una ruta
        elseif (preg_match('#\.php$#', $path)) {
            // Es un archivo PHP directo, no una ruta
            $_GET['url'] = '';
        } else {
            // Es una ruta normal (ej: admin/videos/nuevo)
            $_GET['url'] = $path;
        }
    }
    // Si REQUEST_URI no contiene /public/ pero contiene /prueba-php/public/
    elseif (preg_match('#/prueba-php/public/(.+)$#', $requestUri, $matches)) {
        $path = $matches[1];
        if ($path === 'index.php') {
            $_GET['url'] = '';
        } elseif (preg_match('#\.php$#', $path)) {
            $_GET['url'] = '';
        } else {
            $_GET['url'] = $path;
        }
    }
    // Si REQUEST_URI no contiene /public/
    elseif (strpos($requestUri, '/public/') === false) {
        // Si no es la raíz, extraer la parte después de /
        if ($requestUri !== '/' && $requestUri !== '' && $requestUri !== '/index.php') {
            $_GET['url'] = ltrim($requestUri, '/');
        } else {
            $_GET['url'] = '';
        }
    }
    
    // Si aún no está definido, usar vacío (ruta raíz)
    if (!isset($_GET['url'])) {
        $_GET['url'] = '';
    }
}

// Iniciar sesión para el tracking de visitas
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Registrar visita automáticamente (solo para páginas públicas)
try {
    if (class_exists('VisitTracker')) {
        require_once 'src/helpers/VisitTracker.php';
        $visitTracker = VisitTracker::getInstance();
        $visitTracker->trackVisit();
    }
} catch (Exception $e) {
    error_log("Error al registrar visita: " . $e->getMessage());
}

// Load controllers
require_once 'src/controllers/Controller.php';
require_once 'src/controllers/Pages.php';
require_once 'src/controllers/CartController.php';
require_once 'src/controllers/OrderController.php';
require_once 'src/controllers/PaymentController.php';

// Load AdminController early to ensure functions are available
if (file_exists('src/controllers/AdminController.php')) {
    require_once 'src/controllers/AdminController.php';
} elseif (file_exists('src/controllers/AdminController-new.php')) {
    require_once 'src/controllers/AdminController-new.php';
} elseif (file_exists('src/controllers/AdminController-minimal.php')) {
    require_once 'src/controllers/AdminController-minimal.php';
}

// Parse the URL
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [''];


// Create controller instances
$controller = new Pages();
$cartController = new CartController();
$orderController = new OrderController();
$paymentController = new PaymentController();

// Route the request
if (empty($url[0])) {
    // Default to home page
    $controller->index();
} elseif ($url[0] === 'historia') {
    $controller->historia();
} elseif ($url[0] === 'directiva') {
    $controller->directiva();
} elseif ($url[0] === 'noticias') {
    $controller->noticias();
} elseif ($url[0] === 'blog') {
    if (isset($url[1]) && $url[1] === 'post' && isset($url[2])) {
        $controller->verPost($url[2]);
    } else {
        $controller->blog();
    }
} elseif ($url[0] === 'crear-comentario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->crearComentario();
} elseif ($url[0] === 'evento' && isset($url[1])) {
    $controller->verEvento($url[1]);
} elseif ($url[0] === 'reservar-evento' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->reservarEvento();
} elseif ($url[0] === 'calendario') {
    $controller->calendario();
} elseif ($url[0] === 'galeria') {
    $controller->galeria();
} elseif ($url[0] === 'musica') {
    $controller->musica();
} elseif ($url[0] === 'libro') {
    $controller->libro();
} elseif ($url[0] === 'galeria-multimedia') {
    $controller->galeriaMultimedia();
} elseif ($url[0] === 'descargas') {
    $controller->descargas();
} elseif ($url[0] === 'tienda') {
    $controller->tienda();
} elseif ($url[0] === 'patrocinadores') {
    $controller->patrocinadores();
} elseif ($url[0] === 'hermanamientos') {
    $controller->hermanamientos();
} elseif ($url[0] === 'socios') {
    $controller->socios();
} elseif ($url[0] === 'login') {
    $controller->login();
} elseif ($url[0] === 'registro') {
    $controller->registro();
} elseif ($url[0] === 'contacto') {
    $controller->contacto();
} elseif ($url[0] === 'interactiva') {
    $controller->interactiva();
} elseif ($url[0] === 'profile') {
    $controller->profile();
} elseif ($url[0] === 'update-profile') {
    $controller->updateProfile();
} elseif ($url[0] === 'change-password') {
    $controller->changePassword();
} elseif ($url[0] === 'upload-avatar') {
    $controller->uploadAvatar();
} elseif ($url[0] === 'api') {
    // API routes
    $endpoint = isset($url[1]) ? $url[1] : '';
    
    header('Content-Type: application/json');
    
    if ($endpoint === 'textos') {
        // Endpoint para textos (devuelve JSON vacío por ahora)
        echo json_encode(['success' => true, 'textos' => []]);
        exit;
    } elseif ($endpoint === 'fondos') {
        // Endpoint para fondos (devuelve JSON vacío por ahora)
        echo json_encode(['success' => true, 'fondos' => []]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint no encontrado']);
        exit;
    }
} elseif ($url[0] === 'cart') {
    // Cart routes
    $action = isset($url[1]) ? $url[1] : 'show';
    
    if ($action === 'add') {
        $cartController->addToCart();
    } elseif ($action === 'update') {
        $cartController->updateCart();
    } elseif ($action === 'remove') {
        $cartController->removeFromCart();
    } elseif ($action === 'clear') {
        $cartController->clearCart();
    } elseif ($action === 'info') {
        $cartController->getCartInfo();
    } else {
        $cartController->showCart();
    }
} elseif ($url[0] === 'order') {
    // Order routes
    $action = isset($url[1]) ? $url[1] : 'checkout';
    
    if ($action === 'checkout') {
        $orderController->checkout();
    } elseif ($action === 'process') {
        $orderController->processOrder();
    } elseif ($action === 'validate-coupon') {
        $orderController->validateCoupon();
    } elseif ($action === 'add-wishlist') {
        $orderController->addToWishlist();
    } elseif ($action === 'remove-wishlist') {
        $orderController->removeFromWishlist();
    } elseif ($action === 'clear-wishlist') {
        $orderController->clearWishlist();
    } elseif ($action === 'wishlist') {
        if (isset($url[2]) && $url[2] === 'info') {
            $orderController->getWishlistInfo();
        } else {
            $orderController->getWishlist();
        }
    } elseif ($action === 'confirmation' && isset($url[2])) {
        $orderController->showConfirmation($url[2]);
    } else {
        $orderController->checkout();
    }
} elseif ($url[0] === 'payment') {
    // Payment routes
    $action = isset($url[1]) ? $url[1] : 'stripe';
    
    if ($action === 'stripe') {
        $paymentController->processStripePayment();
    } elseif ($action === 'paypal') {
        $paymentController->processPayPalPayment();
    } elseif ($action === 'bank-transfer') {
        $paymentController->processBankTransfer();
    } else {
        $paymentController->processStripePayment();
    }
} elseif ($url[0] === 'api' && isset($url[1]) && $url[1] === 'video' && isset($url[2]) && $url[2] === 'increment-view' && isset($url[3])) {
    // API endpoint para incrementar visualizaciones de videos
    header('Content-Type: application/json');
    
    try {
        require_once 'src/models/Database.php';
        require_once 'src/models/Video.php';
        
        $videoId = (int)$url[3];
        if ($videoId > 0) {
            $videoModel = new Video();
            $videoModel->incrementViews($videoId);
            echo json_encode(['success' => true, 'message' => 'Visualización registrada']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de video inválido']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al registrar visualización']);
    }
    exit;
} elseif ($url[0] === 'admin') {
    // Admin routes (simple guard + custom login/logout)
    $action = isset($url[1]) ? $url[1] : (isAdminLoggedIn() ? 'dashboard' : 'login');
    
    // DEPURACIÓN: Log del routing
    error_log("Admin routing - Action: " . $action . ", URL array: " . print_r($url, true));

    if ($action === 'login') {
        // Serve admin login page without constructing the controller
        if (file_exists('src/views/admin/login.php')) {
            require 'src/views/admin/login.php';
        } else {
            echo "Error: No se encuentra la vista de login de administrador";
        }
        return;
    }

    if ($action === 'logout') {
        logoutAdmin();
        header('Location: ' . URL_ROOT . '/admin/login');
        exit;
    }

    // Any other admin route requires authentication
    if (!isAdminLoggedIn()) {
        // Verificar si los headers ya se enviaron
        if (headers_sent()) {
            // Si los headers ya se enviaron, usar JavaScript para redirigir
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Redirigiendo...</title>';
            echo '<script>window.location.href = "' . URL_ROOT . '/admin/login";</script>';
            echo '</head><body><p>Redirigiendo al login...</p></body></html>';
        } else {
            header('Location: ' . URL_ROOT . '/admin/login');
        }
        exit;
    }

    // AdminController should already be loaded
    if (class_exists('AdminController')) {
        try {
            $adminController = new AdminController();
            
            // Verificar si es una acción de videos primero (para evitar conflictos con el routing genérico)
            // NO hacer nada aquí si es videos, se maneja más abajo
            if ($action === 'videos') {
                // Saltar este bloque, se maneja más abajo en el bloque específico de videos
                // No hacer return aquí, dejar que continúe al bloque de videos
            } elseif (method_exists($adminController, $action)) {
                call_user_func_array([$adminController, $action], array_slice($url, 2));
                return; // IMPORTANTE: Salir después de ejecutar
            } elseif ($action === 'mensajes' && isset($url[2]) && isset($url[3])) {
                // Manejar acciones de mensajes: /admin/mensajes/view/filename, /admin/mensajes/download/filename, etc.
                $subAction = $url[2];
                $filename = $url[3];
                
                if ($subAction === 'view') {
                    $adminController->viewMessage($filename);
                } elseif ($subAction === 'download') {
                    $adminController->downloadMessage($filename);
                } elseif ($subAction === 'delete') {
                    $adminController->deleteMessage($filename);
                } else {
                    $adminController->mensajes();
                }
            } elseif ($action === 'crearUsuario') {
                // Redirigir al formulario directo que funciona
                header('Location: ' . URL_ROOT . '/admin/crear-usuario.php');
                exit;
            } elseif ($action === 'nuevoEvento') {
                // Redirigir al formulario directo que funciona
                header('Location: ' . URL_ROOT . '/admin/nuevo-evento.php');
                exit;
            } elseif ($action === 'nuevo-producto') {
                // Manejar la ruta nuevo-producto
                $adminController->nuevoProducto();
            } elseif ($action === 'productos') {
                // Manejar la ruta productos
                $adminController->productos();
            } elseif ($action === 'editar-producto') {
                // Manejar la ruta editar-producto
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->editarProducto($id);
            } elseif ($action === 'eliminar-producto') {
                // Manejar la ruta eliminar-producto
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->eliminarProducto($id);
            } elseif ($action === 'upload-product-photo') {
                // Manejar la subida de fotos de productos
                $adminController->uploadProductPhoto();
            } elseif ($action === 'nueva-noticia') {
                // Manejar la ruta nueva-noticia
                $adminController->nuevaNoticia();
            } elseif ($action === 'noticias') {
                // Manejar la ruta noticias
                $adminController->noticias();
            } elseif ($action === 'editar-noticia') {
                // Manejar la ruta editar-noticia
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->editarNoticia($id);
            } elseif ($action === 'ver-noticia') {
                // Manejar la ruta ver-noticia
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->verNoticia($id);
            } elseif ($action === 'eliminar-noticia') {
                // Manejar la ruta eliminar-noticia
                $id = isset($url[2]) ? $url[2] : null;
                $adminController->eliminarNoticia($id);
            } elseif ($action === 'buscar-noticias') {
                // Manejar la ruta buscar-noticias
                $adminController->buscarNoticias();
            } elseif ($action === 'cambiar-estado-noticia') {
                // Manejar la ruta cambiar-estado-noticia
                $id = isset($url[2]) ? $url[2] : null;
                $estado = isset($url[3]) ? $url[3] : null;
                $adminController->cambiarEstadoNoticia($id, $estado);
            } 
            
            // BLOQUE ESPECÍFICO DE VIDEOS - Debe estar fuera del elseif anterior
            if ($action === 'videos') {
                // Manejar rutas de videos
                $subAction = $url[2] ?? null;
                $id = $url[3] ?? null;
                
                // DEPURACIÓN
                error_log("Routing videos - subAction: " . ($subAction ?? 'null') . ", id: " . ($id ?? 'null'));
                
                if ($subAction === 'nuevo') {
                    error_log("Llamando a nuevoVideo()");
                    $adminController->nuevoVideo();
                    return;
                } elseif ($subAction === 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    error_log("Llamando a guardarVideo()");
                    $adminController->guardarVideo();
                    return;
                } elseif ($subAction === 'editar' && $id) {
                    // Asegurar que $id sea un entero
                    $id = (int)$id;
                    error_log("Llamando a editarVideo($id)");
                    $adminController->editarVideo($id);
                    return;
                } elseif ($subAction === 'actualizar' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = (int)$id;
                    error_log("Llamando a actualizarVideo($id)");
                    $adminController->actualizarVideo($id);
                    return;
                } elseif ($subAction === 'eliminar' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = (int)$id;
                    error_log("Llamando a eliminarVideo($id)");
                    $adminController->eliminarVideo($id);
                    return;
                } else {
                    // Si no hay subAction o es numérico, es una página
                    $page = isset($url[2]) && is_numeric($url[2]) ? (int)$url[2] : 1;
                    error_log("Llamando a videos($page) - Sin subAction");
                    $adminController->videos($page);
                    return; // IMPORTANTE: Salir después de ejecutar videos
                }
            }
            
            // Continuar con otras acciones si no es videos
            if ($action === 'cuotas') {
                // Manejar rutas de cuotas
                if (isset($url[2]) && $url[2] === 'nueva') {
                    $adminController->nuevaCuota();
                } elseif (isset($url[2]) && $url[2] === 'editar' && isset($url[3])) {
                    $adminController->editarCuota($url[3]);
                } elseif (isset($url[2]) && $url[2] === 'marcar-pagada' && isset($url[3])) {
                    $adminController->marcarCuotaPagada($url[3]);
                } elseif (isset($url[2]) && $url[2] === 'eliminar' && isset($url[3])) {
                    $adminController->eliminarCuota($url[3]);
                } else {
                    $adminController->cuotas();
                }
            } elseif ($action === 'gestion-galeria') {
                // Manejar la ruta gestión de galería
                if (file_exists('src/views/admin/gestion-galeria.php')) {
                    require 'src/views/admin/gestion-galeria.php';
                } else {
                    echo "Error: No se encuentra la vista de gestión de galería";
                }
                return;
            } elseif ($action === 'flipbooks') {
                // Manejar la ruta gestión de flipbooks
                if (file_exists('src/views/admin/flipbooks.php')) {
                    require 'src/views/admin/flipbooks.php';
                } else {
                    echo "Error: No se encuentra la vista de gestión de flipbooks";
                }
                return;
            } else {
                $adminController->dashboard();
            }
        } catch (Exception $e) {
            // Habilitar visualización de errores para depuración
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            error_log("EXCEPCIÓN en routing admin: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
            echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
            echo "</head><body><div class='container mt-5'>";
            echo "<div class='alert alert-danger'>";
            echo "<h4>Error en el routing de administración</h4>";
            echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>Archivo:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
            echo "<details><summary>Stack Trace</summary><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></details>";
            echo "</div>";
            echo "<a href='" . URL_ROOT . "/admin/dashboard' class='btn btn-primary'>Volver al Dashboard</a>";
            echo "</div></body></html>";
        } catch (Error $e) {
            // Capturar errores fatales también
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            error_log("ERROR FATAL en routing admin: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error Fatal</title>";
            echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
            echo "</head><body><div class='container mt-5'>";
            echo "<div class='alert alert-danger'>";
            echo "<h4>Error Fatal en el routing de administración</h4>";
            echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>Archivo:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
            echo "<details><summary>Stack Trace</summary><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></details>";
            echo "</div>";
            echo "<a href='" . URL_ROOT . "/admin/dashboard' class='btn btn-primary'>Volver al Dashboard</a>";
            echo "</div></body></html>";
        }
    } else {
        echo "Error: No se puede cargar el controlador de administrador";
    }
} else {
    // Page not found
    $controller->notFound();
}
