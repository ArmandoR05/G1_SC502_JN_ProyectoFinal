<?php
session_start();
if(!isset($_SESSION["usuario_id"])) { header("Location: /auth/login.php"); exit; }
?>

<form action="guardar_post.php" method="POST">
    <textarea name="contenido" maxlength="200" required placeholder="Escribe tu micro-blog (máx 200 caracteres)..."></textarea>
    <button type="submit">Publicar</button>
</form>
