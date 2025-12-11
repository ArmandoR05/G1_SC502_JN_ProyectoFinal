<?php
require_once "conexion.php";
session_start();

$errores = [];
$mensaje = "";

// rellena el formulario si hay errores
$nombreCompleto  = "";
$correo          = "";
$telefono        = "";
$cedula          = "";
$direccion       = "";
$fechaNacimiento = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreCompleto  = trim($_POST['nombre'] ?? "");
    $correo          = trim($_POST['correo'] ?? "");
    $telefono        = trim($_POST['telefono'] ?? "");
    $cedula          = trim($_POST['cedula'] ?? "");
    $direccion       = trim($_POST['direccion'] ?? "");
    $fechaNacimiento = trim($_POST['nacimiento'] ?? "");
    $pass1           = $_POST['contrasenna'] ?? "";
    $pass2           = $_POST['ConfContrasenna'] ?? "";

    // Algunas Validaciones 
    if ($nombreCompleto === "") {
        $errores[] = "El nombre completo es obligatorio.";
    }
    if ($correo === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }
    if ($pass1 === "" || strlen($pass1) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }
    if ($pass1 !== $pass2) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    try {
        $conexion = abrirConexion();
    } catch (Exception $e) {
        $errores[] = "No se pudo conectar a la base de datos.";
    }

    if (empty($errores) && isset($conexion)) {

        // Verificar si el correo ya existe
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->fetch_assoc()) {
            $errores[] = "Ya existe una cuenta registrada con ese correo.";
        }
        $stmt->close();

        if (empty($errores)) {

            
            $hash = password_hash($pass1, PASSWORD_BCRYPT);

            $sql = "INSERT INTO usuarios (
                        nombre, email, telefono,
                        password_hash, cedula, direccion, fecha_nacimiento
                    ) VALUES (?,?,?,?,?,?,?)";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param(
                "sssssss",
                $nombreCompleto,   
                $correo,
                $telefono,
                $hash,
                $cedula,
                $direccion,
                $fechaNacimiento
            );

            if ($stmt->execute()) {
                $nuevoId = $stmt->insert_id;
                $_SESSION['id_usuario'] = $nuevoId;
                $stmt->close();
                cerrarConexion($conexion);

                // se redirige al login
                header("Location: login.php");
                exit;
            } else {
                $errores[] = "Error al registrar el usuario: " . $conexion->error;
                $stmt->close();
            }
        }

        cerrarConexion($conexion);
    }

    if (!empty($errores)) {
        $mensaje = "Por favor corrija los errores indicados.";
    }
}
