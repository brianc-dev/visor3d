<?php

require __DIR__ . '/bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (is_user_logged_in()) {
    http_response_code(200);
    die();
}

$errors = [];
$inputs = [];

// Campos para sanitizar y validar
$fields = [
    'username' => 'required | alphanumeric | between: 3,25 | unique: usuario,username',
    'email' => 'required | email | unique: usuario,email',
    'contrasena' => 'required | secure',
    'contrasena2' => 'required | same:contrasena'
];

// Sanitizacion y validacion
[$inputs, $errors] = filter($_POST, $fields);

if ($errors) {
    create_flash_message('signup', 'Por favor, corrije los errores', FLASH_ERROR);
    redirect_with('registrarse.php', [
        'inputs' => $inputs,
        'errors' => $errors
    ]);
}

// Registrar usuario

$usuario = new \visor3d\model\Usuario();
$usuario->__SET('username', $inputs['username']);
$usuario->__SET('email', $inputs['email']);
$usuario->__SET('contrasena', $inputs['contrasena']);

$usuarioModel = new \visor3d\model\UsuarioModel();

$usuarioModel->guardarUsuario($usuario);

// Crear perfil 

$perfil = new \visor3d\model\Perfil();
$perfil->__SET('username', $inputs['username']);

$perfilModel = new \visor3d\model\PerfilModel();
$perfilModel->createPerfil($perfil);

redirect_with_message(
    'iniciarsesion.php',
    'La cuenta ha sido creada exitosamente'
);