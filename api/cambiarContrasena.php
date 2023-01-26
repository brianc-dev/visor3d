<?php
require __DIR__ . '/../bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (!(isset($_POST['contrasenaActual']) && isset($_POST['contrasenaNueva']) && isset($_POST['contrasenaConfirmacion']))) {
    http_response_code(400);
    die();
}

if (!is_user_logged_in()) {
    http_response_code(401);
    die();
}

$fields = [
    'contrasenaActual' => 'string | require | secure',
    'contrasenaNueva' => 'string | require | secure',
    'contrasenaConfirmacion' => 'string | same: contrasenaNueva'
];

$inputs = [];
$errors = [];

[$inputs, $errors] = filter($_POST, $fields);

if ($errors) {
    http_response_code(400);
    die();
}

$usuarioModel = new \visor3d\model\UsuarioModel();

try {
    $usuarioModel->cambiarContrasena($inputs['contrasenaActual'], $inputs['contrasenaNueva'], $inputs['contrasenaConfirmacion']);
} catch (\Exception $e) {
    switch ($e->getCode()) {
        case 1:
        case 2:
            $data = [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
            
            header('Content-Type: application/json');
            echo json_encode($data);
            die();
            break;
        default:
            die($e->getMessage());
            break;
    }
}

$data = [
    'message' => 'Contrasena cambiada exitosamente',
    'code' => 0
];

header('Content-Type: application/json');
echo json_encode($data);