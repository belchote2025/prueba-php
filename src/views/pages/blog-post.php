<?php

// Obtener datos del post
$post = $data['post'];
$postId = is_object($post) ? $post->id : $post['id'];
$postTitulo = is_object($post) ? $post->titulo : $post['titulo'];
$postContenido = is_object($post) ? $post->contenido : $post['contenido'];
$postImagen = is_object($post) ? ($post->imagen_portada ?? $post->imagen_url ?? null) : ($post['imagen_portada'] ?? $post['imagen_url'] ?? null);
$postFecha = is_object($post) ? $post->fecha_publicacion : $post['fecha_publicacion'];
$postAutor = is_object($post) ? ($post->autor_nombre ?? 'Admin') : ($post['autor_nombre'] ?? 'Admin');
$postAutorApellidos = is_object($post) ? ($post->autor_apellidos ?? '') : ($post['autor_apellidos'] ?? '');
$autorCompleto = trim($postAutor . ' ' . $postAutorApellidos);
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-5 mb-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.9) 0%, rgba(139, 0, 0, 0.9) 100%); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/" class="text-white-50">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/blog" class="text-white-50">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($postTitulo); ?></li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-3 text-white"><?php echo htmlspecialchars($postTitulo); ?></h1>
        <p class="lead text-white">
            <i class="bi bi-calendar3 me-2"></i>
            <?php echo formatDate($postFecha, 'blog'); ?>
            <span class="mx-3">|</span>
            <i class="bi bi-person me-2"></i>
            <?php echo htmlspecialchars($autorCompleto); ?>
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Post Content -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm mb-4">
                    <?php if ($postImagen): ?>
                        <img src="<?php echo URL_ROOT; ?>/serve-image.php?path=uploads/news/<?php echo urlencode($postImagen); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($postTitulo); ?>"
                             style="max-height: 500px; object-fit: cover;"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px; display: none;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php else: ?>
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4">
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($postContenido)); ?>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Post Meta -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person text-danger fs-5"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($autorCompleto); ?></p>
                                    <small class="text-muted">Autor</small>
                                </div>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Publicado el <?php echo formatDate($postFecha, 'blog'); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </article>
                
                <!-- Share Buttons -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Compartir este artículo</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-facebook me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($postTitulo); ?>" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($postTitulo . ' - ' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                               target="_blank" 
                               class="btn btn-outline-success btn-sm">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Enlace copiado al portapapeles');" 
                                    class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-clipboard me-1"></i> Copiar enlace
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Posts -->
                <?php if (!empty($data['related_posts'])): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">Artículos relacionados</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($data['related_posts'] as $related): 
                            $relatedId = is_object($related) ? $related->id : $related['id'];
                            $relatedTitulo = is_object($related) ? $related->titulo : $related['titulo'];
                            $relatedImagen = is_object($related) ? ($related->imagen_portada ?? $related->imagen_url ?? null) : ($related['imagen_portada'] ?? $related['imagen_url'] ?? null);
                            $relatedFecha = is_object($related) ? $related->fecha_publicacion : $related['fecha_publicacion'];
                        ?>
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <?php if ($relatedImagen): ?>
                                <img src="<?php echo URL_ROOT; ?>/serve-image.php?path=uploads/news/<?php echo urlencode($relatedImagen); ?>" 
                                     class="rounded me-3" 
                                     alt="<?php echo htmlspecialchars($relatedTitulo); ?>"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="bg-danger bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; display: none;">
                                    <i class="bi bi-image text-danger"></i>
                                </div>
                            <?php else: ?>
                                <div class="bg-danger bg-opacity-10 rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-danger"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    <a href="<?php echo URL_ROOT; ?>/blog/post/<?php echo $relatedId; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($relatedTitulo); ?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo formatDate($relatedFecha, 'blog'); ?>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Back to Blog -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <a href="<?php echo URL_ROOT; ?>/blog" class="btn btn-danger w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver al blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comentarios Section -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h4 class="fw-bold mb-0">
                            <i class="bi bi-chat-dots me-2 text-danger"></i>
                            Comentarios 
                            <span class="badge bg-danger"><?php echo $data['comment_count'] ?? 0; ?></span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulario de comentario -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="fw-bold mb-3">Deja un comentario</h5>
                            <form id="commentForm">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="noticia_id" value="<?php echo $postId; ?>">
                                
                                <?php if (!isset($data['user_logged_in']) || !$data['user_logged_in']): ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Comentario *</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="4" required minlength="10"></textarea>
                                    <small class="text-muted">Mínimo 10 caracteres</small>
                                </div>
                                
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-send me-2"></i>
                                    Enviar comentario
                                </button>
                            </form>
                            <div id="commentMessage" class="mt-3"></div>
                        </div>
                        
                        <!-- Lista de comentarios -->
                        <div id="commentsList">
                            <?php if (!empty($data['comments'])): ?>
                                <?php foreach ($data['comments'] as $comment): 
                                    $commentObj = is_object($comment) ? $comment : (object)$comment;
                                    $commentId = $commentObj->id ?? 0;
                                    $commentNombre = htmlspecialchars($commentObj->nombre ?? 'Anónimo');
                                    $commentTexto = nl2br(htmlspecialchars($commentObj->comentario ?? ''));
                                    $commentFecha = $commentObj->fecha_creacion ?? '';
                                    $respuestas = $commentObj->respuestas ?? [];
                                ?>
                                <div class="comment-item mb-4 pb-4 border-bottom" data-comment-id="<?php echo $commentId; ?>">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="bi bi-person text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="fw-bold mb-0"><?php echo $commentNombre; ?></h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <?php echo formatDate($commentFecha, 'blog'); ?>
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-secondary reply-btn" data-comment-id="<?php echo $commentId; ?>">
                                                    <i class="bi bi-reply me-1"></i> Responder
                                                </button>
                                            </div>
                                            <p class="mb-0"><?php echo $commentTexto; ?></p>
                                            
                                            <!-- Respuestas -->
                                            <?php if (!empty($respuestas)): ?>
                                                <div class="mt-3 ms-4 ps-3 border-start border-2">
                                                    <?php foreach ($respuestas as $reply): 
                                                        $replyObj = is_object($reply) ? $reply : (object)$reply;
                                                        $replyNombre = htmlspecialchars($replyObj->nombre ?? 'Anónimo');
                                                        $replyTexto = nl2br(htmlspecialchars($replyObj->comentario ?? ''));
                                                        $replyFecha = $replyObj->fecha_creacion ?? '';
                                                    ?>
                                                    <div class="mb-3">
                                                        <div class="d-flex">
                                                            <div class="flex-shrink-0">
                                                                <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                    <i class="bi bi-person text-secondary"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2">
                                                                <h6 class="fw-bold mb-0 small"><?php echo $replyNombre; ?></h6>
                                                                <small class="text-muted">
                                                                    <i class="bi bi-calendar3 me-1"></i>
                                                                    <?php echo formatDate($replyFecha, 'blog'); ?>
                                                                </small>
                                                                <p class="mb-0 small mt-1"><?php echo $replyTexto; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Formulario de respuesta (oculto) -->
                                            <div class="reply-form mt-3 ms-4 ps-3 border-start border-2" style="display: none;" data-parent-id="<?php echo $commentId; ?>">
                                                <form class="reply-comment-form">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                                    <input type="hidden" name="noticia_id" value="<?php echo $postId; ?>">
                                                    <input type="hidden" name="comentario_padre_id" value="<?php echo $commentId; ?>">
                                                    
                                                    <?php if (!isset($data['user_logged_in']) || !$data['user_logged_in']): ?>
                                                    <div class="row mb-2">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" name="nombre" placeholder="Nombre" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="email" class="form-control form-control-sm" name="email" placeholder="Email" required>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <textarea class="form-control form-control-sm mb-2" name="comentario" rows="2" placeholder="Escribe tu respuesta..." required minlength="10"></textarea>
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-sm btn-danger">Enviar</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary cancel-reply">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">
                                    <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
                                    Aún no hay comentarios. ¡Sé el primero en comentar!
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Manejo de formulario de comentarios
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(commentForm);
            const messageDiv = document.getElementById('commentMessage');
            
            fetch('<?php echo URL_ROOT; ?>/crear-comentario', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    commentForm.reset();
                    // Recargar comentarios después de 2 segundos
                    setTimeout(() => location.reload(), 2000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            })
            .catch(error => {
                messageDiv.innerHTML = '<div class="alert alert-danger">Error al enviar el comentario</div>';
            });
        });
    }
    
    // Manejo de respuestas
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.querySelector(`.reply-form[data-parent-id="${commentId}"]`);
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
    
    // Cancelar respuesta
    document.querySelectorAll('.cancel-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.reply-form').style.display = 'none';
            this.closest('form').reset();
        });
    });
    
    // Enviar respuesta
    document.querySelectorAll('.reply-comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.createElement('div');
            messageDiv.className = 'alert mt-2';
            this.appendChild(messageDiv);
            
            fetch('<?php echo URL_ROOT; ?>/crear-comentario', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'alert alert-success mt-2';
                    messageDiv.textContent = data.message;
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    messageDiv.className = 'alert alert-danger mt-2';
                    messageDiv.textContent = data.message;
                }
            })
            .catch(error => {
                messageDiv.className = 'alert alert-danger mt-2';
                messageDiv.textContent = 'Error al enviar la respuesta';
            });
        });
    });
});
</script>

