<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'votos.class.php';
$fecha = new DateTime();
$accion = filter_input(INPUT_POST, 'check');
$user = filter_input(INPUT_POST, 'user');
$votos = filter_input(INPUT_POST, 'votos');
$navegador =  $_SERVER['HTTP_USER_AGENT'];
$idLugar = filter_input(INPUT_POST, 'iDlugar');
$tiempo =$fecha->getTimestamp();
$ip = $_SERVER['HTTP_CLIENT_IP'];

$vot = new votos();
if($accion == "votacion"){
    echo $vot->givePoint($user,$votos,$navegador,$idLugar,$tiempo,$ip);
}
if($accion == "ranking"){
    echo $vot->ranking($idLugar);
}

