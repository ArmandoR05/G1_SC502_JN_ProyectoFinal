<?php

function abrirConexion()
{
    $host = "127.0.0.1";       
    $user = "root";            
    $password = "Root1234!";   
    $db = "siem_contabilidades"; 
    $port = 3307;              

    $mysqli = new mysqli($host, $user, $password, $db, $port);

    if ($mysqli->connect_errno) {
        throw new Exception("Error de conexión a la base de datos SIEM: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");

    return $mysqli;
}

function cerrarConexion($mysqli)
{
    $mysqli->close();
}
