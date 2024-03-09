<?php



if ($mysqli->connect_errno){
    $msg="Couldn't connect to DB: ".$mysqli->connect_errno;
    logger("$msg", "FATAL");
    exit();
}

$mysqli->set_charset("utf8mb4");
