<?php

// Constante para el separador de directorios ( / o \ )
define('DS', DIRECTORY_SEPARATOR);

// ROOT ahora es la ruta a la carpeta 'app/', ya que config.php está dentro de 'app/'.
// Ej: C:\xampp\htdocs\CRUD_Inventario\app\
define('ROOT', __DIR__ . DS);

// --- Configuración de Entorno y URL ---
define('IS_LOCAL', in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1','::1'])); // Simplificado, devuelve true/false

// URL base de tu aplicación, apuntando a donde se sirve la carpeta public/
// Si inventafacil.local apunta a TU_PROYECTO_RAIZ/app/public/, esta URL es correcta.
define('URL', IS_LOCAL ? 'http://inventafacil.local/' : 'URL_DE_PRODUCCION_AQUI'); // Reemplaza con tu URL remota si la tienes

// --- Configuración de la Base de Datos ---
define('DB_HOST', IS_LOCAL ? 'localhost' : 'REMOTE HOST');
define('DB_USER', IS_LOCAL ? 'root' : 'REMOTE USER');
define('DB_PASS', IS_LOCAL ? '' : 'REMOTE PASSWORD');
define('DB_NAME', IS_LOCAL ? 'inventafacil' : 'REMOTE DATA BASE NAME'); // Nombre de tu BD en minúsculas

// --- RUTAS DEL SERVIDOR (para PHP includes/requires, relativas a ROOT que es la carpeta 'app/') ---
define('CLASSES',     ROOT . 'classes' . DS);     // Ej: C:\xampp\htdocs\CRUD_Inventario\app\classes\
define('CONTROLLERS', ROOT . 'controllers' . DS); // Ej: C:\xampp\htdocs\CRUD_Inventario\app\controllers\
define('MODELS',      ROOT . 'models' . DS);      // Ej: C:\xampp\htdocs\CRUD_Inventario\app\models\
define('RESOURCES',   ROOT . 'resources' . DS);   // Ej: C:\xampp\htdocs\CRUD_Inventario\app\resources\
define('PUBLIC_PATH', ROOT . 'public' . DS);    // Ruta del servidor a la carpeta public DENTRO de app/
                                                // Ej: C:\xampp\htdocs\CRUD_Inventario\app\public\

define('FUNCTIONS',   RESOURCES . 'functions' . DS); // Ej: ...app/resources/functions/
define('LAYOUTS',     RESOURCES . 'layouts' . DS);   // Ej: ...app/resources/layouts/
define('VIEWS',       RESOURCES . 'views' . DS);     // Ej: ...app/resources/views/

// --- RUTAS URL (para HTML href, src) ---
// Estas se construyen a partir de la constante URL.
// URL ya apunta a la raíz de lo que es público (tu carpeta app/public/).
define('ASSETS_URL', URL . 'assets/');        // Ej: http://inventafacil.local/assets/
define('CSS_URL',    ASSETS_URL . 'css/');   // Ej: http://inventafacil.local/assets/css/
define('JS_URL',     ASSETS_URL . 'js/');     // Ej: http://inventafacil.local/assets/js/

/*
Constantes que probablemente ya no necesitas o que fueron reemplazadas:

#define('CLASSES_PATH' , ROOT . '..' . DS); // Ya no debería ser necesaria con el Autoloader bien configurado
                                         // y ROOT apuntando a la carpeta 'app'.
                                         // ROOT . '..' . DS apuntaría a TU_PROYECTO_RAIZ/,
                                         // lo cual podría ser útil para acceder a archivos fuera de 'app/'
                                         // pero no para las clases dentro de 'app/'.

# Las siguientes son reemplazadas por *_URL para mayor claridad:
#define('ASSETS' , URL . 'assets' . DS);
#define('CSS'    , ASSETS . 'css' . DS);
#define('JS'     , ASSETS . 'js' .DS);
*/

?>