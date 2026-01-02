<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Ventas")) {
    header("Location: dashboard.php");
    exit();
}

$idVenta = $_GET['id'] ?? 0;
if (!$idVenta) {
    header("Location: ventas_lista.php");
    exit();
}

// Venta
$stmtVenta = $pdo->prepare("
    SELECT v.*, u.Nombre_usuario, u.Apellido_usuario
    FROM ventas v
    JOIN usuarios u ON u.IdUsuario = v.idUsuario
    WHERE v.idVenta = :id
");
$stmtVenta->execute(['id' => $idVenta]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Venta no encontrada.");
}

// Detalle
$stmtDetalle = $pdo->prepare("
    SELECT d.*, p.Nombre_producto
    FROM ventas_detalle d
    JOIN productos p ON p.idProducto = d.idProducto
    WHERE d.idVenta = :id
");
$stmtDetalle->execute(['id' => $idVenta]);
$detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Venta</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

<h1 class="text-3xl font-bold mb-6">Venta #<?= $venta['idVenta'] ?></h1>

<div class="bg-white p-6 rounded shadow mb-6">
    <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
    <p><strong>Usuario:</strong> <?= $venta['Nombre_usuario'] . " " . $venta['Apellido_usuario'] ?></p>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-200">
<tr>
    <th class="p-3">Producto</th>
    <th class="p-3 text-center">Cantidad</th>
    <th class="p-3 text-right">Precio</th>
    <th class="p-3 text-right">Total</th>
</tr>
</thead>

<tbody>
<?php foreach ($detalle as $d): ?>
<tr class="border-b">
    <td class="p-3"><?= $d['Nombre_producto'] ?></td>
    <td class="p-3 text-center"><?= $d['cantidad'] ?></td>
    <td class="p-3 text-right">$<?= number_format($d['precio'], 0, ',', '.') ?></td>
    <td class="p-3 text-right">$<?= number_format($d['total'], 0, ',', '.') ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div class="bg-white p-6 rounded shadow mt-6 text-right">
    <p>Subtotal: $<?= number_format($venta['subtotal'], 0, ',', '.') ?></p>
    <p>IVA (19%): $<?= number_format($venta['iva'], 0, ',', '.') ?></p>
    <p class="text-xl font-bold">TOTAL: $<?= number_format($venta['total'], 0, ',', '.') ?></p>
</div>

<div class="mt-6 flex gap-3">
    <a href="ventas_lista.php" class="bg-gray-500 text-white px-4 py-2 rounded">
        ⬅ Volver
    </a>

    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">
        🖨 Imprimir
    </button>
</div>

</main>
</body>
</html>
