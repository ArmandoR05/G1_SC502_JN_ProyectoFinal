<?php
require_once "../php/perfil_datos.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/areacliente.css">
</head>

<body>

    <?php include "../partials/header.php"; ?>

    <?php include "../partials/sidebar.php"; ?>

    <main class="contenido p-4">
        <div class="perfil ">
            <h2 class="mb-1">Mi Perfil</h2>
            <p class="text-secondary mb-4">Datos personales del cliente registrados.</p>

            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <ul class="alert alert-danger">
                    <?php foreach ($errores as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                value="<?= htmlspecialchars($nombreCompleto) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono Celular</label>
                            <input type="text" class="form-control" id="telefono" name="telefono"
                                value="<?= htmlspecialchars($telefono) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula"
                                value="<?= htmlspecialchars($cedula) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                value="<?= htmlspecialchars($direccion) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="nacimiento" name="nacimiento"
                                value="<?= htmlspecialchars($fechaNacimiento) ?>">
                        </div>

                        <hr>

                        <p class="text-muted">Si no deseas cambiar la contraseña, deja estos campos en blanco.</p>

                        <div class="mb-3">
                            <label for="contrasenna" class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" id="contrasenna" name="contrasenna"
                                placeholder="Dejar en blanco si no desea cambiarla">
                        </div>

                        <div class="mb-3">
                            <label for="ConfContrasenna" class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" id="ConfContrasenna" name="ConfContrasenna"
                                placeholder="Repita la nueva contraseña">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary mt-2" id="RealizarCambios"
                                name="RealizarCambios">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-2">© 2025 SIEM Contabilidad | Desarrollado por Equipo de Proyecto</p>
</footer>

</html>