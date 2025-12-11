<?php
//B.D conexion 
$conexion = new mysqli("localhost", "root", "", "siem_contabilidad");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

//Publicacion
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"]);
    $contenido = trim($_POST["contenido"]);

    // Validaciones
    if (empty($usuario) || empty($contenido)) {
        $mensaje = "<div class='alert alert-danger'>Debe completar todos los campos.</div>";
    } elseif (strlen($contenido) > 200) {
        $mensaje = "<div class='alert alert-danger'>El contenido supera los 200 caracteres.</div>";
    } else {
        // Verificacion de que si existe el usuario
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            $mensaje = "<div class='alert alert-danger'>El usuario no existe en la base de datos.</div>";
        } else {
            $fila = $resultado->fetch_assoc();
            $idUsuario = $fila["id_usuario"];

            //Publicar post
            $stmt2 = $conexion->prepare(
                "INSERT INTO blog_posts (id_usuario, contenido) VALUES (?, ?)"
            );
            $stmt2->bind_param("is", $idUsuario, $contenido);

            if ($stmt2->execute()) {
                $mensaje = "<div class='alert alert-success'>¡Blog publicado correctamente!</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al publicar.</div>";
            }
        }
    }
}

//Creacion de post
$posts = $conexion->query("
    SELECT blog_posts.contenido, blog_posts.fecha_publicacion, 
           usuarios.nombre, usuarios.apellido
    FROM blog_posts
    INNER JOIN usuarios ON blog_posts.id_usuario = usuarios.id_usuario
    ORDER BY blog_posts.fecha_publicacion DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog SIEM</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.html">
      <img src="img/logo (1).png" alt="SIEM" height="40" class="me-2">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.html">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="quienes-somos.html">Quiénes somos</a></li>
        <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.html">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="areacliente.html">Área Cliente</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.html">Panel Administrativo</a></li>
        <li class="nav-item"><a class="nav-link" href="login.html">Salir</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">

  <h2 class="text-center mb-4">Publicar en el Blog</h2>

  <?= $mensaje ?>

  <form method="POST" class="card p-4 shadow-sm mb-5">
    <label class="form-label">Correo del usuario:</label>
    <input type="email" name="usuario" class="form-control" placeholder="usuario@correo.com" required>

    <label class="form-label mt-3">Contenido (máx. 200 caracteres):</label>
    <textarea name="contenido" class="form-control" maxlength="200" required></textarea>

    <button class="btn btn-primary mt-3">Publicar</button>
  </form>

  <h3 class="text-center mb-4">Publicaciones Recientes</h3>

  <?php while ($post = $posts->fetch_assoc()): ?>
    <div class="card mb-3 shadow-sm">
      <div class="card-body">
        <p class="mb-1"><?= htmlspecialchars($post["contenido"]) ?></p>
        <small class="text-muted">
          Publicado por <?= $post["nombre"] . " " . $post["apellido"] ?> el <?= $post["fecha_publicacion"] ?>
        </small>
      </div>
    </div>
  <?php endwhile; ?>

</div>

<footer class="bg-dark text-white text-center py-3">
  © 2025 SIEM Contabilidad
</footer>

</body>
</html>
