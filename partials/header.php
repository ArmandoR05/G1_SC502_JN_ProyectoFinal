<?php

$pageTitle = "Área cliente";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/areacliente.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">
                <img src="../img/logo (1).png" alt="SIEM" height="40" class="me-2">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/Proyecto/">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Proyecto/quienes-somos.html">Quiénes somos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Proyecto/blog.html">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Proyecto/contacto.html">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="/Proyecto/quienes-somos.html">Quiénes somos</a></li>
                </ul>
            </div>
        </div>
    </nav>