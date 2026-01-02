<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php"; // $pdo disponible

// Validar que venga el ID
if (!isset($_GET['id'])) {
    die("ID de producto no especificado.");
}

$idProducto = intval($_GET['id']);

// Eliminar producto
$sqlDelete = "DELETE FROM productos WHERE idProducto = :id";
$stmtDelete = $pdo->prepare($sqlDelete);

if ($stmtDelete->execute(['id' => $idProducto])) {
    // Redirigir con mensaje de éxito
    header("Location: productos.php?msg=eliminado");
    exit();
} else {
    echo "Error al eliminar el producto.";
}
