<?php

if (!isset($activePage)) {
    $activePage = "";
}

function activeClass($key, $activePage) {
    return $key === $activePage ? "active fw-semibold" : "";
}
?>

<aside class="menu-lateral p-3">
    <h5 class="mb-3">Menú</h5>

    <div class="links d-flex flex-column">
        <a href="areacliente.php" class="mb-2 <?= activeClass('general', $activePage) ?>">General</a>
        <a href="mi-perfil.php" class="mb-2 <?= activeClass('perfil', $activePage) ?>">Mi perfil</a>
        <a href="facturación.php" class="mb-2 <?= activeClass('facturacion', $activePage) ?>">Facturación</a>
        <a href="soporte.php" class="mb-2 <?= activeClass('soporte', $activePage) ?>">Soporte</a>

        <a href="../login.php" class="salir mt-5">Salir</a>
    </div>
</aside>
