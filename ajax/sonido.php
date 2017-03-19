<?php
ini_set('error_reporting', E_ALL ^ E_NOTICE);
error_reporting(1);
$palabra = $_GET['palabra'];
$meSoundex= soundex($palabra);
$meMetaPhone= metaphone($palabra);
echo json_encode(array("soundex" => $meSoundex, "metaphone" => $meMetaPhone));
?>