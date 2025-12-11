<?php
require_once "php/registro_logica.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
</head>

<body class="registro">
<img src="img/logo (1).png" alt="SIEM" class="logo">

<section class="singin-card card shadow ">
    <div class="card-body m-4">

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <ul class="alert alert-danger mb-3">
                <?php foreach ($errores as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="" method="post">
            <h3>Registrarse en SIEM</h3>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" placeholder="ej: Armando Núñez Rojas"
                       class="form-control" id="nombre" name="nombre"
                       value="<?= htmlspecialchars($nombreCompleto) ?>">
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" placeholder="ej: armando@correo.com"
                       class="form-control" id="correo" name="correo"
                       value="<?= htmlspecialchars($correo) ?>">
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono Celular</label>
                <input type="text" placeholder="88887777"
                       class="form-control" id="telefono" name="telefono"
                       value="<?= htmlspecialchars($telefono) ?>">
            </div>

            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula</label>
                <input type="text" placeholder="102220333"
                       class="form-control" id="cedula" name="cedula"
                       value="<?= htmlspecialchars($cedula) ?>">
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" placeholder="Alajuela / San Ramón"
                       class="form-control" id="direccion" name="direccion"
                       value="<?= htmlspecialchars($direccion) ?>">
            </div>

            <div class="mb-3">
                <label for="nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="nacimiento" name="nacimiento"
                       value="<?= htmlspecialchars($fechaNacimiento) ?>">
            </div>

            <div class="mb-3">
                <label for="contrasenna" class="form-label">Contraseña</label>
                <input type="password" placeholder="Cree una contraseña" class="form-control"
                       id="contrasenna" name="contrasenna">
            </div>

            <div class="mb-3">
                <label for="ConfContrasenna" class="form-label">Confirme su contraseña</label>
                <input type="password" placeholder="Repita su contraseña" class="form-control"
                       id="ConfContrasenna" name="ConfContrasenna">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4" id="Registrarse" name="Registrarse">
                    Registrarse
                </button>

                <div class="enlaces mt-2">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
                </div>
            </div>
        </form>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
