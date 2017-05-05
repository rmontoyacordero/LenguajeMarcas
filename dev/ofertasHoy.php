<?php


ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);

$ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,'www.google.com');
      
       curl_setopt($ch,CURLOPT_HEADER,0);

$hotel = $_POST['hotel'];
$fecha =  $_POST['fecha'];
$reserva = (int) $_POST['plazas'];
$rec = (int)$_POST['rec'];
$precio=0;
$doc = new DOMDocument();
$doc->load(RUTA . '../xml/ofertasHoy.xml');
$xpath = new DOMXPath($doc);
$guardar = false;
if ($result = $xpath->query("//oferta[@nombre='$hotel']/fecha[@dia='$fecha']/plazas")) {
    if ($reserva > (int) $result->item(0)->nodeValue) {
        
    } else {
        $result->item(0)->nodeValue = (int) $result->item(0)->nodeValue - $reserva; 
        $guardar = true;
        
    }
}
if($guardar){
    if ($result = $xpath->query("//oferta[@nombre='$hotel']/fecha[@dia='$fecha']/precio")){
        $precio =(int) $result -> item(0)->nodeValue;
        $doc->save(RUTA . '../xml/ofertasHoy.xml');
    $doc2 = new DOMDocument();
    $xpath = new DOMXPath($doc);
    foreach ($xpath->query("//oferta") as $result){
        if($hotel==$result -> getAttribute('nombre')){
            $identi=$result -> getAttribute('id');
        }
    }
    $fichero = '../xml/bookingsHoy.xml';
    if(file_exists($fichero)){
        $doc2->load(RUTA . '../xml/bookingsHoy.xml');
        $xpath = new DOMXPath($doc2);
        foreach ($xpath->query("//Bookings") as $prueba){
            $booking = $doc2->createElement('booking');
            $prueba->appendChild($booking);
            $atributo = $doc2->createAttribute('id');
            $atributo ->value=$rec;
            $booking->appendChild($atributo);
            $hotelXML = $doc2->createElement('hotel');
            $booking->appendChild($hotelXML);
            $atributo2 = $doc2->createAttribute('id');
            $atributo2 ->value=$identi;
            $hotelXML->appendChild($atributo2);
            $fechaXML = $doc2->createElement('fecha',$fecha);
            $plazasXML = $doc2->createElement('plazas',$reserva);
            $precioXML = $doc2->createElement('precio',$precio);
            $observaciones = $doc2->createElement('observaciones');
            $hotelXML->appendChild($fechaXML);
            $hotelXML->appendChild($plazasXML);
            $hotelXML->appendChild($precioXML);
            $hotelXML->appendChild($observaciones);
            $observaciones->appendChild($doc2->createCDATASection(curl_setopt($ch,CURLOPT_URL,'www.google.com')));
            $doc2->save(RUTA . '../xml/bookingsHoy.xml');
        }

    }else{
        $str = '<?xml version="1.0" encoding="UTF-8"?>';
        $str.='<Bookings>';
        $str.='<InfoAgency>';
        $str.='<name>"OFMA107"</name>';
        $str.='<address>"Málaga, 2006, c/del Politécnico"</address>';
        $str.='</InfoAgency>';
       /* $str.= '<booking id="'.$rec.'">';
        $str.='<hotel id="'.$identi.'">';
        $str.='<fecha>"'.$fecha.'"</fecha>';
        $str.='<plazas>"'.$reserva.'"</plazas>';
        $str.='<precio>"'.$precio.'"</precio>';
        $str.='<observaciones><![CDATA[ lorem ipsum ]]></observaciones>';
        $str.='</hotel>';
        $str.='</booking>';*/
        $str.= '</Bookings>';
        $doc2->loadXML($str);
         $xpath = new DOMXPath($doc2);
        foreach ($xpath->query("//Bookings") as $prueba){
            $booking = $doc2->createElement('booking');
            $prueba->appendChild($booking);
            $atributo = $doc2->createAttribute('id');
            $atributo ->value=$rec;
            $booking->appendChild($atributo);
            $hotelXML = $doc2->createElement('hotel');
            $booking->appendChild($hotelXML);
            $atributo2 = $doc2->createAttribute('id');
            $atributo2 ->value=$identi;
            $hotelXML->appendChild($atributo2);
            $fechaXML = $doc2->createElement('fecha',$fecha);
            $plazasXML = $doc2->createElement('plazas',$reserva);
            $precioXML = $doc2->createElement('precio',$precio);
            $observaciones = $doc2->createElement('observaciones');
            $hotelXML->appendChild($fechaXML);
            $hotelXML->appendChild($plazasXML);
            $hotelXML->appendChild($precioXML);
            $hotelXML->appendChild($observaciones);
            $observaciones->appendChild($doc2->createCDATASection(curl_setopt($ch,CURLOPT_HEADER,0)));
            $doc2->save(RUTA . '../xml/bookingsHoy.xml');
        }
        $doc2->save(RUTA . '../xml/bookingsHoy.xml');

    }
    }
    
       
}
curl_close($ch);
 echo json_encode(array("hotel" => $hotel, "fecha" => $fecha, "reserva" => $reserva, "precio" => $precio, "guardar"=>$guardar));




