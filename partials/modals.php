<?php

?>
<link rel="stylesheet" href="css/areacliente.css">

<!--agregar el producto-->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-bajo">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title">Nuevo producto/servicio</h6>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="modalNombre" placeholder="Ej: Servicio de limpieza">
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Precio (₡)</label>
                        <input type="number" class="form-control" id="modalPrecio" min="0" step="0.01">
                    </div>
                    <div class="col-6">
                        <label class="form-label">IVA</label>
                        <select class="form-select" id="modalIva">
                            <option value="0">0%</option>
                            <option value="4">4%</option>
                            <option value="8">8%</option>
                            <option value="13" selected>13%</option>
                        </select>
                    </div>
                </div>

                <div class="mt-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="modalCantidad" min="1" step="1" value="1">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-primary" id="btnAgregarProducto" type="button">Agregar</button>
            </div>

        </div>
    </div>
</div>


<!-- agrega un cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-bajo">
        <form class="modal-content" method="post" action="">

            <div class="modal-header">
                <h6 class="modal-title">Nuevo cliente</h6>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="accion" value="nuevo_cliente">

                <div class="mb-2">
                    <label class="form-label">Razón social / Nombre</label>
                    <input type="text" class="form-control" name="razon_social" required>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Tipo identificación</label>
                        <select class="form-select" name="tipo_identificacion" required>
                            <option value="">Seleccione...</option>
                            <option value="fisica">Física</option>
                            <option value="juridica">Jurídica</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Número identificación</label>
                        <input type="text" class="form-control" name="numero_identificacion" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                <button class="btn btn-primary" type="submit">Guardar cliente</button>
            </div>

        </form>
    </div>
</div>

