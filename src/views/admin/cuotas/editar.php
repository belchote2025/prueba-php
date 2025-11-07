<?php
if (!isAdminLoggedIn()) {
    header('Location: ' . URL_ROOT . '/admin/login');
    exit;
}

$cuota = $data['cuota'] ?? null;
$usuarios = $data['usuarios'] ?? [];

if (!$cuota) {
    header('Location: ' . URL_ROOT . '/admin/cuotas');
    exit;
}

$cuotaObj = is_object($cuota) ? $cuota : (object)$cuota;
$errors = isset($_SESSION['error_message']) ? [$_SESSION['error_message']] : [];
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">
                    <i class="bi bi-cash-coin me-2"></i>
                    Editar Cuota
                </h2>
                <a href="<?php echo URL_ROOT; ?>/admin/cuotas" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver
                </a>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="<?php echo URL_ROOT; ?>/admin/cuotas/editar/<?php echo $cuotaObj->id ?? 0; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccionar usuario...</option>
                                    <?php foreach ($usuarios as $usuario): 
                                        $usuarioObj = is_object($usuario) ? $usuario : (object)$usuario;
                                    ?>
                                        <option value="<?php echo $usuarioObj->id ?? 0; ?>" 
                                                <?php echo ($cuotaObj->usuario_id ?? 0) == ($usuarioObj->id ?? 0) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars(($usuarioObj->nombre ?? '') . ' (' . ($usuarioObj->email ?? '') . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="año" class="form-label">Año <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="año" name="año" 
                                       value="<?php echo htmlspecialchars($cuotaObj->año ?? date('Y')); ?>" 
                                       min="2020" max="2100" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="mes" class="form-label">Mes (opcional)</label>
                                <select class="form-select" id="mes" name="mes">
                                    <option value="">Cuota Anual</option>
                                    <?php 
                                    $meses = [
                                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                    ];
                                    foreach ($meses as $num => $nombre): 
                                    ?>
                                        <option value="<?php echo $num; ?>" 
                                                <?php echo ($cuotaObj->mes ?? null) == $num ? 'selected' : ''; ?>>
                                            <?php echo $nombre; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="monto" class="form-label">Monto (€) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="monto" name="monto" 
                                       value="<?php echo htmlspecialchars($cuotaObj->monto ?? 0); ?>" 
                                       step="0.01" min="0" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                                       value="<?php echo $cuotaObj->fecha_vencimiento ? date('Y-m-d', strtotime($cuotaObj->fecha_vencimiento)) : ''; ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="pendiente" <?php echo ($cuotaObj->estado ?? 'pendiente') === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="pagada" <?php echo ($cuotaObj->estado ?? '') === 'pagada' ? 'selected' : ''; ?>>Pagada</option>
                                    <option value="vencida" <?php echo ($cuotaObj->estado ?? '') === 'vencida' ? 'selected' : ''; ?>>Vencida</option>
                                    <option value="cancelada" <?php echo ($cuotaObj->estado ?? '') === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" 
                                       value="<?php echo $cuotaObj->fecha_pago ? date('Y-m-d', strtotime($cuotaObj->fecha_pago)) : ''; ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="metodo_pago" class="form-label">Método de Pago</label>
                                <select class="form-select" id="metodo_pago" name="metodo_pago">
                                    <option value="">Seleccionar...</option>
                                    <option value="transferencia" <?php echo ($cuotaObj->metodo_pago ?? '') === 'transferencia' ? 'selected' : ''; ?>>Transferencia</option>
                                    <option value="efectivo" <?php echo ($cuotaObj->metodo_pago ?? '') === 'efectivo' ? 'selected' : ''; ?>>Efectivo</option>
                                    <option value="tarjeta" <?php echo ($cuotaObj->metodo_pago ?? '') === 'tarjeta' ? 'selected' : ''; ?>>Tarjeta</option>
                                    <option value="cheque" <?php echo ($cuotaObj->metodo_pago ?? '') === 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                                    <option value="otro" <?php echo ($cuotaObj->metodo_pago ?? '') === 'otro' ? 'selected' : ''; ?>>Otro</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="referencia_pago" class="form-label">Referencia de Pago</label>
                                <input type="text" class="form-control" id="referencia_pago" name="referencia_pago" 
                                       value="<?php echo htmlspecialchars($cuotaObj->referencia_pago ?? ''); ?>" 
                                       placeholder="Número de referencia, comprobante, etc.">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3" 
                                      placeholder="Notas adicionales sobre la cuota..."><?php echo htmlspecialchars($cuotaObj->notas ?? ''); ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo URL_ROOT; ?>/admin/cuotas" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle me-2"></i>
                                Actualizar Cuota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

