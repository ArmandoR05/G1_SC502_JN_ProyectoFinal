<?php
require_once "conexion.php";

session_start();

// Verificar login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

$mensaje = "";
$listaFacturas = [];

try {
    $conexion = abrirConexion();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}


// editar / eliminar

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    // eliminar facura
    if ($accion === "eliminar_factura") {

        $idFactura = (int) ($_POST["id_factura"] ?? 0);

        if ($idFactura <= 0) {
            $mensaje = "Factura inválida.";
        } else {

            // Verifica que la factura pertenece al usuario 
            $sql = "SELECT f.id_factura
                    FROM facturas f
                    INNER JOIN clientes c ON c.id_cliente = f.id_cliente
                    WHERE f.id_factura = ? AND c.id_usuario = ?
                    LIMIT 1";
            $stmt = $conexion->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ii", $idFactura, $idUsuario);
                $stmt->execute();
                $res = $stmt->get_result();
                $existe = $res->fetch_assoc();
                $stmt->close();

                if (!$existe) {
                    $mensaje = "No tiene permiso para eliminar esta factura.";
                } else {

                    // borra detalle
                    $sql = "DELETE FROM detalle_factura WHERE id_factura = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $idFactura);
                    $stmt->execute();
                    $stmt->close();

                    // borra factura
                    $sql = "DELETE FROM facturas WHERE id_factura = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $idFactura);

                    if ($stmt->execute()) {
                        $mensaje = "Factura eliminada correctamente.";
                    } else {
                        $mensaje = "Error al eliminar factura: " . $conexion->error;
                    }

                    $stmt->close();
                }
            } else {
                $mensaje = "Error interno al validar factura.";
            }
        }
    }

    // editar la factura
    if ($accion === "editar_factura") {

        $idFactura = (int) ($_POST["id_factura"] ?? 0);
        $fecha = trim($_POST["fecha_emision"] ?? "");

        if ($idFactura <= 0 || $fecha === "") {
            $mensaje = "Datos inválidos para editar.";
        } else {

            // Validar pertenencia usuario
            $sql = "SELECT f.id_factura
                    FROM facturas f
                    INNER JOIN clientes c ON c.id_cliente = f.id_cliente
                    WHERE f.id_factura = ? AND c.id_usuario = ?
                    LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $idFactura, $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $existe = $res->fetch_assoc();
            $stmt->close();

            if (!$existe) {
                $mensaje = "No tiene permiso para editar esta factura.";
            } else {

                $sql = "UPDATE facturas
                        SET fecha_emision = ?
                        WHERE id_factura = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("si", $fecha, $idFactura);

                if ($stmt->execute()) {
                    $mensaje = "Factura actualizada correctamente.";
                } else {
                    $mensaje = "Error al actualizar factura: " . $conexion->error;
                }

                $stmt->close();
            }
        }
    }
}

//  Carga facturas del usuario
$sql = "SELECT
            f.id_factura,
            f.numero_factura,
            f.fecha_emision,
            f.subtotal,
            f.impuesto,
            f.total,
            c.razon_social,
            c.numero_identificacion
        FROM facturas f
        INNER JOIN clientes c ON c.id_cliente = f.id_cliente
        WHERE c.id_usuario = ?
        ORDER BY f.fecha_emision DESC, f.id_factura DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $listaFacturas[] = $row;
}

$stmt->close();
cerrarConexion($conexion);
