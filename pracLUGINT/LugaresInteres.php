<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'lugaresInt.class.php';
$accion = filter_input(INPUT_POST, 'check');
$latitud = filter_input(INPUT_POST, 'latitud');
$longitud = filter_input(INPUT_POST, 'longitud');
$cpostal = filter_input(INPUT_POST, 'cpostal');
$lug = filter_input(INPUT_POST, 'lugar');
$foto = filter_input(INPUT_POST, 'foto');
$observaciones = filter_input(INPUT_POST, 'observaciones');

$lugar = new lugaresInt();
if (!(file_exists(FICHERO))) {
    $lugar->genCabecera();
}
if ($accion == "location") {
    echo $lugar->genLugar($latitud, $longitud, $cpostal, $lug, $foto, $observaciones);
}
