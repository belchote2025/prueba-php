<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Usuarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/prueba-php/public/admin/crearUsuario" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Usuario
        </a>
    </div>
</div>

<?php if (!empty($data['users'])): ?>
    <!-- Vista responsive actualizada - <?= date('Y-m-d H:i:s') ?> -->
    
    <!-- Indicador de vista responsive -->
    <div class="alert alert-info d-xl-none mb-3">
        <i class="fas fa-mobile-alt me-2"></i>
        <strong>Vista móvil activada</strong> - Los usuarios se muestran en tarjetas para mejor visualización
    </div>
    
    <!-- Vista de escritorio - Tabla -->
    <div class="d-none d-xl-block">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['users'] as $user): ?>
                        <tr>
                            <td>#<?= $user->id ?></td>
                            <td><?= htmlspecialchars($user->nombre . ' ' . $user->apellidos) ?></td>
                            <td><?= htmlspecialchars($user->email) ?></td>
                            <td>
                                <?php if (!empty($user->password_plain)): ?>
                                    <code class="password-display" style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-size: 0.85em; color: #495057;">
                                        <?= htmlspecialchars($user->password_plain) ?>
                                    </code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user->rol === 'admin' ? 'primary' : 'secondary' ?>">
                                    <?= ucfirst($user->rol) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user->activo ? 'success' : 'danger' ?>">
                                    <?= $user->activo ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td>
                                <?= $user->ultimo_acceso ? date('d/m/Y H:i', strtotime($user->ultimo_acceso)) : 'Nunca' ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/prueba-php/public/admin/editarUsuario/<?= $user->id ?>" class="btn btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-<?= $user->activo ? 'warning' : 'success' ?>" 
                                            onclick="toggleUserStatus(<?= $user->id ?>, <?= $user->activo ? 0 : 1 ?>, '<?= htmlspecialchars($user->nombre) ?>')"
                                            title="<?= $user->activo ? 'Desactivar' : 'Activar' ?>">
                                        <i class="fas fa-<?= $user->activo ? 'ban' : 'check' ?>"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="confirmDelete('usuario', <?= $user->id ?>, '<?= htmlspecialchars($user->nombre) ?>')"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vista móvil - Tarjetas -->
    <div class="d-xl-none">
        <div class="row g-3">
            <?php foreach ($data['users'] as $user): ?>
                <div class="col-12">
                    <div class="card user-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">
                                    <strong><?= htmlspecialchars($user->nombre . ' ' . $user->apellidos) ?></strong>
                                </h6>
                                <span class="badge bg-<?= $user->activo ? 'success' : 'danger' ?>">
                                    <?= $user->activo ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <small class="text-muted">ID:</small>
                                    <div class="fw-bold">#<?= $user->id ?></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Rol:</small>
                                    <div>
                                        <span class="badge bg-<?= $user->rol === 'admin' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($user->rol) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Email:</small>
                                <div class="text-break"><?= htmlspecialchars($user->email) ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Contraseña:</small>
                                <div>
                                    <?php if (!empty($user->password_plain)): ?>
                                        <code class="password-display" style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; color: #495057; word-break: break-all;">
                                            <?= htmlspecialchars($user->password_plain) ?>
                                        </code>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Último Acceso:</small>
                                <div><?= $user->ultimo_acceso ? date('d/m/Y H:i', strtotime($user->ultimo_acceso)) : 'Nunca' ?></div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="/prueba-php/public/admin/editarUsuario/<?= $user->id ?>" class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                                <button type="button" class="btn btn-outline-<?= $user->activo ? 'warning' : 'success' ?> btn-sm flex-fill" 
                                        onclick="toggleUserStatus(<?= $user->id ?>, <?= $user->activo ? 0 : 1 ?>, '<?= htmlspecialchars($user->nombre) ?>')">
                                    <i class="fas fa-<?= $user->activo ? 'ban' : 'check' ?> me-1"></i>
                                    <?= $user->activo ? 'Desactivar' : 'Activar' ?>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm flex-fill" 
                                        onclick="confirmDelete('usuario', <?= $user->id ?>, '<?= htmlspecialchars($user->nombre) ?>')">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php if ($data['totalPages'] > 1): ?>
        <nav aria-label="Paginación de usuarios">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $data['currentPage'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="/prueba-php/public/admin/usuarios/<?= $data['currentPage'] - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                    <li class="page-item <?= $i == $data['currentPage'] ? 'active' : '' ?>">
                        <a class="page-link" href="/prueba-php/public/admin/usuarios/<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= $data['currentPage'] >= $data['totalPages'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="/prueba-php/public/admin/usuarios/<?= $data['currentPage'] + 1 ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    
<?php else: ?>
    <div class="alert alert-info">
        No hay usuarios registrados en el sistema.
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este <span id="entityType">usuario</span>? <br>
                <strong id="entityName"></strong>
                <p class="text-danger mt-2">¡Esta acción no se puede deshacer!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(type, id, name) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('entityType').textContent = type;
    document.getElementById('entityName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/${type}s/eliminar/${id}`;
    modal.show();
}

function toggleUserStatus(userId, newStatus, userName) {
    const action = newStatus ? 'activar' : 'desactivar';
    const confirmMessage = `¿Estás seguro de que deseas ${action} al usuario "${userName}"?`;
    
    if (confirm(confirmMessage)) {
        // Mostrar indicador de carga
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Crear el token CSRF
        const csrfToken = '<?= generateCsrfToken() ?>';
        
        // Realizar la petición AJAX
        fetch(`/prueba-php/public/admin/toggleUserStatus/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                status: newStatus,
                csrf_token: csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            // Restaurar el botón
            button.innerHTML = originalContent;
            button.disabled = false;
            
            if (data.success) {
                // Mostrar toast de éxito
                showToast('success', `Usuario ${action}do correctamente`);
                
                // Recargar la página para mostrar los cambios
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Mostrar toast de error
                showToast('error', data.message || `Error al ${action} el usuario`);
            }
        })
        .catch(error => {
            // Restaurar el botón
            button.innerHTML = originalContent;
            button.disabled = false;
            
            // Mostrar toast de error
            showToast('error', 'Error de conexión. Inténtalo de nuevo.');
            console.error('Error:', error);
        });
    }
}

function showToast(type, message) {
    // Crear el toast si no existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Crear el toast
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Mostrar el toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
    
    // Eliminar el toast del DOM después de que se oculte
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>

<style>
/* Forzar la vista responsive - Actualizado <?= date('Y-m-d H:i:s') ?> */
@media (max-width: 1199px) {
    .d-xl-block {
        display: none !important;
    }
    .d-xl-none {
        display: block !important;
    }
}

/* Estilos para las tarjetas de usuario en móviles */
.user-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.2s ease;
    margin-bottom: 1rem;
}

.user-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.user-card .card-body {
    padding: 1rem;
}

.user-card .card-title {
    font-size: 1rem;
    color: #212529;
}

.user-card .text-muted {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.user-card .password-display {
    font-family: 'Courier New', monospace;
    background: #f8f9fa !important;
    border: 1px solid #e9ecef;
    padding: 4px 8px !important;
    border-radius: 4px;
    font-size: 0.85rem !important;
    color: #495057 !important;
    word-break: break-all;
    display: inline-block;
    max-width: 100%;
}

.user-card .btn {
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
}

.user-card .btn i {
    font-size: 0.75rem;
}

/* Mejorar la responsividad de la tabla en pantallas medianas */
@media (max-width: 1199px) and (min-width: 992px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .table th, .table td {
        padding: 0.5rem 0.3rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
    }
}

/* Ajustes para pantallas muy pequeñas */
@media (max-width: 575px) {
    .user-card .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .user-card .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    
    .user-card .row.g-2 {
        margin-bottom: 1rem !important;
    }
}
</style>

<script>
// Forzar recarga de estilos responsive
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista responsive cargada - <?= date('Y-m-d H:i:s') ?>');
    
    // Verificar si estamos en vista móvil
    if (window.innerWidth < 1200) {
        console.log('Vista móvil detectada - Mostrando tarjetas');
        // Forzar visibilidad de las tarjetas
        const mobileView = document.querySelector('.d-xl-none');
        const desktopView = document.querySelector('.d-xl-block');
        
        if (mobileView) {
            mobileView.style.display = 'block';
        }
        if (desktopView) {
            desktopView.style.display = 'none';
        }
    } else {
        console.log('Vista de escritorio detectada - Mostrando tabla');
    }
    
    // Escuchar cambios de tamaño de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth < 1200) {
            const mobileView = document.querySelector('.d-xl-none');
            const desktopView = document.querySelector('.d-xl-block');
            
            if (mobileView) {
                mobileView.style.display = 'block';
            }
            if (desktopView) {
                desktopView.style.display = 'none';
            }
        } else {
            const mobileView = document.querySelector('.d-xl-none');
            const desktopView = document.querySelector('.d-xl-block');
            
            if (mobileView) {
                mobileView.style.display = 'none';
            }
            if (desktopView) {
                desktopView.style.display = 'block';
            }
        }
    });
});
</script>
