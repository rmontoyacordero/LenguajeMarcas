<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);


$aCiudad = json_decode($_POST['aCiudad']);
echo json_encode($aCiudad).PHP_EOL;
$doc = new DOMDocument();
$doc->load(RUTA . '../xml/CIUDADESSPCORTAS.xml');
$xpath = new DOMXPath($doc);
$inicial=microtime(true);
foreach ($xpath->query("//LUGAR") as $ciudades){
foreach($aCiudad as $valor){
    $ID = $valor->id;
    $CP = $valor->cp;
    $shortName = $valor->shortName;
    $longName = $valor->longName;
    if($ID==$ciudades->getAttribute('ID')){
        $ciudades->setAttribute("CA",$shortName);
        $ciudades->setAttribute("nombre",$longName);
        $ciudades->setAttribute("CP",$CP);
    }
}
}
$tiempo=microtime(true)-$inicial;
$doc->save(RUTA . '../xml/ciudadesCreateAtributos.xml');







