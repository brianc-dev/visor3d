<?php

require __DIR__ . '/bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

$inputs = [];
$errors = [];

[$inputs, $errors] = filter($_POST, [
    'username' => 'string | required',
    'contrasena' => 'string | required'
]);

if ($errors) {
    create_flash_message('error', 'Existen errores en la informacion ingresada', FLASH_ERROR);
    redirect_with('iniciarsesion.php', ['errors' => $errors, 'inputs' => $inputs]);
}

// if login fails
if (!login($inputs['username'], $inputs['contrasena'])) {

    create_flash_message('login', 'Usuario o contrasena incorrecta', FLASH_ERROR);

    redirect_with('iniciarsesion.php', [
        'errors' => $errors,
        'inputs' => $inputs
    ]);
}
// login successfully
redirect_to('perfil.php');