<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/views/home.view.php

    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
    // 2. Llamar a la función que genera el contenido del head y el nav
    setHeader($d); // $d son los datos pasados por el controlador a View::render()
?>

<div class="px-4 py-5 my-5 text-center">
        <i class="bi bi-display display-1 text-primary mb-4"></i>
        <h1 class="display-5 fw-bold text-body-emphasis">Inventa Fácil</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">
                Bienvenido al sistema de gestión de inventario de equipos de cómputo.
                Administra y controla tus activos tecnológicos de manera eficiente y sencilla.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>equipos" type="button" class="btn btn-primary btn-lg px-4 gap-3">
                    <i class="bi bi-laptop"></i> Gestionar Equipos
                </a>
                <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>personal" type="button" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="bi bi-people-fill"></i> Gestionar Personal
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-white border rounded d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <i class="bi bi-clipboard-data text-primary" style="font-size: 1.5em;"></i>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Registro Detallado</h3>
                <p>Mantén un registro completo de cada equipo, incluyendo tipo, marca, modelo, número de serie, estado y fecha de adquisición.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-white border rounded d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <i class="bi bi-person-badge text-success" style="font-size: 1.5em;"></i>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Gestión de Personal</h3>
                <p>Administra la información del personal y conoce quién tiene asignado cada equipo.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-white border rounded d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <i class="bi bi-link-45deg text-warning" style="font-size: 1.5em;"></i>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Asignación Fácil</h3>
                <p>Vincula equipos de cómputo a miembros del personal de forma intuitiva y visualiza las asignaciones actuales.</p>
            </div>
        </div>
    </div>

    <hr class="my-5"> <div class="row featurette align-items-center py-5">
        <div class="col-md-5 text-center"> <i class="bi bi-card-checklist" style="font-size: 8rem; color: var(--bs-teal);"></i> </div>
        <div class="col-md-7">
            <h2 class="featurette-heading fw-normal lh-1">¿Qué es <span class="text-muted">Inventa Fácil?</span></h2>
            <p class="lead mt-3">
                Inventa Fácil es una aplicación web diseñada para la gestión eficiente del inventario de equipos de cómputo. 
                Construida utilizando un framework PHP personalizado, esta herramienta busca simplificar el control y seguimiento de tus activos tecnológicos.
            </p>
            <h3 class="fw-normal lh-1 mt-4">Propósito Principal</h3>
            <p>
                El objetivo es ofrecer una solución práctica para que los administradores, especialmente en empresas de Manzanillo, Colima, puedan llevar un control preciso de sus equipos.   
                Esto facilita la optimización en la asignación de recursos, una mejor planificación para mantenimientos o reemplazos, y ayuda a disminuir las pérdidas o extravíos de equipos. 
            </p>
        </div>
    </div>

<script>
    $( function (){
        console.log("Home view script ejecutado!");
        // app.previousPosts() // Comentado por ahora si no tienes la lógica del foro
        // app.lastPost()      // Comentado por ahora
    })
</script>

<?php
    // 3. Simplemente incluye el pie de página.
    // main_foot.php ya contiene el HTML del footer, los scripts y el cierre de </body></html>
    include_once LAYOUTS . 'main_foot.php';

    // ¡¡¡ASEGÚRATE DE ELIMINAR CUALQUIER LLAMADA A setFooter() o closeFooter() DE AQUÍ!!!
    // Por ejemplo, elimina estas líneas si existen:
    // setFooter($d); <--- ELIMINAR
    // closefooter(); <--- ELIMINAR
?>