<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/models/personal.php
    namespace app\models;

    // Asumimos que Model.php (que extiende DB.php) ya está definido y funcionando.

    class personal extends Model { // Nombre de clase 'personal' (minúscula)

        // protected $table_name = 'personal'; // Opcional, si DB.php no infiere bien el nombre.

        // Campos de tu tabla 'personal'
        protected $fillable = [
            'nombre',
            'apellido',
            'email',
            'puesto',
            'activo' // Importante si quieres filtrar por personal activo
        ];

        public function __construct(){
            parent::__construct(); // Llama al constructor de Model (y este al de DB)
            // Asegurar que la conexión $this->conex esté lista
            if (method_exists($this, 'connect') && (empty($this->conex) || $this->conex->connect_errno) ) {
                $this->connect();
            }
        }

        /**
         * Obtiene todo el personal activo para usar en dropdowns, etc.
         * @return string JSON con el personal activo (id_empleado, nombre, apellido).
         */
        public function obtenerPersonalParaAsignacion() {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("PersonalModel: Conexión a BD no establecida en obtenerPersonalParaAsignacion");
                return json_encode([]);
            }

            // Selecciona solo los campos necesarios y filtra por personal activo.
            // Ajusta la condición de 'activo = 1' si tu campo se llama diferente o no existe.
            $sql = "SELECT id_empleado, nombre, apellido FROM personal WHERE activo = 1 ORDER BY apellido, nombre";
            // Si no tienes campo 'activo' o no quieres filtrar por él aún:
            // $sql = "SELECT id_empleado, nombre, apellido FROM personal ORDER BY apellido, nombre";

            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar la consulta (obtenerPersonalParaAsignacion): " . $this->conex->error);
                return json_encode([]);
            }

            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = [];
            while($fila = $resultado->fetch_assoc()){
                $datos[] = $fila;
            }
            $stmt->close();
            return json_encode($datos);
        }

        // --- MÉTODOS CRUD PARA PERSONAL (Usando Sentencias Preparadas) ---
        // Puedes añadir los métodos completos para crear, actualizar, eliminar y obtener por ID
        // de forma similar a como lo hicimos para el modelo 'equipo.php'.
        // Por ahora, nos enfocamos en el que necesita EquiposController.

        /**
         * Ejemplo: Obtiene todo el personal (puedes adaptar esto para la vista de listado de personal)
         */
        public function obtenerTodos() {
            if (empty($this->conex) || $this->conex->connect_errno) {
                return json_encode([]);
            }
            $sql = "SELECT * FROM personal ORDER BY id_empleado DESC";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) { return json_encode([]); }
            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = [];
            while($fila = $resultado->fetch_assoc()){ $datos[] = $fila; }
            $stmt->close();
            return json_encode($datos);
        }

        // Aquí podrías añadir:
        // public function crear($datos) { ... }
        // public function actualizar($id, $datos) { ... }
        // public function eliminar($id) { ... } // (Borrado lógico cambiando 'activo')
        // public function obtenerPorId($id) { ... }
    }
?>