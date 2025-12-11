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

    $id_cliente = $_POST["id_cliente"] ?? 0;
    $razon_social = $_POST["razon_social"] ?? '';
    $tipo_identificacion = $_POST["tipo_identificacion"] ?? 'fisica';
    $numero_identificacion = $_POST["numero_identificacion"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $provincia = $_POST["provincia"] ?? '';
    $actividad_economica = $_POST["actividad_economica"] ?? '';
    $estado_cuenta = $_POST["estado_cuenta"] ?? 'activo';

    if($id_cliente <= 0 || !$razon_social || !$numero_identificacion){
        echo "error: Datos incompletos";
        exit();
    }

    $conexion = abrirConexion();

    $sql = "UPDATE clientes 
            SET razon_social = ?, tipo_identificacion = ?, numero_identificacion = ?, 
                direccion = ?, provincia = ?, actividad_economica = ?, estado_cuenta = ?
            WHERE id_cliente = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $razon_social, $tipo_identificacion, $numero_identificacion, $direccion, $provincia, $actividad_economica, $estado_cuenta, $id_cliente);

    if($stmt->execute()){
        echo "ok";
    }else{
        echo "error: ".$conexion->error;
    }

    $stmt->close();
    cerrarConexion($conexion);
}
?>