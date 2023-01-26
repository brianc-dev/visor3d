<?php

require __DIR__ . '/../bootstrap.php';
const TARGET_DIR = __DIR__ .  '/../uploads/tmp_modelos/';

if (!is_post_request()) {
    http_response_code(405);
    die();
}

if (!isset($_FILES['modelFile'])) {
    http_response_code(400);
    die();
}

// Valida archivo
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


$tmp = $_FILES['modelFile']['tmp_name'];

$status = $_FILES['modelFile']['error'];
if ($status !== UPLOAD_ERR_OK) {
    http_response_code(500);
    die();
}

const MAX_SIZE = 10 * 1024 * 1024; // 10 MB
$size = filesize($tmp);
if ($size > MAX_SIZE) {
    http_response_code(413);
    die();
}

$mime = get_mime_type($tmp);
if (!in_array($mime, array_keys(ALLOWED_FILES))) {
    http_response_code(400);
    die();
}

do {
    $tmp_filename = bin2hex(random_bytes(25)) . '.obj';
} while (file_exists(TARGET_DIR . $tmp_filename));

$result = move_uploaded_file($tmp, TARGET_DIR . $tmp_filename);

if (!$result) {
    http_response_code(500);
    die();
}

$data = ['tmpModelUrl' => $tmp_filename];
header('Content-Type: application/json');
echo json_encode($data);