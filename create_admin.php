<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'php/conexionBD.php';

try {
    $mysqli = abrirConexion();
    
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<h2>Actualizando usuario admin...</h2>";
    
    $sql = "UPDATE usuarios SET password_hash = ? WHERE email = 'admin@siem.cr'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $hash);
    
    if ($stmt->execute()) {
        echo "✅ Usuario admin creado correctamente<br><br>";
        echo "<strong>Credenciales:</strong><br>";
        echo "Email: admin@siem.cr<br>";
        echo "Password: admin123<br><br>";
    } else {
        echo "❌ Error al actualizar admin<br>";
    }
    $stmt->close();
    
    cerrarConexion($mysqli);
    
    echo "<hr>";
    echo "<h3>✅ PROCESO COMPLETADO</h3>";
    echo "<p><strong>IMPORTANTE:</strong> Ahora BORRA este archivo por seguridad.</p>";
    echo "<p><a href='index.php'>Ir al Login</a></p>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
