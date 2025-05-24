<?php
    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 pt-3">
        <h2><?= htmlspecialchars($d->title) ?></h2>
        <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>personal/crear" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Añadir Nuevo Personal
        </a>
    </div>

    <div class="alert alert-info" role="alert">
        Aquí se mostrará la tabla con el listado del personal. ¡Página en construcción!
    </div>

    <?php if (empty($d->personal)): ?>
        <?php else: ?>
        <?php endif; ?>

<?php
    // 3. Incluir el pie de página
    include_once LAYOUTS . 'main_foot.php';
?>