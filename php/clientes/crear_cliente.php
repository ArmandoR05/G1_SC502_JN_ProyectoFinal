<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id_usuario'])){
    echo "error: No autorizado";
    exit();
}

include '../conexionBD.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Primero crear el usuario
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $email = $_POST["email"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $password = $_POST["password"] ?? '';
    
    // Datos del cliente
    $razon_social = $_POST["razon_social"] ?? '';
    $tipo_identificacion = $_POST["tipo_identificacion"] ?? 'fisica';
    $numero_identificacion = $_POST["numero_identificacion"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $provincia = $_POST["provincia"] ?? '';
    $actividad_economica = $_POST["actividad_economica"] ?? '';

    if(!$nombre || !$apellido || !$email || !$password || !$razon_social || !$numero_identificacion){
        echo "error: Faltan campos obligatorios";
        exit();
    }

    $conexion = abrirConexion();
    
    try {
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

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario (rol 3 = Cliente)
        $sql_usuario = "INSERT INTO usuarios (id_rol, nombre, apellido, email, telefono, password_hash, cedula)
                VALUES (3, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql_usuario);
        $stmt->bind_param("ssssss", $nombre, $apellido, $email, $telefono, $password_hash, $numero_identificacion);
        $stmt->execute();
        $id_usuario = $conexion->insert_id;
        $stmt->close();

        // Insertar cliente
        $sql_cliente = "INSERT INTO clientes (id_usuario, razon_social, tipo_identificacion, numero_identificacion, direccion, provincia, actividad_economica)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt2 = $conexion->prepare($sql_cliente);
        $stmt2->bind_param("issssss", $id_usuario, $razon_social, $tipo_identificacion, $numero_identificacion, $direccion, $provincia, $actividad_economica);
        
        if($stmt2->execute()){
            echo "ok";
        }else{
            echo "error: ".$conexion->error;
        }

        $stmt2->close();
        cerrarConexion($conexion);
        
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
}
?>