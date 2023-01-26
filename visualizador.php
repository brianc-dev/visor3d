<?php

require __DIR__ . "/bootstrap.php";

if (!is_get_request()) {
    http_response_code(405);
    die();
}

if (!isset($_GET['modelo'])) {
    http_response_code(400);
    die();
}

$errors = [];
$inputs = [];

$fields = [
    'modelo' => 'string | required | alphanumeric'
];

[$inputs, $errors] = filter($_GET, $fields);

if ($errors) {
    http_response_code(400);
    die();
}

// Comprobar si modelo existe
$modeloModel = new \visor3d\model\ModeloModel();
$modelo = $modeloModel->consultarModeloPorId($inputs['modelo']);

if (!$modelo) {
    http_response_code(404);
    view('404');
    die();
}

view('visualizador', [
    'modelo' => $modelo->__GET('modelo_url'),
    'descripcion' => $modelo->__GET('nombre'),
    'username' => $modelo->__GET('username'),
    'thumbnail' => $modelo->__GET('thumbnail_url')
]);
