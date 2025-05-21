<?php
    namespace app\controllers; // Namespace para los controladores

    // Usar las clases necesarias
    use app\classes\Views as View;          // Para renderizar las vistas
    use app\models\equipo as EquipoModel;  // Tu modelo para interactuar con la tabla 'equipos'
    use app\models\personal as PersonalModel; // Para obtener la lista de personal para asignación
    use app\classes\Redirect as Redirect;    // Para redirigir después de acciones

    class EquiposController extends Controller { // Hereda de tu controlador base

        private $equipoModelo;    // Propiedad para almacenar la instancia del modelo de equipo
        private $personalModelo;  // Propiedad para almacenar la instancia del modelo de personal

        public function __construct(){
            parent::__construct(); // Llama al constructor del Controller padre si tiene lógica importante
            $this->equipoModelo = new EquipoModel(); // Crea una instancia del modelo de equipo
            $this->personalModelo = new PersonalModel(); // Crea una instancia del modelo de personal

            // Es una buena práctica asegurar que la conexión a la BD esté disponible en los modelos
            // Si tus modelos no lo hacen automáticamente en su constructor, puedes añadirlo aquí:
            if (method_exists($this->equipoModelo, 'connect') && !$this->equipoModelo->conex) {
                $this->equipoModelo->connect();
            }
            if (method_exists($this->personalModelo, 'connect') && !$this->personalModelo->conex) {
                $this->personalModelo->connect();
            }
        }

        /**
         * Método para mostrar la lista de todos los equipos.
         * Se accederá típicamente vía la URL: /equipos o /equipos/index
         */
        public function index($params = null){
            // Obtener todos los equipos (con el nombre del personal asignado) del modelo
            $equiposJson = $this->equipoModelo->obtenerTodosConNombrePersonal();
            $equipos = json_decode($equiposJson); // Decodificar el JSON a un array de objetos PHP

            // Preparar los datos que se pasarán a la vista
            $datosVista = [
                'ua'      => auth\SessionController::sessionValidate() ?? (object)['sv' => 0], // Información de sesión del usuario
                'title'   => 'Inventario de Equipos de Cómputo', // Título de la página
                'equipos' => $equipos, // La lista de equipos
                'url_base'=> URL     // La URL base definida en config.php para construir enlaces
            ];

            // Renderizar la vista para mostrar la lista de equipos
            View::render('equipos/index', $datosVista); // Llama a la vista views/equipos/index.view.php
        }

        /**
         * Método para mostrar el formulario de creación de un nuevo equipo.
         * Se accederá típicamente vía la URL: /equipos/crear
         */
        public function crear($params = null){
            // Obtener la lista de personal activo para el dropdown de asignación
            $personalJson = $this->personalModelo->select(['id_empleado', 'nombre', 'apellido'])
                                                // ->where([['activo', 1]]) // Si tienes un campo 'activo' en personal
                                                 ->get();
            $personal = json_decode($personalJson);

            $datosVista = [
                'ua'        => auth\SessionController::sessionValidate() ?? (object)['sv' => 0],
                'title'     => 'Añadir Nuevo Equipo',
                'equipo'    => null, // Se pasa null porque es un formulario para un nuevo equipo
                'personal_disponible' => $personal, // Lista de personal para el <select>
                'accion'    => URL . 'equipos/guardar', // La URL a la que el formulario enviará los datos
                'url_base'  => URL
            ];
            View::render('equipos/form', $datosVista); // Llama a la vista views/equipos/form.view.php
        }

        /**
         * Método para procesar los datos enviados desde el formulario de creación y guardar un nuevo equipo.
         * Se accederá por POST desde la URL: /equipos/guardar
         */
        public function guardar($params = null){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificar que la petición sea POST
                // Sanitizar los datos del formulario (FILTER_SANITIZE_SPECIAL_CHARS es un buen comienzo)
                $datosFormulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

                // Preparar los datos para el modelo, asegurándose de que los campos opcionales
                // que pueden ser NULL en la BD se manejen correctamente si están vacíos.
                $datosEquipo = [
                    'tipo_equipo'           => $datosFormulario['tipo_equipo'] ?? '',
                    'marca'                 => $datosFormulario['marca'] ?? '',
                    'modelo'                => $datosFormulario['modelo'] ?? '',
                    'numero_serie'          => $datosFormulario['numero_serie'] ?? null,
                    'estado'                => $datosFormulario['estado'] ?? '',
                    'fecha_adquisicion'     => empty($datosFormulario['fecha_adquisicion']) ? null : $datosFormulario['fecha_adquisicion'],
                    'Id_personal_asignado'  => empty($datosFormulario['Id_personal_asignado']) ? null : (int)$datosFormulario['Id_personal_asignado'],
                    'notas'                 => $datosFormulario['notas'] ?? null
                ];

                // Validación básica (deberías expandirla según tus necesidades)
                if (empty($datosEquipo['tipo_equipo']) || empty($datosEquipo['marca']) || empty($datosEquipo['modelo']) || empty($datosEquipo['estado'])) {
                    // Si faltan datos, redirigir de nuevo al formulario con un mensaje de error
                    Redirect::to(URL . 'equipos/crear?error=faltan_datos_obligatorios');
                    return;
                }

                // Intentar crear el equipo usando el modelo
                $idNuevoEquipo = $this->equipoModelo->crear($datosEquipo);

                if ($idNuevoEquipo) {
                    // Si se crea con éxito, redirigir a la lista de equipos con mensaje de éxito
                    Redirect::to(URL . 'equipos?exito=creado');
                } else {
                    // Si falla, redirigir de nuevo al formulario con mensaje de error
                    Redirect::to(URL . 'equipos/crear?error=guardar_fallo');
                }
            } else {
                // Si no es una petición POST, redirigir a la lista de equipos
                Redirect::to(URL . 'equipos');
            }
        }

        /**
         * Método para mostrar el formulario de edición de un equipo existente.
         * Se accederá típicamente vía la URL: /equipos/editar/ID_DEL_EQUIPO
         * (ej. /equipos/editar/1)
         */
        public function editar($params = null){
            $idEquipo = $params[0] ?? null; // El ID del equipo viene del router en $params[0]
            if (!$idEquipo) {
                Redirect::to(URL . 'equipos?error=id_no_proporcionado');
                return;
            }

            // Obtener los datos del equipo a editar
            $equipoJson = $this->equipoModelo->obtenerPorIdConNombrePersonal((int)$idEquipo);
            $equipoArray = json_decode($equipoJson);

            if (!$equipoArray || empty($equipoArray)) {
                Redirect::to(URL . 'equipos?error=equipo_no_encontrado');
                return;
            }
            $equipo = $equipoArray[0]; // El modelo devuelve un array, tomamos el primer elemento

            // Obtener la lista de personal para el dropdown de asignación
            $personalJson = $this->personalModelo->select(['id_empleado', 'nombre', 'apellido'])
                                                // ->where([['activo', 1]]) // Si tienes campo 'activo'
                                                 ->get();
            $personal = json_decode($personalJson);

            $datosVista = [
                'ua'        => auth\SessionController::sessionValidate() ?? (object)['sv' => 0],
                'title'     => 'Editar Equipo: ' . htmlspecialchars($equipo->marca . ' ' . $equipo->modelo),
                'equipo'    => $equipo, // Los datos del equipo a editar
                'personal_disponible' => $personal,
                'accion'    => URL . 'equipos/actualizar/' . $idEquipo, // URL para enviar el formulario de actualización
                'url_base'  => URL
            ];
            View::render('equipos/form', $datosVista); // Reutiliza la vista de formulario
        }

        /**
         * Método para procesar los datos enviados desde el formulario de edición y actualizar un equipo.
         * Se accederá por POST desde la URL: /equipos/actualizar/ID_DEL_EQUIPO
         */
        public function actualizar($params = null){
            $idEquipo = $params[0] ?? null; // ID del equipo desde la URL
            if (!$idEquipo || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                Redirect::to(URL . 'equipos'); // Si no hay ID o no es POST, redirigir
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
                'Id_personal_asignado'  => empty($datosFormulario['Id_personal_asignado']) ? null : (int)$datosFormulario['Id_personal_asignado'],
                'notas'                 => $datosFormulario['notas'] ?? null
            ];

            if (empty($datosEquipo['tipo_equipo']) || empty($datosEquipo['marca']) || empty($datosEquipo['modelo']) || empty($datosEquipo['estado'])) {
                Redirect::to(URL . 'equipos/editar/' . $idEquipo . '?error=faltan_datos_obligatorios');
                return;
            }

            $resultado = $this->equipoModelo->actualizar((int)$idEquipo, $datosEquipo);

            if ($resultado) {
                Redirect::to(URL . 'equipos?exito=actualizado');
            } else {
                Redirect::to(URL . 'equipos/editar/' . $idEquipo . '?error=actualizar_fallo');
            }
        }

        /**
         * Método para eliminar un equipo (borrado lógico o físico).
         * Se accederá típicamente vía POST desde la URL: /equipos/eliminar/ID_DEL_EQUIPO
         * (Por seguridad, las acciones de borrado deben ser POST)
         */
        public function eliminar($params = null){
             // Por simplicidad, lo haremos con GET por ahora, pero POST es mejor.
            $idEquipo = $params[0] ?? null;
            if (!$idEquipo) {
                Redirect::to(URL . 'equipos?error=id_no_proporcionado_eliminar');
                return;
            }

            // Aquí podrías añadir una capa de confirmación si no la tienes en el frontend.
            $resultado = $this->equipoModelo->eliminar((int)$idEquipo); // Asume borrado físico o lógico implementado en el modelo

            if ($resultado) {
                Redirect::to(URL . 'equipos?exito=eliminado');
            } else {
                Redirect::to(URL . 'equipos?error=eliminar_fallo');
            }
        }
    }
?>