<?php

require __DIR__ . '/bootstrap.php';

 if (!is_get_request()) {
    http_response_code(405);
    die();
}

view('privacidad');