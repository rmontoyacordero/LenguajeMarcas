<?php

session_start();

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    $result = array("status" => "ok", "usuario" => $_SESSION['user']);
} else {
    $result = array("status" => "ko");
}
echo json_encode($result);
