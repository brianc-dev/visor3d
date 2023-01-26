<?php

require __DIR__ . '/../bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['descripcion'])) {
    http_response_code(400);
    die();
}

if (!is_user_logged_in()) {
    http_response_code(401);
    die();
}

$fields = [
    'descripcion' => 'string | max : 60'
];

$inputs = [];
$errors = [];

[$inputs, $errors] = filter($data, $fields);

if ($errors) {
    http_response_code(400);
    $data = [
        'message' => $errors,
        'code' => 1,
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}

$perfilModel = new \visor3d\model\PerfilModel();
$result = $perfilModel->modificarDescripcion($inputs['descripcion']);

if (!$result) {
    http_response_code(500);
    die();
}

$data = [
    'descripcion' => $inputs['descripcion'],
    'message' => 'Descripcion cambiada exitosamente',
    'code' => 0
];

header('Content-Type: application/json');
echo json_encode($data);