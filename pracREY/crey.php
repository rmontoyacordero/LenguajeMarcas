<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'crey.xml');

require_once RUTA . 'crey.class.php';

$cr = new crey();
$accion = filter_input(INPUT_POST, 'check');
$idAgencia = filter_input(INPUT_POST, 'idAgencia');
$fecha = filter_input(INPUT_POST, 'date');
$hora = filter_input(INPUT_POST, 'hour');
$tickets = filter_input(INPUT_POST, 'tickets');
$localizador = filter_input(INPUT_POST, 'localizador');
if(!(file_exists(FICHERO))){
     $cr -> genXML();
 }
if ($accion == "inicio") {
    echo $cr->getDates();
}
if ($accion == "reserve") {
    echo $cr->getDispo($idAgencia, $fecha, $hora, $tickets);
}
if ($accion == "review") {
    echo $cr->reviewReservation();
}
if ($accion == "reservasAgencia"){
    echo $cr->mostrarReservas($idAgencia);
}
if($accion == "guardarReserva"){
    echo $cr-> confirmacionReserva($localizador,$fecha,$hora,$tickets,$idAgencia);
}