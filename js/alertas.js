document.addEventListener('DOMContentLoaded', function () {
    const formsEliminar = document.querySelectorAll('form[data-form-eliminar="factura"]');

    formsEliminar.forEach(function (form) {
        const btn = form.querySelector('.btn-eliminar-factura');
        if (!btn) return;

        btn.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Eliminar esta factura?',
                text: 'Se eliminarán también sus líneas de detalle.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-incidente');

    botonesEliminar.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); 

            const url = btn.getAttribute('href');

            Swal.fire({
                title: '¿Desea eliminar este incidente?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; 
                }
            });
        });
    });
});

