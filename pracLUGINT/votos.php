<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

require_once RUTA . 'votos.class.php';

$accion = filter_input(INPUT_POST, 'check');
$user = filter_input(INPUT_POST, 'user');
$votos = filter_input(INPUT_POST, 'votos');
$navegador = filter_input(INPUT_POST, 'navegador');
$idLugar = filter_input(INPUT_POST, 'iDlugar');
$tiempo = filter_input(INPUT_POST, 'tiempo');
$ip =filter_input(INPUT_POST, 'ip');

$vot = new votos();
if($accion == "votacion"){
    echo $vot->givePoint($user,$votos,$navegador,$idLugar,$tiempo,$ip);
    echo $vot->ranking();
    echo $vot->crearListado();
}
echo $vot->givePoint("dani","3","Mozilla","ChIJSThaPwklcg0R6ljlfZcK43U","15000","80.125.0.1");

