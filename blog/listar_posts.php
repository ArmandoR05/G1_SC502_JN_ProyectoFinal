<?php
require "../config/db.php";

$query = $conn->query("
    SELECT b.contenido, b.fecha_publicacion, u.nombre
    FROM blog_posts b
    JOIN usuarios u ON b.id_usuario = u.id_usuario
    ORDER BY b.id_post DESC
");

$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Últimos Posts</h2>

<?php foreach($posts as $post): ?>
    <div class="post">
        <p><?= htmlspecialchars($post["contenido"]) ?></p>
        <small>Publicado por <?= $post["nombre"] ?> - <?= $post["fecha_publicacion"] ?></small>
        <hr>
    </div>
<?php endforeach; ?>
