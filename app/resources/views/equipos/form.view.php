<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/views/equipos/form.view.php

    // 1. Incluir la cabecera (main_head.php)
    // LAYOUTS debe estar definido en config.php
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);

    $equipo = $d->equipo ?? null; // El objeto equipo (o null si es para crear)
    $personal_disponible = $d->personal_disponible ?? []; // Array de objetos de personal
    $accionUrl = $d->accion ?? '#'; // URL a la que el formulario enviará los datos
    $pageTitle = $d->title ?? ($equipo ? 'Editar Equipo' : 'Añadir Nuevo Equipo');
    $urlBase = $d->url_base ?? URL;
?>

<div class="my-4"> <h2><?= htmlspecialchars($pageTitle) ?></h2>
        <hr>

        <?php if (isset($_GET['error'])): // Mostrar mensajes de error si existen en la URL ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> <?= htmlspecialchars($_GET['error']) ?>. Verifique los datos obligatorios.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($accionUrl) ?>" method="POST" class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
    <label for="tipo_equipo" class="form-label">Tipo de Equipo <span class="text-danger">*</span></label>
    <select class="form-select" id="tipo_equipo" name="tipo_equipo" required
            data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccione el tipo de equipo. Es obligatorio.">
        <option value="" <?= (!isset($equipo->tipo_equipo) || $equipo->tipo_equipo == '') ? 'selected' : '' ?>>Seleccione un tipo...</option>
        <option value="Laptop" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Laptop') ? 'selected' : '' ?>>Laptop</option>
        <option value="Desktop" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Desktop') ? 'selected' : '' ?>>Desktop (PC de Escritorio)</option>
        <option value="Servidor" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Servidor') ? 'selected' : '' ?>>Servidor</option>
        <option value="Monitor" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Monitor') ? 'selected' : '' ?>>Monitor</option>
        <option value="Impresora" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Impresora') ? 'selected' : '' ?>>Impresora</option>
        <option value="Tablet" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
        <option value="Otro" <?= (isset($equipo->tipo_equipo) && $equipo->tipo_equipo == 'Otro') ? 'selected' : '' ?>>Otro</option>
        </select>
    <div class="invalid-feedback">Por favor, seleccione un tipo de equipo.</div>
</div>
                <div class="col-md-6">
                    <label for="marca" class="form-label">Marca <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="marca" name="marca"
                            value="<?= htmlspecialchars($equipo->marca ?? '') ?>" required
                            data-bs-toggle="tooltip" data-bs-placement="top" title="La marca  del equipo es obligatoria.">
                    <div class="invalid-feedback">Por favor, ingrese la marca.</div>
                </div>

                <div class="col-md-6">
                    <label for="modelo" class="form-label">Modelo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="modelo" name="modelo"
                                value="<?= htmlspecialchars($equipo->modelo ?? '') ?>" required
                                data-bs-toggle="tooltip" data-bs-placement="top" title="El modelo  del equipo es obligatoria.">
                    <div class="invalid-feedback">Por favor, ingrese el modelo.</div>
                </div>
                <div class="col-md-6">
                    <label for="numero_serie" class="form-label">Número de Serie</label>
                    <input type="text" class="form-control" id="numero_serie" name="numero_serie"
                            value="<?= htmlspecialchars($equipo->numero_serie ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select class="form-select" id="estado" name="estado" required
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Seleccione el estado del quipo. Es obligatorio.">
                        <option value="" <?= (!isset($equipo->estado) || $equipo->estado == '') ? 'selected' : '' ?>>Seleccione un estado...</option>
                        <option value="Activo" <?= (isset($equipo->estado) && $equipo->estado == 'Activo') ? 'selected' : '' ?>>Activo</option>
                        <option value="En Reparacion" <?= (isset($equipo->estado) && $equipo->estado == 'En Reparacion') ? 'selected' : '' ?>>En Reparación</option>
                        <option value="En Bodega" <?= (isset($equipo->estado) && $equipo->estado == 'En Bodega') ? 'selected' : '' ?>>En Bodega</option>
                        <option value="De Baja" <?= (isset($equipo->estado) && $equipo->estado == 'De Baja') ? 'selected' : '' ?>>De Baja</option>
                        </select>
                    <div class="invalid-feedback">Por favor, seleccione un estado.</div>
                </div>
                <div class="col-md-6">
                    <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
                    <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion"
                            value="<?= htmlspecialchars($equipo->fecha_adquisicion ?? '') ?>">
                </div>

                <div class="col-12">
                    <label for="Id_personal_asignado" class="form-label">Asignado a</label>
                    <select class="form-select" id="Id_personal_asignado" name="Id_personal_asignado">
                        <option value="">-- Sin asignar --</option>
                        <?php if (!empty($personal_disponible)): ?>
                            <?php foreach ($personal_disponible as $persona): ?>
                                <option value="<?= htmlspecialchars($persona->id_empleado) ?>"
                                    <?= (isset($equipo->Id_personal_asignado) && $equipo->Id_personal_asignado == $persona->id_empleado) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($persona->nombre . ' ' . $persona->apellido . ($persona->puesto ? ' ('.$persona->puesto.')' : '')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay personal disponible para asignar</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label for="notas" class="form-label">Notas Adicionales</label>
                    <textarea class="form-control" id="notas" name="notas" rows="3"><?= htmlspecialchars($equipo->notas ?? '') ?></textarea>
                </div>
            </div>

            <hr class="my-4">

            <button type="submit" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-save"></i> <?= $equipo && isset($equipo->id_equipo) ? 'Actualizar Equipo' : 'Guardar Equipo' ?>
            </button>
            <a href="<?= htmlspecialchars($urlBase) ?>equipos" class="btn btn-secondary btn-lg">
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