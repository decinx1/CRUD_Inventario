<?php
    namespace app\controllers;

    use app\classes\Views as View;
    use app\models\personal as PersonalModel;
    use app\classes\Redirect as Redirect;
    use app\controllers\auth\SessionController;

    class PersonalController extends Controller {

        private $personalModelo;

        public function __construct(){
            parent::__construct();
            $this->personalModelo = new PersonalModel();
            if (method_exists($this->personalModelo, 'connect') && (empty($this->personalModelo->conex) || $this->personalModelo->conex->connect_errno) ) {
                $this->personalModelo->connect();
            }
        }

        public function index($params = null){
            // Solo mostrar personal activo en la lista principal
            // Necesitaríamos un método en PersonalModel como 'obtenerActivos()'
            // Por ahora, usaremos obtenerTodos() y la vista podría filtrar o mostrar el estado 'activo'.
            // O mejor, creamos obtenerActivos() en el modelo.
            // Para este ejemplo, usaremos el método obtenerPersonalParaAsignacion que ya filtra por activos.
            // O el método obtenerTodos() si quieres verlos todos y manejar 'activo' en la vista.
            $personalJson = $this->personalModelo->obtenerTodos(); // O un método que filtre por activos si lo prefieres
            $personal = json_decode($personalJson);
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Gestión de Personal',
                'personal'   => $personal ?? [],
                'url_base'   => URL,
                'active_nav' => 'personal'
            ];
            View::render('personal/index', $datosVista);
        }

        public function crear($params = null){
            $sessionData = SessionController::sessionValidate();
            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Añadir Nuevo Personal',
                'empleado'   => null, // Para el formulario
                'accion'     => URL . 'personal/guardar',
                'url_base'   => URL,
                'active_nav' => 'personal'
            ];
            View::render('personal/form', $datosVista);
        }

        public function guardar($params = null){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $datosFormulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                $datosPersonal = [
                    'nombre'   => $datosFormulario['nombre'] ?? '',
                    'apellido' => $datosFormulario['apellido'] ?? '',
                    'email'    => $datosFormulario['email'] ?? '',
                    'puesto'   => $datosFormulario['puesto'] ?? '',
                    'activo'   => isset($datosFormulario['activo']) ? 1 : 0 // Manejo del checkbox 'activo'
                ];

                if (empty($datosPersonal['nombre']) || empty($datosPersonal['apellido']) || empty($datosPersonal['email']) || empty($datosPersonal['puesto'])) {
                    Redirect::to(URL . 'personal/crear?error=Faltan_datos_obligatorios');
                    return;
                }
                if (!filter_var($datosPersonal['email'], FILTER_VALIDATE_EMAIL)) {
                    Redirect::to(URL . 'personal/crear?error=Email_invalido');
                    return;
                }

                $resultado = $this->personalModelo->crear($datosPersonal);

                if ($resultado === 'error_duplicado_email') {
                    Redirect::to(URL . 'personal/crear?error=Email_ya_registrado');
                } elseif ($resultado) {
                    Redirect::to(URL . 'personal?exito=Personal_creado_exitosamente');
                } else {
                    Redirect::to(URL . 'personal/crear?error=Fallo_al_guardar_personal');
                }
            } else {
                Redirect::to(URL . 'personal');
            }
        }

        public function editar($params = null){
            $idEmpleado = $params[0] ?? null;
            if (!$idEmpleado || !filter_var($idEmpleado, FILTER_VALIDATE_INT)) {
                Redirect::to(URL . 'personal?error=ID_de_personal_invalido');
                return;
            }

            $empleadoJson = $this->personalModelo->obtenerPorId((int)$idEmpleado);
            $empleadoArray = json_decode($empleadoJson);

            if (!$empleadoArray || empty($empleadoArray)) {
                Redirect::to(URL . 'personal?error=Personal_no_encontrado');
                return;
            }
            $empleado = $empleadoArray[0];
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Editar Personal: ' . htmlspecialchars($empleado->nombre . ' ' . $empleado->apellido),
                'empleado'   => $empleado,
                'accion'     => URL . 'personal/actualizar/' . $idEmpleado,
                'url_base'   => URL,
                'active_nav' => 'personal'
            ];
            View::render('personal/form', $datosVista);
        }

        public function actualizar($params = null){
            $idEmpleado = $params[0] ?? null;
            if (!$idEmpleado || !filter_var($idEmpleado, FILTER_VALIDATE_INT) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                Redirect::to(URL . 'personal?error=Peticion_invalida_o_ID_faltante');
                return;
            }

            $datosFormulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $datosPersonal = [
                'nombre'   => $datosFormulario['nombre'] ?? '',
                'apellido' => $datosFormulario['apellido'] ?? '',
                'email'    => $datosFormulario['email'] ?? '',
                'puesto'   => $datosFormulario['puesto'] ?? '',
                'activo'   => isset($datosFormulario['activo']) ? 1 : 0
            ];

            if (empty($datosPersonal['nombre']) || empty($datosPersonal['apellido']) || empty($datosPersonal['email']) || empty($datosPersonal['puesto'])) {
                Redirect::to(URL . 'personal/editar/' . $idEmpleado . '?error=Faltan_datos_obligatorios');
                return;
            }
             if (!filter_var($datosPersonal['email'], FILTER_VALIDATE_EMAIL)) {
                Redirect::to(URL . 'personal/editar/' . $idEmpleado . '?error=Email_invalido');
                return;
            }

            $resultado = $this->personalModelo->actualizar((int)$idEmpleado, $datosPersonal);

            if ($resultado === 'error_duplicado_email') {
                    Redirect::to(URL . 'personal/editar/' . $idEmpleado . '?error=Email_ya_registrado_por_otro_usuario');
            } elseif ($resultado) {
                Redirect::to(URL . 'personal?exito=Personal_actualizado_exitosamente');
            } else {
                Redirect::to(URL . 'personal/editar/' . $idEmpleado . '?error=Fallo_al_actualizar_personal');
            }
        }

        public function eliminar($params = null){ // Debe ser POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Redirect::to(URL . 'personal?error=Metodo_no_permitido_para_eliminar');
                return;
            }
            $idEmpleado = $params[0] ?? null;
            if (!$idEmpleado || !filter_var($idEmpleado, FILTER_VALIDATE_INT)) {
                Redirect::to(URL . 'personal?error=ID_de_personal_invalido_para_eliminar');
                return;
            }

            $resultado = $this->personalModelo->eliminar((int)$idEmpleado); // Borrado lógico

            if ($resultado) {
                Redirect::to(URL . 'personal?exito=Personal_desactivado_exitosamente');
            } else {
                Redirect::to(URL . 'personal?error=Fallo_al_desactivar_personal');
            }
        }
    }
?>