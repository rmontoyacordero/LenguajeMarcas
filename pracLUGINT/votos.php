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

$vot = new votos();
if($accion == "votacion"){
    echo $vot->givePoint($user,$votos,$navegador,$idLugar);
}
echo $vot->givePoint("dani","5","mozilla","ChIJSThaPwklcg0R6ljlfZcK43U","1500000000","192.168.6.35");
echo "Realizado".PHP_EOL;


