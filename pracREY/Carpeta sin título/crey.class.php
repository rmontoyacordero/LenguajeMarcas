<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'crey.xml');

class crey {

    private $dom, $xpath;
    private $inicio, $fin;
    private $horas;

    function __construct() {
        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->inicio = new DateTime('2017/06/01');
        $this->fin = new DateTime('2017/12/31');
        $this->horas = array("0" => [new DateTime('10:00'), new DateTime('17:00'), 180],
            "2" => [new DateTime('11:00'), new DateTime('16:00'), 150],
            "3" => [new DateTime('11:00'), new DateTime('16:00'), 150],
            "4" => [new DateTime('11:00'), new DateTime('16:00'), 150],
            "5" => [new DateTime('11:00'), new DateTime('16:00'), 150],
            "6" => [new DateTime('10:00'), new DateTime('17:00'), 180]);
    }

    public function genXML() {
        $str = "<entradas>";
        $period = new DatePeriod($this->inicio, DateInterval::createFromDateString('1 day'), $this->fin);
        foreach ($period as $dt) {
            $diaSemana = $dt->format("w");
            if ($diaSemana != "1") {
                $str .= "<entrada fecha='" . $dt->format("Ymd") . "'>";
                $periodH = new DatePeriod($this->horas[$diaSemana][0], DateInterval::createFromDateString('1 hour'), $this->horas[$diaSemana][1]);
                foreach ($periodH as $dtH) {
                    $str .= "<horario hora='" . $dtH->format("Hi") . "' plazas='" . $this->horas[$diaSemana][2] . "'/>";
                }
                $str .= "</entrada>";
            }
        }
        $str .= "</entradas>";
        $this->dom->loadXML($str);
        $this->dom->save(FICHERO);
    }

    /*public function readXML() {
        $fechaMax = 0;
        $fechaMin = 0;
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $aEntradas = [];
        $i = 0; //para acumular fechas
        foreach ($xpath->query("//entrada") as $date) {
            $aEntradas[$i]['fecha'] = $date->getAttribute("fecha");
            if ($i == 0) {
                $fechaMax = $date->getAttribute("fecha");
                $fechaMin = $date->getAttribute("fecha");
            } else {
                $fechaMax = (int) $this->buscaFechaMayor($fechaMax, $date->getAttribute("fecha"));
                $fechaMin = (int) $this->buscaFechaMenor($fechaMin, $date->getAttribute("fecha"));
            }
            foreach ($date->childNodes as $horario) {
                $aEntradas[$i]['horario'][] = array(
                    'hora' => $horario->getAttribute("hora"),
                    'entradas' => $horario->nodeValue
                );
            }
            $i++;
        }
        echo json_encode(array("entradas" => $aEntradas, "fechaMax" => $fechaMax, "fechaMin" => $fechaMin));
    }*/

    public function buscaFechaMayor($fecha1, $fecha2) {
        return ((int) $fecha1 > (int) $fecha2 ) ? $fecha1 : $fecha2;
    }

    public function buscaFechaMenor($fecha1, $fecha2) {
        return ((int) $fecha1 < (int) $fecha2 ) ? $fecha1 : $fecha2;
    }

    public function getDispo($idAgencia, $fecha, $hora, $entradas) {
        session_start();
        if (!(file_exists(FICHERO))) {
            $result = ["status" => "ko", "mensaje" => "El archivo no existe!."];
        } else {
            $this->dom->load(FICHERO);
            $this->xpath = new DOMXPath($this->dom);
            if ($result = $this->xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']")) {
                if (empty($result)) {
                    $result = ["status" => "ko", "mensaje" => "En el día indicado no existen entradas disponibles (lunes)"];
                } else {
                    $reservasAnteriores=0;
                foreach($this->xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']/reserva") as $nodReserva){
                        $reservasAnteriores=(int)($nodReserva->getAttribute('entradas'))+(int)$reservasAnteriores;
                    } 
                    if((int)($result->item(0)->getAttribute('plazas'))>(int)$entradas+(int)$reservasAnteriores){
                            $reserva = $this->dom->createElement('reserva');
                            $result->item(0)->appendChild($reserva);
                            $reserva->setAttribute("idAgencia", $idAgencia);
                            $reserva->setAttribute("entradas", $entradas);
                            $reserva->setAttribute("localizador",session_id());
                            session_regenerate_id();
                            //$hoy = getdate();
                            $hoy=new DateTime();
                           /* $horaActual = $hoy[year] . $hoy[mon] . $hoy[mday] . $hoy[hours] . $hoy[minutes] + 10 .$hoy[seconds];*/
                           $horaActual=$hoy->format("YmdHms");
                            $reserva->setAttribute("tiempo", $horaActual);
                            $this->dom->save(FICHERO);
                            $result = ["status" => "ok", "mensaje" => "Reserva realizada"];
                
                    }else{
                        $reservaOpciones=array();
                        foreach($this->xpath->query("//entrada") as $nodReserva){
                            if($nodReserva->getAttribute('fecha')>$fecha){
                                $fechaEsp=$nodReserva->getAttribute('fecha');
                            foreach($this->xpath->query("//entrada[@fecha='$fechaEsp']/horario[@hora='$hora']") as $nodoV){
                        if($nodoV->getAttribute('plazas')>(int)$entradas+(int)$reservasAnteriores && $i<3){ $reservaOpciones[$fechaEsp]=$nodoV->getAttribute('plazas');
                                $i++;
                                }
                    }
                        }
                    }
                        $result = ["status" => "KO", "mensaje" => "En el día indicado no existen entradas disponibles (Falta entradas)","reservaOpciones"=>$reservaOpciones];
                
                    }
                    
                }
                
            }
        }
        return json_encode($result);
    }
    public function obtencionDias(){
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $fechamax=0;
        $fechaMin=0;
        $i=0;
        foreach($xpath->query("//entrada") as $fechaTotales){
            
            if ($i == 0) {
                $fechaMax = $fechaTotales->getAttribute("fecha");
                $fechaMin = $fechaTotales->getAttribute("fecha");
            } else {
                $fechaMax = (int) $this->buscaFechaMayor($fechaMax, $fechaTotales->getAttribute("fecha"));
                $fechaMin = (int) $this->buscaFechaMenor($fechaMin, $fechaTotales->getAttribute("fecha"));
            }
            $i++;
                    }
            $fechaMax=substr($fechaMax,0,4)."-".substr($fechaMax,4,2)."-".substr($fechaMax,6,2);
            $fechaMin=substr($fechaMin,0,4)."-".substr($fechaMin,4,2)."-".substr($fechaMin,6,2);
        echo json_encode(array("fechamax"=>$fechaMax,"fechamin"=>$fechaMin));
    }
    public function obtencionHoras($fecha){
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $result=array();
        foreach($xpath->query("//entrada[@fecha='$fecha']/horario") as $hora){
            $aux=$hora->getAttribute('hora');
            $result[]=substr($aux,0,2).":".substr($aux,2,2);
        }
    echo json_encode($result);       

    }
    public function reviewReservation(){
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
         
        foreach($xpath->query("//entrada") as $entrada){
            $fecha=$entrada->getAttribute('fecha');
            
            foreach($xpath->query("//entrada[@fecha='$fecha']/horario") as $horario){
                $hora=$horario->getAttribute('hora');
                
                foreach($xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']") as $citas){
                
                 
                if (empty($citas)) {
                     } else {
                foreach($xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']/reserva") as $nodReserva){
                    $hoy = getdate();
                    $horaActual = $hoy[year] . $hoy[mon] . $hoy[mday] . $hoy[hours] . $hoy[minutes];
                    $horaReserva=$nodReserva->getAttribute('tiempo');
                    if((int)$horaActual>(int)$horaReserva){
                        $citas->removeChild($nodReserva);
                    }
                    
                        }
                     }
                }
            }
        }
        $this->dom->save(FICHERO);
    }
    public function mostrarReserva($idAgencia){
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        
        foreach($xpath->query("//entrada") as $entrada){
            $fecha=$entrada->getAttribute('fecha');
            foreach($xpath->query("//entrada[@fecha='$fecha']/horario") as $horario){
                $hora=$horario->getAttribute('hora');
                    foreach($xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']/reserva") as $reserva){
                        if($idAgencia==$reserva->getAttribute('idAgencia')){
                            $localizador=$reserva->getAttribute('localizador');
                            $ticketAgencia[$localizador]=array(
                                'fecha'=> $fecha,
                                'hora'=>$hora,
                                'entradas'=>$reserva->getAttribute('entradas'),
                                'tiempo'=>$reserva->getAttribute('tiempo')

                                );
                            
                        }
                        
                    }
            }
        }
        echo json_encode(array("ticketAgencia"=>$ticketAgencia));
         
    }
    public function confirmacionReserva($localizador,$fecha,$hora,$entradas,$idAgencia){
        
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $result=["localizador" => $localizador, "hora" => $hora,"fecha" => $fecha,
        "entradas" => $entradas, "idAgencia"=>$idAgencia];
        foreach($xpath->query("//entrada[@fecha='$fecha']/horario") as $horario){ 
            if($horario->getAttribute('hora')==$hora){
                $plazasActuales= $horario->getAttribute('plazas');
                $plazasActuales=(int)$plazasActuales-(int)$entradas;
                $horario->setAttribute('plazas',$plazasActuales);
                foreach($xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']/reserva") as $nodoReserva){
                if($nodoReserva->getAttribute('localizador')==$localizador){
                    $horario->removeChild($nodoReserva);
                    $compra = $this->dom->createElement('compra');
                    $horario->appendChild($compra);
                    $compra->setAttribute("idAgencia", $idAgencia);
                    $compra->setAttribute("localizador", $localizador);
                    $compra->setAttribute("fecha", $fecha);
                    $compra->setAttribute("hora", $hora);
                    $compra->setAttribute("entradas", $entradas);      
                    
                }
            }
                $this->dom->save(FICHERO);
                $result = ["status" => "ok", "mensaje" => "Reserva realizada"];
            }
        }
            
                
            
            echo json_encode($result);
        }
        

    }


