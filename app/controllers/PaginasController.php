<?php
    namespace app\controllers;

    use app\classes\Views as View;
    use app\controllers\auth\SessionController; // Para la validación de sesión

    class PaginasController extends Controller {

        public function __construct(){
            parent::__construct();
        }

        /**
         * Muestra la página de Políticas de Privacidad.
         * Se accederá vía la URL: /paginas/privacidad o /politicas-privacidad (depende del router)
         */
        public function privacidad($params = null){
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Políticas de Privacidad - Inventa Fácil',
                'url_base'   => URL,
                'active_nav' => 'politicas_privacidad' // Un identificador para el menú si quieres resaltarlo
            ];
            // Renderizar la vista que creamos en el paso anterior
            View::render('politicas_privacidad', $datosVista);
        }

        /**
         * Muestra la página de Términos de Servicio (Ejemplo si la necesitaras).
         */
        public function terminos($params = null){
            $sessionData = SessionController::sessionValidate();
            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Términos de Servicio - Inventa Fácil',
                'url_base'   => URL,
                'active_nav' => 'terminos_servicio'
            ];
            // Necesitarías crear la vista 'terminos_servicio.view.php'
            View::render('terminos_servicio', $datosVista);
        }
    }
?>