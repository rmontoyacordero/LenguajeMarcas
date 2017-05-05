<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
$aCiudad = json_decode($_POST['aCiudad']);
/*$doc = new DOMDocument();
$doc->load(RUTA . '../xml/CIUDADESSPCORTAS.xml');
$xpath = new DOMXPath($doc);
$inicial=microtime(true);
foreach ($xpath->query("//LUGAR") as $ciudades) {
/*foreach($aCiudad[0] as $codigo){
    if($codigo== $ciudades->getAttribute("ID")){
       $ciudades->setAttribute("CA",$aCiudad[0][$codigo]->shortName);
        $ciudades->setAttribute("nombre",$aCiudad[0][$codigo]->longName);
        $ciudades->setAttribute("CP",$aCiudad[0][$codigo]->cp);
    }
}
   
    
    }*/
   // $tiempo=microtime(true)-$inicial;
   // echo number_format($tiempo,2).' segundos'.PHP_EOL;
    //$doc->save(RUTA . '../xml/ciudadesCreateAtributos.xml');
    