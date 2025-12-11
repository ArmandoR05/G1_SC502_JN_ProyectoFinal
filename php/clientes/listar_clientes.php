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

$resultado = $mysqli->query("SELECT c.*, u.nombre, u.apellido, u.email, u.telefono
                             FROM clientes c 
                             INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                             ORDER BY c.id_cliente DESC");

if($resultado->num_rows > 0):
    while ($fila = $resultado->fetch_assoc()):
?>
<tr>
    <td><?php echo $fila['id_cliente'] ?></td>
    <td><?= htmlspecialchars($fila['razon_social']) ?></td>
    <td><?= htmlspecialchars($fila['numero_identificacion']) ?></td>
    <td><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
    <td><?= htmlspecialchars($fila['email']) ?></td>
    <td><?= htmlspecialchars($fila['telefono'] ?? '-') ?></td>
    <td>
        <span class="badge <?= $fila['estado_cuenta'] === 'activo' ? 'bg-success' : 'bg-danger' ?>">
            <?= htmlspecialchars($fila['estado_cuenta']) ?>
        </span>
    </td>
    <td>
        <button class="btn btn-warning btn-sm" onclick="editarCliente(
            <?= $fila['id_cliente'] ?>,
            '<?= addslashes($fila['razon_social']) ?>',
            '<?= $fila['tipo_identificacion'] ?>',
            '<?= addslashes($fila['numero_identificacion']) ?>',
            '<?= addslashes($fila['direccion'] ?? '') ?>',
            '<?= addslashes($fila['provincia'] ?? '') ?>',
            '<?= addslashes($fila['actividad_economica'] ?? '') ?>',
            '<?= $fila['estado_cuenta'] ?>'
        )">Editar</button>
        <a onclick="return confirm('¿Deseas eliminar este cliente?')" 
           class="btn btn-danger btn-sm" 
           href="php/clientes/eliminar_cliente.php?id=<?= $fila['id_cliente'] ?>">Eliminar</a>    
    </td>
</tr>
<?php 
    endwhile;
else:
?>
<tr><td colspan="8" class="text-center">No hay clientes registrados</td></tr>
<?php 
endif;

cerrarConexion($mysqli);
?>