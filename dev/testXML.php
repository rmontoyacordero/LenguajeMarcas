<?php

ini_set('error_reporting', E_ALL);

define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

echo RUTA . PHP_EOL;

$doc = new DOMDocument();
$hoy = new DateTime('2017-03-16');
$str = '<?xml version="1.0" encoding="UTF-8"?>';
$str .= '<fechas>';
for ($i = 0; $i < 1000; $i++) {
    $fecha = $hoy->format("d/m/Y");
    $str .= '<fecha id="' . $i . '" value="' . $fecha . '">';
    $str .= '<temp>' . rand(-10, 47) . '</temp>';
    $str .= '</fecha>';
    $hoy->add(new DateInterval('P1D'));
}
$str .= '</fechas>';

$doc->loadXML($str);
$doc->save(RUTA . 'demo.xml');


