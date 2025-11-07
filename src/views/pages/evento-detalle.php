<?php
$event = $data['event'];
$eventId = is_object($event) ? $event->id : $event['id'];
$eventTitulo = is_object($event) ? $event->titulo : $event['titulo'];
$eventDescripcion = is_object($event) ? $event->descripcion : $event['descripcion'];
$eventFecha = is_object($event) ? $event->fecha : $event['fecha'];
$eventHora = is_object($event) ? $event->hora : $event['hora'];
$eventLugar = is_object($event) ? $event->lugar : $event['lugar'];
$eventTipo = is_object($event) ? $event->tipo : $event['tipo'];
$eventImagen = is_object($event) ? ($event->imagen_url ?? null) : ($event['imagen_url'] ?? null);
$eventPrecio = is_object($event) ? ($event->precio ?? 0) : ($event['precio'] ?? 0);
$inscripcionesAbiertas = is_object($event) ? ($event->inscripciones_abiertas ?? false) : ($event['inscripciones_abiertas'] ?? false);
$capacidad = is_object($event) ? ($event->capacidad ?? null) : ($event['capacidad'] ?? null);

$canReserve = $data['can_reserve'] ?? false;
$plazasDisponibles = $data['plazas_disponibles'] ?? null;
$userReservations = $data['user_reservations'] ?? [];
$userLoggedIn = $data['user_logged_in'] ?? false;

// Verificar si hay mensaje de éxito de reserva
$reservaId = $_GET['reserva'] ?? null;
?>

<!-- Hero Section -->
<section class="hero-section text-white text-center py-5 mb-5" style="background: linear-gradient(135deg, rgba(220, 20, 60, 0.9) 0%, rgba(139, 0, 0, 0.9) 100%); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/" class="text-white-50">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/eventos" class="text-white-50">Eventos</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($eventTitulo); ?></li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-3 text-white"><?php echo htmlspecialchars($eventTitulo); ?></h1>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Event Details -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <?php if ($eventImagen): ?>
                        <img src="<?php echo URL_ROOT; ?>/serve-image.php?path=uploads/events/<?php echo urlencode($eventImagen); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($eventTitulo); ?>"
                             style="max-height: 400px; object-fit: cover;"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px; display: none;">
                            <i class="bi bi-image text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php else: ?>
                        <div class="card-img-top bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-calendar-event text-danger" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4">
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($eventDescripcion)); ?>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Event Info -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-calendar3 text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Fecha</p>
                                        <small class="text-muted"><?php echo formatDate($eventFecha, 'blog'); ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($eventHora): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-clock text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Hora</p>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($eventHora)); ?></small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($eventLugar): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-geo-alt text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Lugar</p>
                                        <small class="text-muted"><?php echo htmlspecialchars($eventLugar); ?></small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($eventPrecio > 0): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-currency-euro text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Precio</p>
                                        <small class="text-muted"><?php echo number_format($eventPrecio, 2, ',', '.'); ?> €</small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($capacidad): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-people text-danger fs-5"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Capacidad</p>
                                        <small class="text-muted">
                                            <?php if ($plazasDisponibles !== null): ?>
                                                <?php echo $plazasDisponibles; ?> de <?php echo $capacidad; ?> plazas disponibles
                                            <?php else: ?>
                                                <?php echo $capacidad; ?> plazas
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Mis Reservas (si está logueado) -->
                <?php if ($userLoggedIn && !empty($userReservations)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">Mis Reservas</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($userReservations as $reservation): 
                            $resObj = is_object($reservation) ? $reservation : (object)$reservation;
                            $resEstado = $resObj->estado ?? 'pendiente';
                            $resCodigo = $resObj->codigo_reserva ?? '';
                            $resPersonas = $resObj->num_personas ?? 1;
                        ?>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                            <div>
                                <p class="mb-0 fw-bold">Código: <?php echo htmlspecialchars($resCodigo); ?></p>
                                <small class="text-muted">
                                    <?php echo $resPersonas; ?> persona<?php echo $resPersonas > 1 ? 's' : ''; ?> - 
                                    Estado: <span class="badge bg-<?php echo $resEstado === 'confirmada' ? 'success' : ($resEstado === 'cancelada' ? 'danger' : 'warning'); ?>">
                                        <?php echo ucfirst($resEstado); ?>
                                    </span>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Reservation Form Sidebar -->
            <div class="col-lg-4">
                <?php if ($inscripcionesAbiertas): ?>
                    <?php if ($canReserve): ?>
                    <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-calendar-check me-2"></i>
                                Reservar Plaza
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($plazasDisponibles !== null): ?>
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong><?php echo $plazasDisponibles; ?></strong> plazas disponibles
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="<?php echo URL_ROOT; ?>/reservar-evento">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <input type="hidden" name="evento_id" value="<?php echo $eventId; ?>">
                                
                                <?php if (!$userLoggedIn): ?>
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="num_personas" class="form-label">Número de personas *</label>
                                    <select class="form-select" id="num_personas" name="num_personas" required>
                                        <?php 
                                        $maxPersonas = $plazasDisponibles !== null ? min($plazasDisponibles, 10) : 10;
                                        for ($i = 1; $i <= $maxPersonas; $i++): 
                                        ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?> persona<?php echo $i > 1 ? 's' : ''; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notas" class="form-label">Notas adicionales (opcional)</label>
                                    <textarea class="form-control" id="notas" name="notas" rows="3" placeholder="Información adicional que quieras proporcionar..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Confirmar Reserva
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Evento Completo</h5>
                            <p class="text-muted">No hay plazas disponibles para este evento.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Reservas Cerradas</h5>
                        <p class="text-muted">Las inscripciones para este evento no están abiertas.</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Back to Events -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body text-center">
                        <a href="<?php echo URL_ROOT; ?>/eventos" class="btn btn-outline-danger w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver a Eventos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

