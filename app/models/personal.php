<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/models/personal.php
    namespace app\models;

    class personal extends Model {

        // protected $table_name = 'personal'; // Opcional si la clase se llama 'personal' y la tabla 'personal'

        protected $fillable = [
            'nombre',
            'apellido',
            'email',
            'puesto',
            'activo' // Asumiendo que tienes este campo como TINYINT(1) DEFAULT 1
        ];

        public function __construct(){
            parent::__construct();
            if (method_exists($this, 'connect') && (empty($this->conex) || $this->conex->connect_errno) ) {
                $this->connect();
            }
        }

        /**
         * Obtiene todo el personal activo.
         * @return string JSON con el personal activo.
         */
        public function obtenerPersonalParaAsignacion() {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("PersonalModel: Conexión BD no establecida - obtenerPersonalParaAsignacion");
                return json_encode([]);
            }
            $sql = "SELECT id_empleado, nombre, apellido, puesto FROM personal WHERE activo = 1 ORDER BY apellido, nombre";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar consulta (obtenerPersonalParaAsignacion): " . $this->conex->error);
                return json_encode([]);
            }
            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = [];
            while($fila = $resultado->fetch_assoc()){ $datos[] = $fila; }
            $stmt->close();
            return json_encode($datos);
        }

        /**
         * Obtiene todos los registros de personal.
         * @return string JSON con todo el personal.
         */
        public function obtenerTodos() {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("PersonalModel: Conexión BD no establecida - obtenerTodos");
                return json_encode([]);
            }
            // Aquí podrías añadir JOINs si necesitas datos de otras tablas relacionadas con personal
            $sql = "SELECT * FROM personal ORDER BY id_empleado DESC";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar consulta (obtenerTodos): " . $this->conex->error);
                return json_encode([]);
            }
            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = [];
            while($fila = $resultado->fetch_assoc()){ $datos[] = $fila; }
            $stmt->close();
            return json_encode($datos);
        }

        /**
         * Obtiene un registro de personal por su ID.
         * @param int $id El ID del empleado.
         * @return string JSON con los datos del empleado o un array vacío.
         */
        public function obtenerPorId($id) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("PersonalModel: Conexión BD no establecida - obtenerPorId");
                return json_encode([]);
            }
            $sql = "SELECT * FROM personal WHERE id_empleado = ?";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar consulta (obtenerPorId): " . $this->conex->error);
                return json_encode([]);
            }
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $dato = $resultado->fetch_assoc();
            $stmt->close();
            return json_encode($dato ? [$dato] : []);
        }

        /**
         * Crea un nuevo registro de personal.
         * @param array $datos Array asociativo con los datos del personal.
         * Campos esperados: nombre, apellido, email, puesto, activo (opcional, default 1)
         * @return int|string|bool El ID del nuevo registro, 'error_duplicado_email', o false.
         */
        public function crear($datos) {
            if (empty($this->conex) || $this->conex->connect_errno) { return false; }

            $columnasPermitidas = ['nombre' => 's', 'apellido' => 's', 'email' => 's', 'puesto' => 's', 'activo' => 'i'];
            $columnasSql = [];
            $placeholdersSql = [];
            $tiposBind = "";
            $valoresBind = [];
            $datosParaBind = [];

            foreach ($columnasPermitidas as $columna => $tipo) {
                if (array_key_exists($columna, $datos) || ($columna === 'activo' && !isset($datos[$columna]))) { // Incluir 'activo' si no se pasa para usar default
                    $columnasSql[] = "`$columna`";
                    $placeholdersSql[] = '?';
                    $tiposBind .= $tipo;

                    if ($columna === 'activo' && !isset($datos[$columna])) {
                        $datosParaBind[$columna] = 1; // Default a activo si no se especifica
                    } else {
                        $datosParaBind[$columna] = $datos[$columna];
                    }
                    $valoresBind[] = &$datosParaBind[$columna];
                }
            }

            if (empty($columnasSql)) { return false; }

            $sql = "INSERT INTO personal (" . implode(", ", $columnasSql) . ") VALUES (" . implode(", ", $placeholdersSql) . ")";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar (crear): " . $this->conex->error . " SQL: " . $sql);
                return false;
            }
            call_user_func_array([$stmt, 'bind_param'], array_merge([$tiposBind], $valoresBind));

            try {
                if ($stmt->execute()) {
                    $idInsertado = $this->conex->insert_id;
                    $stmt->close();
                    return $idInsertado;
                }
            } catch (\mysqli_sql_exception $e) {
                $stmt->close();
                if ($this->conex->errno === 1062) { // Error de entrada duplicada (para email UNIQUE)
                    return 'error_duplicado_email';
                }
                error_log("PersonalModel: Excepción SQL (crear): " . $e->getMessage());
                return false;
            }
            $stmt->close();
            return false;
        }

        /**
         * Actualiza un registro de personal existente.
         * @param int $id El ID del empleado a actualizar.
         * @param array $datos Array asociativo con los nuevos datos.
         * @return bool|string True si éxito, 'error_duplicado_email', o false.
         */
        public function actualizar($id, $datos) {
            if (empty($this->conex) || $this->conex->connect_errno) { return false; }

            $setsSql = [];
            $tiposBind = "";
            $valoresBind = [];
            $datosParaBind = [];
            $columnasPermitidas = ['nombre' => 's', 'apellido' => 's', 'email' => 's', 'puesto' => 's', 'activo' => 'i'];

            foreach ($columnasPermitidas as $columna => $tipo) {
                if (array_key_exists($columna, $datos)) {
                    $setsSql[] = "`$columna` = ?";
                    $tiposBind .= $tipo;
                    $datosParaBind[$columna] = $datos[$columna];
                    $valoresBind[] = &$datosParaBind[$columna];
                }
            }

            if (empty($setsSql)) { return true; /* No hay nada que actualizar */ }

            $tiposBind .= 'i'; // Para el id_empleado en el WHERE
            $datosParaBind['id_condicion'] = $id;
            $valoresBind[] = &$datosParaBind['id_condicion'];

            $sql = "UPDATE personal SET " . implode(", ", $setsSql) . " WHERE id_empleado = ?";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("PersonalModel: Error al preparar (actualizar): " . $this->conex->error . " SQL: " . $sql);
                return false;
            }
            call_user_func_array([$stmt, 'bind_param'], array_merge([$tiposBind], $valoresBind));

            try {
                $resultado = $stmt->execute();
                $stmt->close();
                return $resultado;
            } catch (\mysqli_sql_exception $e) {
                $stmt->close();
                if ($this->conex->errno === 1062) {
                    return 'error_duplicado_email';
                }
                error_log("PersonalModel: Excepción SQL (actualizar): " . $e->getMessage());
                return false;
            }
        }

        /**
         * Elimina un registro de personal (borrado lógico estableciendo activo = 0).
         * @param int $id El ID del empleado a "eliminar".
         * @return bool True si éxito, false en caso de error.
         */
        public function eliminar($id) {
            // Implementamos borrado lógico
            return $this->actualizar($id, ['activo' => 0]);
        }
    }
?>