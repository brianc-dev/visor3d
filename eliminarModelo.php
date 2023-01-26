<?php

require __DIR__ . '/bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (!is_user_logged_in()) {
    http_response_code(401);
    die();
}

$fields = [
    'modelo' => 'url | required | between: 29,29'
];

[$input, $errors] = filter($_POST, $fields);

if ($errors) {
    http_response_code(400);
    die();
}

$modeloModel = new \visor3d\model\ModeloModel();
$result = $modeloModel->eliminarModelo($input['modelo']);

if (!$result) {
    http_response_code(403);
    die();
}

redirect_with_message('perfil.php', 'Modelo eliminado exitosamente');