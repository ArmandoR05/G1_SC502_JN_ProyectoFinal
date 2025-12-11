<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$response = [
    'status' => 'error',
    'mensaje' => 'Error inesperado',
    'debug' => 'inicio',
];

try{
    include '../conexionBD.php';

    $raw = file_get_contents("php://input");
    $datos = json_decode($raw, true);

    if(!$datos){
        $response['mensaje'] = 'Los datos no pudieron ser procesados correctamente.';
        $response['debug'] = 'json inválido';
        echo json_encode($response);
        exit();
    }

    $email = trim($datos['email'] ?? '');
    $password = trim($datos['password'] ?? '');

    if(!$email || !$password){
        $response['mensaje'] = 'Email y contraseña vacíos.';
        $response['debug'] = 'Campos vacíos';
        echo json_encode($response);
        exit();
    }

    $mysqli = abrirConexion();

    $sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.email, u.password_hash, r.nombre_rol 
            FROM usuarios u 
            INNER JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.email = ? AND u.estado = 'activo'";
    
    $stmt = $mysqli->prepare($sql);

    if (!$stmt){
        $response['mensaje'] = 'Error al preparar la consulta.';
        $response['debug'] = 'prepare falló: ' . $mysqli->error;
        echo json_encode($response);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $response['debug'] = 'consulta ejecutada';

    if($resultado && $resultado->num_rows > 0){
        $fila = $resultado->fetch_assoc();

        if(password_verify($password, $fila['password_hash'])){
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['nombre'] = $fila['nombre'];
            $_SESSION['apellido'] = $fila['apellido'];
            $_SESSION['email'] = $fila['email'];
            $_SESSION['nombre_rol'] = $fila['nombre_rol'];

            $response = [
                'status' => 'ok',
                'nombre' => $fila['nombre'] . ' ' . $fila['apellido'],
                'rol' => $fila['nombre_rol'],
                'debug' => 'login exitoso',
            ];
        }else{
            $response['mensaje'] = 'Contraseña incorrecta';
            $response['debug'] = 'fallo de contraseña verify (false)';
        }
    }else{
        $response['mensaje'] = 'Usuario no encontrado';
        $response['debug'] = 'usuario no existe';
    }

    $stmt->close();
    cerrarConexion($mysqli);

}catch (Exception $e){
    $response['mensaje'] = 'Sucedió un error al realizar el login';
    $response['debug'] = 'catch exception: ' . $e->getMessage();
}

echo json_encode($response);
exit();

?>