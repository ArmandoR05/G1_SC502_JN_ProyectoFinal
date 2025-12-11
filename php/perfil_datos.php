<?php
require_once "conexion.php";
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

$errores = [];
$mensaje = "";

$nombreCompleto   = "";
$email            = "";
$telefono         = "";
$cedula           = "";
$direccion        = "";
$fechaNacimiento  = "";

try {
    $conexion = abrirConexion();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreCompleto  = trim($_POST['nombre'] ?? "");
    $email           = trim($_POST['correo'] ?? "");
    $telefono        = trim($_POST['telefono'] ?? "");
    $cedula          = trim($_POST['cedula'] ?? "");
    $direccion       = trim($_POST['direccion'] ?? "");
    $fechaNacimiento = trim($_POST['nacimiento'] ?? "");
    $pass1           = $_POST['contrasenna'] ?? "";
    $pass2           = $_POST['ConfContrasenna'] ?? "";

    // Validaciones básicas
    if ($nombreCompleto === "") {
        $errores[] = "El nombre completo es obligatorio.";
    }
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }

    $cambiarPassword = false;
    $hashNuevo = null;

    if ($pass1 !== "" || $pass2 !== "") {
        if ($pass1 !== $pass2) {
            $errores[] = "Las contraseñas no coinciden.";
        } elseif (strlen($pass1) < 8) {
            $errores[] = "La nueva contraseña debe tener al menos 8 caracteres.";
        } else {
            $cambiarPassword = true;
            $hashNuevo = password_hash($pass1, PASSWORD_BCRYPT);
        }
    }

    if (empty($errores)) {

        if ($cambiarPassword) {
            $sql = "UPDATE usuarios
                    SET nombre = ?, email = ?, telefono = ?, cedula = ?, direccion = ?, fecha_nacimiento = ?, password_hash = ?
                    WHERE id_usuario = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param(
                "sssssssi",
                $nombreCompleto,
                $email,
                $telefono,
                $cedula,
                $direccion,
                $fechaNacimiento,
                $hashNuevo,
                $idUsuario
            );
        } else {
            $sql = "UPDATE usuarios
                    SET nombre = ?, email = ?, telefono = ?, cedula = ?, direccion = ?, fecha_nacimiento = ?
                    WHERE id_usuario = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param(
                "ssssssi",
                $nombreCompleto,
                $email,
                $telefono,
                $cedula,
                $direccion,
                $fechaNacimiento,
                $idUsuario
            );
        }

        if ($stmt->execute()) {
            $mensaje = "Datos actualizados correctamente.";
        } else {
            $errores[] = "Error al actualizar los datos: " . $conexion->error;
        }

        $stmt->close();
    }
}

if (empty($errores)) {
    $sql = "SELECT nombre, email, telefono, cedula, direccion, fecha_nacimiento
            FROM usuarios
            WHERE id_usuario = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $nombreCompleto   = $fila['nombre'] ?? "";
        $email            = $fila['email'] ?? "";
        $telefono         = $fila['telefono'] ?? "";
        $cedula           = $fila['cedula'] ?? "";
        $direccion        = $fila['direccion'] ?? "";
        $fechaNacimiento  = $fila['fecha_nacimiento'] ?? "";
    }

    $stmt->close();
}

cerrarConexion($conexion);

