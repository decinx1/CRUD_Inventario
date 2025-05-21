<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/classes/Views.php
    namespace app\classes; // Namespace para las clases de tu aplicación

    class Views {

        /**
         * Renderiza (muestra) un archivo de vista y le pasa datos.
         *
         * @param string $viewName El nombre del archivo de vista (sin la extensión '.view.php')
         * que se encuentra en la carpeta definida por la constante VIEWS.
         * Ejemplo: 'home', 'equipos/index', 'auth/inisession'.
         * @param array $data Opcional. Un array asociativo de datos que se pasarán a la vista.
         * Estos datos estarán disponibles en la vista como propiedades de un objeto $d.
         */
        public static function render($viewName, $data = []){
            // Convertir el array de datos en un objeto para un acceso más limpio en la vista (ej. $d->title)
            // Esta función as_object() debe estar definida en tu archivo main_functions.php
            // y cargada por app.php.
            if (function_exists('as_object')) {
                $d = as_object($data);
            } else {
                // Si as_object no existe, podrías simplemente usar el array
                // o lanzar un error, pero es mejor asegurar que exista.
                // Por ahora, para evitar un error fatal si no existe, pasamos el array.
                // O podrías hacer: die("Error: La función as_object() no está definida.");
                $d = $data; // La vista tendría que acceder a los datos como $d['title'] en este caso.
                            // Pero ya confirmamos que tienes as_object().
            }

            // Construir la ruta completa al archivo de la vista.
            // VIEWS es una constante definida en config.php
            // que debería apuntar a TU_PROYECTO_RAIZ/app/resources/views/
            $viewFilePath = VIEWS . $viewName . '.view.php';

            // Verificar si el archivo de la vista existe
            if (file_exists($viewFilePath)) {
                // Si el archivo existe, incluirlo. Esto ejecutará el código PHP
                // y mostrará el HTML de la vista. Las variables $d (y cualquier otra
                // variable definida aquí antes del include) estarán disponibles dentro
                // del archivo de la vista.
                require_once $viewFilePath;
            } else {
                // Si el archivo de la vista no existe, muestra un error o maneja la situación.
                // En un entorno de producción, querrías loguear este error y mostrar una página de error amigable.
                die("Error Crítico: La vista '" . htmlspecialchars($viewName) . "' no fue encontrada en la ruta: " . htmlspecialchars($viewFilePath));
            }

            // El exit() aquí es opcional pero común en algunos mini-frameworks
            // para asegurar que nada más se ejecute después de renderizar la vista principal.
            // Si tus vistas incluyen layouts que a su vez llaman a funciones que cierran </body></html>,
            // este exit podría ser lo que buscas. Si necesitas ejecutar más código PHP después
            // de View::render() en tu controlador, entonces no deberías tener exit() aquí.
            // Basado en tu uso de closefooter() en las vistas, el flujo parece terminar después de renderizar.
            // exit(); // Coméntalo o elimínalo si necesitas ejecutar código después de renderizar la vista en el controlador.
        }
    }
?>