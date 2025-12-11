<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../../index.php");
    exit();
}

include '../conexionBD.php';

$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT u.*, r.nombre_rol 
                             FROM usuarios u 
                             INNER JOIN roles r ON u.id_rol = r.id_rol 
                             ORDER BY u.fecha_registro DESC");

cerrarConexion($mysqli);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios - SIEM</title>
    <link rel="stylesheet" href="../../assets/css/home.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../../home.php">SIEM</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="../../home.php" class="nav-link">Inicio</a>
            <a href="listar_usuarios.php" class="nav-link">Usuarios</a>
            <a href="../login/logout.php" class="nav-link">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <div class="card p-4 shadow">

        <div class="d-flex justify-content-between mb-5">
            <h3>Usuarios Registrados</h3>
            <a class="btn btn-success" href="">+ Agregar Usuario</a>
        </div>

        <table class="table table-striped table-hover align-middle">
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
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
                    <td><?= htmlspecialchars($fila['email']) ?></td>
                    <td><?= htmlspecialchars($fila['telefono'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($fila['cedula'] ?? '-') ?></td>
                    <td><span class="badge bg-info"><?= htmlspecialchars($fila['nombre_rol']) ?></span></td>
                    <td>
                        <span class="badge <?= $fila['estado'] === 'activo' ? 'bg-success' : 'bg-danger' ?>">
                            <?= htmlspecialchars($fila['estado']) ?>
                        </span>
                    </td>
                    <td>
                     <a class="btn btn-secondary btn-sm" href="">Editar</a>
                     <a onclick="return confirm('¿Deseas eliminar este usuario?')" 
                        class="btn btn-danger btn-sm" 
                        href="eliminar_usuario.php?id=<?= $fila['id_usuario'] ?>">Eliminar</a>    
                    </td>
                </tr>

                <?php endwhile; ?>

            </tbody>
        </table>

    </div>

</div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
