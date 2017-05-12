<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);
define(RUTA, __DIR__ . DIRECTORY_SEPARATOR);
define(FICHERO, __DIR__ . DIRECTORY_SEPARATOR . 'usuarios.xml');

session_start();

require_once RUTA . 'login.class.php';

$accion = filter_input(INPUT_POST, 'check');
$user = filter_input(INPUT_POST, 'user');
$pass = md5(filter_input(INPUT_POST, 'password'));

$log = new login();
if ($accion == "logear") {
    $result = $log->loginUser($user, $pass);
    $control = json_decode($result, true);
    if ($control["status"] == 'ok') {
        $_SESSION['user'] = $user;
    } else {
        session_destroy();
    }
    echo $result;
}