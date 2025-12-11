<?php
require_once "conexion.php";
session_start();

// verifica sesion
if (!isset($_SESSION['id_cliente']) && !isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$listaIncidentes   = [];
$erroresIncidentes = [];
$mensajeIncidentes = "";
$incidenteEditar   = null;

try {
    $conexion = abrirConexion();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// OBTENER id_cliente

$idCliente = null;

if (isset($_SESSION['id_cliente'])) {
    $idCliente = (int) $_SESSION['id_cliente'];
} elseif (isset($_SESSION['id_usuario'])) {

    
    $idUsuario = (int) $_SESSION['id_usuario'];

    $sqlCli = "SELECT id_cliente
               FROM clientes
               WHERE id_usuario = ?
               LIMIT 1";

    $stmtCli = $conexion->prepare($sqlCli);
    if ($stmtCli) {
        $stmtCli->bind_param("i", $idUsuario);
        $stmtCli->execute();
        $resCli = $stmtCli->get_result();
        if ($filaCli = $resCli->fetch_assoc()) {
            $idCliente = (int) $filaCli['id_cliente'];
        }
        $stmtCli->close();
    }
}

if (!$idCliente) {
    $mensajeIncidentes = "No se pudo identificar el cliente para cargar los incidentes.";
    cerrarConexion($conexion);
    return;
}

// Crea o edita ticket

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion        = $_POST['accion'] ?? 'crear';
    $fecha         = $_POST['fecha'] ?? date('Y-m-d');
    $tema          = trim($_POST['tema'] ?? '');
    $asunto        = trim($_POST['asunto'] ?? '');
    $descripcion   = trim($_POST['descripcion'] ?? '');
    $medioContacto = trim($_POST['medio_contacto'] ?? 'correo');

    // Validaciones básicas
    if ($tema === '')        $erroresIncidentes[] = "El tema es obligatorio.";
    if ($asunto === '')      $erroresIncidentes[] = "El asunto es obligatorio.";
    if ($descripcion === '') $erroresIncidentes[] = "La descripción es obligatoria.";

    if (empty($erroresIncidentes)) {

        // crea ticket
        if ($accion === 'crear') {

            $sql = "INSERT INTO tickets_soporte 
                        (id_cliente, fecha, tema, asunto, descripcion, medio_contacto, estado, fecha_creacion)
                    VALUES (?, ?, ?, ?, ?, ?, 'abierto', NOW())";

            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                $erroresIncidentes[] = "Error al preparar el registro de incidente: " . $conexion->error;
            } else {
                $stmt->bind_param(
                    "isssss",
                    $idCliente,
                    $fecha,
                    $tema,
                    $asunto,
                    $descripcion,
                    $medioContacto
                );

                if ($stmt->execute()) {
                    $mensajeIncidentes = "Incidente registrado correctamente.";
                } else {
                    $erroresIncidentes[] = "Error al registrar el incidente: " . $stmt->error;
                }
                $stmt->close();
            }

        // editar ticket
        } elseif ($accion === 'editar') {

            $idTicket = isset($_POST['id_ticket']) ? (int) $_POST['id_ticket'] : 0;

            if ($idTicket > 0) {
                $sql = "UPDATE tickets_soporte
                        SET fecha = ?, tema = ?, asunto = ?, descripcion = ?, medio_contacto = ?
                        WHERE id_ticket = ? AND id_cliente = ?";

                $stmt = $conexion->prepare($sql);
                if (!$stmt) {
                    $erroresIncidentes[] = "Error al preparar la actualización: " . $conexion->error;
                } else {
                    $stmt->bind_param(
                        "sssssii",
                        $fecha,
                        $tema,
                        $asunto,
                        $descripcion,
                        $medioContacto,
                        $idTicket,
                        $idCliente
                    );

                    if ($stmt->execute()) {
                        $mensajeIncidentes = "Incidente actualizado correctamente.";
                    } else {
                        $erroresIncidentes[] = "Error al actualizar el incidente: " . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                $erroresIncidentes[] = "Incidente no válido para editar.";
            }
        }
    }
}

// Eliminar incidente

if (isset($_GET['eliminar'])) {
    $idTicketDel = (int) $_GET['eliminar'];

    if ($idTicketDel > 0) {
        $sql = "DELETE FROM tickets_soporte
                WHERE id_ticket = ? AND id_cliente = ?";

        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $idTicketDel, $idCliente);
            if ($stmt->execute()) {
                $mensajeIncidentes = "Incidente eliminado correctamente.";
            } else {
                $erroresIncidentes[] = "No se pudo eliminar el incidente: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $erroresIncidentes[] = "Error al preparar la eliminación: " . $conexion->error;
        }
    }
}

// obtiene el incidente

if (isset($_GET['editar'])) {
    $idTicketEdit = (int) $_GET['editar'];

    if ($idTicketEdit > 0) {
        $sql = "SELECT id_ticket, fecha, tema, asunto, descripcion, medio_contacto
                FROM tickets_soporte
                WHERE id_ticket = ? AND id_cliente = ?";

        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $idTicketEdit, $idCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($fila = $res->fetch_assoc()) {
                $incidenteEditar = $fila;
            }
            $stmt->close();
        }
    }
}

// Lista los incidentes

$sql = "SELECT 
            id_ticket,
            fecha,
            tema,
            asunto,
            estado
        FROM tickets_soporte
        WHERE id_cliente = ?
        ORDER BY fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($fila = $res->fetch_assoc()) {
        $listaIncidentes[] = $fila;
    }
    $stmt->close();
} else {
    $erroresIncidentes[] = "Error al preparar la consulta de incidentes: " . $conexion->error;
}

cerrarConexion($conexion);


