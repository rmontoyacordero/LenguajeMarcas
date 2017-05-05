<?php

ini_set('error_reporting', E_ALL ^ E_NOTICE);

$inicio = microtime(true);

for ($i = 0; $i < 100000; $i++) {
    
}

$fin = microtime(true) - $inicio;

echo "tarea realizada en... " . number_format($fin, 4) . " s".PHP_EOL;

