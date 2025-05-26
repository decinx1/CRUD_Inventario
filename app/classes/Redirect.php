<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/classes/Redirect.php
    namespace app\classes;

    class Redirect {

        /**
         * Redirige al usuario a una nueva ubicación (URL).
         *
         * @param string $location La ruta o URL a la que se desea redirigir.
         * Si es una ruta relativa (ej. 'equipos', 'usuarios/nuevo'),
         * se asumirá que se concatena con la constante URL definida en config.php.
         * Si es una URL completa (ej. 'http://otro.sitio.com'), se usará directamente.
         */
        public static function to($location) {
            // Primero, verifica si las cabeceras ya han sido enviadas.
            // Si ya se enviaron, la redirección con header() no funcionará y causará un error.
            if (headers_sent()) {
                // Como fallback, intenta una redirección con JavaScript.
                // Esto no es ideal porque no detiene la ejecución del script PHP actual
                // y depende de que el cliente tenga JavaScript habilitado.
                echo '<script type="text/javascript">';
                if (strpos($location, 'http') !== 0 && defined('URL')) {
                    // Si no es una URL completa y URL está definida, anteponer URL
                    $finalLocation = rtrim(URL, '/') . '/' . ltrim($location, '/');
                    echo 'window.location.href="' . htmlspecialchars($finalLocation, ENT_QUOTES, 'UTF-8') . '";';
                } else {
                    echo 'window.location.href="' . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . '";';
                }
                echo '</script>';
                echo '<noscript>';
                echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars( (strpos($location, 'http') !== 0 && defined('URL')) ? (rtrim(URL, '/') . '/' . ltrim($location, '/')) : $location, ENT_QUOTES, 'UTF-8') . '" />';
                echo '</noscript>';
                // Es importante considerar que el script PHP puede seguir ejecutándose después de esto.
                // Si hay operaciones críticas después de la redirección, podrían ejecutarse.
                // Generalmente, después de una redirección, se debería llamar a exit().
                // Pero si llegamos aquí, es porque header() ya no es una opción.
                // Considera loguear este evento como una advertencia.
                error_log("Redirect.php: Redirección por header falló (headers_sent), usando fallback JS/meta.");
                exit; // Es mejor añadir exit aquí también para detener el script si es posible.
            }

            // Si es una URL externa (ya contiene http o https)
            if (strpos($location, 'http') === 0) {
                header('Location: ' . $location);
                exit; // Detener la ejecución del script después de enviar el header de redirección.
            }

            // Si es una ruta interna, anteponer la URL base (definida en config.php)
            if (defined('URL')) {
                // Asegurarse de que URL termine con una barra /
                // y que $location no empiece con una barra / para evitar dobles barras.
                $finalLocation = rtrim(URL, '/') . '/' . ltrim($location, '/');
                header('Location: ' . $finalLocation);
                exit;
            } else {
                // Fallback MUY básico si la constante URL no está definida (no debería pasar)
                // Intenta una redirección relativa desde la raíz del dominio.
                // Esto es menos fiable y depende de la estructura de tu servidor.
                error_log("Redirect.php: Constante URL no definida. Usando redirección relativa.");
                header('Location: /' . ltrim($location, '/'));
                exit;
            }
        }
    }
?>