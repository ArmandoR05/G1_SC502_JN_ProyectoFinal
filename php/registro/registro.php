<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../conexionBD.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $email = $_POST["email"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $cedula = $_POST["cedula"] ?? '';
    $password = $_POST["password"] ?? '';

    if(!$nombre || !$apellido || !$email || !$password){
        echo "error: Faltan campos obligatorios";
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $conexion = abrirConexion();

    // Verificar si el email ya existe
    $sql_check = "SELECT id_usuario FROM usuarios WHERE email = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        echo "error: El email ya está registrado";
        $stmt_check->close();
        cerrarConexion($conexion);
        exit();
    }
    $stmt_check->close();

    // Insertar usuario - rol 3 es Cliente por defecto
    $sql = "INSERT INTO usuarios (id_rol, nombre, apellido, email, telefono, password_hash, cedula)
            VALUES (3, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $email, $telefono, $password_hash, $cedula);

    if($stmt->execute()){
        echo "ok";
    }else{
        echo "error: ".$conexion->error;
    }

    $stmt->close();
    cerrarConexion($conexion);

}

?>