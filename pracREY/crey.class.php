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
        $this->horas = array("0" => [new DateTime('10:00'), new DateTime('18:00'), 180],
            "2" => [new DateTime('11:00'), new DateTime('17:00'), 150],
            "3" => [new DateTime('11:00'), new DateTime('17:00'), 150],
            "4" => [new DateTime('11:00'), new DateTime('17:00'), 150],
            "5" => [new DateTime('11:00'), new DateTime('17:00'), 150],
            "6" => [new DateTime('10:00'), new DateTime('18:00'), 180]);
    }

    public function genXML() {
        $str = "<entradas>";
        $period = new DatePeriod($this->inicio, DateInterval::createFromDateString('1 day'), $this->fin->modify('+1 day'));

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

    public function getDates() {
        $fechaMax = 0;
        $fechaMin = 0;
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $i = 0; //para acumular fechas
        foreach ($xpath->query("//entrada") as $date) {
            if ($i == 0) {
                $fechaMax = $date->getAttribute("fecha");
                $fechaMin = $date->getAttribute("fecha");
            } else {
                $fechaMax = (int) $this->buscaFechaMayor($fechaMax, $date->getAttribute("fecha"));
                $fechaMin = (int) $this->buscaFechaMenor($fechaMin, $date->getAttribute("fecha"));
            }
            $i++;
        }
        echo json_encode(array("fechaMax" => $fechaMax, "fechaMin" => $fechaMin));
    }

    public function buscaFechaMayor($fecha1, $fecha2) {
        return ((int) $fecha1 > (int) $fecha2 ) ? $fecha1 : $fecha2;
    }

    public function buscaFechaMenor($fecha1, $fecha2) {
        return ((int) $fecha1 < (int) $fecha2 ) ? $fecha1 : $fecha2;
    }

    public function getDispo($idAgencia, $fecha, $hora, $entradas) {
        $response = null;
        $fecha = new DateTime($fecha);
        $fecha = $fecha->format('Ymd');
        $hora = new DateTime($hora);
        $hora = $hora->format('Hi');
        $fechaActual = new DateTime();
        $fechaActual = $fechaActual->format('YmdHi');
        if (!(file_exists(FICHERO))) {
            $response = ["status" => "ko", "mensaje" => "El archivo no existe!."];
        } else {
            $this->dom->load(FICHERO);
            $this->xpath = new DOMXPath($this->dom);
            if ($result = $this->xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']")) {
                $plazaTotal = $this->getPlazas($result);

                if (($plazaTotal - $entradas) > 0) {
                    session_start();
                    $reserva = $this->dom->createElement('reserva');
                    $result->item(0)->appendChild($reserva);
                    $reserva->setAttribute("idAgencia", $idAgencia);
                    $reserva->setAttribute("entradas", $entradas);
                    $horaActual = $fechaActual + 10;
                    $reserva->setAttribute("tiempo", $horaActual);
                    $reserva->setAttribute("localizador",session_id());
                    
                    $this->dom->save(RUTA . 'crey.xml');
                    session_regenerate_id();
                    $response = json_encode(array("status" => "ok", "mensaje" => "<strong>Correcto!</strong> La reserva ha sido realizada correctamente tienes 10 minutos para comprar tu entrada"));
                } else {
                    while (count($nextAvailable) < 3 && $fecha != '20171231') {
                        $fecha = new DateTime($fecha);
                        $fecha->add(new DateInterval('P1D'));
                        $fecha = $fecha->format('Ymd');
                        if ($result = $this->xpath->query("//entrada[@fecha='$fecha']/horario[@hora='$hora']")) {
                            if ($result->length > 0) {
                                $plazasDisponibles = $this->getPlazas($result);
                                if ((int) $plazasDisponibles > $entradas) {
                                    $nextAvailable[] = ["fecha" => $fecha, "hora" => ((int) $hora > 10) ? $hora : (int) 0 . $hora, "entradas" => $plazasDisponibles];
                                }
                            }
                        }
                    }
                    if (empty($nextAvailable)) {
                        $response = json_encode(array("status" => "ko", "mensaje" => "<strong>Imposible reservar!</strong> Sin fechas disponibles"));
                    } else {
                        $response = json_encode(array("status" => "ko", "DiasDisponibles" => $nextAvailable));
                    }
                }
            }
        }
        return $response;
    }

    public function reviewReservation() {
        $this->dom->load(FICHERO);
        $this->xpath = new DOMXPath($this->dom);
        $hoy = new DateTime();
        $horaActual = $hoy->format('YmdHi');
        $horaActual = (int) $horaActual;
        if ($result = $this->xpath->query("//reserva[@tiempo < '$horaActual']")) {
            foreach ($result as $nodos) {
                $nodos->parentNode->removeChild($nodos);
            }
        }
        $this->dom->save(FICHERO);
    }

    public function getPlazas($result) {
        $plazaTotal = (int) $result->item(0)->getAttribute('plazas');
        foreach ($result->item(0)->childNodes as $nodos) {
            if ($nodos->nodeType == XML_ELEMENT_NODE) {
                $plazaTotal -= (int) $nodos->getAttribute('entradas');
            }
        }
        return $plazaTotal;
    }
    function mostrarReservas($idAgencia){
        $this->dom->load(FICHERO);
        $xpath = new DOMXPath($this->dom);
        $result = $this->xpath->query("//reserva[@idAgencia='$idAgencia']");
        foreach ($result as $reserva) {
            $fecha = $reserva->parentNode->parentNode->getAttribute("fecha");
            $hora = $reserva->parentNode->getAttribute("hora");
            $localizador=$reserva->getAttribute('localizador');
                            $ticketAgencia[$localizador]=array(
                                'fecha'=> $fecha,
                                'hora'=>$hora,
                                'entradas'=>$reserva->getAttribute('entradas'),
                                'tiempo'=>$reserva->getAttribute('tiempo')

                                );
                            
        }
       /* 
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
        }*/
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
