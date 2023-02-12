<?php
// Este script se encarga de manejar la solicitud de cambio de image de perfil
require __DIR__ . '/../bootstrap.php';

// Si no es POST, ignora solicitud
if (!is_post_request()) {
    http_response_code(405);
    die();
}

// Si no se ha definido la variable, ignora solicitud
if (!isset($_FILES['profilePhoto'])) {
    http_response_code(400);
    die();
}

// Si el usuario no esta logueado, desautoriza
if (!is_user_logged_in()) {
    http_response_code(401);
    die();
}

// Valida archivo
const ALLOWED_FILES = [
    'image/png' => 'png',
    'image/jpg' => 'jpg',
    'image/jpeg' => 'jpeg',
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

const MAX_SIZE = 1 * 1024 * 1024; // 1 MB

$tmp = $_FILES['profilePhoto']['tmp_name'];

$status = $_FILES['profilePhoto']['error'];
if ($status !== UPLOAD_ERR_OK) {
    http_response_code(500);
    die();
}

$size = filesize($tmp);
if ($size > MAX_SIZE) {
    http_response_code(413);
    die();
}

$mime = get_mime_type($tmp);

if (!in_array($mime, array_keys(ALLOWED_FILES))) {
    http_response_code(415);
    die();
}

// Procesar imagen
list($width, $height) = getimagesize($tmp);

$image;

switch ($mime) {
    case 'image/png':
        $image = imagecreatefrompng($tmp);
        break;
    case 'image/jpg':
    case 'image/jpeg':
        $image = imagecreatefromjpeg($tmp);
        break;
    default:
        break;
}

if ($width > $height) {
    $y = 0;
    $x = ($width - $height) / 2;
    $smallestSide = $height;
} else {
    $x = 0;
    $y = ($height - $width) / 2;
    $smallestSide = $width;
}

$imageSize = 400;
$processedImage = imagecreatetruecolor($imageSize, $imageSize);
imagecopyresampled($processedImage, $image, 0, 0, $x, $y, $imageSize, $imageSize, $smallestSide, $smallestSide);

$result = imagepng($processedImage, $tmp);

if (!$result) {
    http_response_code(500);
    die();
}

$perfilModel = new \visor3d\model\PerfilModel();
$pictureUrl = $perfilModel->guardarFotoDePerfil($tmp);

$data = [
    'url' => $pictureUrl
];

$res = json_encode($data);
header('Content-Type: application/json; charset=utf-8');
echo $res;
die();