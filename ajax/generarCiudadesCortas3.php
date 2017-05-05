<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);


$aCiudad = json_decode($_POST['aCiudad']);
echo json_encode($aCiudad).PHP_EOL;
$doc = new DOMDocument();
$doc->load(RUTA . '../xml/CIUDADESSPCORTAS.xml');
$xpath = new DOMXPath($doc);
$inicial=microtime(true);
$str = '<?xml version="1.0" encoding="UTF-8"?>';
$str .= '<LUGARES>';
foreach ($xpath->query("//LUGAR") as $ciudades){
foreach($aCiudad as $valor){
    $ID = $valor->id;
    $CP = $valor->cp;
    $shortName = $valor->shortName;
    $longName = $valor->longName;
    if($ID==$ciudades->getAttribute('ID')){ 
        $str .= '<LUGAR ID="' . $ID . '" CIUDAD="' . $ciudades->getAttribute('CIUDAD') . '" CIUDADCT="' . $ciudades->getAttribute('CIUDADCT') . '" LATITUD="' . $ciudades->getAttribute('LATITUD') . '" LONGITUD="' . $ciudades->getAttribute('LONGITUD') . '" CA="' . $shortName . '" NOMBRE="' . $longName . '" CP="' . $CP . '">';
    $str .= '</LUGAR>';
    }
}
}
$str .= '</LUGARES>';
$doc->loadXML($str);
$tiempo=microtime(true)-$inicial;
$doc->save(RUTA . '../xml/ciudadesCreateAtributos3.xml');