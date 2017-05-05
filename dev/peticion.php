<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$foo = $_POST['foo'];
$bar = $_POST['bar'];

$doc = new DOMDocument();
$hoy = new DateTime('2017-03-16');
$str = '<?xml version="1.0" encoding="UTF-8"?>';
$str .= '<fechas>';
for ($i = 0; $i < 10; $i++) {
    $fecha = $hoy->format("d/m/Y");
    $str .= '<fecha id="' . $i . '" value="' . $fecha . '" foo="' . $foo . '" bar="' . $bar . '">';
    $str .= '<temp>' . rand(-10, 47) . '</temp>';
    $str .= '</fecha>';
    $hoy->add(new DateInterval('P1D'));
}
$str .= '</fechas>';

$doc->loadXML($str);
$doc->save(RUTA . 'demo.xml');

echo json_encode(array("foo" => $foo, "bar" => $bar, "xml" => $str));
