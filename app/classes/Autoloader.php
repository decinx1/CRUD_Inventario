<?php
// Ubicación: TU_PROYECTO_RAIZ/app/classes/Autoloader.php
namespace app\classes;

class Autoloader {
    public static function register(){
        spl_autoload_register([__CLASS__,'autoload']);
    }

    private static function autoload($classNameWithNamespace) {
        $namespacePrefix = 'app\\';

        // Verifica si la clase solicitada usa el prefijo de namespace 'app\'
        if (strpos($classNameWithNamespace, $namespacePrefix) === 0) {
            // Quita el prefijo 'app\' para obtener la ruta relativa
            // Ej: "app\classes\Router" se convierte en "classes\Router"
            $relativeClassPath = substr($classNameWithNamespace, strlen($namespacePrefix));

            // Construye la ruta completa al archivo usando la constante ROOT
            // ROOT debería ser TU_PROYECTO_RAIZ/app/
            // $filePath será, por ejemplo:
            // C:/xampp/htdocs/CRUD_Inventario/app/classes/Router.php
            $filePath = ROOT . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassPath) . '.php';

            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            } else {
                // Para depurar, puedes descomentar esto y revisar tus logs de error de PHP:
                // error_log("Autoloader: Archivo NO ENCONTRADO para la clase '" . $classNameWithNamespace . "' en la ruta: '" . $filePath . "'. ROOT es: '" . ROOT . "'");
            }
        } else {
            // error_log("Autoloader: La clase '" . $classNameWithNamespace . "' NO usa el prefijo de namespace esperado '" . $namespacePrefix . "'.");
        }
        return false;
    }
}
?>