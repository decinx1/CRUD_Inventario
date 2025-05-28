<?php
    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);

    $empleado = $d->empleado ?? null; // Objeto empleado o null
    $accionUrl = $d->accion ?? '#';
    $pageTitle = $d->title ?? ($empleado ? 'Editar Personal' : 'Añadir Nuevo Personal');
    $urlBase = $d->url_base ?? URL;
?>

<div class="my-4">
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
        <hr>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> <?= htmlspecialchars($_GET['error']) ?>. Por favor, verifique los datos.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($accionUrl) ?>" method="POST" class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?= htmlspecialchars($empleado->nombre ?? '') ?>" required
                           data-bs-toggle="tooltip" data-bs-placement="top" title="El nombre es obligatorio.">
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido(s) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="apellido" name="apellido"
                           value="<?= htmlspecialchars($empleado->apellido ?? '') ?>" required
                           data-bs-toggle="tooltip" data-bs-placement="top" title="El apellido es obligatorio.">
                    <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= htmlspecialchars($empleado->email ?? '') ?>" required
                           data-bs-toggle="tooltip" data-bs-placement="top" title="El correo electrónico es obligatorio y debe ser único.">
                    <div class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</div>
                </div>
                <div class="col-md-6">
                    <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="puesto" name="puesto"
                           value="<?= htmlspecialchars($empleado->puesto ?? '') ?>" required
                           data-bs-toggle="tooltip" data-bs-placement="top" title="El puesto es obligatorio.">
                    <div class="invalid-feedback">Por favor, ingrese el puesto.</div>
                </div>

                <div class="col-12">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="1" id="activo" name="activo"
                            <?= (isset($empleado->activo) && $empleado->activo == 1) ? 'checked' : (!isset($empleado->activo) && !$empleado ? 'checked' : '') ?>
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Marcar si el empleado está activo en el sistema.">
                        <label class="form-check-label" for="activo">
                            Activo
                        </label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-save"></i> <?= $empleado && isset($empleado->id_empleado) ? 'Actualizar Personal' : 'Guardar Personal' ?>
            </button>
            <a href="<?= htmlspecialchars($urlBase) ?>personal" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </form>
    </div>

    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

<?php
    // 3. Incluir el pie de página
    include_once LAYOUTS . 'main_foot.php';
?>