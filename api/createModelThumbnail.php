<?php

require __DIR__ . '/../bootstrap.php';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (!is_user_logged_in()) {
    http_response_code(401);
    die();
}

if (!(isset($_POST['modeloUrl']) && isset($_POST['imageUrl']))) {
    http_response_code(400);
    die();
}

$fields = [
    'modeloUrl' => 'url | required',
    'imageUrl' => 'string | required'
];

[$input, $errors] = filter($_POST, $fields);

if ($errors) {
    http_response_code(400);
    die(1);
}

$modeloModel = new \visor3d\model\ModeloModel();

try {
    $modeloModel->createThumbnail($input['modeloUrl'], $input['imageUrl']);
} catch (Exception $e) {
    http_response_code(500);
    die($e->getMessage());
}

http_response_code(201);