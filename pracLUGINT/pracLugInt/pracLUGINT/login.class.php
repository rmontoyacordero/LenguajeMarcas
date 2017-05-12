<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'usuarios.xml');

class login{
    private $dom,$xpath,$respuesta;

    function __construct() {
        $this->dom = new DOMDocument('1.0', 'utf-8');
    }
    function loginUser($user,$pass){
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $result = false;
        $this->respuesta=array("status"=>"ko", "mensaje"=>"No se encuentra usuario","pass"=>$result->nodeValue);
        foreach ($this->xpath->query("//usuario[@login='".$user."'][@password='".$pass."']") as $key) {
            $this->respuesta=array("status"=>"ok", "mensaje"=>"Usuario en sistema");
        }
        return json_encode($this->respuesta);
    }
}