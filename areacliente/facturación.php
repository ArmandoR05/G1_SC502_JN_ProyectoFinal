<?php
require_once "../php/facturacion_datos.php";
?>

<?php include "../partials/header.php"; ?>

<?php include "../partials/sidebar.php"; ?>

<main class="p-4 contenido">
    <div class="container py-3">
        <div class="mt-5">
            <h2>Facturación</h2>
            <p class="text-secondary">Emitir factura.</p>
        </div>

        <?php
        if (!empty($mensaje)) {
            echo '<div class="alert alert-info">' . htmlspecialchars($mensaje) . '</div>';
        }
        ?>

        <form method="post" action="" id="formFactura">

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h5 class="mb-1">Datos del Usuario</h5>
                            <small class="text-muted">
                                Usuario: <?= htmlspecialchars($nombreUsuario ?? "") ?>
                                (<?= htmlspecialchars($emailUsuario ?? "") ?>)
                            </small>
                        </div>

                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal"
                            data-bs-target="#modalCliente">
                            + Nuevo cliente
                        </button>
                    </div>

                    <?php if (empty($listaClientes)): ?>
                        <div class="alert alert-warning mb-0">
                            No tiene clientes registrados todavía.
                        </div>
                    <?php else: ?>
                        <div class="row g-3 mt-2">
                            <div class="col-md-8">
                                <label class="form-label">Cliente</label>
                                <select class="form-select" name="id_cliente" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php foreach ($listaClientes as $cli): ?>
                                        <option value="<?= (int) $cli['id_cliente'] ?>">
                                            <?= htmlspecialchars($cli['razon_social']) ?>
                                            (<?= htmlspecialchars($cli['numero_identificacion']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Detalles de la factura</h5>

                        <button class="btn btn-sm btn-outline-success" type="button" data-bs-toggle="modal"
                            data-bs-target="#modalProducto">
                            + Nuevo producto/servicio
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Importe</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="detalleTabla"></tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span><strong id="sub">₡ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>IVA</span><strong id="iva">₡ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span><strong id="total">₡ 0,00</strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <input type="hidden" name="accion" value="emitir">

                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <button class="btn btn-primary" type="submit">Emitir factura</button>

                        <button class="btn btn-info" type="button" onclick="window.location.href='facturas.php'">
                            Ver facturas
                        </button>

                    </div>

                </div>
            </div>

        </form>
    </div>

    <?php include "../partials/modals.php"; ?>

</main>

<?php include "../partials/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

<script src="../js/facturacion.js"></script>

</body>

</html>