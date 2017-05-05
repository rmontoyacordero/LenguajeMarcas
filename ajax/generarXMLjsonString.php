<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
$aCiudades;
$directorio = opendir(RUTA . "datas"); //ruta actual
$dom = new DOMDocument('1.0', 'utf-8');
$str = '<LUGARES>';

/*$doc -> loadXML($str);
$xpath = new DOMXPath($doc);*/
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (!(is_dir($archivo)))//verificamos si es o no un directorio
    {
       $aCiudades = json_decode(file_get_contents(RUTA . "datas/" .$archivo),true);
foreach($aCiudades as $valor){
    

    $ID = $valor['id'];
    $CP = $valor['cp'];
    $shortName = $valor['shortName'];
    $longName = $valor['longName'];
    $str .= '<LUGAR id="'.$ID.'" cp="'.$CP.'" siglas="'.$shortName.'" nombre="'.$longName.'">';
        $str .= '</LUGAR>';
}
    }
}
$str .= '</LUGARES>';
$dom->loadXML($str);
$dom->save(RUTA . '../xml/pruebaGenerarString.xml');