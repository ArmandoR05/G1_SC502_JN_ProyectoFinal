<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id_usuario'])){
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

include '../conexionBD.php';

$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT * FROM roles WHERE estado = 'activo' ORDER BY nombre_rol");

$roles = [];
while ($fila = $resultado->fetch_assoc()) {
    $roles[] = $fila;
}

cerrarConexion($mysqli);

echo json_encode($roles);
?>