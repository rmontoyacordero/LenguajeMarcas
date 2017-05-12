<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'lugaresInt.class.php';
$data = filter_input(INPUT_POST, 'data');
$lugInt = new lugaresInt($data);

if (!file_exists(FICHERO)) {
    $lugInt->genXML();
}
if (isset($data)) {
    $lugInt->savePlace();
}