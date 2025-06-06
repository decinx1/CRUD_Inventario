<?php
    namespace app\models;

    class equipo extends Model { 

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
            // Asegura que la conexión $this->conex esté lista.
            if (method_exists($this, 'connect') && (empty($this->conex) || $this->conex->connect_errno) ) {
                $this->connect();
            }
        }

        /**
         * Obtiene todos los equipos, uniéndolos con la tabla de personal
         * para obtener el nombre de la persona asignada.
         * @return string JSON con los equipos, o un JSON con array vacío si hay error/no hay datos.
         */
        public function obtenerTodosConNombrePersonal() {
            // ... (el código de este método que ya tienes) ...
            // Solo una pequeña corrección en tu consulta SQL original:
            // Quita los espacios extra antes de $stmt = ...
            $sql = "SELECT e.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_personal, p.puesto as puesto_personal
                    FROM equipos e
                    LEFT JOIN personal p ON e.Id_personal_asignado = p.id_empleado
                    ORDER BY e.id_equipo DESC"; // Añadido ORDER BY para consistencia

            $stmt = $this->conex->prepare($sql);

            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar la consulta (obtenerTodosConNombrePersonal): " . $this->conex->error);
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

        /**
         * Obtiene un equipo específico por su ID, incluyendo el nombre del personal asignado.
         * @param int $id El ID del equipo.
         * @return string JSON con el equipo (dentro de un array), o un JSON con array vacío si no se encuentra o hay error.
         */
        public function obtenerPorIdConNombrePersonal($id) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("EquipoModel: Conexión a BD no establecida en obtenerPorIdConNombrePersonal");
                return json_encode([]);
            }
            $sql = "SELECT e.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_personal, p.puesto as puesto_personal
                    FROM equipos e
                    LEFT JOIN personal p ON e.Id_personal_asignado = p.id_empleado
                    WHERE e.id_equipo = ?";

            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar la consulta (obtenerPorIdConNombrePersonal): " . $this->conex->error);
                return json_encode([]);
            }
            $stmt->bind_param('i', $id); // 'i' porque id_equipo es un entero
            $stmt->execute();
            $resultado = $stmt->get_result();
            $dato = $resultado->fetch_assoc(); // Solo esperamos una fila
            $stmt->close();
            return json_encode($dato ? [$dato] : []); // Devuelve un array para consistencia si se encuentra
        }
        /**
         * Busca equipos en la base de datos por un término dado.
         * Realiza una búsqueda LIKE en varios campos y devuelve los resultados.
         * Utiliza sentencias preparadas para evitar inyecciones SQL.
         * 
         * @param string $termino El término de búsqueda. Puede ser parte del tipo, marca, modelo, número de serie, estado o nombre del personal asignado
         * @return string JSON con los equipos encontrados, o un JSON vacío.
         */
        public function buscarEquipos($termino) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("EquipoModel: Conexión BD no establecida - buscarEquipos");
                return json_encode([]);
            }

            // Añadir los comodines % para la búsqueda LIKE
            $terminoLike = "%" . $termino . "%";

            // La consulta busca en varios campos usando OR y hace el JOIN a personal
            $sql = "SELECT e.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_personal, p.puesto as puesto_personal
                    FROM equipos e
                    LEFT JOIN personal p ON e.Id_personal_asignado = p.id_empleado
                    WHERE (e.tipo_equipo LIKE ? OR 
                            e.marca LIKE ? OR 
                            e.modelo LIKE ? OR 
                            e.numero_serie LIKE ? OR
                            e.estado LIKE ? OR
                            CONCAT(p.nombre, ' ', p.apellido) LIKE ?)";
            $sql .= " ORDER BY e.id_equipo DESC";

            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar consulta (buscarEquipos): " . $this->conex->error . " SQL: " . $sql);
                return json_encode([]);
            }

            // Vincular el mismo parámetro a todos los placeholders (?)
            // "ssssss" porque hay 6 placeholders y todos son strings para LIKE
            $stmt->bind_param('ssssss', $terminoLike, $terminoLike, $terminoLike, $terminoLike, $terminoLike, $terminoLike);
            
            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = [];
            while($fila = $resultado->fetch_assoc()){
                $datos[] = $fila;
            }
            $stmt->close();
            return json_encode($datos);
        }

        /**
         * Crea un nuevo equipo en la base de datos usando sentencias preparadas.
         * @param array $datos Array asociativo con los datos del equipo.
         * Campos esperados: tipo_equipo, marca, modelo, numero_serie, estado,
         * fecha_adquisicion (YYYY-MM-DD o null), Id_personal_asignado (int o null), notas.
         * @return int|bool El ID del equipo insertado o false en caso de error.
         */
        public function crear($datos) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("EquipoModel: Conexión a BD no establecida en crear");
                return false;
            }

            // Definir los campos y sus tipos para el bind_param
            // Asegúrate que el orden y los tipos coincidan con tu tabla y los datos que pasas
            $columnasPermitidas = [
                'tipo_equipo' => 's', 'marca' => 's', 'modelo' => 's', 'numero_serie' => 's',
                'estado' => 's', 'fecha_adquisicion' => 's', 'Id_personal_asignado' => 'i', 'notas' => 's'
            ];

            $columnasSql = [];
            $placeholdersSql = [];
            $tiposBind = "";
            $valoresBind = [];
            $datosParaBind = []; // Array para almacenar los valores a los que se enlazarán las referencias

            foreach ($columnasPermitidas as $columna => $tipo) {
                if (array_key_exists($columna, $datos)) {
                    $columnasSql[] = "`$columna`"; // Usar backticks para los nombres de columna
                    $placeholdersSql[] = '?';
                    $tiposBind .= $tipo;

                    // Manejo especial para campos que pueden ser NULL
                    if (($columna === 'Id_personal_asignado' || $columna === 'fecha_adquisicion' || $columna === 'numero_serie' || $columna === 'notas') &&
                        ($datos[$columna] === '' || is_null($datos[$columna]))) {
                        $datosParaBind[$columna] = null;
                    } else {
                        $datosParaBind[$columna] = $datos[$columna];
                    }
                    $valoresBind[] = &$datosParaBind[$columna]; // Pasar por referencia
                }
            }

            if (empty($columnasSql)) {
                error_log("EquipoModel: No hay datos válidos para crear el equipo.");
                return false;
            }

            $sql = "INSERT INTO equipos (" . implode(", ", $columnasSql) . ") VALUES (" . implode(", ", $placeholdersSql) . ")";

            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar la consulta (crear equipo): " . $this->conex->error . " SQL: " . $sql);
                return false;
            }

            call_user_func_array([$stmt, 'bind_param'], array_merge([$tiposBind], $valoresBind));

            if ($stmt->execute()) {
                $idInsertado = $this->conex->insert_id;
                $stmt->close();
                return $idInsertado;
            } else {
                error_log("EquipoModel: Error al ejecutar la consulta (crear equipo): " . $stmt->error);
                $stmt->close();
                return false;
            }
        }

        /**
         * Actualiza un equipo existente en la base de datos usando sentencias preparadas.
         * @param int $id El ID del equipo a actualizar.
         * @param array $datos Array asociativo con los nuevos datos del equipo.
         * @return bool True si fue exitoso, false en caso de error.
         */
        public function actualizar($id, $datos) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("EquipoModel: Conexión a BD no establecida en actualizar");
                return false;
            }

            $setsSql = [];
            $tiposBind = "";
            $valoresBind = [];
            $datosParaBind = []; // Array para almacenar los valores a los que se enlazarán las referencias

            $columnasPermitidas = [
                'tipo_equipo' => 's', 'marca' => 's', 'modelo' => 's', 'numero_serie' => 's',
                'estado' => 's', 'fecha_adquisicion' => 's', 'Id_personal_asignado' => 'i', 'notas' => 's'
            ];

            foreach ($columnasPermitidas as $columna => $tipo) {
                if (array_key_exists($columna, $datos)) {
                    $setsSql[] = "`$columna` = ?";
                    $tiposBind .= $tipo;
                    if (($columna === 'Id_personal_asignado' || $columna === 'fecha_adquisicion' || $columna === 'numero_serie' || $columna === 'notas') &&
                        ($datos[$columna] === '' || is_null($datos[$columna]))) {
                        $datosParaBind[$columna] = null;
                    } else {
                        $datosParaBind[$columna] = $datos[$columna];
                    }
                    $valoresBind[] = &$datosParaBind[$columna];
                }
            }

            if (empty($setsSql)) {
                error_log("EquipoModel: No hay datos para actualizar el equipo.");
                return true; // O false, dependiendo de si consideras "nada que actualizar" como un error.
            }

            $tiposBind .= 'i'; // Para el id_equipo en el WHERE
            $datosParaBind['id_equipo_condicion'] = $id; // Usar una clave diferente para evitar colisión si 'id_equipo' está en $datos
            $valoresBind[] = &$datosParaBind['id_equipo_condicion'];

            $sql = "UPDATE equipos SET " . implode(", ", $setsSql) . " WHERE id_equipo = ?";

            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar la consulta (actualizar equipo): " . $this->conex->error . " SQL: " . $sql);
                return false;
            }

            call_user_func_array([$stmt, 'bind_param'], array_merge([$tiposBind], $valoresBind));

            $resultado = $stmt->execute();
            if (!$resultado) {
                error_log("EquipoModel: Error al ejecutar la consulta (actualizar equipo): " . $stmt->error);
            }
            $stmt->close();
            return $resultado;
        }

        /**
         * Elimina un equipo de la base de datos (borrado físico).
         * Considera implementar borrado lógico cambiando un campo 'activo' a 0.
         * @param int $id El ID del equipo a eliminar.
         * @return bool True si fue exitoso, false en caso de error.
         */
        public function eliminar($id) {
            if (empty($this->conex) || $this->conex->connect_errno) {
                error_log("EquipoModel: Conexión a BD no establecida en eliminar");
                return false;
            }

            // Para borrado lógico (si tienes un campo 'activo' en la tabla 'equipos'):
            // return $this->actualizar($id, ['activo' => 0]);

            $sql = "DELETE FROM equipos WHERE id_equipo = ?";
            $stmt = $this->conex->prepare($sql);
            if ($stmt === false) {
                error_log("EquipoModel: Error al preparar la consulta (eliminar equipo): " . $this->conex->error);
                return false;
            }
            $stmt->bind_param('i', $id);

            $resultado = $stmt->execute();
            if (!$resultado) {
                error_log("EquipoModel: Error al ejecutar la consulta (eliminar equipo): " . $stmt->error);
            }
            $stmt->close();
            return $resultado;
        }
    }
?>