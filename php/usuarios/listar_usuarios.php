<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if(!isset($_SESSION['id_usuario'])){
    echo '<tr><td colspan="8" class="text-center">No autorizado</td></tr>';
    exit();
}

include '../conexionBD.php';

$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT u.*, r.nombre_rol 
                             FROM usuarios u 
                             INNER JOIN roles r ON u.id_rol = r.id_rol 
                             ORDER BY u.fecha_registro DESC");

if($resultado->num_rows > 0):
    while ($fila = $resultado->fetch_assoc()):
?>
<tr>
    <td><?php echo $fila['id_usuario'] ?></td>
    <td><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
    <td><?= htmlspecialchars($fila['email']) ?></td>
    <td><?= htmlspecialchars($fila['telefono'] ?? '-') ?></td>
    <td><?= htmlspecialchars($fila['cedula'] ?? '-') ?></td>
    <td><span class="badge bg-info"><?= htmlspecialchars($fila['nombre_rol']) ?></span></td>
    <td>
        <span class="badge <?= $fila['estado'] === 'activo' ? 'bg-success' : 'bg-danger' ?>">
            <?= htmlspecialchars($fila['estado']) ?>
        </span>
    </td>
    <td>
        <button class="btn btn-warning btn-sm" onclick="editarUsuario(
            <?= $fila['id_usuario'] ?>,
            '<?= addslashes($fila['nombre']) ?>',
            '<?= addslashes($fila['apellido']) ?>',
            '<?= addslashes($fila['email']) ?>',
            '<?= addslashes($fila['telefono'] ?? '') ?>',
            '<?= addslashes($fila['cedula'] ?? '') ?>',
            <?= $fila['id_rol'] ?>,
            '<?= $fila['estado'] ?>'
        )">Editar</button>
        <a onclick="return confirm('¿Deseas eliminar este usuario?')" 
           class="btn btn-danger btn-sm" 
           href="php/usuarios/eliminar_usuario.php?id=<?= $fila['id_usuario'] ?>">Eliminar</a>    
    </td>
</tr>
<?php 
    endwhile;
else:
?>
<tr><td colspan="8" class="text-center">No hay usuarios registrados</td></tr>
<?php 
endif;

cerrarConexion($mysqli);
?>