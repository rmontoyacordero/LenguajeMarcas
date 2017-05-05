<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$doc = new DOMDocument();
$doc->load(RUTA . 'demo.xml');
$xpath = new DOMXPath($doc);
foreach ($xpath->query("//fecha") as $nodo) {
    echo $nodo->nodeName . PHP_EOL;
    $nodo->setAttribute("humedad", rand(60, 100));
    $nodoNuevo = $doc->createElement('humedad');
    $nodo->appendChild($nodoNuevo);
    $nodoNuevo->appendChild($doc->createTextNode(rand(60, 100)));
}
$doc->save(RUTA . 'demo.xml');
