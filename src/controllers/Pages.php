<?php
class Pages extends Controller {
    private $userModel;
    private $eventModel;

    public function __construct() {
        // Cargar el modelo de usuario para autenticación
        $this->userModel = $this->model('User');
        
        // Cargar el modelo de eventos
        if (class_exists('Event')) {
            $this->eventModel = $this->model('Event');
        }
        
        // Registrar visita automáticamente para páginas públicas
        $this->trackPageVisit();
    }
    
    /**
     * Registrar visita de la página actual
     */
    private function trackPageVisit() {
        try {
            if (class_exists('VisitTracker')) {
                require_once __DIR__ . '/../helpers/VisitTracker.php';
                $visitTracker = VisitTracker::getInstance();
                $visitTracker->trackVisit();
            }
        } catch (Exception $e) {
            error_log("Error al registrar visita en Pages: " . $e->getMessage());
        }
    }

    // Página de inicio
    public function index() {
        // Cargar imágenes del carrusel dinámicamente
        $carouselImages = $this->getCarouselImages();
        
        // Cargar imágenes de la galería dinámicamente
        $galleryImages = $this->getGalleryImages();
        
        // Cargar eventos próximos dinámicamente
        $upcomingEvents = $this->getUpcomingEvents();
        
        $data = [
            'title' => 'Inicio',
            'description' => 'Bienvenidos a la Filá Mariscales de Caballeros Templarios de Elche',
            'carousel_images' => $carouselImages,
            'upcoming_events' => $upcomingEvents,
            'gallery' => $galleryImages
        ];
        $this->view('pages/home', $data);
    }

    // Página de blog
    public function blog() {
        // Modo diagnóstico si se pasa ?debug=1
        if (isset($_GET['debug']) && $_GET['debug'] == '1') {
            $this->debugBlog();
            return;
        }
        
        try {
            // Cargar modelo de noticias
            $newsModel = $this->model('News');
            
            // Obtener página actual
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            
            $perPage = 9; // 9 posts por página (3 columnas x 3 filas)
            
            // Obtener noticias publicadas
            $news = [];
            $totalNews = 0;
            $totalPages = 0;
            
            try {
                $news = $newsModel->getPublishedNews($page, $perPage);
                $totalNews = $newsModel->getNewsCountByStatus('publicado');
                $totalPages = $totalNews > 0 ? ceil($totalNews / $perPage) : 0;
            } catch (Exception $e) {
                error_log("Error en blog - getPublishedNews: " . $e->getMessage());
                // Continuar con arrays vacíos si hay error
            }
            
            // Obtener post destacado (el más reciente)
            $featuredNews = !empty($news) ? $news[0] : null;
            
            // Obtener categorías (simulado por ahora, se puede mejorar después)
            $categories = [
                ['name' => 'Fiestas', 'count' => 0, 'icon' => 'bi-people'],
                ['name' => 'Cultura', 'count' => 0, 'icon' => 'bi-book'],
                ['name' => 'Eventos', 'count' => 0, 'icon' => 'bi-calendar-event'],
                ['name' => 'Historia', 'count' => 0, 'icon' => 'bi-hourglass-split'],
                ['name' => 'Actualidad', 'count' => 0, 'icon' => 'bi-newspaper'],
                ['name' => 'Logros', 'count' => 0, 'icon' => 'bi-trophy']
            ];
            
            $data = [
                'title' => 'Blog',
                'description' => 'Artículos y publicaciones de la Filá Mariscales',
                'news' => $news,
                'featured_news' => $featuredNews,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_news' => $totalNews,
                'categories' => $categories
            ];
            
            $this->view('pages/blog', $data);
        } catch (Exception $e) {
            error_log("Error fatal en blog(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Mostrar error amigable
            $data = [
                'title' => 'Error',
                'description' => 'Error al cargar el blog',
                'news' => [],
                'featured_news' => null,
                'current_page' => 1,
                'total_pages' => 0,
                'total_news' => 0,
                'categories' => [],
                'error' => 'Error al cargar las noticias. Por favor, inténtalo más tarde.'
            ];
            
            $this->view('pages/blog', $data);
        }
    }
    
    // Diagnóstico del blog
    private function debugBlog() {
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Diagnóstico Blog</title>';
        echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}';
        echo '.container{max-width:800px;margin:0 auto;background:white;padding:20px;border-radius:8px;}';
        echo '.success{color:#28a745;background:#d4edda;padding:10px;margin:10px 0;border-radius:4px;}';
        echo '.error{color:#dc3545;background:#f8d7da;padding:10px;margin:10px 0;border-radius:4px;}';
        echo '.info{color:#004085;background:#d1ecf1;padding:10px;margin:10px 0;border-radius:4px;}';
        echo 'pre{background:#f8f9fa;padding:15px;border-radius:4px;overflow-x:auto;}</style></head><body>';
        echo '<div class="container"><h1>🔍 Diagnóstico del Blog</h1>';
        
        // 1. Verificar configuración
        echo '<h2>1. Verificar Configuración</h2>';
        $configFile = dirname(dirname(__DIR__)) . '/src/config/config.php';
        if (file_exists($configFile)) {
            echo '<div class="success">✓ config.php existe</div>';
            require_once $configFile;
        } else {
            echo '<div class="error">✗ config.php NO existe</div>';
            echo '</div></body></html>';
            exit;
        }
        
        // 2. Verificar modelo News
        echo '<h2>2. Verificar Modelo News</h2>';
        $newsModelFile = dirname(dirname(__DIR__)) . '/src/models/News.php';
        if (file_exists($newsModelFile)) {
            echo '<div class="success">✓ News.php existe</div>';
            require_once dirname(dirname(__DIR__)) . '/src/models/Database.php';
            require_once $newsModelFile;
        } else {
            echo '<div class="error">✗ News.php NO existe</div>';
            echo '</div></body></html>';
            exit;
        }
        
        // 3. Verificar BD
        echo '<h2>3. Verificar Base de Datos</h2>';
        try {
            if (defined('DB_HOST') && defined('DB_NAME')) {
                echo '<div class="info">DB_HOST: ' . DB_HOST . '</div>';
                echo '<div class="info">DB_NAME: ' . DB_NAME . '</div>';
                $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo '<div class="success">✓ Conexión a BD exitosa</div>';
            } else {
                echo '<div class="error">✗ Constantes de BD no definidas</div>';
                echo '</div></body></html>';
                exit;
            }
        } catch (Exception $e) {
            echo '<div class="error">✗ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '</div></body></html>';
            exit;
        }
        
        // 4. Verificar tabla noticias
        echo '<h2>4. Verificar Tabla noticias</h2>';
        try {
            $stmt = $pdo->query("DESCRIBE noticias");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo '<div class="success">✓ Tabla noticias existe</div>';
            echo '<div class="info">Columnas:</div><ul>';
            $hasEstado = false;
            $hasActiva = false;
            foreach ($columns as $col) {
                echo '<li><strong>' . htmlspecialchars($col['Field']) . '</strong> (' . htmlspecialchars($col['Type']) . ')</li>';
                if ($col['Field'] === 'estado') $hasEstado = true;
                if ($col['Field'] === 'activa') $hasActiva = true;
            }
            echo '</ul>';
            echo '<div class="info">Estado: ' . ($hasEstado ? '✓' : '✗') . ' | Activa: ' . ($hasActiva ? '✓' : '✗') . '</div>';
        } catch (Exception $e) {
            echo '<div class="error">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        
        // 5. Probar modelo News
        echo '<h2>5. Probar Modelo News</h2>';
        try {
            $newsModel = new News();
            echo '<div class="success">✓ Instancia de News creada</div>';
            
            // Probar getPublishedNews
            try {
                $news = $newsModel->getPublishedNews(1, 5);
                echo '<div class="success">✓ getPublishedNews() ejecutado</div>';
                echo '<div class="info">Posts encontrados: ' . count($news) . '</div>';
            } catch (Exception $e) {
                echo '<div class="error">✗ Error en getPublishedNews(): ' . htmlspecialchars($e->getMessage()) . '</div>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            }
            
            // Probar getNewsCountByStatus
            try {
                $count = $newsModel->getNewsCountByStatus('publicado');
                echo '<div class="success">✓ getNewsCountByStatus() ejecutado</div>';
                echo '<div class="info">Total publicados: ' . $count . '</div>';
            } catch (Exception $e) {
                echo '<div class="error">✗ Error en getNewsCountByStatus(): ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="error">✗ Error al crear News: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        
        // 6. Verificar vistas
        echo '<h2>6. Verificar Vistas</h2>';
        $blogView = dirname(dirname(__DIR__)) . '/src/views/pages/blog.php';
        if (file_exists($blogView)) {
            echo '<div class="success">✓ blog.php existe</div>';
        } else {
            echo '<div class="error">✗ blog.php NO existe</div>';
        }
        
        echo '<hr><p><a href="' . URL_ROOT . '/blog">← Volver al blog (sin debug)</a></p>';
        echo '</div></body></html>';
        exit;
    }
    
    // Ver post individual del blog
    public function verPost($id = null) {
        if (!$id) {
            redirect('blog');
        }
        
        $newsModel = $this->model('News');
        $post = $newsModel->getNewsById($id);
        
        if (!$post || ($post->estado ?? 'borrador') !== 'publicado') {
            // Si no existe o no está publicado, redirigir al blog
            redirect('blog');
        }
        
        // Incrementar vistas (si existe el campo)
        // Esto se puede hacer con una actualización directa
        
        // Obtener posts relacionados (últimos 3 posts)
        $relatedPosts = $newsModel->getPublishedNews(1, 3);
        // Filtrar el post actual
        $relatedPosts = array_filter($relatedPosts, function($item) use ($id) {
            return ($item->id ?? $item['id'] ?? null) != $id;
        });
        $relatedPosts = array_slice($relatedPosts, 0, 3);
        
        // Cargar comentarios aprobados
        $comments = [];
        $commentCount = 0;
        if (class_exists('Comment')) {
            $commentModel = $this->model('Comment');
            $comments = $commentModel->getCommentsWithReplies($id, true);
            $commentCount = $commentModel->countCommentsByNewsId($id, true);
        }
        
        $data = [
            'title' => $post->titulo ?? $post['titulo'] ?? 'Post',
            'description' => substr(strip_tags($post->contenido ?? $post['contenido'] ?? ''), 0, 160),
            'post' => $post,
            'related_posts' => $relatedPosts,
            'comments' => $comments,
            'comment_count' => $commentCount,
            'user_logged_in' => isset($_SESSION['user_id'])
        ];
        
        $this->view('pages/blog-post', $data);
    }
    
    // Crear comentario (AJAX)
    public function crearComentario() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        
        // Validar CSRF token
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'Token de seguridad inválido']);
            exit;
        }
        
        $noticiaId = intval($_POST['noticia_id'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comentarioPadreId = !empty($_POST['comentario_padre_id']) ? intval($_POST['comentario_padre_id']) : null;
        
        // Validaciones
        if (empty($noticiaId) || empty($comentario) || empty($nombre) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email inválido']);
            exit;
        }
        
        if (strlen($comentario) < 10) {
            echo json_encode(['success' => false, 'message' => 'El comentario debe tener al menos 10 caracteres']);
            exit;
        }
        
        // Verificar que la noticia existe
        $newsModel = $this->model('News');
        $noticia = $newsModel->getNewsById($noticiaId);
        if (!$noticia) {
            echo json_encode(['success' => false, 'message' => 'Noticia no encontrada']);
            exit;
        }
        
        // Crear comentario
        if (class_exists('Comment')) {
            $commentModel = $this->model('Comment');
            $data = [
                'noticia_id' => $noticiaId,
                'usuario_id' => $_SESSION['user_id'] ?? null,
                'nombre' => $nombre,
                'email' => $email,
                'comentario' => $comentario,
                'comentario_padre_id' => $comentarioPadreId,
                'aprobado' => false // Requiere aprobación del admin
            ];
            
            $commentId = $commentModel->createComment($data);
            
            if ($commentId) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Comentario enviado. Será publicado después de la aprobación del administrador.',
                    'comment_id' => $commentId
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el comentario']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Sistema de comentarios no disponible']);
        }
        exit;
    }

    // Página de calendario
    public function calendario() {
        // Cargar eventos dinámicamente
        $events = $this->getAllEvents();
        
        $data = [
            'title' => 'Calendario',
            'description' => 'Calendario de eventos de la Filá Mariscales',
            'events' => $events
        ];
        $this->view('pages/calendario', $data);
    }

    // Página de descargas
    public function descargas() {
        // Cargar documentos dinámicamente
        $documents = [];
        $categories = [];
        $error = null;
        
        try {
            if (class_exists('Document')) {
                $documentModel = new Document();
                
                // Intentar crear la tabla si no existe (puede fallar si no hay permisos, pero no es crítico)
                try {
                    $documentModel->createTable();
                } catch (Exception $e) {
                    // Si falla la creación, puede que la tabla ya exista, continuamos
                    error_log("Warning: No se pudo crear tabla documentos (puede que ya exista): " . $e->getMessage());
                }
                
                $page = $_GET['page'] ?? 1;
                $perPage = 12;
                $category = $_GET['category'] ?? null;
                $search = $_GET['search'] ?? null;
                
                // Intentar obtener documentos
                if ($search) {
                    $documents = $documentModel->searchDocuments($search, $page, $perPage);
                } elseif ($category) {
                    $documents = $documentModel->getDocumentsByCategory($category, $page, $perPage);
                } else {
                    $documents = $documentModel->getAllDocuments($page, $perPage);
                }
                
                // Si documents es null o false, establecer como array vacío
                if (!is_array($documents)) {
                    $documents = [];
                }
                
                // Obtener categorías
                try {
                    $categories = $documentModel->getCategories();
                    if (!is_array($categories)) {
                        $categories = [];
                    }
                } catch (Exception $e) {
                    $categories = [];
                    error_log("Error al cargar categorías: " . $e->getMessage());
                }
            } else {
                $error = "La clase Document no está disponible";
            }
        } catch (Exception $e) {
            $error = "Error al cargar documentos: " . $e->getMessage();
            error_log($error);
        } catch (Throwable $e) {
            $error = "Error crítico al cargar documentos: " . $e->getMessage();
            error_log($error);
        }
        
        $data = [
            'title' => 'Descargas',
            'description' => 'Documentos y archivos para descargar',
            'documents' => $documents,
            'categories' => $categories,
            'error' => $error
        ];
        
        $this->view('pages/descargas', $data);
    }

    // Descargar documento
    public function descargarDocumento($id) {
        try {
            if (!class_exists('Document')) {
                $this->redirect('/descargas');
                return;
            }
            
            $documentModel = new Document();
            $document = $documentModel->getDocumentById($id);
            
            if (!$document || !file_exists($document->archivo_ruta)) {
                $this->redirect('/descargas');
                return;
            }
            
            // Incrementar contador de descargas
            $documentModel->incrementDownloads($id);
            
            // Configurar headers para descarga
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $document->archivo_nombre . '"');
            header('Content-Length: ' . filesize($document->archivo_ruta));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            // Leer y enviar el archivo
            readfile($document->archivo_ruta);
            exit;
            
        } catch (Exception $e) {
            error_log("Error al descargar documento: " . $e->getMessage());
            $this->redirect('/descargas');
        }
    }

    // Página de directiva
    public function directiva() {
        $data = [
            'title' => 'Directiva',
            'description' => 'Conoce a los miembros de la junta directiva de la Filá Mariscales'
        ];
        $this->view('pages/directiva', $data);
    }

    // Página de galería
    public function galeria() {
        // Cargar imágenes de la galería dinámicamente
        $galleryImages = $this->getGalleryImages();
        
        $data = [
            'title' => 'Galería',
            'description' => 'Galería de imágenes de la Filá Mariscales',
            'gallery_images' => $galleryImages
        ];
        $this->view('pages/galeria', $data);
    }

    // Página de hermanamientos
    public function hermanamientos() {
        $data = [
            'title' => 'Hermanamientos',
            'description' => 'Nuestras relaciones con otras filás y entidades'
        ];
        $this->view('pages/hermanamientos', $data);
    }

    // Página de eventos
    public function eventos() {
        // Cargar eventos dinámicamente
        $events = $this->getAllEvents();
        
        $data = [
            'title' => 'Eventos',
            'description' => 'Todos los eventos y actividades de la Filá Mariscales',
            'events' => $events
        ];
        $this->view('pages/eventos', $data);
    }
    
    // Ver detalle de evento y reservar
    public function verEvento($id = null) {
        if (!$id) {
            redirect('eventos');
        }
        
        $eventModel = $this->model('Event');
        $event = $eventModel->getEventById($id);
        
        if (!$event || !$event->activo) {
            redirect('eventos');
        }
        
        // Cargar reservas del usuario si está logueado
        $userReservations = [];
        $canReserve = false;
        $plazasDisponibles = null;
        
        if (class_exists('EventReservation')) {
            $reservationModel = $this->model('EventReservation');
            
            // Verificar disponibilidad
            if ($event->inscripciones_abiertas && $event->capacidad) {
                $reservadas = $reservationModel->countConfirmedReservations($id);
                $plazasDisponibles = max(0, $event->capacidad - $reservadas);
                $canReserve = $plazasDisponibles > 0;
            } elseif ($event->inscripciones_abiertas) {
                $canReserve = true; // Sin límite de capacidad
            }
            
            // Si el usuario está logueado, obtener sus reservas
            if (isset($_SESSION['user_id'])) {
                $userReservations = $reservationModel->getReservationsByUserId($_SESSION['user_id']);
                // Filtrar solo las de este evento
                $userReservations = array_filter($userReservations, function($res) use ($id) {
                    return ($res->evento_id ?? $res['evento_id'] ?? 0) == $id;
                });
            }
        }
        
        $data = [
            'title' => $event->titulo ?? 'Evento',
            'description' => substr(strip_tags($event->descripcion ?? ''), 0, 160),
            'event' => $event,
            'can_reserve' => $canReserve,
            'plazas_disponibles' => $plazasDisponibles,
            'user_reservations' => $userReservations,
            'user_logged_in' => isset($_SESSION['user_id'])
        ];
        
        $this->view('pages/evento-detalle', $data);
    }
    
    // Procesar reserva de evento
    public function reservarEvento() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('eventos');
        }
        
        // Validar CSRF
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            setFlashMessage('error', 'Token de seguridad inválido');
            redirect('eventos');
        }
        
        $eventoId = intval($_POST['evento_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $numPersonas = intval($_POST['num_personas'] ?? 1);
        $notas = trim($_POST['notas'] ?? '');
        
        // Validaciones
        if (empty($eventoId) || empty($nombre) || empty($email)) {
            setFlashMessage('error', 'Todos los campos obligatorios deben completarse');
            redirect('evento/' . $eventoId);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('error', 'Email inválido');
            redirect('evento/' . $eventoId);
        }
        
        if ($numPersonas < 1 || $numPersonas > 10) {
            setFlashMessage('error', 'El número de personas debe estar entre 1 y 10');
            redirect('evento/' . $eventoId);
        }
        
        // Verificar que el evento existe y permite reservas
        $eventModel = $this->model('Event');
        $event = $eventModel->getEventById($eventoId);
        
        if (!$event || !$event->activo || !$event->inscripciones_abiertas) {
            setFlashMessage('error', 'Este evento no permite reservas');
            redirect('eventos');
        }
        
        // Verificar disponibilidad
        if (class_exists('EventReservation')) {
            $reservationModel = $this->model('EventReservation');
            
            if ($event->capacidad && !$reservationModel->checkAvailability($eventoId, $numPersonas)) {
                setFlashMessage('error', 'No hay suficientes plazas disponibles');
                redirect('evento/' . $eventoId);
            }
            
            // Crear reserva
            $reservationData = [
                'evento_id' => $eventoId,
                'usuario_id' => $_SESSION['user_id'] ?? null,
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono ?: null,
                'num_personas' => $numPersonas,
                'estado' => 'pendiente',
                'notas' => $notas ?: null
            ];
            
            $reservationId = $reservationModel->createReservation($reservationData);
            
            if ($reservationId) {
                // Obtener la reserva creada para mostrar el código
                $reservation = $reservationModel->getReservationById($reservationId);
                
                setFlashMessage('success', 'Reserva realizada correctamente. Tu código de reserva es: ' . ($reservation->codigo_reserva ?? ''));
                redirect('evento/' . $eventoId . '?reserva=' . $reservationId);
            } else {
                setFlashMessage('error', 'Error al crear la reserva. Por favor, inténtalo de nuevo.');
                redirect('evento/' . $eventoId);
            }
        } else {
            setFlashMessage('error', 'Sistema de reservas no disponible');
            redirect('eventos');
        }
    }

    // Página del libro de la filá
    public function libro() {
        $data = [
            'title' => 'Libro de la Filá',
            'description' => 'Historia y anécdotas de la Filá Mariscales'
        ];
        $this->view('pages/libro', $data);
    }

    // Página de galería multimedia
    public function galeriaMultimedia() {
        // Cargar videos desde la base de datos
        $videos = [];
        $videoCount = 0;
        
        if (class_exists('Video')) {
            $videoModel = $this->model('Video');
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            // En público solo mostramos videos activos
            $videos = $videoModel->getActiveVideos($page, 12);
            // Contar total (simplificado, se puede mejorar)
            $videoCount = count($videos);
        }
        
        $data = [
            'title' => 'Galería Multimedia',
            'description' => 'Videos de actuaciones y eventos de la Filá Mariscales',
            'videos' => $videos,
            'video_count' => $videoCount
        ];
        $this->view('pages/galeria-multimedia', $data);
    }

    // Página de música
    public function musica() {
        $data = [
            'title' => 'Himno y Música',
            'description' => 'Escucha nuestro himno y otras piezas musicales de la Filá Mariscales'
        ];
        $this->view('pages/musica', $data);
    }

    // Página de noticias
    public function noticias() {
        // Cargar noticias reales usando el modelo News
        $news = $this->getPublishedNews();
        
        $data = [
            'title' => 'Noticias',
            'description' => 'Últimas noticias y actualizaciones de la Filá Mariscales',
            'news' => $news
        ];
        $this->view('pages/noticias', $data);
    }

    // Página de patrocinadores
    public function patrocinadores() {
        $data = [
            'title' => 'Patrocinadores',
            'description' => 'Nuestros patrocinadores y colaboradores'
        ];
        $this->view('pages/patrocinadores', $data);
    }

    // Página de socios
    public function socios() {
        $data = [
            'title' => 'Zona de Socios',
            'description' => 'Área exclusiva para socios de la Filá Mariscales'
        ];
        
        // Verificar si el usuario está logueado
        if (isLoggedIn()) {
            // Obtener datos del usuario logueado
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            
            if ($user) {
                // Preparar datos del socio
                $socio_data = [
                    'nombre' => $user->nombre,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'numero_socio' => 'SOC-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'categoria' => ucfirst($user->rol),
                    'fecha_ingreso' => date('d/m/Y', strtotime($user->fecha_registro)),
                    'cuota_al_dia' => $user->activo == 1,
                    'ultima_cuota' => date('m/Y'),
                    'proximo_evento' => 'Reunión mensual - ' . date('d/m/Y', strtotime('+1 month')),
                    'ultimo_acceso' => $user->ultimo_acceso ? date('d/m/Y H:i', strtotime($user->ultimo_acceso)) : 'Primera vez'
                ];
                
                $data['socio_data'] = $socio_data;
                $data['user'] = $user;
            }
        }
        
        $this->view('pages/socios', $data);
    }

    // Página de perfil
    public function profile() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            $this->redirect('/socios');
        }
        
        // Obtener datos del usuario logueado
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        if (!$user) {
            $this->redirect('/socios');
        }
        
        $data = [
            'title' => 'Mi Perfil',
            'description' => 'Gestiona tu información personal',
            'user' => $user
        ];
        $this->view('pages/profile', $data);
    }

    // Actualizar perfil del usuario
    public function updateProfile() {
        if (!isLoggedIn()) {
            $this->redirect('/socios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');

            // Validaciones básicas
            if (empty($nombre) || empty($apellidos) || empty($email)) {
                setFlashMessage('Todos los campos obligatorios deben ser completados.', 'error');
                $this->redirect('/profile');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlashMessage('El email no tiene un formato válido.', 'error');
                $this->redirect('/profile');
                return;
            }

            try {
                $db = new Database();
                $stmt = $db->query("UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, telefono = ?, direccion = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bindParam(1, $nombre);
                $stmt->bindParam(2, $apellidos);
                $stmt->bindParam(3, $email);
                $stmt->bindParam(4, $telefono);
                $stmt->bindParam(5, $direccion);
                $stmt->bindParam(6, $user_id);
                
                if ($stmt->execute()) {
                    // Actualizar la sesión
                    $_SESSION['user_name'] = $nombre . ' ' . $apellidos;
                    $_SESSION['user_email'] = $email;
                    
                    setFlashMessage('Perfil actualizado correctamente.', 'success');
                } else {
                    setFlashMessage('Error al actualizar el perfil.', 'error');
                }
            } catch (Exception $e) {
                setFlashMessage('Error interno del servidor.', 'error');
            }
        }
        
        $this->redirect('/profile');
    }

    // Cambiar contraseña del usuario
    public function changePassword() {
        if (!isLoggedIn()) {
            $this->redirect('/socios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validaciones
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                setFlashMessage('Todos los campos son obligatorios.', 'error');
                $this->redirect('/profile');
                return;
            }

            if ($new_password !== $confirm_password) {
                setFlashMessage('Las contraseñas nuevas no coinciden.', 'error');
                $this->redirect('/profile');
                return;
            }

            if (strlen($new_password) < 6) {
                setFlashMessage('La nueva contraseña debe tener al menos 6 caracteres.', 'error');
                $this->redirect('/profile');
                return;
            }

            try {
                $db = new Database();
                $stmt = $db->query("SELECT password FROM usuarios WHERE id = ?");
                $stmt->bindParam(1, $user_id);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_OBJ);

                if (!$user || !password_verify($current_password, $user->password)) {
                    setFlashMessage('La contraseña actual es incorrecta.', 'error');
                    $this->redirect('/profile');
                    return;
                }

                // Actualizar contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->query("UPDATE usuarios SET password = ?, password_plain = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bindParam(1, $hashed_password);
                $stmt->bindParam(2, $new_password);
                $stmt->bindParam(3, $user_id);
                
                if ($stmt->execute()) {
                    setFlashMessage('Contraseña cambiada correctamente.', 'success');
                } else {
                    setFlashMessage('Error al cambiar la contraseña.', 'error');
                }
            } catch (Exception $e) {
                setFlashMessage('Error interno del servidor.', 'error');
            }
        }
        
        $this->redirect('/profile');
    }

    // Subir avatar del usuario
    public function uploadAvatar() {
        // Configurar headers para JSON
        header('Content-Type: application/json');
        
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error al subir el archivo: ' . ($_FILES['avatar']['error'] ?? 'Archivo no encontrado')]);
            return;
        }

        $file = $_FILES['avatar'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowed_types)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF']);
            return;
        }

        if ($file['size'] > $max_size) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande. Máximo 2MB']);
            return;
        }

        try {
            $user_id = $_SESSION['user_id'];
            $upload_dir = 'public/uploads/avatars/';
            
            // Crear directorio si no existe
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    throw new Exception('No se pudo crear el directorio de uploads');
                }
            }

            // Verificar permisos de escritura
            if (!is_writable($upload_dir)) {
                throw new Exception('El directorio no tiene permisos de escritura');
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $user_id . '_' . time() . '.' . $extension;
            $filepath = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Actualizar en la base de datos usando PDO directo
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=mariscales_db', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $stmt = $pdo->prepare("UPDATE usuarios SET avatar = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$filename, $user_id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Avatar actualizado correctamente', 'filename' => $filename]);
                } catch (PDOException $e) {
                    // Si falla la BD, al menos el archivo se subió
                    echo json_encode(['success' => true, 'message' => 'Avatar subido correctamente (error al actualizar BD)', 'filename' => $filename]);
                }
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo en el servidor']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Página de tienda
    public function tienda() {
        $products = [];
        
        try {
            // Usar la misma lógica que AdminController
            $pdo = new PDO('mysql:host=localhost;dbname=mariscales_db', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Consulta que incluye las categorías (igual que AdminController)
            $stmt = $pdo->query('SELECT p.*, c.nombre as categoria_nombre 
                                FROM productos p 
                                LEFT JOIN categorias c ON p.categoria_id = c.id 
                                ORDER BY p.id DESC');
            $all_products = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Filtrar solo los activos si existe la columna activo
            foreach ($all_products as $product) {
                if (property_exists($product, 'activo')) {
                    if ($product->activo == 1) {
                        $products[] = $product;
                    }
                } else {
                    // Si no hay columna activo, mostrar todos
                    $products[] = $product;
                }
            }
            
        } catch (Exception $e) {
            error_log("Error obteniendo productos para tienda: " . $e->getMessage());
            $products = [];
        }
        
        $data = [
            'title' => 'Tienda Online',
            'description' => 'Compra los artículos oficiales de la Filá Mariscales',
            'products' => $products
        ];
        $this->view('pages/tienda', $data);
    }

    // Página de contacto
    public function contacto() {
        $data = [
            'title' => 'Contacto',
            'description' => 'Ponte en contacto con la Filá Mariscales de Caballeros Templarios de Elche'
        ];
        $this->view('pages/contacto', $data);
    }

    // Página de historia
    public function historia() {
        $data = [
            'title' => 'Historia',
            'description' => 'Descubre la rica tradición y el legado de los Caballeros Templarios de Elche'
        ];
        $this->view('pages/historia', $data);
    }

    // Login page
    public function login() {
        // If already logged in, redirect to dashboard
        if (isLoggedIn()) {
            $this->redirect('/socios');
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            // FILTER_SANITIZE_STRING está deprecado en PHP 8.1+
            // Los campos se sanitizarán individualmente con htmlspecialchars() cuando se usen
            
            $data = [
                'title' => 'Iniciar Sesión',
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor ingrese su email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor ingrese su contraseña';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                // User not found
                $data['email_err'] = 'No se encontró ningún usuario con ese email';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Contraseña incorrecta';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Iniciar Sesión',
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    // Register page
    public function registro() {
        // If already logged in, redirect to dashboard
        if (isLoggedIn()) {
            $this->redirect('/socios');
        }

        $data = [
            'title' => 'Registro',
            'nombre' => '',
            'apellidos' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'nombre_err' => '',
            'apellidos_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
        ];

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            // FILTER_SANITIZE_STRING está deprecado en PHP 8.1+
            // Los campos se sanitizarán individualmente con htmlspecialchars() cuando se usen
            
            $data = [
                'title' => 'Registro',
                'nombre' => trim($_POST['nombre']),
                'apellidos' => trim($_POST['apellidos']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'nombre_err' => '',
                'apellidos_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Nombre
            if (empty($data['nombre'])) {
                $data['nombre_err'] = 'Por favor ingrese su nombre';
            }

            // Validate Apellidos
            if (empty($data['apellidos'])) {
                $data['apellidos_err'] = 'Por favor ingrese sus apellidos';
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor ingrese su email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Por favor ingrese un email válido';
            } else {
                // Check if email exists
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'El email ya está registrado';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor ingrese una contraseña';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'La contraseña debe tener al menos 6 caracteres';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Por favor confirme su contraseña';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Las contraseñas no coinciden';
                }
            }

            // Make sure errors are empty
            if (empty($data['nombre_err']) && empty($data['apellidos_err']) && 
                empty($data['email_err']) && empty($data['password_err']) && 
                empty($data['confirm_password_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User
                if ($this->userModel->register($data)) {
                    setFlashMessage('success', 'Registro exitoso. Por favor inicie sesión.');
                    $this->redirect('/login');
                } else {
                    die('Algo salió mal');
                }
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Registro',
                'nombre' => '',
                'apellidos' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'nombre_err' => '',
                'apellidos_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->nombre . ' ' . $user->apellidos;
        $_SESSION['user_role'] = $user->rol;
        
        if ($user->rol === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/socios');
        }
    }

    // Logout
    public function logout() {
        // Limpiar todas las variables de sesión
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir a la página de socios (que tiene el formulario de login)
        $this->redirect('/socios');
    }

    // Private area
    public function zonaPrivada() {
        $this->requireLogin();
        
        $data = [
            'title' => 'Zona Privada',
            'description' => 'Área privada de miembros de la Filá Mariscales'
        ];
        $this->view('zona-privada', $data);
    }

    // Admin dashboard
    public function admin() {
        // Redirect to admin controller
        $this->redirect('/admin');
    }

    // Página interactiva
    public function interactiva() {
        $data = [
            'title' => 'Zona Interactiva',
            'description' => 'Descubre la tradición templaria de forma interactiva'
        ];
        $this->view('pages/interactiva', $data);
    }

    // 404 page
    public function notFound() {
        $data = [
            'title' => 'Página no encontrada',
            'description' => 'La página que buscas no existe'
        ];
        $this->view('404', $data);
    }

    // Método para obtener eventos próximos
    private function getUpcomingEvents() {
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getRecentEvents(3);
                $formattedEvents = [];
                
                foreach ($events as $event) {
                    $formattedEvents[] = [
                        'title' => $event->titulo,
                        'description' => $event->descripcion ?? 'Evento de la Filá Mariscales',
                        'date' => $event->fecha,
                        'time' => $event->hora,
                        'location' => $event->lugar ?? 'Por determinar',
                        'status' => $event->es_publico ? 'Confirmado' : 'Próximamente',
                        'image' => !empty($event->imagen_url) ? '/' . $event->imagen_url : null // Usar null para indicar que no hay imagen
                    ];
                }
                
                return $formattedEvents;
            } catch (Exception $e) {
                // Si hay error, usar eventos por defecto
            }
        }
        
        // Eventos por defecto si no hay modelo o hay error
        return [
            [
                'title' => 'Presentación de la Filá',
                'description' => 'Presentación oficial de la Filá Mariscales para las fiestas 2024',
                'date' => '2024-10-15',
                'time' => '20:00',
                'location' => 'Sede Social',
                'status' => 'Próximamente',
                'image' => null // Sin imagen placeholder
            ],
            [
                'title' => 'Cena de Hermandad',
                'description' => 'Cena de hermandad para todos los miembros de la filá',
                'date' => '2024-10-20',
                'time' => '21:00',
                'location' => 'Restaurante El Rincón',
                'status' => 'Confirmado',
                'image' => null // Sin imagen placeholder
            ],
            [
                'title' => 'Ensayo General',
                'description' => 'Ensayo general del desfile de Moros y Cristianos',
                'date' => '2024-10-25',
                'time' => '18:00',
                'location' => 'Punto de encuentro: Ayuntamiento',
                'status' => 'Próximamente',
                'image' => null // Sin imagen placeholder
            ]
        ];
    }

    // Método para obtener todos los eventos
    private function getAllEvents() {
        if ($this->eventModel) {
            try {
                $events = $this->eventModel->getAllEvents(1, 50); // Obtener hasta 50 eventos
                return $events;
            } catch (Exception $e) {
                // Si hay error, devolver array vacío
            }
        }
        
        return [];
    }

    // Método para obtener imágenes del carrusel
    private function getCarouselImages() {
        $uploadDir = 'uploads/carousel/';
        $images = [];
        
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $images[] = [
                        'path' => $file,
                        'name' => $fileInfo['basename'],
                        'url' => $this->getImageUrl($file)
                    ];
                }
            }
        }
        
        // Si no hay imágenes subidas, usar imágenes por defecto
        if (empty($images)) {
            $images = [
                [
                    'path' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'name' => 'Caballeros Templarios',
                    'url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
                ],
                [
                    'path' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=2025&q=80',
                    'name' => 'Desfile Medieval',
                    'url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-4.0.3&auto=format&fit=crop&w=2025&q=80'
                ],
                [
                    'path' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                    'name' => 'Castillo Medieval',
                    'url' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
                ]
            ];
        }
        
        return $images;
    }

    // Método para obtener imágenes de la galería
    private function getGalleryImages() {
        $uploadDir = 'uploads/gallery/';
        $images = [];
        
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileInfo = pathinfo($file);
                    $images[] = [
                        'thumb' => $this->getImageUrl($file),
                        'full' => $this->getImageUrl($file),
                        'caption' => 'Imagen de la Filá Mariscales',
                        'alt' => 'Galería Filá Mariscales',
                        'name' => $fileInfo['basename']
                    ];
                }
            }
        }
        
        // Si no hay imágenes subidas, usar imágenes por defecto
        if (empty($images)) {
            // No usar imágenes placeholder - devolver array vacío
            $images = [];
        }
        
        return $images;
    }

    // Método para generar URLs de imágenes
    private function getImageUrl($filePath) {
        // Si es una URL externa, devolverla tal como está
        if (strpos($filePath, 'http') === 0) {
            return $filePath;
        }
        
        // Verificar si el acceso directo funciona
        $directUrl = '/' . $filePath;
        
        // Por ahora, usar el script servidor para asegurar que funcione
        return URL_ROOT . '/serve-image.php?path=' . urlencode($filePath);
    }

    // Método para obtener noticias publicadas usando el modelo News
    private function getPublishedNews() {
        try {
            // Cargar el modelo News
            $newsModel = $this->model('News');
            
            if (!$newsModel) {
                error_log("Error: No se pudo cargar el modelo News");
                return [];
            }
            
            // Obtener noticias publicadas (12 noticias)
            $news = $newsModel->getPublishedNews(1, 12);
            
            error_log("Noticias obtenidas del modelo: " . count($news));
            
            // Formatear las noticias para la vista
            $formattedNews = [];
            foreach ($news as $item) {
                $formattedNews[] = [
                    'id' => $item->id,
                    'titulo' => $item->titulo,
                    'contenido' => $item->contenido,
                    'categoria' => $item->categoria ?? 'general',
                    'imagen_portada' => $item->imagen_portada,
                    'autor_nombre' => $item->autor_nombre ?? 'Administrador',
                    'autor_apellidos' => $item->autor_apellidos ?? '',
                    'fecha_publicacion' => $item->fecha_publicacion,
                    'estado' => $item->estado,
                    'resumen' => $this->getNewsSummary($item->contenido),
                    'imagen_url' => $item->imagen_portada ? 
                        URL_ROOT . '/serve-image.php?path=uploads/news/' . $item->imagen_portada : 
                        URL_ROOT . '/serve-image.php?path=assets/images/backgrounds/knight-templar-background.jpg'
                ];
            }
            
            error_log("Noticias formateadas: " . count($formattedNews));
            return $formattedNews;
            
        } catch (Exception $e) {
            error_log("Error obteniendo noticias: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return [];
        }
    }

    // Método para generar resumen de noticia
    private function getNewsSummary($content, $maxLength = 150) {
        // Limpiar HTML y obtener texto plano
        $text = strip_tags($content);
        
        // Si el texto es más corto que el máximo, devolverlo completo
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        
        // Truncar y agregar puntos suspensivos
        return substr($text, 0, $maxLength) . '...';
    }

    // Método para obtener color de categoría
    public function getCategoryColor($category) {
        $colors = [
            'general' => 'secondary',
            'evento' => 'success',
            'novedad' => 'danger',
            'actualidad' => 'info',
            'ensayo' => 'warning',
            'bienvenida' => 'primary',
            'cultura' => 'secondary',
            'deportes' => 'success',
            'social' => 'info',
            'historia' => 'dark'
        ];
        
        return $colors[$category] ?? 'secondary';
    }
}
