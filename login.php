<?php
require_once "php/login_logica.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIEM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/general.css">
</head>

<body class="login">
<img src="img/logo (1).png" alt="SIEM" class="logo">

<section class="singin-card card shadow ">
    <div class="card-body m-3">

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
            <h3>Iniciar sesión</h3>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" placeholder="ej: armando@correo.com"
                       class="form-control" id="correo" name="correo"
                       value="<?= htmlspecialchars($correoLogin) ?>">
            </div>

            <div class="mb-3">
                <label for="contrasenna" class="form-label">Contraseña</label>
                <input type="password" placeholder="Escriba su contraseña"
                       class="form-control" id="contrasenna" name="contrasenna">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">
                    Iniciar sesión
                </button>

                <div class="enlaces mt-2">
                    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
                    <a href="index.html" class="salir mt-3">INDEX (DESARROLLO)</a>
                </div>
            </div>
        </form>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-2">© 2025 SIEM Contabilidad | Desarrollado por Equipo de Proyecto</p>
</footer>

</html>
