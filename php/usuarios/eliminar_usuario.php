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

    // No permitir eliminar el usuario logueado
    if($id === $_SESSION['id_usuario']){
        header('Location: listar_usuarios.php?error=No puede eliminar su propio usuario');
        exit();
    }

    $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

}

cerrarConexion($mysqli);
header('Location: listar_usuarios.php');
exit();

?>