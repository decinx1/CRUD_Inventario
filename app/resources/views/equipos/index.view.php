<?php
    include_once LAYOUTS . 'main_head.php'; //llama al header
    setHeader($d);
?>

<div class="container mt-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= htmlspecialchars($d->title) ?></h2>
        <a href="<?= htmlspecialchars($d->url_base) ?>equipos/crear" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Nuevo Equipo
        </a>
    </div>

    <?php if (empty($d->equipos)): ?>
        <div class="alert alert-info" role="alert">
            No hay equipos registrados por el momento. ¡Puedes añadir uno nuevo!
        </div>
    <?php else: ?>
        <div class="table-responsive"> <table class="table table-striped table-hover table-bordered caption-top"> <caption>Lista de Equipos de Cómputo</caption> <thead class="table-dark"> <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>N/S</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php /* foreach ($d->equipos as $equipo): ?>
                        <tr>
                            <td><?= htmlspecialchars($equipo->id_equipo) ?></td>
                            <td><?= htmlspecialchars($equipo->tipo_equipo) ?></td>
                            <td><?= htmlspecialchars($equipo->marca) ?></td>
                            <td><?= htmlspecialchars($equipo->modelo) ?></td>
                            <td><?= htmlspecialchars($equipo->numero_serie ?? '-') ?></td>
                            <td><span class="badge bg-<?= strtolower($equipo->estado) === 'activo' ? 'success' : (strtolower($equipo->estado) === 'en bodega' ? 'secondary' : 'warning') ?>">
                                <?= htmlspecialchars($equipo->estado) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($equipo->nombre_personal ?? 'No asignado') ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($d->url_base) ?>equipos/editar/<?= htmlspecialchars($equipo->id_equipo) ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= htmlspecialchars($d->url_base) ?>equipos/eliminar/<?= htmlspecialchars($equipo->id_equipo) ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; */ ?>
                    <tr>
                        <td>1</td>
                        <td>Laptop</td>
                        <td>Dell</td>
                        <td>Latitude 5490</td>
                        <td>SNHG523X</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td>Juan Pérez</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                     <tr>
                        <td>2</td>
                        <td>Desktop</td>
                        <td>HP</td>
                        <td>ProDesk</td>
                        <td>SNHP987Z</td>
                        <td><span class="badge bg-secondary">En Bodega</span></td>
                        <td>No asignado</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    $( function (){
        console.log("Home view script ejecutado!");
        // app.previousPosts() // Comentado por ahora
        // app.lastPost()      // Comentado por ahora
    })
</script>

<?php
    // Simplemente incluye el pie de página.
    include_once LAYOUTS . 'main_foot.php'; //llama al footer 
?>