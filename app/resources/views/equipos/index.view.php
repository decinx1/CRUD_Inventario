<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/views/home.view.php

    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
    // 2. Llamar a la función que genera el contenido del head y el nav
    setHeader($d); // $d son los datos pasados por el controlador a View::render()
?>

<div class="d-flex justify-content-between align-items-center mb-3 pt-3">
        <h2><?= htmlspecialchars($d->title) ?></h2>
        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos/crear" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Nuevo Equipo
        </a>
    </div>

    <div class="alert alert-info" role="alert">
        Aquí se mostrará la tabla con el listado de equipos. ¡Página en construcción!
    </div>

    <?php if (empty($d->equipos)): ?>
        <?php else: ?>
        <?php endif; ?>

<?php
    // 3. Incluir el pie de página
    include_once LAYOUTS . 'main_foot.php';
?>