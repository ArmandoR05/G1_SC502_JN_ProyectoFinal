<?php
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../../index.php");
    exit();
}

include '../conexionBD.php';

$mysqli = abrirConexion();

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    $stmt = $mysqli->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

cerrarConexion($mysqli);
header('Location: ../../clientes.php');
exit();
?>