<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
$aCiudades;
$directorio = opendir(RUTA . "datas"); //ruta actual
$dom = new DOMDocument('1.0', 'utf-8');
$lugares = $dom->createElement('LUGARES');

$dom->appendChild($lugares);

/*$doc -> loadXML($str);
$xpath = new DOMXPath($doc);*/
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (!(is_dir($archivo)))//verificamos si es o no un directorio
    {
       $aCiudades = json_decode(file_get_contents(RUTA . "datas/" .$archivo),true);
foreach($aCiudades as $valor){
    $lugar = $dom->createElement('LUGAR');
    $lugares->appendChild($lugar);
    $ID = $valor['id'];
    $CP = $valor['cp'];
    $shortName = $valor['shortName'];
    $longName = $valor['longName'];
    $lugar ->setAttribute("id",$ID);
    $lugar ->setAttribute("siglas",$shortName);
        $lugar ->setAttribute("nombre",$longName);
        $lugar ->setAttribute("cp",$CP);
}
    }
}

$dom->save(RUTA . '../xml/pruebaGenerarAtributos.xml');