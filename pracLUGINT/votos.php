<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . '');
require_once RUTA . 'votos.class.php';


$accion = filter_input(INPUT_POST, 'check');
$user = filter_input(INPUT_POST, 'user');
$pass = md5(filter_input(INPUT_POST, 'password'));
