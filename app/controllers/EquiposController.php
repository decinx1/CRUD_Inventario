<?php
    namespace app\controllers;

    use app\classes\Views as View;
    use app\models\equipo as EquipoModel;
    use app\models\personal as PersonalModel;
    use app\classes\Redirect as Redirect;
    // Asegúrate que SessionController esté en la ruta correcta y con el namespace correcto
    use app\controllers\auth\SessionController;


    class EquiposController extends Controller {

        private $equipoModelo;
        private $personalModelo;

        public function __construct(){
            parent::__construct();
            $this->equipoModelo = new EquipoModel();
            $this->personalModelo = new PersonalModel();

            if (method_exists($this->equipoModelo, 'connect') && (empty($this->equipoModelo->conex) || $this->equipoModelo->conex->connect_errno) ) {
                $this->equipoModelo->connect();
            }
            if (method_exists($this->personalModelo, 'connect') && (empty($this->personalModelo->conex) || $this->personalModelo->conex->connect_errno) ) {
                $this->personalModelo->connect();
            }
        }

        /**
         * Muestra la lista de todos los equipos.
         * Los datos de cada equipo ya incluyen lo necesario para el modal "Leer Más".
         */
        public function index($params = null){
            $equiposJson = $this->equipoModelo->obtenerTodosConNombrePersonal();
            $equipos = json_decode($equiposJson);
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'         => $sessionData ?? (object)['sv' => 0],
                'title'      => 'Inventario de Equipos de Cómputo',
                'equipos'    => $equipos ?? [],
                'url_base'   => URL,
                'active_nav' => 'equipos'
            ];
            View::render('equipos/index', $datosVista);
        }

        public function buscar($params = null) {
            // Obtener el término de búsqueda de la URL (?termino=...)
            $termino = filter_input(INPUT_GET, 'termino', FILTER_SANITIZE_SPECIAL_CHARS);
            $termino = trim($termino ?? '');

            // Pasamos el término al método index para que se encargue de la lógica y renderizado.
            // Esto reutiliza la vista index.view.php
            $this->index(['termino_busqueda' => $termino]);
        }

        /**
         * Muestra el formulario para crear un nuevo equipo.
         */
        public function crear($params = null){
            $personalJson = $this->personalModelo->obtenerPersonalParaAsignacion(); // Usamos el método específico
            $personal = json_decode($personalJson);
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'        => $sessionData ?? (object)['sv' => 0],
                'title'     => 'Añadir Nuevo Equipo',
                'equipo'    => null,
                'personal_disponible' => $personal ?? [], // Asegurar que sea un array
                'accion'    => URL . 'equipos/guardar',
                'url_base'  => URL,
                'active_nav' => 'equipos'
            ];
            View::render('equipos/form', $datosVista);
        }

        /**
         * Guarda un nuevo equipo en la base de datos.
         */
        public function guardar($params = null){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $datosFormulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                $datosEquipo = [
                    'tipo_equipo'           => $datosFormulario['tipo_equipo'] ?? '',
                    'marca'                 => $datosFormulario['marca'] ?? '',
                    'modelo'                => $datosFormulario['modelo'] ?? '',
                    'numero_serie'          => $datosFormulario['numero_serie'] ?? null,
                    'estado'                => $datosFormulario['estado'] ?? '',
                    'fecha_adquisicion'     => empty($datosFormulario['fecha_adquisicion']) ? null : $datosFormulario['fecha_adquisicion'],
                    'Id_personal_asignado'  => (isset($datosFormulario['Id_personal_asignado']) && $datosFormulario['Id_personal_asignado'] !== '') ? (int)$datosFormulario['Id_personal_asignado'] : null,
                    'notas'                 => $datosFormulario['notas'] ?? null
                ];

                if (empty($datosEquipo['tipo_equipo']) || empty($datosEquipo['marca']) || empty($datosEquipo['modelo']) || empty($datosEquipo['estado'])) {
                    Redirect::to(URL . 'equipos/crear?error=Faltan_datos_obligatorios');
                    return;
                }

                $resultado = $this->equipoModelo->crear($datosEquipo);

                if ($resultado === 'error_duplicado') {
                    Redirect::to(URL . 'equipos/crear?error=Numero_de_serie_ya_existe');
                } elseif ($resultado) { // $resultado es el ID del nuevo equipo
                    Redirect::to(URL . 'equipos?exito=Equipo_creado_exitosamente');
                } else {
                    Redirect::to(URL . 'equipos/crear?error=Fallo_al_guardar_el_equipo');
                }
            } else {
                Redirect::to(URL . 'equipos');
            }
        }

        /**
         * Muestra el formulario para editar un equipo existente.
         */
        public function editar($params = null){
            $idEquipo = $params[0] ?? null;
            if (!$idEquipo || !filter_var($idEquipo, FILTER_VALIDATE_INT)) { // Validar que el ID sea un entero
                Redirect::to(URL . 'equipos?error=ID_de_equipo_invalido');
                return;
            }

            $equipoJson = $this->equipoModelo->obtenerPorIdConNombrePersonal((int)$idEquipo);
            $equipoArray = json_decode($equipoJson);

            if (!$equipoArray || empty($equipoArray)) {
                Redirect::to(URL . 'equipos?error=Equipo_no_encontrado');
                return;
            }
            $equipo = $equipoArray[0];

            $personalJson = $this->personalModelo->obtenerPersonalParaAsignacion();
            $personal = json_decode($personalJson);
            $sessionData = SessionController::sessionValidate();

            $datosVista = [
                'ua'        => $sessionData ?? (object)['sv' => 0],
                'title'     => 'Editar Equipo: ' . htmlspecialchars($equipo->marca . ' ' . $equipo->modelo),
                'equipo'    => $equipo,
                'personal_disponible' => $personal ?? [], // Asegurar que sea un array
                'accion'    => URL . 'equipos/actualizar/' . $idEquipo,
                'url_base'  => URL,
                'active_nav' => 'equipos'
            ];
            View::render('equipos/form', $datosVista);
        }

        /**
         * Actualiza un equipo existente en la base de datos.
         */
        public function actualizar($params = null){
            $idEquipo = $params[0] ?? null;
            if (!$idEquipo || !filter_var($idEquipo, FILTER_VALIDATE_INT) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                Redirect::to(URL . 'equipos?error=Peticion_invalida_o_ID_faltante');
                return;
            }

            $datosFormulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $datosEquipo = [
                'tipo_equipo'           => $datosFormulario['tipo_equipo'] ?? '',
                'marca'                 => $datosFormulario['marca'] ?? '',
                'modelo'                => $datosFormulario['modelo'] ?? '',
                'numero_serie'          => $datosFormulario['numero_serie'] ?? null,
                'estado'                => $datosFormulario['estado'] ?? '',
                'fecha_adquisicion'     => empty($datosFormulario['fecha_adquisicion']) ? null : $datosFormulario['fecha_adquisicion'],
                'Id_personal_asignado'  => (isset($datosFormulario['Id_personal_asignado']) && $datosFormulario['Id_personal_asignado'] !== '') ? (int)$datosFormulario['Id_personal_asignado'] : null,
                'notas'                 => $datosFormulario['notas'] ?? null
            ];

            if (empty($datosEquipo['tipo_equipo']) || empty($datosEquipo['marca']) || empty($datosEquipo['modelo']) || empty($datosEquipo['estado'])) {
                Redirect::to(URL . 'equipos/editar/' . $idEquipo . '?error=Faltan_datos_obligatorios');
                return;
            }

            $resultado = $this->equipoModelo->actualizar((int)$idEquipo, $datosEquipo);

            if ($resultado === 'error_duplicado_ns_actualizar') { // Suponiendo que el modelo puede devolver esto
                Redirect::to(URL . 'equipos/editar/' . $idEquipo . '?error=Numero_de_serie_ya_existe_en_otro_equipo');
            } elseif ($resultado) {
                Redirect::to(URL . 'equipos?exito=Equipo_actualizado_exitosamente');
            } else {
                Redirect::to(URL . 'equipos/editar/' . $idEquipo . '?error=Fallo_al_actualizar_el_equipo');
            }
        }

        /**
         * Elimina un equipo. Espera una petición POST.
         */
        public function eliminar($params = null){
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Redirect::to(URL . 'equipos?error=Metodo_no_permitido');
                return;
            }

            $idEquipo = $params[0] ?? null;
            if (!$idEquipo || !filter_var($idEquipo, FILTER_VALIDATE_INT)) {
                Redirect::to(URL . 'equipos?error=ID_de_equipo_invalido_para_eliminar');
                return;
            }

            $resultado = $this->equipoModelo->eliminar((int)$idEquipo);

            if ($resultado) {
                Redirect::to(URL . 'equipos?exito=Equipo_eliminado_exitosamente');
            } else {
                Redirect::to(URL . 'equipos?error=Fallo_al_eliminar_el_equipo');
            }
        }
    }
?>