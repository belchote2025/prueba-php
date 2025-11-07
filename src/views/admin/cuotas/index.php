<?php
// Verificar autenticación
if (!isAdminLoggedIn()) {
    header('Location: ' . URL_ROOT . '/admin/login');
    exit;
}

$cuotas = $data['cuotas'] ?? [];
$pendientes = $data['pendientes'] ?? 0;
$vencidas = $data['vencidas'] ?? 0;
$usuarios = $data['usuarios'] ?? [];
$filtroEstado = $data['filtroEstado'] ?? null;
$filtroUsuario = $data['filtroUsuario'] ?? null;
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="bi bi-cash-coin me-2"></i>
            Gestión de Cuotas
        </h2>
        <a href="<?php echo URL_ROOT; ?>/admin/cuotas/nueva" class="btn btn-danger">
            <i class="bi bi-plus-circle me-2"></i>
            Nueva Cuota
        </a>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Cuotas Pendientes</h5>
                    <h2 class="mb-0"><?php echo $pendientes; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Cuotas Vencidas</h5>
                    <h2 class="mb-0"><?php echo $vencidas; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo URL_ROOT; ?>/admin/cuotas" class="row g-3">
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" <?php echo $filtroEstado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="pagada" <?php echo $filtroEstado === 'pagada' ? 'selected' : ''; ?>>Pagada</option>
                        <option value="vencida" <?php echo $filtroEstado === 'vencida' ? 'selected' : ''; ?>>Vencida</option>
                        <option value="cancelada" <?php echo $filtroEstado === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="usuario_id" class="form-label">Usuario</label>
                    <select name="usuario_id" id="usuario_id" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($usuarios as $usuario): 
                            $usuarioObj = is_object($usuario) ? $usuario : (object)$usuario;
                        ?>
                            <option value="<?php echo $usuarioObj->id ?? 0; ?>" 
                                    <?php echo $filtroUsuario == ($usuarioObj->id ?? 0) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(($usuarioObj->nombre ?? '') . ' (' . ($usuarioObj->email ?? '') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="<?php echo URL_ROOT; ?>/admin/cuotas" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($cuotas)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Vencimiento</th>
                                <th>Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cuotas as $cuota): 
                                $cuotaObj = is_object($cuota) ? $cuota : (object)$cuota;
                                $cuotaId = $cuotaObj->id ?? 0;
                                $usuarioNombre = $cuotaObj->usuario_nombre ?? 'N/A';
                                $usuarioEmail = $cuotaObj->usuario_email ?? '';
                                $año = $cuotaObj->año ?? date('Y');
                                $mes = $cuotaObj->mes ?? null;
                                $monto = $cuotaObj->monto ?? 0;
                                $estado = $cuotaObj->estado ?? 'pendiente';
                                $fechaVencimiento = $cuotaObj->fecha_vencimiento ?? null;
                                $fechaPago = $cuotaObj->fecha_pago ?? null;
                                
                                // Determinar color del badge según estado
                                $estadoClass = 'secondary';
                                if ($estado === 'pagada') $estadoClass = 'success';
                                elseif ($estado === 'pendiente') $estadoClass = 'warning';
                                elseif ($estado === 'vencida') $estadoClass = 'danger';
                                elseif ($estado === 'cancelada') $estadoClass = 'dark';
                                
                                // Verificar si está vencida
                                if ($estado === 'pendiente' && $fechaVencimiento && strtotime($fechaVencimiento) < time()) {
                                    $estadoClass = 'danger';
                                }
                            ?>
                            <tr>
                                <td><?php echo $cuotaId; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuarioNombre); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($usuarioEmail); ?></small>
                                </td>
                                <td><?php echo $año; ?></td>
                                <td>
                                    <?php if ($mes): ?>
                                        <?php 
                                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                        echo $meses[$mes] ?? $mes;
                                        ?>
                                    <?php else: ?>
                                        <span class="text-muted">Anual</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo number_format($monto, 2); ?> €</strong>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $estadoClass; ?>">
                                        <?php echo ucfirst($estado); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fechaVencimiento): ?>
                                        <?php echo date('d/m/Y', strtotime($fechaVencimiento)); ?>
                                        <?php if ($estado === 'pendiente' && strtotime($fechaVencimiento) < time()): ?>
                                            <br><small class="text-danger">Vencida</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fechaPago): ?>
                                        <?php echo date('d/m/Y', strtotime($fechaPago)); ?>
                                        <?php if ($cuotaObj->metodo_pago ?? ''): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($cuotaObj->metodo_pago); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo URL_ROOT; ?>/admin/cuotas/editar/<?php echo $cuotaId; ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($estado === 'pendiente'): ?>
                                            <button type="button" 
                                                    class="btn btn-outline-success" 
                                                    title="Marcar como pagada"
                                                    onclick="marcarComoPagada(<?php echo $cuotaId; ?>)">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                        <form method="POST" 
                                              action="<?php echo URL_ROOT; ?>/admin/cuotas/eliminar/<?php echo $cuotaId; ?>" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta cuota?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-cash-coin text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">No hay cuotas registradas</p>
                    <a href="<?php echo URL_ROOT; ?>/admin/cuotas/nueva" class="btn btn-danger">
                        <i class="bi bi-plus-circle me-2"></i>
                        Crear Primera Cuota
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para marcar como pagada -->
<div class="modal fade" id="modalPagar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formPagar" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Marcar Cuota como Pagada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago">
                            <option value="">Seleccionar...</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="cheque">Cheque</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="referencia_pago" class="form-label">Referencia de Pago</label>
                        <input type="text" class="form-control" id="referencia_pago" name="referencia_pago" 
                               placeholder="Número de referencia, comprobante, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Marcar como Pagada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function marcarComoPagada(cuotaId) {
    const form = document.getElementById('formPagar');
    form.action = '<?php echo URL_ROOT; ?>/admin/cuotas/marcar-pagada/' + cuotaId;
    
    const modal = new bootstrap.Modal(document.getElementById('modalPagar'));
    modal.show();
}
</script>

