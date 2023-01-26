<?php

/**
 * Redirect to another URL
 *
 * @param string $url el url. Ejemplo: 'login.php'
 * @return void
 */
function redirect_to(string $url): void {
    header('Location:' . $url);
    die();
}

/**
 * Redirect to a URL with data stored in the items array
 * @param string $url el url. Ejemplo: 'login.php'
 * @param array $items la data a almacenar en $_SESSION
 */
function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }
    
    redirect_to($url);
}

/**
 * Redirect to a URL with a flash message
 * Use flash() or display_all_flash_messages() to display message
 * @param string $url el url. Ejemplo: 'login.php'
 * @param string $message el mensaje. Ejemplo: 'Hola'
 * @param string $type el tipo de flash. Default = FLASH_SUCCESS
 */
function redirect_with_message(string $url, string $message, string $type = FLASH_SUCCESS)
{
    flash('flash_' . uniqid(), $message, $type);
    redirect_to($url);
}

/**
 * Display a view
 *
 * @param string $filename
 * @param array $data
 * @return void
 */
function view(string $filename, array $data = []): void {
        // create variables from the associative array
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        
        require_once __DIR__ . '/../include/' . $filename . '.php';
}

/**
 * Return true if the request method is POST
 *
 * @return boolean
 */
function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

/**
 * Return true if the request method is GET
 *
 * @return boolean
 */
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

/**
 * Return the error class if error is found in the array $errors
 *
 * @param array $errors
 * @param string $field
 * @return string
 */
function error_class(array $errors, string $field): string
{
    return isset($errors[$field]) ? 'error' : '';
}

/**
 * Get data from the $_SESSION
 * Example: [$inputs, $errors] = session_flash('inputs', 'errors');
 * Flash data specified by $keys from the $_SESSION
 * @param ...$keys Ejemplo: 'inputs', 'errors',...
 * @return array
 */
function session_flash(...$keys): array
{
    $data = [];
    foreach ($keys as $key) {
        if (isset($_SESSION[$key])) {
            $data[] = $_SESSION[$key];
            unset($_SESSION[$key]);
        } else {
            $data[] = [];
        }
    }
    return $data;
}

/**
 * Genera un id de 25 caracteres.
 *
 * @return string Un cadena de 25 caracteres hexadecimales
 **/
function generateFileId()
{   
    return mb_strimwidth(bin2hex(random_bytes(21)), 0, 25);
}

/**
 * Return a mime type of file or false if an error occurred
 *
 * @param string $filename
 * @return string | bool
 */
function get_mime_type(string $filename)
{
    $info = finfo_open(FILEINFO_MIME_TYPE);
    if (!$info) {
        return false;
    }

    $mime_type = finfo_file($info, $filename);
    finfo_close($info);

    return $mime_type;
}

/**
 * Return a human-readable file size
 *
 * @param int $bytes
 * @param int $decimals
 * @return string
 */
function format_filesize(int $bytes, int $decimals = 2): string
{
    $units = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $units[(int)$factor];
}

/**
 * Genera un id random de 25 caracteres
 */
function randomId(): string {
    return mb_strimwidth(preg_replace('/[^A-Za-z0-9\-]/', bin2hex(random_bytes(1)), base64_encode(random_bytes(36))), 0 , 25);
}
