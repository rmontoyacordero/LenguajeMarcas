<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARAT);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'LugaresInteres.xml');

class LugaresInteres {
private $dom,$xpath;

function __construct() {
        $this->dom = new DOMDocument('1.0', 'utf-8');

        
        
    }
function genXML(){
    $this->dom = new DOMDocument();
    $str='<Lugares>';
    $str.='<informacionGeneral>';
    $str.='<nombreProducto>Lugares de inters</nombreProducto>';
    $str.='<version>1.0</version>';
    $str.='<grupo>S11AW</grupo>';
    $str.='<fecha>20170427</fecha>';
    $str.='</informacionGeneral></Lugares>';
    $this->dom->loadXML($str);
    $this->dom->save(RUTA . 'LugaresInteres.xml');
}
function genNodos($latitud,$longitud,$cpostal,$lugartext,$fototext,$obsertext){
    $this->dom = new DOMDocument();
    $this->dom->load(FICHERO);
    $this->xpath = new DOMXPath( $this->dom);
    foreach ( $this->xpath->query("//Lugares") as $prueba){
            $lugar =  $this->dom->createElement('lugar');
            $lugar->setAttribute("id", uniqid());
            $prueba->appendChild($lugar);
            $localizacion =  $this->dom->createElement('localizacion');
            $lugar->appendChild($localizacion);
            $geo= $this->dom->createElement('geo');
            $geo->setAttribute("lat",$latitud);
            $geo->setAttribute("lon",$longitud);
            $localizacion->appendChild($geo);
            $cpostal= $this->dom->createElement('cpostal',$cpostal);
            $lugarpos= $this->dom->createElement('lugar',$lugartext);
            $foto= $this->dom->createElement('foto',$fototext);
            $observaciones= $this->dom->createElement('observaciones',$obsertext);
            $geo->appendChild($cpostal);
            $geo->appendChild($lugarpos);
            $geo->appendChild($foto);
            $geo->appendChild($observaciones);
            $this->dom->save(FICHERO);
            /*$str='<lugar id='uniqid()'><localizacion><geo lat='$latitud' lon='$longitud'><cpostal>'$cpostal'</cpostal><lugar>'$lugartext'</lugar><foto>'$fototext'</foto><observaciones>'$obsertext'</observaciones></geo><localizacion></lugar>';
            $this->dom->loadXML($str);*/

        }
        return json_encode(array("status"=>"ok", "mensaje"=>"Alta realizada"));
    }    
    
                    

}

