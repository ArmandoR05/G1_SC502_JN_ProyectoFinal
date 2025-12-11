<?php
require_once "conexion.php";
session_start();

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

$mensaje = "";
$listaClientes = [];
$nombreUsuario = "";
$emailUsuario = "";

try {
    $conexion = abrirConexion();
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}


//  rocesa Post

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    //  Crea nuevo cliente 
    if ($accion === 'nuevo_cliente') {

        $razonSocial = trim($_POST['razon_social'] ?? "");
        $tipoIdentificacion = trim($_POST['tipo_identificacion'] ?? "");
        $numeroIdentificacion = trim($_POST['numero_identificacion'] ?? "");

        if ($razonSocial === "" || $tipoIdentificacion === "" || $numeroIdentificacion === "") {
            $mensaje = "Complete Razón social, Tipo de identificación y Número de identificación.";
        } else {

            // evita duplicado por usuario
            $sql = "SELECT id_cliente 
                    FROM clientes 
                    WHERE id_usuario = ? AND numero_identificacion = ?
                    LIMIT 1";
            $stmt = $conexion->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("is", $idUsuario, $numeroIdentificacion);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->fetch_assoc()) {
                    $mensaje = "Ya existe un cliente con esa identificación.";
                }
                $stmt->close();
            }

            if ($mensaje === "") {
                $sql = "INSERT INTO clientes (
                            id_usuario,
                            razon_social,
                            tipo_identificacion,
                            numero_identificacion
                        ) VALUES (?,?,?,?)";

                $stmt = $conexion->prepare($sql);

                if (!$stmt) {
                    die("ERROR en prepare(): " . $conexion->error . "<br>SQL: " . $sql);
                }

                $stmt->bind_param(
                    "isss",
                    $idUsuario,
                    $razonSocial,
                    $tipoIdentificacion,
                    $numeroIdentificacion
                );

                if ($stmt->execute()) {
                    $mensaje = "Cliente registrado correctamente.";
                } else {
                    $mensaje = "Error al registrar cliente: " . $conexion->error;
                }
                $stmt->close();
            }
        }

    // Emitir factura 
    } elseif ($accion === 'emitir') {

        $idCliente = isset($_POST['id_cliente']) ? (int) $_POST['id_cliente'] : 0;

        if ($idCliente <= 0) {
            $mensaje = "Debe seleccionar un cliente para emitir la factura.";
        } else {

            // Verifica que el cliente sea de ESTE usuario
            $sql = "SELECT id_cliente 
                    FROM clientes 
                    WHERE id_cliente = ? AND id_usuario = ?
                    LIMIT 1";
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                die("ERROR en prepare(): " . $conexion->error . "<br>SQL: " . $sql);
            }
            $stmt->bind_param("ii", $idCliente, $idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $existeCliente = $res->fetch_assoc();
            $stmt->close();

            if (!$existeCliente) {
                $mensaje = "El cliente seleccionado no pertenece a su cuenta.";
            } else {

                $descripciones = $_POST['descripcion'] ?? [];
                $precios       = $_POST['precio'] ?? [];
                $ivas          = $_POST['iva'] ?? [];
                $cantidades    = $_POST['cantidad'] ?? [];

                if (count($descripciones) === 0) {
                    $mensaje = "Debe agregar al menos un producto o servicio.";
                } else {

                    $subtotalTotal = 0.0;
                    $impuestoTotal = 0.0;
                    $totalFactura  = 0.0;

                    for ($i = 0; $i < count($descripciones); $i++) {
                        $desc  = trim($descripciones[$i] ?? "");
                        $precio = (float)($precios[$i] ?? 0);
                        $ivaPct = (float)($ivas[$i] ?? 0);
                        $cant   = (int)($cantidades[$i] ?? 0);

                        if ($desc === "" || $cant <= 0 || $precio < 0) {
                            continue;
                        }

                        $subtotalLinea = $precio * $cant;
                        $impuestoLinea = $subtotalLinea * ($ivaPct / 100);
                        $totalLinea    = $subtotalLinea + $impuestoLinea;

                        $subtotalTotal += $subtotalLinea;
                        $impuestoTotal += $impuestoLinea;
                        $totalFactura  += $totalLinea;
                    }

                    if ($subtotalTotal <= 0) {
                        $mensaje = "Los datos de los productos/servicios no son válidos.";
                    } else {

                        // Generar número factura
                        $sql = "SELECT IFNULL(MAX(id_factura), 0) + 1 AS siguiente FROM facturas";
                        $res = $conexion->query($sql);
                        $row = $res ? $res->fetch_assoc() : null;
                        $siguiente = (int)($row['siguiente'] ?? 1);
                        $numeroFactura = "FE-" . str_pad($siguiente, 6, "0", STR_PAD_LEFT);

                        // Insertar FACTURA
                        $sql = "INSERT INTO facturas 
                                    (id_cliente, numero_factura, fecha_emision, subtotal, impuesto, total, estado)
                                VALUES 
                                    (?, ?, CURDATE(), ?, ?, ?, 'aceptada')";
                        $stmt = $conexion->prepare($sql);

                        if (!$stmt) {
                            die("ERROR en prepare(): " . $conexion->error . "<br>SQL: " . $sql);
                        }

                        $stmt->bind_param("isddd", $idCliente, $numeroFactura, $subtotalTotal, $impuestoTotal, $totalFactura);
                        $okFactura = $stmt->execute();
                        $idFactura = $stmt->insert_id;
                        $stmt->close();

                        if (!$okFactura) {
                            $mensaje = "Error al guardar la factura: " . $conexion->error;
                        } else {

                            // Insertar DETALLE
                            $sql = "INSERT INTO detalle_factura 
                                        (id_factura, descripcion, precio, iva, cantidad, total_linea)
                                    VALUES 
                                        (?, ?, ?, ?, ?, ?)";
                            $stmt = $conexion->prepare($sql);

                            if (!$stmt) {
                                die("ERROR en prepare(): " . $conexion->error . "<br>SQL: " . $sql);
                            }

                            for ($i = 0; $i < count($descripciones); $i++) {
                                $desc  = trim($descripciones[$i] ?? "");
                                $precio = (float)($precios[$i] ?? 0);
                                $ivaPct = (float)($ivas[$i] ?? 0);
                                $cant   = (int)($cantidades[$i] ?? 0);

                                if ($desc === "" || $cant <= 0 || $precio < 0) {
                                    continue;
                                }

                                $subtotalLinea = $precio * $cant;
                                $impuestoLinea = $subtotalLinea * ($ivaPct / 100);
                                $totalLinea    = $subtotalLinea + $impuestoLinea;

                                $stmt->bind_param("isddid", $idFactura, $desc, $precio, $ivaPct, $cant, $totalLinea);
                                $stmt->execute();
                            }

                            $stmt->close();

                            $mensaje = "Factura emitida correctamente con número: " . $numeroFactura;
                        }
                    }
                }
            }
        }
    }
}


// Carga datos del usuario

$sql = "SELECT nombre, email FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $nombreUsuario = $row['nombre'] ?? "";
    $emailUsuario = $row['email'] ?? "";
}
$stmt->close();


// Carga clientes del usuario

$sql = "SELECT id_cliente, razon_social, numero_identificacion
        FROM clientes
        WHERE id_usuario = ?
        ORDER BY razon_social";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $listaClientes[] = $row;
}

$stmt->close();
cerrarConexion($conexion);



