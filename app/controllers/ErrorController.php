<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/controllers/ErrorController.php
    namespace app\controllers;

    // Asumimos que Controller.php ya está definido y funcionando
    // y que el Autoloader puede cargarlo.

    class ErrorController extends Controller { // Hereda de tu controlador base

        public function __construct(){
            parent::__construct(); // Llama al constructor del padre si es necesario
        }

        public function error404(){
            // En lugar de solo un echo, podrías renderizar una vista de error más bonita
            // header("HTTP/1.0 404 Not Found");
            // View::render('errors/404', ['title' => 'Página no encontrada']);
            // Por ahora, el echo está bien para depurar:
            echo "Error 404: Página no encontrada (desde ErrorController)";
            // No es necesario die; o exit; aquí si el Router ya lo hace después de llamar a esto.
            // Pero tu Router.php SÍ tiene un exit; después de llamar a callErrorController,
            // así que el die; original aquí era redundante.
        }

        public function errorMNF(){ // MNF = Method Not Found
            // header("HTTP/1.0 404 Not Found"); // O un error 500 si prefieres
            // View::render('errors/method_not_found', ['title' => 'Método no encontrado']);
            echo "Error: El método solicitado no fue encontrado en el controlador (desde ErrorController)";
        }
    }
?>