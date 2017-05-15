<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'votos.class.php';
$fecha = new DateTime();
$accion = filter_input(INPUT_POST, 'check');
$user = filter_input(INPUT_POST, 'user');
$votos = filter_input(INPUT_POST, 'votos');
$navegador =  "adios";
$idLugar = filter_input(INPUT_POST, 'iDlugar');
$tiempo ="manolo";
$ip = "hola";
$vot = new votos();
if($accion == "votacion"){
    echo $vot->givePoint($user,$votos,$navegador,$idLugar,$tiempo,$ip);
}
if($accion == "ranking"){
    echo $vot->ranking($idLugar);
}
//echo $vot->ranking("ChIJr9bPxM4ncg0RjP--4tPku58");

