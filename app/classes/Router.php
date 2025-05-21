<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/classes/Router.php
    namespace app\classes;

    // Importa el ErrorController para usarlo si algo sale mal.
    // El Autoloader se encargará de cargar ErrorController.php cuando se necesite.
    use app\controllers\ErrorController;

    class Router {
        private $uriParts = []; // Almacenará las partes de la URI procesada
        private $controllerName = 'Home'; // Controlador por defecto
        private $methodName = 'index';    // Método por defecto
        private $params = [];             // Parámetros pasados al método del controlador

        public function __construct() {
            // El constructor podría usarse para alguna configuración inicial si fuera necesario,
            // pero por ahora lo dejaremos simple.
        }

        /**
         * Procesa la URI actual y llama al controlador y método apropiados.
         */
        public function route() {
            $this->parseUri();
            $this->loadController();
        }

        /**
         * Analiza la URI obtenida de $_GET['uri'] (configurada por .htaccess).
         * Limpia y divide la URI en partes.
         */
        private function parseUri() {
            $uri = $_GET['uri'] ?? ''; // Obtiene 'uri' de la URL, o una cadena vacía si no existe
            $uri = trim($uri, '/');    // Quita las barras de los extremos
            $uri = filter_var($uri, FILTER_SANITIZE_URL); // Limpia la URI de caracteres no deseados
            $this->uriParts = $uri ? explode('/', $uri) : []; // Divide la URI por '/' en un array
        }

        /**
         * Determina y carga el controlador y el método, luego llama al método.
         */
        private function loadController() {
            // Determinar el nombre del Controlador
            if (!empty($this->uriParts[0])) {
                // La primera parte de la URI es el nombre del controlador.
                // Convierte a CamelCase (ej. "miControlador" -> "MiControlador")
                $this->controllerName = ucfirst(strtolower($this->uriParts[0]));
                array_shift($this->uriParts); // Quita el controlador del array de partes
            }

            // Caso especial para el controlador de sesión en la subcarpeta 'auth'
            if ($this->controllerName === 'Session') { // Si la URI es 'session/...'
                $fullControllerName = 'app\\controllers\\auth\\' . $this->controllerName . 'Controller';
            } else {
                $fullControllerName = 'app\\controllers\\' . $this->controllerName . 'Controller';
            }

            // Determinar el nombre del Método
            if (!empty($this->uriParts[0])) {
                $this->methodName = strtolower($this->uriParts[0]); // Convierte el método a minúsculas (convención)
                array_shift($this->uriParts); // Quita el método del array de partes
            }

            // Los elementos restantes en $this->uriParts son los parámetros
            $this->params = $this->uriParts ? array_values($this->uriParts) : [];

            // Verificar si la clase del controlador existe
            if (class_exists($fullControllerName)) {
                $controllerInstance = new $fullControllerName(); // Crea una instancia del controlador

                // Verificar si el método existe en el controlador
                if (method_exists($controllerInstance, $this->methodName)) {
                    // Llamar al método del controlador, pasando los parámetros
                    // call_user_func_array es útil para pasar un array de parámetros como argumentos individuales.
                    call_user_func_array([$controllerInstance, $this->methodName], [$this->params]);
                } else {
                    // Método no encontrado, llamar al ErrorController
                    $this->callErrorController('errorMNF'); // MNF = Method Not Found
                }
            } else {
                // Controlador no encontrado, llamar al ErrorController
                // error_log("Router: Controlador no encontrado - " . $fullControllerName); // Para depuración
                $this->callErrorController('error404');
            }
        }

        /**
         * Llama a un método del ErrorController.
         * @param string $method El método a llamar en ErrorController (error404 o errorMNF)
         */
        private function callErrorController($method) {
            // El Autoloader debería cargar ErrorController si aún no está cargado.
            $errorController = new ErrorController();
            if (method_exists($errorController, $method)) {
                $errorController->$method();
            } else {
                // Fallback muy básico si ErrorController o sus métodos no existen
                die("Error Crítico: El controlador de errores o el método especificado no pudieron ser cargados.");
            }
            exit; // Detener la ejecución después de mostrar el error
        }
    }
?>