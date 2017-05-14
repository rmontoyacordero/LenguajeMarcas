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
            foreach ($this->xpath->query("//lugar[@id='$idLugar']") as $nodo) {
            $node_voto = $this->dom->createElement('JAVIER');
            $node_voto->setAttribute("user",$user);
            $node_voto->setAttribute("t",$tiempo);
            $node_item =$this->dom->createElement('item',$votos);
            $node_ip=$this->dom->createElement('item',$ip);
            $node_userAgent=$this->dom->createElement('userAgent',$navegador);
            $nodo->appendChild($node_voto);
            $node_voto->appendChild($node_item);
            $node_voto->appendChild($node_ip);
            $node_voto->appendChild($node_userAgent);
        }   
    $this->dom->save(FICHERO);           
    }
    function ranking($idLugar){
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $contador=0;
        foreach ($this->xpath->query("/lugares/lugar") as $nodo){
            $id=$nodo->getAttribute('id');
            $puntuacion=0;
            $votos = $this->xpath->query("/lugares/lugar[@id='".$id."']/voto/item");
            $num_votaciones=(int)$votos->length.PHP_EOL;
            foreach($votos as $nodoItem){
                $puntuacion+=(int)$nodoItem->nodeValue;
            }
            if($puntuacion!=null){
                $ranking[$contador]=$id;
                $ranking_variable[$contador]=($puntuacion/$num_votaciones);
                $contador++; 
            }    
        }
    
        $result= $this->burbuja($ranking,$ranking_variable);
        
       foreach($this->xpath->query("/lugares/lugar") as $nodo){
            $id=$nodo->getAttribute('id');
            for($i=0;$i<$ranking->length;$i++){
                if($id==$ranking[i]){
                    $nodo->setAttribute('pos',$i);
                }
            }
        }
       $this->dom->save(FICHERO);
       $tiempoinicial=0;
       foreach($this->xpath->query("/lugares/lugar[@id='$idLugar']/voto") as $nodo){
           $tiempo= $nodo->getAttribute('t');
           if($tiempo>$tiempoinicial){
                $votante = $nodo->getAttribute('user');
                $fecha=new DateTime();
                $fecha->setTimestamp($nodo->getAttribute('t'));
                $fecha=$fecha->format('Y-m-d H:i');
                $tiempoinicial=$tiempo;
           }
            
        }
        $puntuacion=0;
        foreach($this->xpath->query("/lugares/lugar[@id='$idLugar']/voto/item") as $nodo){
            $puntuacion+=(int)$nodo->nodeValue;
        }
        echo json_encode(array("votante"=>$votante,"puntuacion"=>$puntuacion,"fecha"=>$fecha));
    }
    function burbuja($ranking,$ranking_variable){
                for($i=1;$i<count($ranking_variable);$i++)
            {
                for($j=0;$j<count($ranking_variable)-$i;$j++)
                {
                    if($ranking_variable[$j]>$ranking_variable[$j+1])
                    {
                        $k=$ranking_variable[$j+1];
                        $h=$ranking[$j+1];
                        $ranking_variable[$j+1]=$ranking_variable[$j];
                        $ranking[$j+1]=$ranking[$j];
                        $ranking_variable[$j]=$k;
                        $ranking[$j]=$h;
                    }
                }
            }
        
            return $ranking;
    }
   /* function crearListado(){
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
       echo json_encode($aPlayas);*/
    }
