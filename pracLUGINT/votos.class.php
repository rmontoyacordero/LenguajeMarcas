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
            $num_votaciones=0;
            foreach($this->xpath->query("//lugar[@id='".$id."']/voto/item") as $nodoItem){
                $puntuacion+=$nodoItem->nodeValue;
                $num_votaciones=$num_votaciones+1;
            }
            $ranking[$id]=($puntuacion/$num_votaciones);
            $ranking_variable[$contador]=($puntuacion/$num_votaciones);
            $contador=$contador+1;
        }
        $result=burbuja($ranking,$ranking_variable);
        for($i=0;$i<count($result);$i++){
            foreach($this->xpath->query("//lugar[@id='".$result[i]."']/voto/item") as $nodoItem){
                $nodoItem->setAttribute("pos",($i+1)); 
                $this->dom->save(FICHERO);
            }
        }
    }
    function burbuja($ranking,$ranking_variable){

        foreach($ranking as $id){
            array_push($result,$id);
        }
        for($i=1;$i<count($ranking_variable);$i++)
    {
        for($j=0;$j<count($ranking_variable)-$i;$j++)
        {
            if($ranking_variable[$j]>$ranking_variable[$j+1])
            {
                $k=$ranking_variable[$j+1];
                $i=$result[$j+1];
                $ranking_variable[$j+1]=$ranking_variable[$j];
                $result[$j+1]=$result[$j];
                $ranking_variable[$j]=$k;
                $result[$j]=$i;
            }
        }
    }
 
    return $result;
        
    }
}