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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_POST["id_usuario"] ?? 0;
    $id_rol = $_POST["id_rol"] ?? 3;
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $email = $_POST["email"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $cedula = $_POST["cedula"] ?? '';
    $estado = $_POST["estado"] ?? 'activo';

    if ($id_usuario <= 0 || !$nombre || !$apellido || !$email) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Datos incompletos']);
        exit();
    }

    try {
        $conexion = abrirConexion();

        $sql = "UPDATE usuarios 
                SET nombre = ?, apellido = ?, email = ?, telefono = ?, 
                    cedula = ?, id_rol = ?, estado = ?
                WHERE id_usuario = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssisi", $nombre, $apellido, $email, $telefono, $cedula, $id_rol, $estado, $id_usuario);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'ok', 'mensaje' => 'Usuario actualizado exitosamente']);
        } else {
            echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar usuario']);
        }

        $stmt->close();
        cerrarConexion($conexion);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error: ' . $e->getMessage()]);
    }
}
?>