<?php
session_start();
require "../config/db.php";

if(!isset($_SESSION["usuario_id"])) { exit("Acceso denegado"); }

$contenido = trim($_POST['contenido']);

if(strlen($contenido) > 200){
    exit("El post no puede superar 200 caracteres");
}

$query = $conn->prepare("INSERT INTO blog_posts (id_usuario, contenido) VALUES (?, ?)");
$query->execute([$_SESSION["usuario_id"], $contenido]);

header("Location: listar_posts.php");
exit;
?>
