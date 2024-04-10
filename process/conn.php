<?php

    session_start();

    $userBD = "root";
    $passwordBD = "";
    $nameBD = "pizzaria";
    $hostBD = "localhost";

    try {
        $conn = new PDO("mysql:host={$hostBD};dbname={$nameBD}",$userBD,$passwordBD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    } catch (PDOException $err) {
       print("Erro: " . $err->getMessage() . "<br>");
       die();
    }
?>