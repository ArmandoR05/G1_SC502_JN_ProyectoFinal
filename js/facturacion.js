

document.addEventListener("DOMContentLoaded", () => {

    // variables base
    const detalleTabla = document.getElementById("detalleTabla");
    const subSpan = document.getElementById("sub");
    const ivaSpan = document.getElementById("iva");
    const totalSpan = document.getElementById("total");

    const btnAgregarProducto = document.getElementById("btnAgregarProducto");

    const modalNombre = document.getElementById("modalNombre");
    const modalPrecio = document.getElementById("modalPrecio");
    const modalIva = document.getElementById("modalIva");
    const modalCantidad = document.getElementById("modalCantidad");

    const modalProductoEl = document.getElementById("modalProducto");

    // Si esta pantalla no es facturación se sale
    if (!detalleTabla || !subSpan || !ivaSpan || !totalSpan || !btnAgregarProducto) {
        return;
    }

    // Pone la mneda en Colones
    function formatearMonto(valor) {
        const n = Number(valor) || 0;
        return "₡ " + n.toLocaleString("es-CR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function recalcularTotales() {
        const filas = detalleTabla.querySelectorAll("tr");
        let subtotal = 0;
        let impuesto = 0;

        filas.forEach(fila => {
            const precio = parseFloat(fila.dataset.precio || "0");
            const iva = parseFloat(fila.dataset.iva || "0");
            const cantidad = parseInt(fila.dataset.cantidad || "0", 10);

            const subLinea = precio * cantidad;
            const impLinea = subLinea * (iva / 100);

            subtotal += subLinea;
            impuesto += impLinea;
        });

        const total = subtotal + impuesto;

        subSpan.innerText = formatearMonto(subtotal);
        ivaSpan.innerText = formatearMonto(impuesto);
        totalSpan.innerText = formatearMonto(total);
    }

    function crearFilaDetalle({ nombre, precio, iva, cantidad }) {
        const subtotalLinea = precio * cantidad;
        const impuestoLinea = subtotalLinea * (iva / 100);
        const totalLinea = subtotalLinea + impuestoLinea;

        const tr = document.createElement("tr");
        tr.dataset.precio = String(precio);
        tr.dataset.iva = String(iva);
        tr.dataset.cantidad = String(cantidad);

        tr.innerHTML = `
            <td>
                ${nombre}
                <input type="hidden" name="descripcion[]" value="${nombre}">
            </td>
            <td class="text-end">
                ${formatearMonto(precio)}
                <input type="hidden" name="precio[]" value="${precio}">
            </td>
            <td class="text-end">
                ${iva}%
                <input type="hidden" name="iva[]" value="${iva}">
            </td>
            <td class="text-end">
                ${cantidad}
                <input type="hidden" name="cantidad[]" value="${cantidad}">
            </td>
            <td class="text-end">
                ${formatearMonto(totalLinea)}
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btnEliminar">X</button>
            </td>
        `;

        tr.querySelector(".btnEliminar").addEventListener("click", () => {
            tr.remove();
            recalcularTotales();
        });

        return tr;
    }

    function limpiarModalProducto() {
        if (modalNombre) modalNombre.value = "";
        if (modalPrecio) modalPrecio.value = "";
        if (modalCantidad) modalCantidad.value = "1";
        if (modalIva) modalIva.value = "13";
    }

    function cerrarModalProducto() {
        if (window.bootstrap && modalProductoEl) {
            const instance =
                window.bootstrap.Modal.getInstance(modalProductoEl) ||
                new window.bootstrap.Modal(modalProductoEl);
            instance.hide();
        }
    }

    // agrega un producto
    btnAgregarProducto.addEventListener("click", () => {
        const nombre = (modalNombre?.value || "").trim();
        const precio = parseFloat(modalPrecio?.value || "");
        const iva = parseFloat(modalIva?.value || "0");
        const cantidad = parseInt(modalCantidad?.value || "", 10);

        if (!nombre || isNaN(precio) || isNaN(iva) || isNaN(cantidad) || cantidad <= 0 || precio < 0) {
            alert("Complete correctamente los datos del producto/servicio.");
            return;
        }

        const tr = crearFilaDetalle({ nombre, precio, iva, cantidad });
        detalleTabla.appendChild(tr);

        limpiarModalProducto();
        cerrarModalProducto();
        recalcularTotales();
    });

    // ---------- Inicial ----------
    recalcularTotales();
});
