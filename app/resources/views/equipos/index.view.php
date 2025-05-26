<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/views/equipos/index.view.php


    include_once LAYOUTS . 'main_head.php';
    setHeader($d);

?>

<div class="d-flex justify-content-between align-items-center mb-3 pt-3">
        <h2><?= htmlspecialchars($d->title ?? 'Listado de Equipos') ?></h2>
        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos/crear" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Nuevo Equipo
        </a>
    </div>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Equipo <?= htmlspecialchars($_GET['exito']) ?> exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error: <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <?php if (empty($d->equipos)): ?>
        <div class="alert alert-info mt-4" role="alert">
            No hay equipos registrados por el momento. ¡Puedes añadir uno nuevo usando el botón de arriba!
        </div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover table-bordered caption-top">
                <caption>Lista de Equipos de Cómputo Registrados</caption>
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>N/S</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($d->equipos as $equipo): ?>
                        <tr>
                            <td><?= htmlspecialchars($equipo->id_equipo) ?></td>
                            <td><?= htmlspecialchars($equipo->tipo_equipo) ?></td>
                            <td><?= htmlspecialchars($equipo->marca) ?></td>
                            <td><?= htmlspecialchars($equipo->modelo) ?></td>
                            <td><?= htmlspecialchars($equipo->numero_serie ?? '-') ?></td>
                            <td>
                                <?php
                                    $estadoClase = 'secondary'; // Default
                                    if (isset($equipo->estado)) {
                                        switch (strtolower($equipo->estado)) {
                                            case 'activo': $estadoClase = 'success'; break;
                                            case 'en reparacion': $estadoClase = 'warning'; break;
                                            case 'en bodega': $estadoClase = 'info'; break;
                                            case 'de baja': $estadoClase = 'danger'; break;
                                        }
                                    }
                                ?>
                                <span class="badge bg-<?= $estadoClase ?>"><?= htmlspecialchars($equipo->estado) ?></span>
                            </td>
                            <td><?= htmlspecialchars($equipo->nombre_personal ?? 'No asignado') ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#modalDetallesEquipo<?= htmlspecialchars($equipo->id_equipo) ?>" title="Ver Detalles">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos/editar/<?= htmlspecialchars($equipo->id_equipo) ?>" class="btn btn-sm btn-warning me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminar<?= htmlspecialchars($equipo->id_equipo) ?>" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalDetallesEquipo<?= htmlspecialchars($equipo->id_equipo) ?>" tabindex="-1" aria-labelledby="modalDetallesEquipoLabel<?= htmlspecialchars($equipo->id_equipo) ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDetallesEquipoLabel<?= htmlspecialchars($equipo->id_equipo) ?>">Detalles del Equipo: <?= htmlspecialchars($equipo->marca . ' ' . $equipo->modelo) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>ID:</strong> <?= htmlspecialchars($equipo->id_equipo) ?></p>
                                        <p><strong>Tipo:</strong> <?= htmlspecialchars($equipo->tipo_equipo) ?></p>
                                        <p><strong>Marca:</strong> <?= htmlspecialchars($equipo->marca) ?></p>
                                        <p><strong>Modelo:</strong> <?= htmlspecialchars($equipo->modelo) ?></p>
                                        <p><strong>Número de Serie:</strong> <?= htmlspecialchars($equipo->numero_serie ?? '-') ?></p>
                                        <p><strong>Estado:</strong> <span class="badge bg-<?= $estadoClase ?>"><?= htmlspecialchars($equipo->estado) ?></span></p>
                                        <p><strong>Fecha de Adquisición:</strong> <?= htmlspecialchars(isset($equipo->fecha_adquisicion) && $equipo->fecha_adquisicion ? date('d/m/Y', strtotime($equipo->fecha_adquisicion)) : '-') ?></p>
                                        <p><strong>Asignado a:</strong> <?= htmlspecialchars($equipo->nombre_personal ?? 'No asignado') ?> <?= htmlspecialchars(isset($equipo->puesto_personal) && $equipo->puesto_personal ? '('.$equipo->puesto_personal.')' : '') ?></p>
                                        <p><strong>Notas:</strong></p>
                                        <p><?= nl2br(htmlspecialchars($equipo->notas ?? '-')) ?></p>
                                        <p><small>Registrado el: <?= htmlspecialchars(isset($equipo->created_at) && $equipo->created_at ? date('d/m/Y H:i', strtotime($equipo->created_at)) : '-') ?></small></p>
                                        <p><small>Última actualización: <?= htmlspecialchars(isset($equipo->updated_at) && $equipo->updated_at ? date('d/m/Y H:i', strtotime($equipo->updated_at)) : '-') ?></small></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos/editar/<?= htmlspecialchars($equipo->id_equipo) ?>" class="btn btn-primary">Editar Equipo</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalConfirmarEliminar<?= htmlspecialchars($equipo->id_equipo) ?>" tabindex="-1" aria-labelledby="modalConfirmarEliminarLabel<?= htmlspecialchars($equipo->id_equipo) ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="modalConfirmarEliminarLabel<?= htmlspecialchars($equipo->id_equipo) ?>"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar el equipo: <strong><?= htmlspecialchars($equipo->marca . ' ' . $equipo->modelo) ?></strong> (N/S: <?= htmlspecialchars($equipo->numero_serie ?? 'N/A') ?>)?</p>
                                        <p class="text-danger">Esta acción no se puede deshacer.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos/eliminar/<?= htmlspecialchars($equipo->id_equipo) ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
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