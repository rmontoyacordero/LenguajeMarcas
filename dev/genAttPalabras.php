<?php


ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$doc = new DOMDocument();
$doc->load(RUTA . '../xml/PALABRAS.xml');
$xpath = new DOMXPath($doc);
$inicial=microtime(true);
foreach ($xpath->query("//ROW[@ID]") as $palabras) {
    $valor = $palabras->getAttribute("PALABRA");
    $soni = soundex($valor);
    $meta= metaphone($valor);
    $palabras->setAttribute("soundex",$soni);
    $palabras->setAttribute("metaphone",$meta);    
}
$tiempo=microtime(true)-$inicial;
echo number_format($tiempo,2).' segundos'.PHP_EOL;
    $doc->save(RUTA . '../xml/PalabrasAtt.xml');

