<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inicio - SIEM</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SIEM - Sistema Contable</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="#" class="nav-link">Inicio</a>
                <a href="php/usuarios/listar_usuarios.php" class="nav-link">Usuarios</a>
                <a href="php/login/logout.php" class="nav-link">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">

        <div class="row">
            <div class="col-12">
                <h2>Bienvenido, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?>!</h2>
                <p class="text-muted">Rol: <?php echo $_SESSION['nombre_rol']; ?></p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-6">--</p>
                        <a href="php/usuarios/listar_usuarios.php" class="btn btn-light btn-sm">Ver más</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text display-6">--</p>
                        <a href="#" class="btn btn-light btn-sm">Ver más</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Facturas</h5>
                        <p class="card-text display-6">--</p>
                        <a href="#" class="btn btn-light btn-sm">Ver más</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Asesorías</h5>
                        <p class="card-text display-6">--</p>
                        <a href="#" class="btn btn-light btn-sm">Ver más</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>