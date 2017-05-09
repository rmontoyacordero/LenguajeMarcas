<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

class lugaresInt {

    private $dom, $xpath;

    function __construct() {
        $this->dom = new DOMDocument('1.0', 'utf-8');
    }

    function genLugar($latitud,$longitud,$cpostal,$lugartext,$fototext,$obsertext){
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
        }
        return json_encode(array("status"=>"ok", "mensaje"=>"Alta realizada"));
    }   

    public function genCabecera() {
        $str = '<lugares>';
        $str .=     '<informacionGeneral>';
        $str .=         '<nombreProducto>Lugares de interés</nombreProducto>';
        $str .=          '<grupo>S11AW</grupo>';
        $str .=          '<fecha>20170427</fecha>';
        $str .=     '</informacionGeneral>';
        $str .= '</lugares>';
        $this->dom->loadXML($str);
        $this->dom->save(FICHERO);
    }

}
