<?php
    namespace app\models;

    class equipo extends Model { // El nombre de la clase 'equipo' (minúscula) es para que coincida
                                 // con cómo DB.php podría intentar derivar el nombre de la tabla.
                                 // Si prefieres 'Equipo' (mayúscula), asegúrate de que DB.php
                                 // lo maneje o define explícitamente $this->table_name = 'equipos';

        // Opcional: Si el nombre de tu tabla es diferente al nombre de la clase (ej. 'equipos_computo')
        // protected $table_name = 'equipos';

        // Lista de campos que se permitirían en una asignación masiva (actualmente no se usa para inserción/actualización directa con estos métodos).
        protected $fillable = [
            'tipo_equipo',
            'marca',
            'modelo',
            'numero_serie',
            'estado',
            'fecha_adquisicion',
            'Id_personal_asignado',
            'notas'
            // 'activo' // Si implementas borrado lógico con un campo 'activo' en la tabla
        ];

        public function __construct(){
            parent::__construct(); // Llama al constructor de Model (que llama al de DB)
            // Esta línea asegura que la conexión esté disponible como $this->conex
            // Si el constructor de DB ya hace $this->conex = new \mysqli(...),
            // entonces $this->connect() podría no ser necesario aquí si ya se llamó.
            // Es una buena práctica asegurarse de que $this->conex esté listo.
            if (method_exists($this, 'connect') && (empty($this->conex) || $this->conex->connect_errno) ) {
                $this->connect();
            }
        }

        /**
         * Obtiene todos los equipos, opcionalmente uniéndolos con la tabla de personal
         * para obtener el nombre de la persona asignada.
         * Usa sentencias preparadas implícitamente si no hay parámetros de usuario.
         * @param bool $soloActivos Si es true y tienes un campo 'activo', filtra por él.
         * @return string JSON con los equipos, o un JSON vacío si hay error/no hay datos.
         */
        public function obtenerTodosConNombrePersonal($soloActivos = false) { // Cambiado $soloActivos a false por defecto ya que no tienes campo 'activo' en tu tabla equipos del PDF
            if (empty($this->conex) || $this->conex->connect_errno) {
                 // error_log("Conexión a BD no establecida en obtenerTodosConNombrePersonal");
                return json_encode([]);
            }

            // Base de la consulta
            $sql = "SELECT e.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_personal
                    FROM equipos e
                    LEFT JOIN personal p ON 