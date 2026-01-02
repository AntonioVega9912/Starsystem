<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php";

if (!isset($_POST['idProducto'])) {
    die("ID inválido.");
}

$id = $_POST['idProducto'];
$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];

// VALIDAR DUPLICADO
$sql = "SELECT COUNT(*) FROM productos WHERE Codigo_producto = ? AND idProducto != ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$codigo, $id]);
$existe = $stmt->fetchColumn();

if ($existe > 0) {
    header("Location: productos_editar.php?id=$id&error=duplicado");
    exit;
}

// ACTUALIZAR
$sql = "UPDATE productos SET 
        Codigo_producto = ?, 
        Nombre_producto = ?, 
        Descripcion = ?, 
        Precio = ?
        WHERE idProducto = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$codigo, $nombre, $descripcion, $precio, $id]);

header("Location: productos.php?success=1");
exit;
