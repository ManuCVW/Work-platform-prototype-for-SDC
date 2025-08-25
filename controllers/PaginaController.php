<?php

namespace Controllers;

use MVC\Router;

class PaginaController {
    public static function landing(Router $router) {
        // Render a la vista 
        $router->render('../..../../landing', [
            "titulo" => "Inicio"
        ]);
    }
    public static function contacto(Router $router) {
        // Render a la vista 
        $router->render('auth/contacto', [
            "titulo" => "Contacto"
        ]);
    }
}