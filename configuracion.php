<?php

require __DIR__ . '/bootstrap.php';

if (!is_get_request()) {
    http_response_code(405);
    die();
}

if (!is_user_logged_in()) {
    redirect_to('iniciarsesion.php');
}

$perfilModel = new visor3d\model\PerfilModel();
$perfil = $perfilModel->consultarPerfil($_SESSION['username']);

view('configuracion', ['nombre' => $perfil->__GET('nombre'), 'descripcion' => $perfil->__GET('descripcion')]);