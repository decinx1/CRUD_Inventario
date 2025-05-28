<?php
    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);

?>

<div class="d-flex justify-content-between align-items-center mb-3 pt-3">
        <h2><?= htmlspecialchars($d->title ?? 'Gestión de Personal') ?></h2>
        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>personal/crear" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Añadir Nuevo Personal
        </a>
    </div>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Personal <?= htmlspecialchars($_GET['exito']) ?> exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error: <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <?php if (empty($d->personal)): ?>
        <div class="alert alert-info mt-4" role="alert">
            No hay personal registrado por el momento. ¡Puedes añadir nuevo personal usando el botón de arriba!
        </div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover table-bordered caption-top">
                <caption>Lista del Personal Registrado</caption>
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Puesto</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($d->personal as $persona): ?>
                        <tr>
                            <td><?= htmlspecialchars($persona->id_empleado) ?></td>
                            <td><?= htmlspecialchars($persona->nombre) ?></td>
                            <td><?= htmlspecialchars($persona->apellido) ?></td>
                            <td><?= htmlspecialchars($persona->email) ?></td>
                            <td><?= htmlspecialchars($persona->puesto) ?></td>
                            <td>
                                <?php
                                    $estadoClase = (isset($persona->activo) && $persona->activo == 1) ? 'success' : 'danger';
                                    $estadoTexto = (isset($persona->activo) && $persona->activo == 1) ? 'Activo' : 'Inactivo';
                                ?>
                                <span class="badge bg-<?= $estadoClase ?>"><?= htmlspecialchars($estadoTexto) ?></span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#modalDetallesPersonal<?= htmlspecialchars($persona->id_empleado) ?>" title="Ver Detalles">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>personal/editar/<?= htmlspecialchars($persona->id_empleado) ?>" class="btn btn-sm btn-warning me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-<?= (isset($persona->activo) && $persona->activo == 1) ? 'danger' : 'success' ?>" data-bs-toggle="modal" data-bs-target="#modalConfirmarEstadoPersonal<?= htmlspecialchars($persona->id_empleado) ?>" title="<?= (isset($persona->activo) && $persona->activo == 1) ? 'Desactivar' : 'Activar' ?>">
                                    <i class="bi bi-<?= (isset($persona->activo) && $persona->activo == 1) ? 'person-x-fill' : 'person-check-fill' ?>"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalDetallesPersonal<?= htmlspecialchars($persona->id_empleado) ?>" tabindex="-1" aria-labelledby="modalDetallesPersonalLabel<?= htmlspecialchars($persona->id_empleado) ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDetallesPersonalLabel<?= htmlspecialchars($persona->id_empleado) ?>">Detalles del Personal: <?= htmlspecialchars($persona->nombre . ' ' . $persona->apellido) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID Empleado:</strong> <?= htmlspecialchars($persona->id_empleado) ?></p>
                                        <p><strong>Nombre:</strong> <?= htmlspecialchars($persona->nombre) ?></p>
                                        <p><strong>Apellido:</strong> <?= htmlspecialchars($persona->apellido) ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($persona->email) ?></p>
                                        <p><strong>Puesto:</strong> <?= htmlspecialchars($persona->puesto) ?></p>
                                        <p><strong>Estado:</strong> <span class="badge bg-<?= $estadoClase ?>"><?= htmlspecialchars($estadoTexto) ?></span></p>
                                        <p><small>Registrado el: <?= htmlspecialchars(isset($persona->created_at) && $persona->created_at ? date('d/m/Y H:i', strtotime($persona->created_at)) : (isset($persona->fecha_creacion) && $persona->fecha_creacion ? date('d/m/Y H:i', strtotime($persona->fecha_creacion)) : '-' )) ?></small></p>
                                        <p><small>Última actualización: <?= htmlspecialchars(isset($persona->updated_at) && $persona->updated_at ? date('d/m/Y H:i', strtotime($persona->updated_at)) : '-') ?></small></p>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>personal/editar/<?= htmlspecialchars($persona->id_empleado) ?>" class="btn btn-primary">Editar Personal</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalConfirmarEstadoPersonal<?= htmlspecialchars($persona->id_empleado) ?>" tabindex="-1" aria-labelledby="modalConfirmarEstadoLabel<?= htmlspecialchars($persona->id_empleado) ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header <?= (isset($persona->activo) && $persona->activo == 1) ? 'bg-danger text-white' : 'bg-success text-white' ?>">
                                        <h5 class="modal-title" id="modalConfirmarEstadoLabel<?= htmlspecialchars($persona->id_empleado) ?>"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Cambio de Estado</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if(isset($persona->activo) && $persona->activo == 1): ?>
                                            <p>¿Estás seguro de que deseas <strong>desactivar</strong> a <?= htmlspecialchars($persona->nombre . ' ' . $persona->apellido) ?>?</p>
                                            <p class="text-warning">El personal desactivado no podrá ser asignado a nuevos equipos y podría perder acceso al sistema.</p>
                                        <?php else: ?>
                                            <p>¿Estás seguro de que deseas <strong>activar</strong> a <?= htmlspecialchars($persona->nombre . ' ' . $persona->apellido) ?>?</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="<?= htmlspecialchars($d->url_base ?? URL) ?>personal/eliminar/<?= htmlspecialchars($persona->id_empleado) ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-<?= (isset($persona->activo) && $persona->activo == 1) ? 'danger' : 'success' ?>">
                                                Sí, <?= (isset($persona->activo) && $persona->activo == 1) ? 'Desactivar' : 'Activar' ?>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php
    // 3. Incluir el pie de página
    include_once LAYOUTS . 'main_foot.php';
?>