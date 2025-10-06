<?php
// Página interactiva con elementos dinámicos
?>

<!-- Hero Section Interactivo -->
<section class="hero-interactive py-5 particles">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center scroll-reveal">
                <h1 class="display-3 fw-bold text-gradient mb-4 animate-fadeInDown">
                    <i class="bi bi-magic me-3 animate-float"></i>
                    <span class="text-shimmer">Zona Interactiva</span>
                </h1>
                <p class="lead mb-5 animate-fadeInUp">
                    Descubre la tradición templaria de forma interactiva
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Contadores Animados -->
<section class="counters-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-calendar-event fs-1 text-danger"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="50">0</h3>
                    <p class="text-muted">Años de Tradición</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-people-fill fs-1 text-success"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="150">0</h3>
                    <p class="text-muted">Miembros Activos</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-trophy-fill fs-1 text-warning"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="25">0</h3>
                    <p class="text-muted">Premios Obtenidos</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="counter-card card-animated hover-lift">
                    <div class="counter-icon animate-bounce">
                        <i class="bi bi-heart-fill fs-1 text-danger"></i>
                    </div>
                    <h3 class="counter text-gradient" data-target="1000">0</h3>
                    <p class="text-muted">Seguidores</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Tarjetas Interactivas -->
<section class="interactive-cards py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-stars me-3 animate-glow"></i>
                    Explora Nuestra Historia
                </h2>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-shield-fill fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Orígenes Templarios</h5>
                        <p class="card-text">Descubre cómo comenzó nuestra tradición en 1975 y los valores que nos guían.</p>
                        <button class="btn btn-danger btn-animated" onclick="showModal('templarios')">
                            <i class="bi bi-eye me-2"></i>Explorar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-music-note-beamed fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bandas de Música</h5>
                        <p class="card-text">Conoce nuestras bandas y la música que nos acompaña en cada desfile.</p>
                        <button class="btn btn-danger btn-animated" onclick="showModal('bandas')">
                            <i class="bi bi-play-circle me-2"></i>Escuchar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="interactive-card card-tilt hover-lift scroll-reveal">
                    <div class="card-header bg-danger text-white">
                        <i class="bi bi-calendar-event fs-1 animate-float"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Eventos Anuales</h5>
                        <p class="card-text">Participa en nuestros eventos y actividades durante todo el año.</p>
                        <button class="btn btn-danger btn-animated" onclick="showModal('eventos')">
                            <i class="bi bi-calendar-check me-2"></i>Ver Eventos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Actividades Divertidas -->
<section class="fun-activities-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-emoji-smile me-3 animate-bounce"></i>
                    ¡Diviértete con Nosotros!
                </h2>
                <p class="lead">Descubre actividades interactivas y juegos relacionados con la Filá Mariscales</p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Generador de Nombres Templarios -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-badge display-4 text-danger"></i>
                        </div>
                        <h5 class="card-title">Generador de Nombres Templarios</h5>
                        <p class="card-text">¡Genera tu nombre de caballero templario personalizado!</p>
                        <button class="btn btn-outline-danger" onclick="generateTemplarName()">
                            <i class="bi bi-shuffle me-2"></i>Generar Nombre
                        </button>
                        <div id="templarName" class="mt-3 p-3 bg-light rounded" style="display: none;">
                            <h6 class="text-danger mb-0"></h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Constructor de Castillos -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-building display-4 text-warning"></i>
                        </div>
                        <h5 class="card-title">Constructor de Castillos</h5>
                        <p class="card-text">Construye tu propio castillo templario</p>
                        <button class="btn btn-outline-warning" onclick="startCastleBuilder()">
                            <i class="bi bi-hammer me-2"></i>Construir
                        </button>
                        <div id="castleContainer" class="mt-3" style="display: none;">
                            <div class="castle-preview mb-3">
                                <div id="castlePreview" class="mx-auto" style="width: 200px; height: 150px; background: linear-gradient(45deg, #8B4513, #A0522D); border-radius: 10px; position: relative; border: 3px solid #654321;">
                                    <div class="castle-tower" style="position: absolute; top: -20px; left: 20px; width: 30px; height: 40px; background: #8B4513; border-radius: 5px;"></div>
                                    <div class="castle-tower" style="position: absolute; top: -20px; right: 20px; width: 30px; height: 40px; background: #8B4513; border-radius: 5px;"></div>
                                    <div class="castle-flag" style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); width: 20px; height: 15px; background: #dc143c; border-radius: 2px;"></div>
                                </div>
                            </div>
                            <div class="castle-controls">
                                <div class="mb-2">
                                    <label class="form-label small">Color del Castillo:</label>
                                    <input type="color" id="castleColor" class="form-control form-control-color" value="#8B4513" onchange="updateCastle()">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Tipo de Bandera:</label>
                                    <select id="flagType" class="form-select form-select-sm" onchange="updateCastle()">
                                        <option value="🏰">Castillo</option>
                                        <option value="⚔️">Espada</option>
                                        <option value="🛡️">Escudo</option>
                                        <option value="⚜️">Flor de Lis</option>
                                        <option value="👑">Corona</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Tamaño:</label>
                                    <input type="range" id="castleSize" class="form-range" min="150" max="250" value="200" onchange="updateCastle()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Simulador de Batalla -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-check display-4 text-success"></i>
                        </div>
                        <h5 class="card-title">Simulador de Batalla</h5>
                        <p class="card-text">¡Lucha como un verdadero caballero templario!</p>
                        <button class="btn btn-outline-success" onclick="startBattle()">
                            <i class="bi bi-sword me-2"></i>Iniciar Batalla
                        </button>
                        <div id="battleContainer" class="mt-3" style="display: none;">
                            <div class="battle-arena p-3 bg-dark text-white rounded mb-3">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <h6 class="text-danger">Templario</h6>
                                        <div class="progress mb-2">
                                            <div id="templarHealth" class="progress-bar bg-danger" style="width: 100%"></div>
                                        </div>
                                        <small>Vida: <span id="templarHealthText">100</span></small>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h6 class="text-warning">Enemigo</h6>
                                        <div class="progress mb-2">
                                            <div id="enemyHealth" class="progress-bar bg-warning" style="width: 100%"></div>
                                        </div>
                                        <small>Vida: <span id="enemyHealthText">100</span></small>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button class="btn btn-danger btn-sm" onclick="attack()">
                                        <i class="bi bi-lightning me-1"></i>Atacar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Simulador de Torneo -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-trophy display-4 text-info"></i>
                        </div>
                        <h5 class="card-title">Simulador de Torneo</h5>
                        <p class="card-text">Participa en un torneo de caballería</p>
                        <button class="btn btn-outline-info" onclick="startTournament()">
                            <i class="bi bi-sword me-2"></i>Iniciar Torneo
                        </button>
                        <div id="tournamentContainer" class="mt-3" style="display: none;">
                            <div class="tournament-bracket p-3 bg-light rounded mb-3">
                                <h6 class="text-center mb-3">Torneo de Caballería</h6>
                                <div class="tournament-rounds">
                                    <div class="round mb-3">
                                        <h6 class="small">Ronda 1</h6>
                                        <div class="match mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span id="player1">Tú</span>
                                                <span class="badge bg-primary" id="score1">0</span>
                                                <span class="text-muted">vs</span>
                                                <span class="badge bg-primary" id="score2">0</span>
                                                <span id="opponent1">Sir Lancelot</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="round mb-3">
                                        <h6 class="small">Final</h6>
                                        <div class="match mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span id="finalPlayer">Ganador Ronda 1</span>
                                                <span class="badge bg-warning" id="finalScore1">0</span>
                                                <span class="text-muted">vs</span>
                                                <span class="badge bg-warning" id="finalScore2">0</span>
                                                <span id="finalOpponent">Sir Galahad</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-success btn-sm" onclick="playMatch()" id="playBtn">
                                        <i class="bi bi-play me-1"></i>Jugar Partida
                                    </button>
                                </div>
                            </div>
                            <div class="tournament-results" style="display: none;">
                                <div class="alert alert-success text-center">
                                    <h6 id="tournamentResult"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Creador de Escudos -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-palette display-4 text-purple"></i>
                        </div>
                        <h5 class="card-title">Creador de Escudos</h5>
                        <p class="card-text">Diseña tu propio escudo templario</p>
                        <button class="btn btn-outline-secondary" onclick="startShieldCreator()">
                            <i class="bi bi-paint-bucket me-2"></i>Crear Escudo
                        </button>
                        <div id="shieldContainer" class="mt-3" style="display: none;">
                            <div class="shield-preview mb-3">
                                <div id="shieldPreview" class="mx-auto" style="width: 100px; height: 120px; border: 3px solid #8B4513; background: linear-gradient(45deg, #dc143c, #8b0000); border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; position: relative;">
                                    <div class="shield-symbol" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 2rem;">⚔️</div>
                                </div>
                            </div>
                            <div class="shield-controls">
                                <div class="mb-2">
                                    <label class="form-label small">Color Principal:</label>
                                    <input type="color" id="primaryColor" class="form-control form-control-color" value="#dc143c" onchange="updateShield()">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Símbolo:</label>
                                    <select id="shieldSymbol" class="form-select form-select-sm" onchange="updateShield()">
                                        <option value="⚔️">Espada</option>
                                        <option value="🛡️">Escudo</option>
                                        <option value="⚜️">Flor de Lis</option>
                                        <option value="✠">Cruz</option>
                                        <option value="👑">Corona</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Creador de Armaduras -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-fill display-4 text-primary"></i>
                        </div>
                        <h5 class="card-title">Creador de Armaduras</h5>
                        <p class="card-text">Diseña tu armadura de caballero</p>
                        <button class="btn btn-outline-primary" onclick="startArmorCreator()">
                            <i class="bi bi-gear me-2"></i>Crear Armadura
                        </button>
                        <div id="armorContainer" class="mt-3" style="display: none;">
                            <div class="armor-preview mb-3">
                                <div id="armorPreview" class="mx-auto" style="width: 120px; height: 160px; background: linear-gradient(45deg, #C0C0C0, #A8A8A8); border-radius: 10px; position: relative; border: 3px solid #808080;">
                                    <div class="armor-helmet" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 60px; height: 40px; background: #C0C0C0; border-radius: 50% 50% 0 0;"></div>
                                    <div class="armor-body" style="position: absolute; top: 35px; left: 50%; transform: translateX(-50%); width: 80px; height: 80px; background: #C0C0C0; border-radius: 5px;"></div>
                                    <div class="armor-symbol" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #dc143c; font-size: 1.5rem;">⚔️</div>
                                </div>
                            </div>
                            <div class="armor-controls">
                                <div class="mb-2">
                                    <label class="form-label small">Material:</label>
                                    <select id="armorMaterial" class="form-select form-select-sm" onchange="updateArmor()">
                                        <option value="#C0C0C0">Acero</option>
                                        <option value="#FFD700">Oro</option>
                                        <option value="#8B4513">Bronce</option>
                                        <option value="#2F4F4F">Hierro</option>
                                        <option value="#B87333">Cobre</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Símbolo:</label>
                                    <select id="armorSymbol" class="form-select form-select-sm" onchange="updateArmor()">
                                        <option value="⚔️">Espada</option>
                                        <option value="🛡️">Escudo</option>
                                        <option value="⚜️">Flor de Lis</option>
                                        <option value="✠">Cruz</option>
                                        <option value="👑">Corona</option>
                                        <option value="🏰">Castillo</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Nivel de Protección:</label>
                                    <input type="range" id="armorLevel" class="form-range" min="1" max="5" value="3" onchange="updateArmor()">
                                    <small class="text-muted">Nivel: <span id="armorLevelText">3</span>/5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Simulador de Misiones -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-map display-4 text-success"></i>
                        </div>
                        <h5 class="card-title">Simulador de Misiones</h5>
                        <p class="card-text">Embárcate en aventuras épicas</p>
                        <button class="btn btn-outline-success" onclick="startMission()">
                            <i class="bi bi-compass me-2"></i>Iniciar Misión
                        </button>
                        <div id="missionContainer" class="mt-3" style="display: none;">
                            <div class="mission-details p-3 bg-light rounded mb-3">
                                <h6 id="missionTitle" class="text-center mb-3">Misión Seleccionada</h6>
                                <div class="mission-info mb-3">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small class="text-muted">Dificultad</small>
                                            <div id="missionDifficulty" class="fw-bold">Media</div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Recompensa</small>
                                            <div id="missionReward" class="fw-bold">100 oro</div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Duración</small>
                                            <div id="missionDuration" class="fw-bold">3 días</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mission-progress mb-3">
                                    <div class="progress">
                                        <div id="missionProgress" class="progress-bar bg-success" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted">Progreso: <span id="missionProgressText">0%</span></small>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-success btn-sm" onclick="advanceMission()" id="missionBtn">
                                        <i class="bi bi-play me-1"></i>Avanzar
                                    </button>
                                </div>
                            </div>
                            <div class="mission-results" style="display: none;">
                                <div class="alert alert-success text-center">
                                    <h6 id="missionResult"></h6>
                                    <p class="mb-0">Recompensa obtenida: <span id="finalReward"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Juegos Interactivos -->
<section class="games-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-reveal">
                <h2 class="display-5 fw-bold text-gradient animate-fadeInUp">
                    <i class="bi bi-controller me-3 animate-bounce"></i>
                    Zona de Juegos
                </h2>
                <p class="lead">Diviértete con nuestros juegos temáticos sobre la tradición templaria</p>
            </div>
        </div>
        
        <!-- Selector de Juegos -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="game-selector text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-danger active" onclick="showGame('quiz')">
                            <i class="bi bi-question-circle me-2"></i>Quiz
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="showGame('memory')">
                            <i class="bi bi-card-text me-2"></i>Memoria
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="showGame('sequence')">
                            <i class="bi bi-music-note-beamed me-2"></i>Secuencia
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="showGame('puzzle')">
                            <i class="bi bi-puzzle me-2"></i>Puzzle
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="showGame('clicker')">
                            <i class="bi bi-hand-index me-2"></i>Clicker
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Juego de Quiz -->
        <div class="game-container" id="quiz-game">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="quiz-container card-animated scroll-reveal">
                    <div class="quiz-header">
                        <h4>¿Cuánto sabes sobre nosotros?</h4>
                        <div class="progress mb-3">
                            <div class="progress-bar animate-pulse" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="quiz-content">
                        <div class="question" id="question1">
                                <h5>Cargando pregunta...</h5>
                            <div class="answers">
                                    <!-- Las preguntas se cargarán dinámicamente -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="quiz-results" style="display: none;">
                        <h4 class="text-center">¡Resultados!</h4>
                        <div class="score-display">
                            <h2 class="text-gradient counter" data-target="0">0</h2>
                            <p>puntos obtenidos</p>
                        </div>
                        <button class="btn btn-danger btn-animated" onclick="restartQuiz()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Jugar de Nuevo
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Juego de Memoria -->
        <div class="game-container" id="memory-game" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="memory-container card-animated scroll-reveal">
                        <div class="memory-header text-center mb-4">
                            <h4>Juego de Memoria Templaria</h4>
                            <p>Encuentra las parejas de símbolos templarios</p>
                            <div class="memory-stats">
                                <span class="badge bg-danger me-2">Movimientos: <span id="memory-moves">0</span></span>
                                <span class="badge bg-success me-2">Parejas: <span id="memory-pairs">0</span>/8</span>
                                <span class="badge bg-warning">Tiempo: <span id="memory-time">00:00</span></span>
                            </div>
                        </div>
                        <div class="memory-grid" id="memory-grid">
                            <!-- Las cartas se generarán dinámicamente -->
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-danger btn-animated" onclick="startMemoryGame()">
                                <i class="bi bi-play-circle me-2"></i>Iniciar Juego
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Juego de Secuencia Musical -->
        <div class="game-container" id="sequence-game" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="sequence-container card-animated scroll-reveal">
                        <div class="sequence-header text-center mb-4">
                            <h4>Secuencia Musical Templaria</h4>
                            <p>Reproduce la secuencia de notas musicales</p>
                            <div class="sequence-stats">
                                <span class="badge bg-danger me-2">Nivel: <span id="sequence-level">1</span></span>
                                <span class="badge bg-success me-2">Puntuación: <span id="sequence-score">0</span></span>
                            </div>
                        </div>
                        <div class="sequence-buttons">
                            <button class="btn btn-outline-danger sequence-btn" data-note="1">
                                <i class="bi bi-music-note"></i>
                            </button>
                            <button class="btn btn-outline-success sequence-btn" data-note="2">
                                <i class="bi bi-music-note"></i>
                            </button>
                            <button class="btn btn-outline-warning sequence-btn" data-note="3">
                                <i class="bi bi-music-note"></i>
                            </button>
                            <button class="btn btn-outline-danger sequence-btn" data-note="4">
                                <i class="bi bi-music-note"></i>
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-danger btn-animated" onclick="startSequenceGame()">
                                <i class="bi bi-play-circle me-2"></i>Iniciar Secuencia
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Juego de Puzzle -->
        <div class="game-container" id="puzzle-game" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="puzzle-container card-animated scroll-reveal">
                        <div class="puzzle-header text-center mb-4">
                            <h4>Puzzle del Escudo Templario</h4>
                            <p>Arma el escudo templario moviendo las piezas</p>
                            <div class="puzzle-stats">
                                <span class="badge bg-danger me-2">Movimientos: <span id="puzzle-moves">0</span></span>
                                <span class="badge bg-success me-2">Tiempo: <span id="puzzle-time">00:00</span></span>
                            </div>
                        </div>
                        <div class="puzzle-board" id="puzzle-board">
                            <!-- El puzzle se generará dinámicamente -->
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-danger btn-animated" onclick="startPuzzleGame()">
                                <i class="bi bi-shuffle me-2"></i>Mezclar y Jugar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Juego Clicker -->
        <div class="game-container" id="clicker-game" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="clicker-container card-animated scroll-reveal">
                        <div class="clicker-header text-center mb-4">
                            <h4>Recolector Templario</h4>
                            <p>Haz clic para recolectar elementos templarios</p>
                        </div>
                        <div class="clicker-stats text-center mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-danger" id="clicker-coins">0</h3>
                                        <p>Monedas</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-success" id="clicker-level">1</h3>
                                        <p>Nivel</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-warning" id="clicker-cps">1</h3>
                                        <p>Por segundo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clicker-main text-center">
                            <button class="btn btn-danger btn-lg clicker-btn" onclick="clickCoin()">
                                <i class="bi bi-coin"></i>
                            </button>
                        </div>
                        <div class="clicker-upgrades mt-4">
                            <h5>Mejoras</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-outline-danger upgrade-btn" onclick="buyUpgrade('cps')">
                                        <i class="bi bi-lightning me-2"></i>Velocidad (+1/s) - 10 monedas
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-outline-danger upgrade-btn" onclick="buyUpgrade('multiplier')">
                                        <i class="bi bi-star me-2"></i>Multiplicador x2 - 50 monedas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modales Interactivos -->
<div class="modal fade" id="interactiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para la página interactiva */
.counter-card {
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.8) 100%);
    border: 2px solid #dc143c;
    color: #000000;
    backdrop-filter: blur(10px);
}

.counter-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(220,20,60,0.2);
    background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
    color: #ffffff;
}

.interactive-card {
    height: 100%;
    border: 2px solid #dc143c;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
}

.interactive-card .card-header {
    padding: 2rem;
    text-align: center;
    border: none;
    background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
    color: #ffffff;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, #dc143c, #8b0000);
    transform: translateX(-50%);
    box-shadow: 0 0 10px rgba(220,20,60,0.5);
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
    width: 50%;
}

.timeline-item:nth-child(odd) {
    left: 0;
    padding-right: 3rem;
}

.timeline-item:nth-child(even) {
    left: 50%;
    padding-left: 3rem;
}

.timeline-marker {
    position: absolute;
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
    border-radius: 50%;
    top: 0;
    border: 4px solid #ffffff;
    box-shadow: 0 0 0 4px #dc143c, 0 0 15px rgba(220,20,60,0.6);
}

.timeline-item:nth-child(odd) .timeline-marker {
    right: -10px;
}

.timeline-item:nth-child(even) .timeline-marker {
    left: -10px;
}

.timeline-content {
    padding: 1.5rem;
    border-radius: 10px;
    background: rgba(255,255,255,0.9);
    border: 2px solid #dc143c;
    box-shadow: 0 5px 15px rgba(220,20,60,0.2);
    color: #000000;
    backdrop-filter: blur(10px);
}

.quiz-container {
    padding: 2rem;
    border-radius: 15px;
    background: rgba(255,255,255,0.9);
    border: 2px solid #dc143c;
    box-shadow: 0 10px 30px rgba(220,20,60,0.2);
    backdrop-filter: blur(10px);
}

.answers {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.answer-btn {
    padding: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 2px solid #dc143c;
    background: rgba(255,255,255,0.9);
    color: #000000;
    backdrop-filter: blur(5px);
}

.answer-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220,20,60,0.3);
    background: #dc143c;
    color: #ffffff;
}

.answer-btn.correct {
    background: #dc143c;
    color: #ffffff;
    border-color: #8b0000;
    box-shadow: 0 0 15px rgba(220,20,60,0.5);
}

.answer-btn.incorrect {
    background: #000000;
    color: #ffffff;
    border-color: #000000;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

.score-display {
    text-align: center;
    padding: 2rem;
}

/* Estilos para los juegos */
.game-selector .btn-group {
    flex-wrap: wrap;
    gap: 0.5rem;
}

.game-selector .btn-outline-danger {
    border-color: #dc143c;
    color: #dc143c;
    background: #ffffff;
    transition: all 0.3s ease;
}

.game-selector .btn-outline-danger:hover {
    background: #dc143c;
    color: #ffffff;
    border-color: #8b0000;
    box-shadow: 0 5px 15px rgba(220,20,60,0.3);
}

.game-selector .btn-outline-danger.active {
    background: #dc143c;
    color: #ffffff;
    border-color: #8b0000;
    box-shadow: 0 0 15px rgba(220,20,60,0.5);
}

.game-container {
    min-height: 400px;
}

.memory-container, .sequence-container, .puzzle-container, .clicker-container {
    padding: 2rem;
    border-radius: 15px;
    background: rgba(255,255,255,0.9);
    border: 2px solid #dc143c;
    box-shadow: 0 10px 30px rgba(220,20,60,0.2);
    backdrop-filter: blur(10px);
}

.memory-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    max-width: 400px;
    margin: 0 auto;
}

.memory-card {
    aspect-ratio: 1;
    border-radius: 10px;
    background: rgba(255,255,255,0.9);
    border: 2px solid #dc143c;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #000000;
    backdrop-filter: blur(5px);
}

.memory-card:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(220,20,60,0.3);
    background: #dc143c;
    color: #ffffff;
}

.memory-card.flipped {
    background: #dc143c;
    color: #ffffff;
    border-color: #8b0000;
    box-shadow: 0 0 15px rgba(220,20,60,0.5);
}

.memory-card.matched {
    background: #000000;
    color: #ffffff;
    border-color: #000000;
    box-shadow: 0 0 15px rgba(0,0,0,0.5);
    animation: pulse 0.5s ease-in-out;
}

.sequence-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    max-width: 300px;
    margin: 0 auto;
}

.sequence-btn {
    aspect-ratio: 1;
    font-size: 2rem;
    border-radius: 15px;
    transition: all 0.3s ease;
    border: 2px solid #dc143c;
    background: rgba(255,255,255,0.9);
    color: #000000;
    backdrop-filter: blur(5px);
}

.sequence-btn:hover {
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(220,20,60,0.3);
}

.sequence-btn.active {
    transform: scale(1.1);
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(220,20,60,0.5);
}

.sequence-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
}

.puzzle-board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
    max-width: 300px;
    margin: 0 auto;
    background: #000000;
    padding: 2px;
    border-radius: 10px;
    border: 2px solid #dc143c;
}

.puzzle-piece {
    aspect-ratio: 1;
    background: rgba(255,255,255,0.9);
    border: 1px solid #dc143c;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #000000;
    backdrop-filter: blur(5px);
}

.puzzle-piece:hover {
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 0 10px rgba(220,20,60,0.5);
}

.puzzle-piece.empty {
    background: #000000;
    cursor: default;
    border-color: #000000;
}

.puzzle-piece.correct {
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 0 15px rgba(220,20,60,0.7);
}

.clicker-btn {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    font-size: 3rem;
    transition: all 0.2s ease;
    border: 4px solid #dc143c;
    background: #ffffff;
    color: #000000;
}

.clicker-btn:hover {
    transform: scale(1.1);
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(220,20,60,0.4);
}

.clicker-btn:active {
    transform: scale(0.95);
    background: #8b0000;
}

.stat-item {
    padding: 1rem;
    border-radius: 10px;
    background: rgba(255,255,255,0.9);
    border: 2px solid #dc143c;
    margin-bottom: 1rem;
    color: #000000;
    backdrop-filter: blur(5px);
}

.upgrade-btn {
    width: 100%;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border: 2px solid #dc143c;
    background: rgba(255,255,255,0.9);
    color: #000000;
    backdrop-filter: blur(5px);
}

.upgrade-btn:hover {
    transform: translateY(-2px);
    background: #dc143c;
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(220,20,60,0.3);
}

.upgrade-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@media (max-width: 768px) {
    .timeline::before {
        left: 20px;
    }
    
    .timeline-item {
        width: 100%;
        left: 0 !important;
        padding-left: 3rem !important;
        padding-right: 0 !important;
    }
    
    .timeline-marker {
        left: 10px !important;
    }
    
    .memory-grid {
        grid-template-columns: repeat(3, 1fr);
        max-width: 300px;
    }
    
    .sequence-buttons {
        grid-template-columns: repeat(2, 1fr);
        max-width: 250px;
    }
    
    .puzzle-board {
        max-width: 250px;
    }
    
    .clicker-btn {
        width: 120px;
        height: 120px;
        font-size: 2.5rem;
    }
}

/* Estilos para las actividades divertidas */
.text-purple {
    color: #6f42c1 !important;
}

.fun-activities-section .card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.fun-activities-section .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.memory-card {
    transition: all 0.3s ease;
    user-select: none;
}

.memory-card:hover {
    transform: scale(1.05);
}

.memory-card.flipped {
    background: #28a745 !important;
    transform: rotateY(180deg);
}

.battle-arena {
    border-radius: 15px;
    background: linear-gradient(135deg, #2c3e50, #34495e) !important;
}

.shield-preview {
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.shield-preview:hover {
    transform: scale(1.1);
}

.quiz-question {
    border-radius: 10px;
    border-left: 4px solid #dc143c;
}

.alert {
    border-radius: 10px;
    border: none;
}

.progress {
    height: 8px;
    border-radius: 10px;
    background-color: rgba(255,255,255,0.2);
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.5s ease;
}

/* Animaciones personalizadas */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(220, 20, 60, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(220, 20, 60, 0.8);
    }
}

.animate-glow {
    animation: glow 2s ease-in-out infinite;
}

/* Efectos de hover mejorados */
.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

/* Estilos para formularios */
.form-control-color {
    width: 50px;
    height: 38px;
    padding: 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.form-select-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

/* Estilos para nuevas actividades */
.castle-preview {
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.castle-preview:hover {
    transform: scale(1.05);
}

.tournament-bracket {
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
}

.match {
    padding: 0.5rem;
    border-radius: 8px;
    background: white;
    border: 1px solid #dee2e6;
}

.armor-preview {
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.armor-preview:hover {
    transform: scale(1.05);
}

.mission-details {
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
}

.mission-info .col-4 {
    border-right: 1px solid #dee2e6;
}

.mission-info .col-4:last-child {
    border-right: none;
}

.form-range {
    height: 6px;
    background: #dee2e6;
    border-radius: 3px;
}

.form-range::-webkit-slider-thumb {
    background: #dc143c;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.form-range::-moz-range-thumb {
    background: #dc143c;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Responsive para actividades */
@media (max-width: 768px) {
    .memory-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
    
    .memory-card {
        width: 40px !important;
        height: 40px !important;
        font-size: 1.2rem !important;
    }
    
    .shield-preview {
        width: 80px !important;
        height: 96px !important;
    }
    
    .castle-preview {
        width: 150px !important;
        height: 112px !important;
    }
    
    .armor-preview {
        width: 100px !important;
        height: 133px !important;
    }
    
    .battle-arena .row {
        flex-direction: column;
    }
    
    .battle-arena .col-6 {
        width: 100% !important;
        margin-bottom: 1rem;
    }
    
    .mission-info .col-4 {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .mission-info .col-4:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
}
</style>

<script>
// Funciones para la página interactiva
function showModal(type) {
    const modal = new bootstrap.Modal(document.getElementById('interactiveModal'));
    const title = document.getElementById('modalTitle');
    const content = document.getElementById('modalContent');
    
    switch(type) {
        case 'templarios':
            title.textContent = 'Orígenes Templarios';
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             class="img-fluid rounded" alt="Templarios">
                    </div>
                    <div class="col-md-6">
                        <h6>Nuestra Historia</h6>
                        <p>La Filá Mariscales de Caballeros Templarios fue fundada en 1975, inspirada en la noble tradición de los caballeros templarios medievales.</p>
                        <ul>
                            <li>Fundación: 1975</li>
                            <li>Valores: Honor, Lealtad, Tradición</li>
                            <li>Colores: Rojo y Negro</li>
                            <li>Símbolo: Cruz Templaria</li>
                        </ul>
                    </div>
                </div>
            `;
            break;
        case 'bandas':
            title.textContent = 'Bandas de Música';
            content.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-music-note-beamed fs-1 text-danger mb-3"></i>
                    <h6>Nuestras Bandas</h6>
                    <p>Contamos con varias bandas de música que nos acompañan en todos nuestros desfiles y eventos.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Banda Principal</h6>
                            <p>La banda principal de la filá</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Banda Juvenil</h6>
                            <p>Formada por nuestros miembros más jóvenes</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Banda de Tambores</h6>
                            <p>Especializada en percusión</p>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'eventos':
            title.textContent = 'Eventos Anuales';
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Eventos Principales</h6>
                        <ul>
                            <li>Fiestas de Moros y Cristianos (Abril)</li>
                            <li>Cena Anual de Hermandad (Marzo)</li>
                            <li>Ensayos Generales (Febrero)</li>
                            <li>Celebración de San Jorge (Abril)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Actividades Regulares</h6>
                        <ul>
                            <li>Ensayos semanales</li>
                            <li>Reuniones de directiva</li>
                            <li>Actividades sociales</li>
                            <li>Eventos benéficos</li>
                        </ul>
                    </div>
                </div>
            `;
            break;
    }
    
    modal.show();
}

// Funciones del quiz
let currentQuestion = 0;
let score = 0;
let quizQuestions = [
    {
        question: "¿En qué año se fundó la Filá Mariscales?",
        answers: ["1975", "1980", "1970", "1985"],
        correct: 0,
        explanation: "La Filá Mariscales de Caballeros Templarios fue fundada en 1975."
    },
    {
        question: "¿Cuáles son los colores principales de la filá?",
        answers: ["Rojo y Negro", "Azul y Blanco", "Verde y Dorado", "Morado y Plateado"],
        correct: 0,
        explanation: "Los colores tradicionales de la Filá Mariscales son el rojo y el negro."
    },
    {
        question: "¿Qué símbolo representa a los Caballeros Templarios?",
        answers: ["Cruz Templaria", "Águila Imperial", "León Rampante", "Corona Real"],
        correct: 0,
        explanation: "La Cruz Templaria es el símbolo distintivo de los Caballeros Templarios."
    },
    {
        question: "¿En qué ciudad se encuentra la Filá Mariscales?",
        answers: ["Elche", "Alicante", "Valencia", "Murcia"],
        correct: 0,
        explanation: "La Filá Mariscales tiene su sede en la ciudad de Elche."
    },
    {
        question: "¿Cuántos años de tradición tiene la filá?",
        answers: ["50 años", "45 años", "40 años", "55 años"],
        correct: 0,
        explanation: "Desde su fundación en 1975, la filá tiene más de 50 años de tradición."
    },
    {
        question: "¿Qué tipo de música acompaña a la filá?",
        answers: ["Bandas de música", "Orquesta sinfónica", "Música electrónica", "Coro a capella"],
        correct: 0,
        explanation: "Las bandas de música son una parte fundamental de la tradición de la filá."
    },
    {
        question: "¿Cuál es el evento principal en el que participa la filá?",
        answers: ["Fiestas de Moros y Cristianos", "Feria de Abril", "Fallas de Valencia", "Semana Santa"],
        correct: 0,
        explanation: "Las Fiestas de Moros y Cristianos son el evento principal de la filá."
    },
    {
        question: "¿Qué valores representa la filá?",
        answers: ["Honor, Lealtad y Tradición", "Poder, Riqueza y Gloria", "Libertad, Igualdad y Fraternidad", "Paz, Amor y Armonía"],
        correct: 0,
        explanation: "Los valores fundamentales de la filá son el Honor, la Lealtad y la Tradición."
    },
    {
        question: "¿En qué mes se celebran principalmente las Fiestas de Moros y Cristianos?",
        answers: ["Abril", "Mayo", "Junio", "Julio"],
        correct: 0,
        explanation: "Las Fiestas de Moros y Cristianos se celebran principalmente en el mes de abril."
    },
    {
        question: "¿Qué tipo de desfile es característico de la filá?",
        answers: ["Desfile de Entrada", "Desfile de Salida", "Desfile de Gala", "Desfile de Despedida"],
        correct: 0,
        explanation: "El Desfile de Entrada es uno de los más característicos y esperados de la filá."
    },
    {
        question: "¿Cuántos miembros activos tiene aproximadamente la filá?",
        answers: ["150 miembros", "100 miembros", "200 miembros", "250 miembros"],
        correct: 0,
        explanation: "La Filá Mariscales cuenta con aproximadamente 150 miembros activos."
    },
    {
        question: "¿Qué instrumento musical es fundamental en las bandas de la filá?",
        answers: ["Tambor", "Guitarra", "Piano", "Violín"],
        correct: 0,
        explanation: "El tambor es un instrumento fundamental en las bandas de música de la filá."
    },
    {
        question: "¿En qué década se expandió significativamente la filá?",
        answers: ["1990s", "1980s", "2000s", "2010s"],
        correct: 0,
        explanation: "En la década de 1990, la filá se expandió significativamente y creó nuevas secciones."
    },
    {
        question: "¿Qué actividad se realiza durante los ensayos generales?",
        answers: ["Preparación para desfiles", "Reuniones de directiva", "Actividades sociales", "Eventos benéficos"],
        correct: 0,
        explanation: "Los ensayos generales se realizan para preparar a todos los miembros para los desfiles."
    },
    {
        question: "¿Cuál es el nombre completo de la filá?",
        answers: ["Filá Mariscales de Caballeros Templarios", "Filá Mariscales de Elche", "Filá Templarios de Mariscales", "Filá Caballeros de Mariscales"],
        correct: 0,
        explanation: "El nombre completo es 'Filá Mariscales de Caballeros Templarios'."
    },
    {
        question: "¿Qué tipo de premios ha obtenido la filá?",
        answers: ["Premios de desfiles", "Premios musicales", "Premios deportivos", "Premios literarios"],
        correct: 0,
        explanation: "La filá ha obtenido numerosos premios en los desfiles de Moros y Cristianos."
    },
    {
        question: "¿En qué año participó por primera vez en las Fiestas de Moros y Cristianos?",
        answers: ["1980", "1975", "1985", "1990"],
        correct: 0,
        explanation: "La filá participó por primera vez en las Fiestas de Moros y Cristianos en 1980."
    },
    {
        question: "¿Qué representa el escudo de la filá?",
        answers: ["Símbolos templarios", "Símbolos musicales", "Símbolos de Elche", "Símbolos de Valencia"],
        correct: 0,
        explanation: "El escudo de la filá contiene símbolos relacionados con la tradición templaria."
    },
    {
        question: "¿Cuántas bandas de música tiene la filá?",
        answers: ["Varias bandas", "Una sola banda", "Dos bandas", "Tres bandas"],
        correct: 0,
        explanation: "La filá cuenta con varias bandas de música, incluyendo la banda principal y la juvenil."
    },
    {
        question: "¿Qué evento se celebra en marzo?",
        answers: ["Cena Anual de Hermandad", "Ensayos Generales", "Fiestas de Moros y Cristianos", "Celebración de San Jorge"],
        correct: 0,
        explanation: "La Cena Anual de Hermandad se celebra tradicionalmente en el mes de marzo."
    },
    {
        question: "¿Qué modernización se incorporó en 2020?",
        answers: ["Nuevas tecnologías digitales", "Nuevos instrumentos musicales", "Nuevos uniformes", "Nueva sede"],
        correct: 0,
        explanation: "En 2020 se incorporaron nuevas tecnologías y se mejoró la presencia digital de la filá."
    },
    {
        question: "¿Cuál es el objetivo principal de la filá?",
        answers: ["Mantener la tradición templaria", "Ganar premios", "Hacer música", "Organizar eventos"],
        correct: 0,
        explanation: "El objetivo principal es mantener y transmitir la tradición templaria a las nuevas generaciones."
    },
    {
        question: "¿Qué tipo de actividades sociales realiza la filá?",
        answers: ["Eventos de hermandad", "Conciertos públicos", "Competiciones deportivas", "Exposiciones de arte"],
        correct: 0,
        explanation: "La filá realiza diversos eventos de hermandad para fortalecer los lazos entre sus miembros."
    },
    {
        question: "¿En qué consiste la celebración de San Jorge?",
        answers: ["Festividad religiosa y cultural", "Concierto musical", "Desfile especial", "Cena de gala"],
        correct: 0,
        explanation: "La celebración de San Jorge es una festividad que combina aspectos religiosos y culturales."
    },
    {
        question: "¿Qué significa 'Filá' en el contexto de las fiestas?",
        answers: ["Grupo o comparsa", "Banda de música", "Tipo de desfile", "Evento festivo"],
        correct: 0,
        explanation: "'Filá' se refiere al grupo o comparsa que participa en las Fiestas de Moros y Cristianos."
    }
];

function checkAnswer(button) {
    const isCorrect = button.getAttribute('data-correct') === 'true';
    
    // Deshabilitar todos los botones
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.disabled = true;
        if (btn.getAttribute('data-correct') === 'true') {
            btn.classList.add('correct');
        } else if (btn === button && !isCorrect) {
            btn.classList.add('incorrect');
        }
    });
    
    if (isCorrect) {
        score += 10;
        showNotification('¡Correcto! +10 puntos', 'success');
    } else {
        const correctAnswer = quizQuestions[currentQuestion].answers[quizQuestions[currentQuestion].correct];
        showNotification(`Incorrecto. La respuesta correcta era: ${correctAnswer}`, 'error');
    }
    
    // Actualizar progreso
    const progressBar = document.querySelector('.progress-bar');
    const progress = ((currentQuestion + 1) / quizQuestions.length) * 100;
    progressBar.style.width = progress + '%';
    
    // Mostrar siguiente pregunta o resultados
    setTimeout(() => {
        if (currentQuestion < quizQuestions.length - 1) {
            nextQuestion();
        } else {
        showResults();
        }
    }, 2000);
}

function nextQuestion() {
    currentQuestion++;
    loadQuestion(currentQuestion);
    
    // Resetear botones
    document.querySelectorAll('.answer-btn').forEach(btn => {
        btn.disabled = false;
        btn.classList.remove('correct', 'incorrect');
    });
}

function loadQuestion(questionIndex) {
    const question = quizQuestions[questionIndex];
    const questionElement = document.querySelector('.question h5');
    const answersContainer = document.querySelector('.answers');
    
    questionElement.textContent = question.question;
    answersContainer.innerHTML = '';
    
    question.answers.forEach((answer, index) => {
        const button = document.createElement('button');
        button.className = 'btn btn-outline-danger answer-btn';
        button.textContent = answer;
        button.setAttribute('data-correct', index === question.correct ? 'true' : 'false');
        button.onclick = () => checkAnswer(button);
        answersContainer.appendChild(button);
    });
}

function showResults() {
    document.querySelector('.quiz-content').style.display = 'none';
    document.querySelector('.quiz-results').style.display = 'block';
    
    // Animar contador de puntuación
    const scoreElement = document.querySelector('.score-display .counter');
    scoreElement.setAttribute('data-target', score);
    
    // Iniciar animación del contador
    animateCounter(scoreElement);
}

function restartQuiz() {
    currentQuestion = 0;
    score = 0;
    
    document.querySelector('.quiz-content').style.display = 'block';
    document.querySelector('.quiz-results').style.display = 'none';
    
    // Cargar la primera pregunta
    loadQuestion(0);
    
    // Resetear progreso
    document.querySelector('.progress-bar').style.width = '0%';
}

// Variables globales para los juegos
let currentGame = 'quiz';
let memoryGame = {
    cards: [],
    flippedCards: [],
    matchedPairs: 0,
    moves: 0,
    startTime: null,
    timer: null
};

let sequenceGame = {
    sequence: [],
    playerSequence: [],
    level: 1,
    score: 0,
    isPlaying: false,
    isPlayerTurn: false
};

let puzzleGame = {
    board: [],
    emptyIndex: 8,
    moves: 0,
    startTime: null,
    timer: null
};

let clickerGame = {
    coins: 0,
    level: 1,
    coinsPerSecond: 1,
    multiplier: 1,
    upgrades: {
        cps: { cost: 10, level: 0 },
        multiplier: { cost: 50, level: 0 }
    },
    interval: null
};

// Función para cambiar entre juegos
function showGame(gameType) {
    // Ocultar todos los juegos
    document.querySelectorAll('.game-container').forEach(game => {
        game.style.display = 'none';
    });
    
    // Remover clase active de todos los botones
    document.querySelectorAll('.game-selector .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar el juego seleccionado
    document.getElementById(gameType + '-game').style.display = 'block';
    
    // Activar el botón correspondiente
    event.target.classList.add('active');
    
    currentGame = gameType;
}

// === JUEGO DE MEMORIA ===
function startMemoryGame() {
    const symbols = ['🛡️', '⚔️', '🏰', '👑', '⚡', '🔥', '⭐', '💎'];
    const cards = [...symbols, ...symbols]; // Duplicar para hacer parejas
    
    // Mezclar las cartas
    for (let i = cards.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [cards[i], cards[j]] = [cards[j], cards[i]];
    }
    
    memoryGame.cards = cards;
    memoryGame.flippedCards = [];
    memoryGame.matchedPairs = 0;
    memoryGame.moves = 0;
    memoryGame.startTime = Date.now();
    
    // Crear el grid de cartas
    const grid = document.getElementById('memory-grid');
    grid.innerHTML = '';
    
    cards.forEach((symbol, index) => {
        const card = document.createElement('div');
        card.className = 'memory-card';
        card.dataset.index = index;
        card.dataset.symbol = symbol;
        card.innerHTML = '?';
        card.onclick = () => flipCard(index);
        grid.appendChild(card);
    });
    
    // Iniciar timer
    memoryGame.timer = setInterval(updateMemoryTimer, 1000);
    updateMemoryStats();
}

function flipCard(index) {
    const card = document.querySelector(`[data-index="${index}"]`);
    if (card.classList.contains('flipped') || card.classList.contains('matched')) return;
    
    card.classList.add('flipped');
    card.innerHTML = card.dataset.symbol;
    memoryGame.flippedCards.push(index);
    
    if (memoryGame.flippedCards.length === 2) {
        memoryGame.moves++;
        updateMemoryStats();
        
        setTimeout(() => {
            checkMemoryMatch();
        }, 1000);
    }
}

function checkMemoryMatch() {
    const [index1, index2] = memoryGame.flippedCards;
    const card1 = document.querySelector(`[data-index="${index1}"]`);
    const card2 = document.querySelector(`[data-index="${index2}"]`);
    
    if (card1.dataset.symbol === card2.dataset.symbol) {
        card1.classList.add('matched');
        card2.classList.add('matched');
        memoryGame.matchedPairs++;
        
        if (memoryGame.matchedPairs === 8) {
            clearInterval(memoryGame.timer);
            showNotification('¡Felicidades! Has completado el juego de memoria', 'success');
        }
    } else {
        card1.classList.remove('flipped');
        card2.classList.remove('flipped');
        card1.innerHTML = '?';
        card2.innerHTML = '?';
    }
    
    memoryGame.flippedCards = [];
    updateMemoryStats();
}

function updateMemoryStats() {
    document.getElementById('memory-moves').textContent = memoryGame.moves;
    document.getElementById('memory-pairs').textContent = memoryGame.matchedPairs;
}

function updateMemoryTimer() {
    if (!memoryGame.startTime) return;
    
    const elapsed = Math.floor((Date.now() - memoryGame.startTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    
    document.getElementById('memory-time').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// === JUEGO DE SECUENCIA ===
function startSequenceGame() {
    sequenceGame.sequence = [];
    sequenceGame.playerSequence = [];
    sequenceGame.level = 1;
    sequenceGame.score = 0;
    sequenceGame.isPlaying = true;
    sequenceGame.isPlayerTurn = false;
    
    addToSequence();
    playSequence();
    updateSequenceStats();
}

function addToSequence() {
    const randomNote = Math.floor(Math.random() * 4) + 1;
    sequenceGame.sequence.push(randomNote);
}

function playSequence() {
    let index = 0;
    const playNext = () => {
        if (index < sequenceGame.sequence.length) {
            const note = sequenceGame.sequence[index];
            const button = document.querySelector(`[data-note="${note}"]`);
            
            button.classList.add('active');
            setTimeout(() => {
                button.classList.remove('active');
                index++;
                setTimeout(playNext, 300);
            }, 500);
        } else {
            sequenceGame.isPlayerTurn = true;
            enableSequenceButtons();
        }
    };
    
    disableSequenceButtons();
    setTimeout(playNext, 1000);
}

function enableSequenceButtons() {
    document.querySelectorAll('.sequence-btn').forEach(btn => {
        btn.disabled = false;
        btn.classList.remove('disabled');
    });
}

function disableSequenceButtons() {
    document.querySelectorAll('.sequence-btn').forEach(btn => {
        btn.disabled = true;
        btn.classList.add('disabled');
    });
}

function playSequenceNote(note) {
    if (!sequenceGame.isPlayerTurn) return;
    
    const button = document.querySelector(`[data-note="${note}"]`);
    button.classList.add('active');
    setTimeout(() => button.classList.remove('active'), 200);
    
    sequenceGame.playerSequence.push(note);
    
    const currentIndex = sequenceGame.playerSequence.length - 1;
    if (sequenceGame.playerSequence[currentIndex] !== sequenceGame.sequence[currentIndex]) {
        // Error
        showNotification('Secuencia incorrecta. ¡Inténtalo de nuevo!', 'error');
        startSequenceGame();
        return;
    }
    
    if (sequenceGame.playerSequence.length === sequenceGame.sequence.length) {
        // Nivel completado
        sequenceGame.score += sequenceGame.level * 10;
        sequenceGame.level++;
        sequenceGame.playerSequence = [];
        
        setTimeout(() => {
            addToSequence();
            playSequence();
            updateSequenceStats();
        }, 1000);
    }
}

function updateSequenceStats() {
    document.getElementById('sequence-level').textContent = sequenceGame.level;
    document.getElementById('sequence-score').textContent = sequenceGame.score;
}

// === JUEGO DE PUZZLE ===
function startPuzzleGame() {
    const numbers = [1, 2, 3, 4, 5, 6, 7, 8, 0]; // 0 es el espacio vacío
    
    // Mezclar los números
    for (let i = numbers.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [numbers[i], numbers[j]] = [numbers[j], numbers[i]];
    }
    
    puzzleGame.board = numbers;
    puzzleGame.emptyIndex = numbers.indexOf(0);
    puzzleGame.moves = 0;
    puzzleGame.startTime = Date.now();
    
    renderPuzzle();
    updatePuzzleStats();
    
    // Iniciar timer
    puzzleGame.timer = setInterval(updatePuzzleTimer, 1000);
}

function renderPuzzle() {
    const board = document.getElementById('puzzle-board');
    board.innerHTML = '';
    
    puzzleGame.board.forEach((number, index) => {
        const piece = document.createElement('button');
        piece.className = 'puzzle-piece';
        
        if (number === 0) {
            piece.classList.add('empty');
            piece.innerHTML = '';
        } else {
            piece.innerHTML = number;
            piece.onclick = () => movePuzzlePiece(index);
        }
        
        board.appendChild(piece);
    });
}

function movePuzzlePiece(index) {
    const emptyIndex = puzzleGame.emptyIndex;
    const row = Math.floor(index / 3);
    const col = index % 3;
    const emptyRow = Math.floor(emptyIndex / 3);
    const emptyCol = emptyIndex % 3;
    
    // Verificar si es un movimiento válido (adyacente)
    const isValidMove = (Math.abs(row - emptyRow) === 1 && col === emptyCol) ||
                       (Math.abs(col - emptyCol) === 1 && row === emptyRow);
    
    if (isValidMove) {
        // Intercambiar piezas
        [puzzleGame.board[index], puzzleGame.board[emptyIndex]] = 
        [puzzleGame.board[emptyIndex], puzzleGame.board[index]];
        
        puzzleGame.emptyIndex = index;
        puzzleGame.moves++;
        
        renderPuzzle();
        updatePuzzleStats();
        
        // Verificar si el puzzle está resuelto
        if (isPuzzleSolved()) {
            clearInterval(puzzleGame.timer);
            showNotification('¡Felicidades! Has resuelto el puzzle', 'success');
        }
    }
}

function isPuzzleSolved() {
    for (let i = 0; i < 8; i++) {
        if (puzzleGame.board[i] !== i + 1) return false;
    }
    return puzzleGame.board[8] === 0;
}

function updatePuzzleStats() {
    document.getElementById('puzzle-moves').textContent = puzzleGame.moves;
}

function updatePuzzleTimer() {
    if (!puzzleGame.startTime) return;
    
    const elapsed = Math.floor((Date.now() - puzzleGame.startTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    
    document.getElementById('puzzle-time').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// === JUEGO CLICKER ===
function clickCoin() {
    clickerGame.coins += clickerGame.multiplier;
    updateClickerStats();
    
    // Efecto visual
    const btn = document.querySelector('.clicker-btn');
    btn.style.transform = 'scale(0.95)';
    setTimeout(() => {
        btn.style.transform = 'scale(1)';
    }, 100);
    
    // Verificar subida de nivel
    if (clickerGame.coins >= clickerGame.level * 100) {
        clickerGame.level++;
        showNotification(`¡Subiste al nivel ${clickerGame.level}!`, 'success');
    }
}

function buyUpgrade(type) {
    const upgrade = clickerGame.upgrades[type];
    
    if (clickerGame.coins >= upgrade.cost) {
        clickerGame.coins -= upgrade.cost;
        upgrade.level++;
        upgrade.cost = Math.floor(upgrade.cost * 1.5);
        
        if (type === 'cps') {
            clickerGame.coinsPerSecond++;
        } else if (type === 'multiplier') {
            clickerGame.multiplier *= 2;
        }
        
        updateClickerStats();
        updateUpgradeButtons();
        showNotification(`¡Mejora ${type} comprada!`, 'success');
    }
}

function updateClickerStats() {
    document.getElementById('clicker-coins').textContent = clickerGame.coins;
    document.getElementById('clicker-level').textContent = clickerGame.level;
    document.getElementById('clicker-cps').textContent = clickerGame.coinsPerSecond;
}

function updateUpgradeButtons() {
    const cpsBtn = document.querySelector('[onclick="buyUpgrade(\'cps\')"]');
    const multBtn = document.querySelector('[onclick="buyUpgrade(\'multiplier\')"]');
    
    cpsBtn.innerHTML = `<i class="bi bi-lightning me-2"></i>Velocidad (+1/s) - ${clickerGame.upgrades.cps.cost} monedas`;
    multBtn.innerHTML = `<i class="bi bi-star me-2"></i>Multiplicador x2 - ${clickerGame.upgrades.multiplier.cost} monedas`;
    
    cpsBtn.disabled = clickerGame.coins < clickerGame.upgrades.cps.cost;
    multBtn.disabled = clickerGame.coins < clickerGame.upgrades.multiplier.cost;
}

function startClickerGame() {
    if (clickerGame.interval) return;
    
    clickerGame.interval = setInterval(() => {
        clickerGame.coins += clickerGame.coinsPerSecond;
        updateClickerStats();
        updateUpgradeButtons();
    }, 1000);
}

// Función para mostrar notificaciones
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Agregar event listeners a los botones de respuesta
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el quiz con la primera pregunta
    loadQuestion(0);
    
    // Event listeners para el juego de secuencia
    document.querySelectorAll('.sequence-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const note = parseInt(this.dataset.note);
            playSequenceNote(note);
        });
    });
    
    // Inicializar juego clicker
    startClickerGame();
});

// ==================== FUNCIONES PARA ACTIVIDADES DIVERTIDAS ====================

// Generador de Nombres Templarios
function generateTemplarName() {
    const prefixes = ['Sir', 'Lord', 'Sir', 'Don', 'Maestro', 'Gran'];
    const names = ['Arturo', 'Lancelot', 'Galahad', 'Percival', 'Gawain', 'Tristán', 'Bors', 'Bedivere', 'Mordred', 'Agravain'];
    const surnames = ['de la Cruz', 'del Escudo', 'de la Espada', 'de la Torre', 'del León', 'del Águila', 'de la Rosa', 'del Dragón', 'de la Corona', 'del Sol'];
    
    const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    const name = names[Math.floor(Math.random() * names.length)];
    const surname = surnames[Math.floor(Math.random() * surnames.length)];
    
    const fullName = `${prefix} ${name} ${surname}`;
    
    const nameDiv = document.getElementById('templarName');
    nameDiv.querySelector('h6').textContent = fullName;
    nameDiv.style.display = 'block';
    
    // Efecto de aparición
    nameDiv.style.opacity = '0';
    nameDiv.style.transform = 'translateY(20px)';
    setTimeout(() => {
        nameDiv.style.transition = 'all 0.5s ease';
        nameDiv.style.opacity = '1';
        nameDiv.style.transform = 'translateY(0)';
    }, 100);
}

// Constructor de Castillos
function startCastleBuilder() {
    document.getElementById('castleContainer').style.display = 'block';
    updateCastle();
}

function updateCastle() {
    const castleColor = document.getElementById('castleColor').value;
    const flagType = document.getElementById('flagType').value;
    const castleSize = document.getElementById('castleSize').value;
    
    const castle = document.getElementById('castlePreview');
    const towers = castle.querySelectorAll('.castle-tower');
    const flag = castle.querySelector('.castle-flag');
    
    castle.style.width = castleSize + 'px';
    castle.style.background = `linear-gradient(45deg, ${castleColor}, ${darkenColor(castleColor, 20)})`;
    castle.style.borderColor = darkenColor(castleColor, 30);
    
    towers.forEach(tower => {
        tower.style.background = castleColor;
    });
    
    flag.textContent = flagType;
}

// Simulador de Batalla
let templarHealth = 100;
let enemyHealth = 100;
let battleActive = false;

function startBattle() {
    templarHealth = 100;
    enemyHealth = 100;
    battleActive = true;
    document.getElementById('battleContainer').style.display = 'block';
    updateBattleDisplay();
}

function attack() {
    if (!battleActive) return;
    
    // Daño del templario al enemigo
    const templarDamage = Math.floor(Math.random() * 20) + 10;
    enemyHealth = Math.max(0, enemyHealth - templarDamage);
    
    // Daño del enemigo al templario
    const enemyDamage = Math.floor(Math.random() * 15) + 5;
    templarHealth = Math.max(0, templarHealth - enemyDamage);
    
    updateBattleDisplay();
    
    if (templarHealth <= 0) {
        showBattleResult('¡Has sido derrotado! 💀', 'danger');
    } else if (enemyHealth <= 0) {
        showBattleResult('¡Victoria! Has derrotado al enemigo! ⚔️', 'success');
    }
}

function updateBattleDisplay() {
    document.getElementById('templarHealth').style.width = templarHealth + '%';
    document.getElementById('templarHealthText').textContent = templarHealth;
    document.getElementById('enemyHealth').style.width = enemyHealth + '%';
    document.getElementById('enemyHealthText').textContent = enemyHealth;
}

function showBattleResult(message, type) {
    battleActive = false;
    const result = document.createElement('div');
    result.className = `alert alert-${type} mt-3`;
    result.textContent = message;
    document.getElementById('battleContainer').appendChild(result);
}

// Simulador de Torneo
let tournamentData = {
    currentRound: 1,
    playerScore: 0,
    opponentScore: 0,
    finalPlayerScore: 0,
    finalOpponentScore: 0,
    isFinal: false
};

function startTournament() {
    tournamentData = {
        currentRound: 1,
        playerScore: 0,
        opponentScore: 0,
        finalPlayerScore: 0,
        finalOpponentScore: 0,
        isFinal: false
    };
    
    document.getElementById('tournamentContainer').style.display = 'block';
    document.getElementById('tournament-results').style.display = 'none';
    updateTournamentDisplay();
}

function playMatch() {
    if (!tournamentData.isFinal) {
        // Ronda 1
        const playerRoll = Math.floor(Math.random() * 20) + 1;
        const opponentRoll = Math.floor(Math.random() * 20) + 1;
        
        tournamentData.playerScore += playerRoll;
        tournamentData.opponentScore += opponentRoll;
        
        if (tournamentData.playerScore >= 50 || tournamentData.opponentScore >= 50) {
            tournamentData.isFinal = true;
            document.getElementById('playBtn').textContent = 'Jugar Final';
            document.getElementById('finalPlayer').textContent = tournamentData.playerScore > tournamentData.opponentScore ? 'Tú' : 'Sir Lancelot';
        }
    } else {
        // Final
        const playerRoll = Math.floor(Math.random() * 20) + 1;
        const opponentRoll = Math.floor(Math.random() * 20) + 1;
        
        tournamentData.finalPlayerScore += playerRoll;
        tournamentData.finalOpponentScore += opponentRoll;
        
        if (tournamentData.finalPlayerScore >= 30 || tournamentData.finalOpponentScore >= 30) {
            showTournamentResults();
            return;
        }
    }
    
    updateTournamentDisplay();
}

function updateTournamentDisplay() {
    document.getElementById('score1').textContent = tournamentData.playerScore;
    document.getElementById('score2').textContent = tournamentData.opponentScore;
    document.getElementById('finalScore1').textContent = tournamentData.finalPlayerScore;
    document.getElementById('finalScore2').textContent = tournamentData.finalOpponentScore;
}

function showTournamentResults() {
    const isWinner = tournamentData.finalPlayerScore > tournamentData.finalOpponentScore;
    const result = isWinner ? '¡Felicidades! Has ganado el torneo! 🏆' : 'Has sido derrotado en la final. ¡Inténtalo de nuevo! ⚔️';
    
    document.getElementById('tournamentResult').textContent = result;
    document.getElementById('tournament-results').style.display = 'block';
    document.getElementById('playBtn').style.display = 'none';
}

// Creador de Escudos
function startShieldCreator() {
    document.getElementById('shieldContainer').style.display = 'block';
    updateShield();
}

function updateShield() {
    const primaryColor = document.getElementById('primaryColor').value;
    const symbol = document.getElementById('shieldSymbol').value;
    
    const shield = document.getElementById('shieldPreview');
    const symbolDiv = shield.querySelector('.shield-symbol');
    
    shield.style.background = `linear-gradient(45deg, ${primaryColor}, ${darkenColor(primaryColor, 20)})`;
    symbolDiv.textContent = symbol;
}

function darkenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) - amt;
    const G = (num >> 8 & 0x00FF) - amt;
    const B = (num & 0x0000FF) - amt;
    return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
}

// Creador de Armaduras
function startArmorCreator() {
    document.getElementById('armorContainer').style.display = 'block';
    updateArmor();
}

function updateArmor() {
    const material = document.getElementById('armorMaterial').value;
    const symbol = document.getElementById('armorSymbol').value;
    const level = document.getElementById('armorLevel').value;
    
    const armor = document.getElementById('armorPreview');
    const helmet = armor.querySelector('.armor-helmet');
    const body = armor.querySelector('.armor-body');
    const symbolDiv = armor.querySelector('.armor-symbol');
    
    // Actualizar colores según el material
    helmet.style.background = material;
    body.style.background = material;
    armor.style.background = `linear-gradient(45deg, ${material}, ${darkenColor(material, 20)})`;
    armor.style.borderColor = darkenColor(material, 30);
    
    // Actualizar símbolo
    symbolDiv.textContent = symbol;
    
    // Actualizar nivel de protección (tamaño)
    const size = 120 + (level * 10);
    armor.style.width = size + 'px';
    armor.style.height = (size * 1.33) + 'px';
    
    // Actualizar texto del nivel
    document.getElementById('armorLevelText').textContent = level;
}

// Simulador de Misiones
let missionData = {
    currentMission: null,
    progress: 0,
    isActive: false
};

const missions = [
    {
        title: "Rescate de la Princesa",
        difficulty: "Fácil",
        reward: "50 oro",
        duration: "2 días",
        maxProgress: 100
    },
    {
        title: "Defensa del Castillo",
        difficulty: "Media",
        reward: "100 oro",
        duration: "3 días",
        maxProgress: 100
    },
    {
        title: "Búsqueda del Santo Grial",
        difficulty: "Difícil",
        reward: "200 oro",
        duration: "5 días",
        maxProgress: 100
    },
    {
        title: "Batalla contra el Dragón",
        difficulty: "Épica",
        reward: "500 oro",
        duration: "7 días",
        maxProgress: 100
    }
];

function startMission() {
    // Seleccionar misión aleatoria
    missionData.currentMission = missions[Math.floor(Math.random() * missions.length)];
    missionData.progress = 0;
    missionData.isActive = true;
    
    document.getElementById('missionContainer').style.display = 'block';
    document.getElementById('mission-results').style.display = 'none';
    
    // Actualizar información de la misión
    document.getElementById('missionTitle').textContent = missionData.currentMission.title;
    document.getElementById('missionDifficulty').textContent = missionData.currentMission.difficulty;
    document.getElementById('missionReward').textContent = missionData.currentMission.reward;
    document.getElementById('missionDuration').textContent = missionData.currentMission.duration;
    
    updateMissionDisplay();
}

function advanceMission() {
    if (!missionData.isActive) return;
    
    // Avanzar progreso aleatoriamente
    const progressGain = Math.floor(Math.random() * 25) + 10;
    missionData.progress = Math.min(missionData.progress + progressGain, missionData.currentMission.maxProgress);
    
    updateMissionDisplay();
    
    // Verificar si la misión está completa
    if (missionData.progress >= missionData.currentMission.maxProgress) {
        completeMission();
    }
}

function updateMissionDisplay() {
    const progressBar = document.getElementById('missionProgress');
    const progressText = document.getElementById('missionProgressText');
    
    progressBar.style.width = missionData.progress + '%';
    progressText.textContent = missionData.progress + '%';
    
    // Cambiar color según el progreso
    if (missionData.progress < 30) {
        progressBar.className = 'progress-bar bg-danger';
    } else if (missionData.progress < 70) {
        progressBar.className = 'progress-bar bg-warning';
    } else {
        progressBar.className = 'progress-bar bg-success';
    }
}

function completeMission() {
    missionData.isActive = false;
    
    const result = `¡Misión completada! Has terminado "${missionData.currentMission.title}" exitosamente.`;
    document.getElementById('missionResult').textContent = result;
    document.getElementById('finalReward').textContent = missionData.currentMission.reward;
    
    document.getElementById('mission-results').style.display = 'block';
    document.getElementById('missionBtn').style.display = 'none';
}
</script>
