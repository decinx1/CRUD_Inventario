<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/classes/DB.php
    namespace app\classes; // Namespace correcto

    class DB { // El nombre de la clase debe ser DB
        // ... (todo el código de tu clase DB: propiedades, constructor, connect(), where(), get(), etc.) ...
        // Por ejemplo:
        public $db_host;
        public $db_name;
        private $db_user;
        private $db_passwd;
        public $conex = null; // Inicializa a null

        // Atributos de control para las consultas
        public $s = " * ";
        public $c = "";
        public $j = "";
        public $w = " 1 ";
        public $o = "";
        public $l = "";


        public function __construct($dbh = DB_HOST, $dbn = DB_NAME, $dbu = DB_USER, $dbp = DB_PASS){
            // DB_HOST, DB_NAME, etc., deben estar definidas en config.php
            if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
                die("Error Crítico: Las constantes de conexión a la base de datos no están definidas. Revisa config.php.");
            }
            $this->db_host   = $dbh;
            $this->db_name   = $dbn;
            $this->db_user   = $dbu;
            $this->db_passwd = $dbp;

            // Intentar conectar en el constructor es una opción,
            // o hacerlo bajo demanda cuando se necesite.
            // $this->connect();
        }

        public function connect(){
            $this->conex = new \mysqli($this->db_host, $this->db_user, $this->db_passwd, $this->db_name);
            if ($this->conex->connect_errno) {
                // En lugar de un echo, es mejor loguear el error o lanzar una excepción
                error_log("Error al conectarse a la BD: " . $this->conex->connect_error);
                // die("Error al conectarse a la BD " . $this->conex->connect_error);
                return null; // O false, o lanzar excepción
            }
            $this->conex->set_charset("utf8mb4"); // utf8mb4 es mejor que utf8
            return $this->conex;
        }

        // ... (resto de tus métodos: all, select, count, join, where, orderBy, limit, get)
        // ¡RECUERDA LA IMPORTANCIA DE USAR SENTENCIAS PREPARADAS EN where() y get() o en los modelos!

        // Ejemplo simplificado de get() usando $this->conex y asumiendo que where ya ha sido modificado
        // para construir $this->w de forma segura o que los modelos harán el binding.
        public function get(){
            if (empty($this->conex) || $this->conex->connect_errno) {
                // error_log("DB: Conexión no establecida o errónea antes de get(). Intentando conectar...");
                $this->connect(); // Intenta conectar si no lo está o hay error
                if (empty($this->conex) || $this->conex->connect_errno) {
                    error_log("DB: Fallo al conectar en get().");
                    return json_encode(["error" => "Error de base de datos en get."]);
                }
            }

            // El nombre de la tabla se infiere del nombre de la clase que hereda (ej. 'equipo' se convierte en 'equipos')
            // Esta lógica puede necesitar ajustes si el nombre de la clase no es el singular de la tabla
            $tableName = strtolower(str_replace("app\\models\\","",get_class( $this ))) . 's'; // Simple pluralización añadiendo 's'
            if (get_class($this) === 'app\classes\DB') { // Si se llama directamente desde DB (no debería para get)
                // Tratar de obtener el nombre de la tabla de alguna otra manera o lanzar error
                // Por ahora, asumimos que no se llama así
                 error_log("DB::get() llamado en la instancia de DB. No se puede determinar la tabla.");
                 return json_encode(["error" => "Error interno del modelo al determinar tabla."]);
            }


            $sql = "SELECT " .
                        $this->s .
                        $this->c .
                        " FROM " .  $tableName . // Usar el nombre de tabla inferido
                        ( $this->j != "" ? " " . $this->j : "") . // Eliminado alias 'a' por ahora para simplificar
                        " WHERE " . $this->w .
                        $this->o .
                        $this->l;

            // error_log("SQL en DB::get(): " . $sql); // Para depurar la consulta

            $r = $this->conex->query( $sql ); // Usar $this->conex
            if (!$r) {
                error_log("Error en la consulta SQL: " . $this->conex->error . " | SQL: " . $sql);
                return json_encode(["error" => "Error al ejecutar la consulta."]);
            }

            $result = [];
            if ($r instanceof \mysqli_result) { // Asegurar que $r es un resultado válido
                while( $f = $r->fetch_assoc() ){
                    $result[] = $f;
                }
                $r->free(); // Liberar el conjunto de resultados
            }
            return json_encode( $result );
        }

    } // Cierre de la clase DB
?>