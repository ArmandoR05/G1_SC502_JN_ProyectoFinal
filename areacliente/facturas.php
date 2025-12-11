<?php
require_once "../php/facturas_datos.php";
?>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<main class="p-4 contenido">
    <div class="container py-3">

        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <div>
                <h2 class="mb-1">Mis facturas</h2>
                <p class="text-secondary mb-0">Listado de facturas emitidas por sus clientes.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="facturación.php" class="btn btn-outline-primary btn-sm">
                    ← Volver a facturación
                </a>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <?php if (empty($listaFacturas)): ?>
                    <div class="alert alert-warning mb-0">
                        No tiene facturas registradas todavía.
                    </div>
                <?php else: ?>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th># Factura</th>
                                    <th>Cliente</th>
                                    <th>Identificación</th>
                                    <th>Fecha</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($listaFacturas as $f): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($f['numero_factura']) ?></td>
                                        <td><?= htmlspecialchars($f['razon_social']) ?></td>
                                        <td><?= htmlspecialchars($f['numero_identificacion']) ?></td>

                                        <td>
                                            <?= htmlspecialchars($f['fecha_emision']) ?>
                                        </td>

                                        <td class="text-end">₡ <?= number_format((float) $f['subtotal'], 2) ?></td>
                                        <td class="text-end">₡ <?= number_format((float) $f['impuesto'], 2) ?></td>
                                        <td class="text-end">₡ <?= number_format((float) $f['total'], 2) ?></td>

                                        <td class="text-end">
                                            <form method="post" action="" class="d-inline" data-form-eliminar="factura">
                                                <input type="hidden" name="accion" value="eliminar_factura">
                                                <input type="hidden" name="id_factura" value="<?= (int) $f['id_factura'] ?>">
                                                <button class="btn btn-sm btn-outline-danger btn-eliminar-factura"
                                                    type="button">
                                                    Eliminar
                                                </button>
                                            </form>
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
<script src="../js/alertas.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include "../partials/footer.php"; ?>