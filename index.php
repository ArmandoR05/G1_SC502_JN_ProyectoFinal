<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(isset($_SESSION['id_usuario'])){
    header("Location: home.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Sesión - SIEM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

    <div class="login-card">

        <h3>SIEM - Iniciar Sesión</h3>
        <p class="text-center text-muted">Sistema de Gestión Contable</p>

        <form id="loginForm" action="">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>

            <button type="submit" class="btn btn-login">Ingresar</button>

            <div class="enlaces">
                <p> <a href="#">¿Olvidaste tu contraseña?</a></p>
                <p> <a href="registro.php">Crear cuenta nueva</a> </p>
            </div>

        </form>

        <div class="mt-3 p-2 bg-light rounded text-center">
            <small><strong>Prueba:</strong> admin@siem.cr / admin123</small>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="php/login/login.js"></script>

</body>

</html>