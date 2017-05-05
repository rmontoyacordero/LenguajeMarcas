<?php
ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
$doc = new DOMDocument();
$doc->load(RUTA . '../xml/ofertasHoy.xml');
$xpath = new DOMXPath($doc);
$hoteles;
$i=0;
$fechas=array();
foreach ($xpath->query("//oferta") as $oferta) {
    $hoteles[$i]["id"] = $oferta->getAttribute("id");
    $hoteles[$i]["nombre"] = $oferta->getAttribute("nombre");
    $cont =0;
     foreach ($oferta->childNodes as $item) {
        if ($item->nodeType == XML_ELEMENT_NODE) {
            $hoteles[$i]["Ofertas"][$cont] =array("fecha"=>$item->getAttribute("dia"),$item->childNodes[1]->nodeName => $item->childNodes[1]->nodeValue,$item->childNodes[3]->nodeName => $item->childNodes[3]->nodeValue);
            $cont++;
                array_push($fechas,$item->getAttribute("dia"));
               
          }
     }
     $i++;
}
$fechamax=max($fechas);
$fechamin=min($fechas);
echo json_encode(array("hoteles" => $hoteles,"fechamax" => $fechamax,"fechamin" => $fechamin));
