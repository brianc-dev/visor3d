<?php

/**
 * Encuentra un usuario por su username
 * @param string $username username
 * @return \visor3d\model\Usuario el usuario
 */
function find_user_by_username(string $username) {

    $usuarioModel = new \visor3d\model\UsuarioModel();
    return $usuarioModel->recuperarUsuario($username);
}

/**
 * Encuentra un perfil por su username
 * @param string $username username
 * @return \visor3d\model\Perfil perfil o null si no fue encontrado
 */
function find_profile_by_username(string $username) {

    $perfilModel = new \visor3d\model\PerfilModel();
    return $perfilModel->consultarPerfil($username);
}

function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}

function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}

function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    // if user found, check the password
    if ($user && password_verify($password, $user->__GET('contrasena'))) {

        // prevent session fixation attack
        session_regenerate_id();

        // set username in the session
        $_SESSION['username'] = $user->__GET('username');
        $_SESSION['user_id']  = hash('ripemd256', $user->__GET('username'));

        return true;
    }

    return false;
}

function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id']);
        session_destroy();
        redirect_to('iniciarsesion.php');
    }
}

function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}
