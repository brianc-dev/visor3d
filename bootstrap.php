<?php
// Este archivo carga las dependencias, librerias y clases que usa la aplicacion.

// Cambia el nombre default por 'id'
session_name("id");
session_start();

// Include classes
$_CONFIG = parse_ini_file('.config');

require __DIR__ . '/model/Usuario.php';
require __DIR__ . '/model/Perfil.php';
require __DIR__ . '/model/Modelo.php';

require __DIR__ . '/libs/helpers.php';
require __DIR__ . '/libs/flash.php';
require __DIR__ . '/libs/sanitization.php';
require __DIR__ . '/libs/validacion.php';
require __DIR__ . '/libs/filter.php';
require __DIR__ . '/auth.php';
