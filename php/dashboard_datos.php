<?php
require_once "conexion.php";
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

try {
    $conexion = abrirConexion();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

/* 
Total de facturas
*/

$totalFacturas = 0;

$sql = "SELECT COUNT(*) AS total
        FROM facturas f
        INNER JOIN clientes c ON c.id_cliente = f.id_cliente
        WHERE c.id_usuario = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) { die("Error prepare facturas: " . $conexion->error); }

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
if ($fila = $resultado->fetch_assoc()) {
    $totalFacturas = (int) $fila['total'];
}
$stmt->close();

/* 
Total de tiquetes
*/

$totalAsesorias = 0;  

$sql = "SELECT COUNT(*) AS total
        FROM tickets_soporte ts
        INNER JOIN clientes c ON c.id_cliente = ts.id_cliente
        WHERE c.id_usuario = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) { die("Error prepare tickets_soporte: " . $conexion->error); }

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
if ($fila = $resultado->fetch_assoc()) {
    $totalAsesorias = (int) $fila['total'];
}
$stmt->close();

cerrarConexion($conexion);