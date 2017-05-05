<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'LugaresInteres.xml');

require_once RUTA . 'LugaresInteres.class.php';
$accion = filter_input(INPUT_POST, 'check');
$latitud = filter_input(INPUT_POST, 'latitud');
$longitud = filter_input(INPUT_POST, 'longitud');
$cpostal = filter_input(INPUT_POST, 'cpostal');
$lugar = filter_input(INPUT_POST, 'lugar');
$foto = filter_input(INPUT_POST, 'foto');
$observaciones = filter_input(INPUT_POST, 'observaciones');
$cr = new LugaresInteres();
if(!(file_exists(FICHERO))){
     $cr -> genXML();
 }
 if ($accion == "inicio") {
    echo $cr->genNodos($latitud,$longitud,$cpostal,$lugar,$foto,$observaciones);
}
/*$cr->genNodos(40,50,60,70,80,90);*/
