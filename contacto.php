<?php
require_once "conexion.php";

$errores = [];
$mensaje = "";

$input = json_decode(file_get_contents("php://input"), true);

$name = trim($input["name"] ?? "");
$email = trim($input["email"] ?? "");
$message  = trim($input["message"] ?? "");

if ($name === "") $errores[] = "Debe escribir un nombre";
if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido";
if ($message === "") $errores[] = "Debe escribir un mensaje";

if (empty($errores)) {
    $conexion = abrirConexion();

    $sql = "INSERT INTO contacto (nombre, correo, mensaje) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "OK";
    } else {
        http_response_code(500);
        echo "Error saving the message";
    }

    $stmt->close();
    cerrarConexion($conexion);
} else {
    http_response_code(400);
    echo "Incorrect data";
}
?>
