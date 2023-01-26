<?php
require_once __DIR__ . '/bootstrap.php';

    // Si es el request es GET
    if (is_get_request()) {
        // Si id esta en el url, carga el perfil
        if (isset($_GET['id'])) {

            $errors = [];
            $input = [];

            $fields = [
                'id' => 'string | required | alphanumeric | between: 3,25'
            ];

            [$input, $errors] = filter($_GET, $fields);

            if ($errors) {
                // 400 Bad Request
                http_response_code(400);
                 die();
            }
            
            $perfilModel = new \visor3d\model\PerfilModel();
            $perfil = $perfilModel->consultarPerfil($input['id']);

            if (!$perfil) {
                // Si el perfil no fue encontrado
                // 404 Not Found
                http_response_code(404);
                include "404.php";
                 die();
            } else {
                // Si no, carga el perfil
                view('perfil', ['perfil' => $perfil]);
                die();
            }
        } else {
            if (!is_user_logged_in()) {
                // Si el usuario no esta logueado, y id no esta definido, redirecciona a la pagina de iniciar sesion
                redirect_to('iniciarsesion.php');
            }
            // Si no, carga el perfil
            $perfilModel = new \visor3d\model\PerfilModel();
            $perfil = $perfilModel->consultarPerfil($_SESSION['username']);
            view('perfil', ['perfil' => $perfil]);
            die();
        }
        
    } else {
        // 405 Method Not Allowed
        http_response_code(405);
        die();
    }
