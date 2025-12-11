<?php
require_once "../php/incidentes_datos.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte | Área cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/areacliente.css">
</head>

<body>

    <?php include "../partials/header.php"; ?>
    <?php include "../partials/sidebar.php"; ?>

    <main class="contenido p-4">
        <div class="container py-4">

            <h2>Soporte e incidentes</h2>
            <p class="text-muted">Desde aquí podés registrar nuevos incidentes y revisar el historial de soporte.</p>

            <!-- Mensajes o error -->
            <?php if (!empty($mensajeIncidentes)): ?>
                <div class="alert alert-success alert-sm">
                    <?= htmlspecialchars($mensajeIncidentes) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($erroresIncidentes)): ?>
                <div class="alert alert-danger alert-sm">
                    <ul class="mb-0">
                        <?php foreach ($erroresIncidentes as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- nuevo  -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <?= $incidenteEditar ? "Editar incidente #{$incidenteEditar['id_ticket']}" : "Registrar nuevo incidente" ?>
                    </h5>

                    <form method="post" class="row g-3">
                        <input type="hidden" name="accion" value="<?= $incidenteEditar ? 'editar' : 'crear' ?>">

                        <?php if ($incidenteEditar): ?>
                            <input type="hidden" name="id_ticket"
                                value="<?= htmlspecialchars($incidenteEditar['id_ticket']) ?>">
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="form-control"
                                value="<?= htmlspecialchars($incidenteEditar['fecha'] ?? date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="tema" class="form-label">Tema</label>
                            <input type="text" name="tema" id="tema" class="form-control"
                                placeholder="Acceso al sistema, reportes, configuración..."
                                value="<?= htmlspecialchars($incidenteEditar['tema'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-5">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input type="text" name="asunto" id="asunto" class="form-control"
                                placeholder="Describa brevemente el problema"
                                value="<?= htmlspecialchars($incidenteEditar['asunto'] ?? '') ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción detallada</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control"
                                placeholder="Explique con detalle qué ocurrió, qué estaba haciendo, mensajes de error, etc."
                                required><?= htmlspecialchars($incidenteEditar['descripcion'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="medio_contacto" class="form-label">Medio de contacto preferido</label>
                            <select name="medio_contacto" id="medio_contacto" class="form-select">
                                <?php
                                $medioActual = $incidenteEditar['medio_contacto'] ?? 'correo';
                                $opciones = [
                                    'correo' => 'Correo electrónico',
                                    'whatsapp' => 'WhatsApp',
                                    'telefono' => 'Llamada telefónica'
                                ];
                                foreach ($opciones as $valor => $texto) {
                                    $sel = ($medioActual === $valor) ? 'selected' : '';
                                    echo "<option value=\"$valor\" $sel>$texto</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <?= $incidenteEditar ? 'Guardar cambios' : 'Registrar incidente' ?>
                            </button>

                            <?php if ($incidenteEditar): ?>
                                <a href="soporte.php" class="btn btn-outline-secondary btn-sm">
                                    Cancelar
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de incidentes -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Mis incidentes registrados</h5>

                    <?php if (empty($listaIncidentes)): ?>

                        <p class="text-muted mb-0">Aún no has registrado incidents de soporte.</p>

                    <?php else: ?>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Tema</th>
                                        <th>Asunto</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listaIncidentes as $inc): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($inc['id_ticket']) ?></td>
                                            <td><?= htmlspecialchars($inc['fecha']) ?></td>
                                            <td><?= htmlspecialchars($inc['tema']) ?></td>
                                            <td><?= htmlspecialchars($inc['asunto']) ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = 'secondary';
                                                if ($inc['estado'] === 'abierto') {
                                                    $badgeClass = 'warning text-dark';
                                                } elseif ($inc['estado'] === 'en_progreso') {
                                                    $badgeClass = 'info text-dark';
                                                } elseif ($inc['estado'] === 'resuelto') {
                                                    $badgeClass = 'success';
                                                }
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= htmlspecialchars($inc['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="soporte.php?editar=<?= (int) $inc['id_ticket'] ?>"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Editar
                                                </a>
                                                <a href="soporte.php?eliminar=<?= (int) $inc['id_ticket'] ?>"
                                                    class="btn btn-sm btn-outline-danger btn-eliminar-incidente">
                                                    Eliminar
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <?php include "../partials/footer.php"; ?>
    <script src="../js/alertas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>