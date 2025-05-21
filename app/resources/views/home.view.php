<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/views/home.view.php

    // 1. Incluir la cabecera
    include_once LAYOUTS . 'main_head.php';
    // 2. Llamar a la función que genera el contenido del head y el nav
    setHeader($d); // $d son los datos pasados por el controlador a View::render()
?>

<div class="container" style="margin-top: 20px; background-color: white; padding: 20px;">
    <h1>¡Mi Header se Muestra!</h1>
    <p>Si ves esto y el menú de navegación arriba, ¡vamos por buen camino!</p>
    <p>Título de la página: <?= htmlspecialchars($d->title ?? 'Sin Título') ?></p>
    <p>Estado de sesión (sv): <?= isset($d->ua->sv) ? ($d->ua->sv ? 'Válida' : 'No válida') : 'No definida' ?></p>
    <?php if(isset($d->ua->sv) && $d->ua->sv && isset($d->ua->username)): ?>
        <p>Usuario: <?= htmlspecialchars($d->ua->username) ?></p>
    <?php endif; ?>
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