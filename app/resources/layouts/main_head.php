<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/layouts/main_head.php
    function setHeader($args){ // $args es el objeto de datos pasado desde la vista (que lo recibió del controlador)
        // $args->ua contiene la información de la sesión del usuario
        // $args->title contiene el título de la página
        $ua = as_object( $args->ua ); // Asegura que $ua sea un objeto.

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL ?>bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title><?= htmlspecialchars($args->title) // Usamos htmlspecialchars para seguridad ?></title>
<style>
        /* --- INICIO DE ESTILOS PARA STICKY FOOTER Y LAYOUT --- */
        html {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column; /* Apila los elementos verticalmente */
            min-height: 100vh;   /* El body ocupa AL MENOS toda la altura de la ventana */
            margin: 0;           /* Quita márgenes por defecto del body */
            padding-top: 0px;   /* AJUSTA ESTO a la altura de tu header + navbar si son fijos.*/
            background-color: #f8f9fa; /* Color de fondo más suave para la página */
            color: #212529;       /* Color de texto principal de Bootstrap */
        }

        /* Contenedor principal del contenido de la página */
        .main-content {
            flex-grow: 1; /* PERMITE QUE ESTA SECCIÓN CREZCA y empuje el footer hacia abajo */
            /* 'container' de Bootstrap ya da márgenes laterales, puedes añadir padding vertical si quieres */
            padding-top: 1rem;    /* Un poco de espacio arriba del contenido */
            padding-bottom: 1rem; /* Un poco de espacio abajo del contenido */
        }

        /* Ajustes para el header y navbar si son fijos o sticky */
        #app.sticky-top {
            /* Si este div es el que se pega arriba, su altura es la que necesitas para el padding-top del body */
            /* Puedes inspeccionar su altura en las herramientas de desarrollador del navegador */
            /* y ajustar el padding-top del body en consecuencia. */
        }

        /* Estilos para el footer (aunque la mayoría se aplican con clases Bootstrap) */
        .footer {
            width: 100%; /* Asegura que ocupe todo el ancho */
            /* Las clases bg-dark, text-white, text-center, py-3 se aplican en la etiqueta <footer> */
        }
        /* --- FIN DE ESTILOS PARA STICKY FOOTER Y LAYOUT --- */
    </style>
</head>
<body>
<div id="app" class="container-fluid p-0 sticky-top">
        <header class="row m-0 bg-dark bg-gradient" data-bs-theme="dark">
            <div class="col-9">
                <h1 class="ml-3 mt-2 text-primary">Inventa Fácil</h1> </div>
            <div class="col-3 mt-2">
                <form class="d-flex" role="search">
                    <input class="form-control me-2" id="buscar-palabra" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success" onclick="" type="button"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </header>
        <nav class="navbar navbar-expand-lg bg-dark bg-gradient mb-3" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>" aria-current="page" href="<?= URL ?>">
                        <i class="bi bi-house-fill"></i>
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeNav ?? '') === 'equipos' ? 'active' : '' ?>" href="<?= htmlspecialchars($urlBase) ?>equipos">
                        <i class="bi bi-pc-display"></i>Inventario de Equipos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($activeNav ?? '') === 'personal' ? 'active' : '' ?>" href="<?= URL ?>personal">
                        <i class="bi bi-person-vcard-fill"></i>
                        Personal
                    </a>
                </li>
                <?php /* if(isset($ua->sv) && $ua->sv ) : // Si la sesión es válida (ejemplo de cómo podrías añadir más enlaces) ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activeNav ?? '') === 'otra_seccion' ? 'active' : '' ?>" href="<?= URL ?>otra_seccion">Otra Sección</a>
                    </li>
                <?php endif; */ ?>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-3">
                <?php if( !(isset($ua->sv) && $ua->sv) ) : // Si no hay sesión válida ?>
                    <li class="nav-item">
                        <a href="<?= URL ?>session/iniSession" class="nav-link <?= ($activeNav ?? '') === 'login' ? 'active' : '' ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </a>
                    </li>
                <?php else : // Si hay sesión válida ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($ua->username ?? 'Usuario') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a href="<?= URL ?>session/logout" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
    </div>
<?php
} // Fin de la función setHeader
?>