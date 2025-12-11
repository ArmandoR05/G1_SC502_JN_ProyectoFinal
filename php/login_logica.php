<?php
require_once "conexion.php";
session_start();

$errores = [];
$mensaje = "";
$correoLogin = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correoLogin = trim($_POST['correo'] ?? "");
    $pass        = $_POST['contrasenna'] ?? "";

    if ($correoLogin === "" || !filter_var($correoLogin, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Ingrese un correo electrónico válido.";
    }
    if ($pass === "") {
        $errores[] = "La contraseña es obligatoria.";
    }

    if (empty($errores)) {
        try {
            $conexion = abrirConexion();
        } catch (Exception $e) {
            $errores[] = "No se pudo conectar a la base de datos.";
        }

        if (empty($errores) && isset($conexion)) {

            $sql = "SELECT id_usuario, password_hash 
                    FROM usuarios 
                    WHERE email = ? LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $correoLogin);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $hashBD = $fila['password_hash'];

                if (password_verify($pass, $hashBD)) {
                    $_SESSION['id_usuario'] = $fila['id_usuario'];

                    $stmt->close();
                    cerrarConexion($conexion);

                    header("Location: index.html");
                    exit;
                } else {
                    $errores[] = "Correo o contraseña incorrectos.";
                }
            } else {
                $errores[] = "Correo o contraseña incorrectos.";
            }

            $stmt->close();
            cerrarConexion($conexion);
        }
    }

    if (!empty($errores)) {
        $mensaje = "No se pudo iniciar sesión. Revise los datos.";
    }
}
