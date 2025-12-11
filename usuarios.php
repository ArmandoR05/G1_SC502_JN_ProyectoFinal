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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - SIEM</title>
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">SIEM</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="home.php" class="nav-link">Inicio</a>
            <a href="usuarios.php" class="nav-link">Usuarios</a>
            <a href="clientes.php" class="nav-link">Clientes</a>
            <a href="asesorias.php" class="nav-link">Asesorías</a>
            <a href="pagos.php" class="nav-link">Pagos</a>
            <a href="php/login/logout.php" class="nav-link">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <div class="card p-4 shadow">

        <div class="d-flex justify-content-between mb-4">
            <h3>Gestión de Usuarios</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="nuevoUsuario()">
                + Agregar Usuario
            </button>
        </div>

        <table class="table table-striped table-hover align-middle" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Cédula</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaUsuariosBody">
                <tr><td colspan="8" class="text-center">Cargando...</td></tr>
            </tbody>
        </table>

    </div>

</div>

<!-- Modal Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuario">
                    <input type="hidden" id="id_usuario" name="id_usuario">
                    <input type="hidden" id="accion" name="accion" value="crear">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido *</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_rol" class="form-label">Rol *</label>
                            <select class="form-select" id="id_rol" name="id_rol" required>
                                <!-- Se llena con JS -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="grupoPassword">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/usuarios.js"></script>

</body>
</html>