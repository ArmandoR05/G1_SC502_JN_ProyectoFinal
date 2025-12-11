<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(isset($_SESSION['id_usuario'])){
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - SIEM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  
  <div class="login-card" style="max-width: 500px;">
    <h3>Crear Cuenta</h3>
    <p class="text-center text-muted mb-4">Regístrate en SIEM</p>
    
    <form id="registroForm" name="registroForm">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="nombre" class="form-label">Nombre *</label>
          <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre">
        </div>
        <div class="col-md-6 mb-3">
          <label for="apellido" class="form-label">Apellido *</label>
          <input type="text" class="form-control" id="apellido" placeholder="Ingrese su apellido">
        </div>
      </div>
      
      <div class="mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control" id="email" placeholder="ejemplo@correo.com">
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" class="form-control" id="telefono" placeholder="8888-8888">
        </div>
        <div class="col-md-6 mb-3">
          <label for="cedula" class="form-label">Cédula</label>
          <input type="text" class="form-control" id="cedula" placeholder="0-0000-0000">
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password" class="form-label">Contraseña *</label>
          <input type="password" class="form-control" id="password" placeholder="Mínimo 6 caracteres">
        </div>
        <div class="col-md-6 mb-3">
          <label for="confirmar" class="form-label">Confirmar *</label>
          <input type="password" class="form-control" id="confirmar" placeholder="Repita su contraseña">
        </div>
      </div>
      
      <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-login">Registrarse</button>
    </form>
    
    <div class="enlaces">
      <p>¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="php/registro/registro.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>