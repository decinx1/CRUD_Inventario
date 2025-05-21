<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/layouts/main_head.php
    function setHeader($args){ // $args es el objeto de datos pasado desde la vista (que lo recibió del controlador)
        // $args->ua contiene la información de la sesión del usuario
        // $args->title contiene el título de la página
        $ua = as_object( $args->ua ); // Asegura que $ua sea un objeto.
                                      // as_object() debe estar definida en main_functions.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL ?>bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title><?= htmlspecialchars($args->title) // Usamos htmlspecialchars para seguridad ?></title>
    <style>
        body {
            color: #888;
            background-color : #CCC;
        }
    </style>
</head>
<body>
<div id="app" class="container-fluid p-0 sticky-top">
        <header class="row m-0 bg-dark bg-gradient" data-bs-theme="dark">
            <div class="col-9">
                <h1 class="ml-3 mt-2">Inventa Fácil</h1> </div>
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
                            <a class="nav-link" aria-current="page" href="<?= URL ?>">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL ?>equipos">Inventario Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL ?>personal">Personal</a>
                        </li>
                        <?php if(isset($ua->sv) && $ua->sv ) : // Si la sesión es válida ?>
                            <?php endif ?>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-3"> <?php if( !$ua->sv ) : // Si no hay sesión válida ?>
                        <li class="nav-item">
                            <a href="<?= URL ?>session/iniSession" class="nav-link btn btn-link"> Iniciar Sesión
                            </a>
                        </li>
                        <?php else : // Si hay sesión válida ?>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle"
                                    role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($ua->username ?? 'Usuario') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end"> <li>
                                        <a href="<?= URL ?>session/logout" class="dropdown-item btn btn-link"> Cerrar Sesión
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<?php
} // Fin de la función setHeader
?>