<?php

require __DIR__ . '/../bootstrap.php';

if (!is_get_request()) {
    http_response_code(405);
    die();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    die();
}

// Sanitize
$fields = [
    'id' => 'string | required | alphanumeric | between: 3,25'
];

$inputs = [];
$errors = [];

[$inputs, $errors] = filter($_GET, $fields);

if ($errors) {
    http_response_code(400);
    die();
}

$modeloModel = new \visor3d\model\ModeloModel();
$modelos = $modeloModel->consultarModelosPorUsername($inputs['id']);

$data = [];

// Convierte de objetos a array asociativo
foreach ($modelos as $key => $value) {
    $str = $value->__GET('modelo_url');
    $url = substr($str, 0, strlen($str) - 4);

    $data[] = [
        'username' => $value->__GET('username'),
        'nombre' => $value->__GET('nombre'),
        'modelo_url' => $url,
        'thumbnail_url' => $value->__GET('thumbnail_url')
    ];
}

$response = json_encode($data);
header('Content-Type: application/json; charset=utf-8');
echo $response;
die();
