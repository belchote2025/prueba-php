<?php
require_once __DIR__ . '/../../../src/config/config.php';

// Configurar datos para el layout
$data = [
    'title' => 'Eventos',
    'description' => 'Todos los eventos y actividades de la Filá Mariscales de Caballeros Templarios'
];

// Iniciar el buffer de salida
ob_start();
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(220, 20, 60, 0.1) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(220, 20, 60, 0.1) 100%);
        padding: 4rem 0 2rem 0;
        margin-top: 80px; /* Espacio para navbar fijo */
    }

    .text-gradient {
        background: linear-gradient(45deg, #dc143c, #8b0000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-family: 'Cinzel', serif;
        font-weight: 700;
    }

    .event-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .event-date {
        background: linear-gradient(135deg, #dc143c, #8b0000);
        color: white;
        padding: 1rem;
        text-align: center;
        min-width: 80px;
    }

    .event-date .day {
        font-size: 1.5rem;
        font-weight: bold;
        line-height: 1;
    }

    .event-date .month {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .event-status {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
    }

    .filter-buttons {
        margin-bottom: 2rem;
    }

    .filter-btn {
        margin: 0.25rem;
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: linear-gradient(45deg, #dc143c, #8b0000);
        border-color: #dc143c;
        color: white;
    }

    .no-events {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .no-events i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="text-gradient">
                    <i class="bi bi-calendar-event me-3"></i>Eventos y Actividades
                </h1>
                <p class="lead">Descubre todos los eventos, actividades y celebraciones de la Filá Mariscales de Caballeros Templarios</p>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="py-5">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="filter-buttons text-center">
            <button class="btn btn-outline-primary filter-btn active" data-filter="all">
                <i class="bi bi-calendar3 me-2"></i>Todos los Eventos
            </button>
            <button class="btn btn-outline-primary filter-btn" data-filter="upcoming">
                <i class="bi bi-clock me-2"></i>Próximos
            </button>
            <button class="btn btn-outline-primary filter-btn" data-filter="past">
                <i class="bi bi-calendar-check me-2"></i>Pasados
            </button>
        </div>

        <!-- Events Grid -->
        <div class="row g-4" id="events-container">
            <?php if (!empty($data['events'])): ?>
                <?php foreach ($data['events'] as $event): ?>
                    <?php
                    $eventDate = isset($event->fecha) ? $event->fecha : (isset($event['date']) ? $event['date'] : '');
                    $eventTime = isset($event->hora) ? $event->hora : (isset($event['time']) ? $event['time'] : '');
                    $eventTitle = isset($event->titulo) ? $event->titulo : (isset($event['title']) ? $event['title'] : '');
                    $eventDescription = isset($event->descripcion) ? $event->descripcion : (isset($event['description']) ? $event['description'] : '');
                    $eventLocation = isset($event->lugar) ? $event->lugar : (isset($event['location']) ? $event['location'] : '');
                    $eventStatus = isset($event->estado) ? $event->estado : (isset($event['status']) ? $event['status'] : 'Próximamente');
                    
                    $isUpcoming = strtotime($eventDate) >= time();
                    $eventClass = $isUpcoming ? 'upcoming' : 'past';
                    ?>
                    <div class="col-lg-4 col-md-6 event-item" data-event-type="<?php echo $eventClass; ?>">
                        <div class="card event-card h-100 position-relative">
                            <!-- Event Status Badge -->
                            <div class="event-status">
                                <span class="badge bg-<?php echo $isUpcoming ? 'success' : 'secondary'; ?>">
                                    <?php echo $isUpcoming ? 'Próximo' : 'Pasado'; ?>
                                </span>
                            </div>

                            <!-- Event Date -->
                            <div class="event-date">
                                <div class="day"><?php echo date('j', strtotime($eventDate)); ?></div>
                                <div class="month"><?php echo strtoupper(date('M', strtotime($eventDate))); ?></div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($eventTitle); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($eventDescription); ?></p>
                                
                                <div class="event-details">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-clock me-2 text-danger"></i>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($eventTime)); ?></small>
                                    </div>
                                    <?php if ($eventLocation): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt me-2 text-danger"></i>
                                            <small class="text-muted"><?php echo htmlspecialchars($eventLocation); ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0">
                                <a href="#" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="bi bi-info-circle me-1"></i>Más Información
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="no-events">
                        <i class="bi bi-calendar-x"></i>
                        <h3>No hay eventos disponibles</h3>
                        <p>Próximamente se publicarán nuevos eventos y actividades.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More Button (if needed) -->
        <?php if (count($data['events']) >= 10): ?>
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-arrow-down-circle me-2"></i>Cargar Más Eventos
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const eventItems = document.querySelectorAll('.event-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter events
            eventItems.forEach(item => {
                const eventType = item.getAttribute('data-event-type');
                
                if (filter === 'all' || eventType === filter) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Add fadeIn animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>

<?php
// Obtener el contenido del buffer
$content = ob_get_clean();

// Incluir el layout principal
include __DIR__ . '/../layouts/main.php';
?>
