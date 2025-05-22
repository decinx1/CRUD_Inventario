<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/controllers/HomeController.php
    namespace app\controllers; // Namespace para los controladores

    // Usamos la clase View para renderizar la vista
    use app\classes\Views as View;
    // Usamos el SessionController para verificar el estado de la sesión del usuario
    use app\controllers\auth\SessionController;

    // HomeController hereda de la clase base Controller
    class HomeController extends Controller {

        public function __construct(){
            // Llama al constructor de la clase padre (Controller)
            // Podrías añadir aquí lógica que se ejecute para todos los métodos de este controlador.
            parent::__construct();
        }

        /**
         * Método 'index'
         * Este es el método que el Router llamará por defecto cuando se acceda
         * a la ruta raíz del sitio (ej. http://inventafacil.local/)
         * o a /home o /home/index.
         *
         * @param array|null $params Parámetros pasados desde el Router (actualmente no se usan aquí).
         */
        public function index($params = null){
            // Validar la sesión del usuario
            $datosDeSesion = \app\controllers\auth\SessionController::sessionValidate(); // Usar el FQCN es más seguro aquí

            // Preparar los datos que se pasarán a la vista
            $datosParaLaVista = [
                'ua'         => $datosDeSesion ?? (object)['sv' => 0],
                'title'      => 'Bienvenido a Inventa Fácil',
                'url_base'   => URL, // La URL base definida en config.php
                'active_nav' => 'home'  // <--- ¡AÑADIDO! Esto le dice a main_head.php qué enlace resaltar
            ];

            View::render('home', $datosParaLaVista);
        }
    }
?>