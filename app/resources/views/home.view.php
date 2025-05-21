<?php
    // LAYOUTS debe apuntar a app/resources/layouts/
    include_once LAYOUTS . 'main_head.php';

    // $d es el objeto datosVista del controlador, ya convertido a objeto por View::render
    setHeader($d);
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

<?php
    include_once LAYOUTS . 'main_foot.php';
    setFooter($d); // $d también está disponible aquí
    closefooter();
?>