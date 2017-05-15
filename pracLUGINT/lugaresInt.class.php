<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

class lugaresInt {

    private $doc, $xpath;
    private $array;

    function __construct($data) {
        $this->doc = new DOMDocument('1.0', 'utf-8');
        if (isset($data)) {
            $this->array = json_decode($data, true);
        }
    }

    function genXML() {
        $str = '<?xml version="1.0" encoding="UTF-8"?>
                <lugares>
                    <informacionGeneral>
                        <nombreProducto>Lugares de interés</nombreProducto>
                        <version>1.0</version>
                        <grupo>S11AW</grupo>
                        <fecha>20170427</fecha>
                    </informacionGeneral>
                </lugares>';
        $this->doc->loadXML($str);
        $this->doc->save(FICHERO);
    }

    function savePlace() {
        $this->doc->load(FICHERO);
        $this->xpath = new DOMXPath($this->doc);
        $id = $this->array[0]["value"];
        $lat = $this->array[5]["value"];
        $lon = $this->array[6]["value"];
        $URL = $this->array[8]["value"];
        if ($result = $this->xpath->query('//lugar[@id="' . $id . '"]')) {
            foreach ($result as $nodos) {
                $nodos->parentNode->removeChild($nodos);
            }
        }
        $lugar = $this->doc->createElement('lugar', '');
        $lugar->setAttribute('id', $this->array[0]["value"]);
        $this->xpath->query('//lugares')->item(0)->appendChild($lugar);
        $localizacion = $this->doc->createElement('localizacion', '');
        $this->xpath->query('//lugar[@id="' . $id . '"]')->item(0)->appendChild($localizacion);
        $geo = $this->doc->createElement('geo', '');
        $geo->setAttribute('lat', $lat);
        $geo->setAttribute('lon', $lon);
        $this->xpath->query('//lugar[@id="' . $id . '"]/localizacion')->item(0)->appendChild($geo);
        for ($i = 1; $i <= 7; $i++) {
            if (!empty($this->array[$i]["value"]) && $i !== 5 && $i !== 6) {
                $nodo = $this->doc->createElement($this->array[$i]["name"], $this->array[$i]["value"]);
                $this->xpath->query('//geo[@lat="' . $lat . '" and @lon="' . $lon . '"]')->item(0)->appendChild($nodo);
            }
        }
        if (!empty($URL)) {
            $observaciones = $this->doc->createElement('observaciones');
            $this->xpath->query('//geo[@lat="' . $lat . '" and @lon="' . $lon . '"]')->item(0)->appendChild($observaciones);
            $URLLugar = $this->doc->createCDATASection($URL);
            $observaciones->appendChild($URLLugar);
        }
        $this->doc->save(FICHERO);
    }

}
