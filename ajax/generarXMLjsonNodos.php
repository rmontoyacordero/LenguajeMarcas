<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
$aCiudades;
$directorio = opendir(RUTA . "datas"); //ruta actual
$dom = new DOMDocument('1.0', 'utf-8');
$lugares = $dom->createElement('LUGARES');

$dom->appendChild($lugares);

while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (!(is_dir($archivo)))//verificamos si es o no un directorio
    {
       $aCiudades = json_decode(file_get_contents(RUTA . "datas/" .$archivo),true);
foreach($aCiudades as $valor){
    /*while ($prac = current($value)) {
        echo key($value).'<br />';
    next($value);*/
    $lugar = $dom->createElement('LUGAR');
    $lugares->appendChild($lugar);
    $ID = $valor['id'];
    $CP = $valor['cp'];
    $shortName = $valor['shortName'];
    $longName = $valor['longName'];
    $nodoNuevo1 = $dom->createElement("id",$ID);
    $nodoNuevo2 = $dom->createElement("siglas",$shortName);
        $nodoNuevo3 = $dom->createElement("nombre",$longName);
        $nodoNuevo4 = $dom->createElement("cp",$CP);
        $lugar->appendChild($nodoNuevo1);
        $lugar->appendChild($nodoNuevo2);
        $lugar->appendChild($nodoNuevo3);
        $lugar->appendChild($nodoNuevo4);
}
    }
}

$dom->save(RUTA . '../xml/pruebaGenerar.xml');