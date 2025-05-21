<?php

// Activar el reporte de todos los errores y mostrarlos en pantalla (muy útil para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Archivo de Arranque de la Aplicación (Front Controller)
 *
 * Todas las peticiones web son dirigidas a este archivo por el servidor web (vía .htaccess).
 * Este archivo es responsable de cargar la configuración inicial, el autoloader de clases,
 * y luego iniciar la aplicación principal que manejará el enrutamiento y la lógica.
 */

// 1. Incluir el archivo principal de la aplicación (app.php)
//    __DIR__ es una constante mágica de PHP que devuelve la ruta al directorio
//    del archivo actual (en este caso, TU_PROYECTO_RAIZ/app/public/).
//    Al usar '/../app.php', subimos un nivel (a la carpeta 'app/') para encontrar 'app.php'.
require_once __DIR__ . '/../app.php';

// 2. Ejecutar la aplicación
//    Asumimos que tu clase principal se llama 'App' y está dentro del namespace 'app',
//    y tiene un método estático 'run()' para iniciar todo.
//    Si tu clase App no está en un namespace o está en uno diferente, ajusta esto.
\app\App::run();

?>