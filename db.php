<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:Content-Type");
    header("Content-Type: application/json; charset:UTF-8");

    $host = "localhost";
    $userName = "Admin";
    $pswd = "@connectM1nt";
    $dbName = "vueTry";

    $mysqli = new mysqli($host, $userName, $pswd, $dbName);

    if($mysqli->connect_error){
        echo json_encode([
            "status" => "error",
            "message" => "Connexion a la base de donnee impossible: " . $mysqli->connect_error
        ]);
        exit();
    }

    $mysqli->set_charset("utf8mb4");
?>