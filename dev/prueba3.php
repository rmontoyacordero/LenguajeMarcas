<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$CA=0;
$nombre=0;
$CP=0;
$doc = new DOMDocument();
$inicial=microtime(true);
$str = '<?xml version="1.0" encoding="UTF-8"?>';
$str .= '<LUGARES>';
for ($i = 0; $i < 10; $i++) {
    $str .= '<LUGAR ID="' . $i . '" CIUDAD="' . $fecha . '" CIUDADCT="' . $foo . '" LATITUD="' . $bar . '" LONGITUD="' . $i . '" CA="' . $i . '" NOMBRE="' . $i . '" CP="' . $i . '">';
    $str .= '</LUGAR>';
}
$str .= '</LUGARES>';

$doc->loadXML($str);
$tiempo=microtime(true)-$inicial;
    echo number_format($tiempo,2).' segundos'.PHP_EOL;
$doc->save(RUTA . 'CiudadesAtributos.xml');