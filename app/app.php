<?php
// Ubicación: TU_PROYECTO_RAIZ/app/app.php
namespace app;

use app\classes\Autoloader;
use app\classes\Router;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class App {

    public function __construct(){
        $this->init();
    }

    private function init(){
        $this->initConfig();
        $this->loadFunctions();
        $this->initAutoloader();
        $this->initRouter();
    }

    private function initConfig(){
        if(!file_exists(__DIR__ . "/config.php")){ // config.php está en el mismo dir que app.php
            die("Error Crítico: No se encontró el archivo de configuración 'config.php'.");
        }
        require_once __DIR__ . '/config.php';
    }

    private function loadFunctions(){
        if(!defined('FUNCTIONS') || !file_exists(FUNCTIONS . "main_functions.php")){
            die("Error Crítico: No se encontró el archivo 'main_functions.php'. Verifica la constante FUNCTIONS.");
        }
        require_once FUNCTIONS . 'main_functions.php';
    }

    private function initAutoloader(){
        if(!defined('CLASSES') || !file_exists(CLASSES . "Autoloader.php")){
            die("Error Crítico: No se encontró el archivo 'Autoloader.php'. Verifica la constante CLASSES.");
        }
        require_once CLASSES . 'Autoloader.php'; // Carga la clase Autoloader en sí misma
        Autoloader::register(); // Llama al método estático
    }

    private function initRouter(){
        // El autoloader debería cargar Router.php
        $router = new Router();
        $router->route();
    }

    /**
     * Método estático público para ejecutar la aplicación.
     * Este es el método que se llama desde public/index.php.
     */
    public static function run(){ // <--- ASEGÚRATE DE QUE SEA public static
        $app = new self(); // Crea una instancia de App, lo que dispara __construct() e init()
    }
}
?>