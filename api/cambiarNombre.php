<?php

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (!isset($_POST['newNombre'])) {
    http_response_code(400);
    die();
}

$fields = [
    'newNombre' => 'string | required | between: 2, 35'
];

$messages = [
    'newNombre' => [
        'required' => 'No puede estar vacio',
        'between' => 'Debe tener entre 2 y 35 caracteres'
    ]
];

[$inputs, $errors] = filter($_POST, $fields, $messages);

if ($errors) {
    http_response_code(400);
    $data = [
        'message' => 'Error al intentar cambiar el nombre',
        'code' => 1,
        'errors' => $errors
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}

$perfilModel = new \visor3d\model\PerfilModel();
$perfilModel->modificarNombre($inputs['newNombre']);

$data = [
    'message' => 'Nombre cambiado exitosamente',
    'code' => 0,
    'nombre' => $inputs['newNombre']
];

header('Content-Type: application/json');
echo json_encode($data);