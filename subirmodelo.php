<?php

require __DIR__ . '/bootstrap.php';

if (!is_user_logged_in()) {
    redirect_to('iniciarsesion.php');
}

if (is_get_request()) {
    view('subirmodelo');
    die();
}
    
const ALLOWED_FILES = [
        'text/plain' => 'obj',
];

/**
 *  Messages associated with the upload error code
 */
const MESSAGES = [
    UPLOAD_ERR_OK => 'File uploaded successfully',
    UPLOAD_ERR_INI_SIZE => 'File is too big to upload',
    UPLOAD_ERR_FORM_SIZE => 'File is too big to upload',
    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
    UPLOAD_ERR_CANT_WRITE => 'File is failed to save to disk.',
    UPLOAD_ERR_EXTENSION => 'File is not allowed to upload to this server',
];

const MAX_SIZE = 10 * 1024 * 1024; // 10 MB

if (is_post_request() && isset($_FILES['modelo']) && isset($_POST['nombre'])) {

    // Validar nombre

    $fields = [
        'nombre' => 'string | required | max : 50'
    ];

    $inputs = [];
    $errors = [];

    [$inputs, $errors] = filter($_POST, $fields);

    if ($errors) {
        redirect_with_message('subirmodelo.php', 'Existen errores en el nombre del modelo', FLASH_ERROR);
    }

    $status = $_FILES['modelo']['error'];
    $filename = $_FILES['modelo']['name'];
    $tmp = $_FILES['modelo']['tmp_name'];
    $filesize = filesize($tmp);

    // Valida estado de subida
    if ($status !== UPLOAD_ERR_OK) {
        redirect_with_message('subirmodelo.php', 'Un error ocurrio al intentar subir el archivo', FLASH_ERROR);
    }

    // Valida tamano del archivo
    if ($filesize > MAX_SIZE) {
        redirect_with_message('subirmodelo.php', 'El archivo es mas grande del tamano permitido. Limite: 10MB', FLASH_ERROR);
    }

    // Valida mime
    $mime = get_mime_type($tmp);
    if (!in_array($mime, array_keys(ALLOWED_FILES))) {
        redirect_with_message('subirmodelo.php', 'El tipo de archivo no esta permitido', FLASH_ERROR);
    }

    $modeloModel = new \visor3d\model\ModeloModel();
    $modeloId = $modeloModel->guardarModelo($tmp, $inputs['nombre']);

    redirect_with_message('visualizador.php?modelo=' . $modeloId, 'El modelo fue subido exitosamente', FLASH_SUCCESS);
}
