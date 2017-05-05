<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$CA=0;
$nombre=0;
$CP=0;
$doc = new DOMDocument();
$doc->load(RUTA . '../xml/CIUDADESSPCORTAS.xml');
$xpath = new DOMXPath($doc);
$inicial=microtime(true);
foreach ($xpath->query("//LUGAR") as $ciudades) {
    $nodoNuevo = $doc->createElement('CA',$CA);
    $nodoNuevo2 = $doc->createElement('nombre',$nombre);
    $nodoNuevo3 = $doc->createElement('CP',$CP);
    $ciudades->appendChild($nodoNuevo);
    $ciudades->appendChild($nodoNuevo2);
    $ciudades->appendChild($nodoNuevo3);
    }
    $tiempo=microtime(true)-$inicial;
    echo number_format($tiempo,2).' segundos'.PHP_EOL;
    $doc->save(RUTA . '../xml/ciudadesCreateNodos.xml');