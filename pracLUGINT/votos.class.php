<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'lugaresInt.xml');

class votos{
    private $dom,$xpath,$respuesta;

    function __construct() {
        $this->dom = new DOMDocument('1.0', 'utf-8');
    }
    function givePoint($user,$votos,$navegador,$idLugar,$tiempo,$ip){
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        foreach ($this->xpath->query("//lugar[@id='".$idLugar."']") as $nodo) {
            $node_voto = $this->dom->createElement('JAVIER');
            $nodo->appendChild($node_voto);
            $node_voto->setAttribute("user",$user);
            $node_voto->setAttribute("t",$tiempo);
            $node_item =$this->dom->createElement('item',$votos);
            $node_ip=$this->dom->createElement('item',$ip);
            $node_userAgent=$this->dom->createElement('userAgent',$navegador);
            $node_voto->appendChild($node_item);
            $node_voto->appendChild($node_ip);
            $node_voto->appendChild($node_userAgent);
            $this->dom->save(FICHERO);
        }              
    }
    function ranking(){
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $contador=0;
        foreach ($this->xpath->query("//lugar") as $nodo){
            $id=$nodo->getAttribute('id');
            $puntuacion=0;
            $votos = $this->xpath->query("//lugar[@id='".$id."']/voto/item");
            $num_votaciones=$votos->length;
            foreach($votos as $nodoItem){
                $puntuacion+=$nodoItem->nodeValue;
            }
            $ranking[$id]=($puntuacion/$num_votaciones);
            $ranking_variable[$contador]=($puntuacion/$num_votaciones);
            $contador=$contador+1;      
        }
         sort($ranking);
         $result = sort($ranking);
        //$result=burbuja($ranking,$ranking_variable);
        for($i=0;$i<count($result);$i++){
            foreach($this->xpath->query("//lugar[@id='".$result[i]."']/voto/item") as $nodoItem){
                $nodoItem->setAttribute("pos",($i+1)); 
                $this->dom->save(FICHERO);
            }
        }
    }
    function crearListado(){
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $i=0;
        foreach ($this->xpath->query("//lugar/localizacion/geo") as $nodo_geo){
                    $aPlayas[$nodo_geo->parentNode->parentNode->getAttribute("id")]=array(
                                'lat'=>$nodo_geo->getAttribute('lat'),
                                'lon'=>$nodo_geo->getAttribute('lon'),
                                'lugar'=>$nodo_geo->childNodes[1]->nodeValue,
                                'ciudad'=>$nodo_geo->childNodes[3]->nodeValue,
                                'pais'=>$nodo_geo->childNodes[5]->nodeValue,
                                'direccion'=>$nodo_geo->childNodes[7]->nodeValue,
                                'foto'=>$nodo_geo->childNodes[9]->nodeValue
                                );
                                
                    if($nodo_geo->parentNode->parentNode->getAttribute("pos")){
                        $aPlayas[$nodo_geo->parentNode->parentNode->getAttribute("id")]+=array(
                            'pos'=>$nodo_geo->parentNode->parentNode->getAttribute("pos")
                        );
                        
                    }
          }
       echo json_encode(array("aPlayas"=>$aPlayas));
    }
}