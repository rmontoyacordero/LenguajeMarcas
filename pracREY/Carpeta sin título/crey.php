<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'crey.xml');


require_once RUTA . 'crey.class.php';

$informacion=$_POST['informacion'];
$fecha=$_POST['fecha'];

$idAgencia=$_POST['idAgencia'];
$entradas=$_POST['plazas'];
$hora=$_POST['hora'];
$localizador=$_POST['localizador'];
/*Colocar aqui todas las variables con un filter input=> $accion = filteer_input(input_post,'nombre')
mirar para enviar parametros por php.
*/

$cr = new crey();
 if(!(file_exists(FICHERO))){
     $cr -> genXML();
 }else{
     $cr->reviewReservation();
 }
 
switch($informacion){
    case "leer": $cr->obtencionDias();
    break;
    case "horas": $fecha=substr($fecha,0,4)."".substr($fecha,5,2)."".substr($fecha,8,2);
                  $cr->obtencionHoras($fecha);
    break;
    case "reserva": $fecha=substr($fecha,0,4)."".substr($fecha,5,2)."".substr($fecha,8,2);
                    echo $cr -> getDispo($idAgencia, $fecha, $hora, $entradas);
    break;
    case "confirmar": $cr->mostrarReserva($idAgencia);
    break;
    case "guardarReserva": $fecha=substr($fecha,0,4)."".substr($fecha,4,2)."".substr($fecha,6,2);
                        echo $cr->confirmacionReserva($localizador,$fecha,$hora,$entradas,$idAgencia);
    break;
}

 

