<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$idHotel = 1; //vendrá via POST
$fecha = '2017-03-21'; //vendrá via POST
$reserva = 3;

$doc = new DOMDocument();
$doc->load(RUTA . 'datos.xml');
$xpath = new DOMXPath($doc);

$guardar = false;

if ($result = $xpath->query("//oferta[@id='$idHotel']/fecha[@dia='$fecha']/plazas")) {
    if ($reserva > (int) $result->item(0)->nodeValue) {
        echo " imposible reservar ..." . PHP_EOL;
    } else {
        $result->item(0)->nodeValue = (int) $result->item(0)->nodeValue - $reserva;
        echo " Reservar efectuada ... de $reserva plazas" . PHP_EOL;
        $guardar = true;
    }
}
if ($guardar) {
    $doc->save(RUTA . 'datos.xml');
}


