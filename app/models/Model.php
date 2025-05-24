<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/models/Model.php
    namespace app\models; // Namespace para los modelos

    // Importa la clase DB para poder heredar de ella.
    // Asumimos que DB.php está en app/classes/DB.php y tiene el namespace app\classes
    use app\classes\DB;

    class Model extends DB { // La clase Model hereda de la clase DB

        public function __construct(){
            // Llama al constructor de la clase padre (DB)
            // Esto es importante para que se inicialice la conexión a la base de datos
            // y cualquier otra configuración que tengas en el constructor de DB.
            parent::__construct();

            // Opcional: Asegurar explícitamente la conexión aquí si el constructor de DB no lo hace
            // o si quieres una lógica de conexión específica para todos los modelos.
            // La mayoría de las veces, el constructor de DB ya manejará la conexión.
            // if (method_exists($this, 'connect') && (empty($this->conex) || $this->conex->connect_errno) ) {
            //     $this->connect();
            // }
        }

        // Aquí podrías añadir en el futuro métodos comunes que todos tus modelos
        // podrían necesitar, por ejemplo, un método genérico para buscar por ID,
        // o para sanitizar datos, etc. Por ahora, puede ser así de simple.
    }
?>