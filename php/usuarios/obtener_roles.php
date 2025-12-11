<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'mensaje' => 'No autorizado']);
    exit();
}

include '../conexionBD.php';

try {
    $mysqli = abrirConexion();

    $sql = "SELECT * FROM roles WHERE estado = 'activo' ORDER BY nombre_rol";
    $resultado = $mysqli->query($sql);

    $roles = [];
    while ($fila = $resultado->fetch_assoc()) {
        $roles[] = $fila;
    }

    cerrarConexion($mysqli);

    echo json_encode([
        'status' => 'ok',
        'datos' => $roles
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Error: ' . $e->getMessage()
    ]);
}
?>