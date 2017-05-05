<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$idHotel = 3; //vendrá via POST

$doc = new DOMDocument();
$doc->load(RUTA . 'datos.xml');
$xpath = new DOMXPath($doc);
foreach ($xpath->query("//oferta[@id='$idHotel']/fecha") as $dias) {
    $fecha = $dias->getAttribute("dia");
    echo $dias->nodeName . " " . $fecha . PHP_EOL;
    foreach ($dias->childNodes as $item) {
        echo $item->nodeType." ".$item->nodeName.PHP_EOL;
        if ($item->nodeType == XML_ELEMENT_NODE) {
            echo $item->nodeName . " " . $item->nodeValue . PHP_EOL;
        }
    }
}


