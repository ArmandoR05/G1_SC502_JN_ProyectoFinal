<?php
require_once "../php/dashboard_datos.php";
?>


<body>

    <?php include "../partials/header.php"; ?>
    <?php include "../partials/sidebar.php"; ?>

    <main class="contenido p-4">
        <div class="container py-4">
            <h2 class="text-center mb-4">Dash Board principal</h2>
            <h3 class="m-4 text-center">Resumen general</h3>

            
            <div class="row g-3 justify-content-center">

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted">Facturas emitidas</h6>
                            <h3 class="fw-bold" id="facturas">
                                <?= $totalFacturas ?>
                            </h3>
                            <a href="reportes-cliente.php" class="btn btn-outline-primary btn-sm mt-2" id="verFacturas">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-muted">Asesorías activas</h6>
                            <h3 class="fw-bold" id="asesorias">
                                <?= $totalAsesorias ?>
                            </h3>
                            <a href="#" class="btn btn-outline-primary btn-sm mt-2" id="verAsesorias">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

<?php include "../partials/footer.php"; ?>

</html>
