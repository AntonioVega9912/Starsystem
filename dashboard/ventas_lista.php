<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Ventas")) {
    header("Location: dashboard.php");
    exit();
}

// Obtener ventas
$sql = "
SELECT 
    v.idVenta,
    v.fecha,
    v.subtotal,
    v.iva,
    v.total,
    u.Nombre_usuario,
    u.Apellido_usuario
FROM ventas v
LEFT JOIN usuarios u ON u.IdUsuario = v.idUsuario
ORDER BY v.idVenta DESC
";

$ventas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ventas</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

<h1 class="text-3xl font-bold mb-6">Listado de Ventas</h1>


<!-- Botón Nueva Venta -->
<a href="ventas_crear.php" 
   class="bg-green-600 text-white px-4 py-2 rounded mb-6 inline-block hover:bg-green-700">
    ➕ Nueva Venta
</a>

<div class="flex gap-3 mb-6">

    <a href="reporte_ventas_pdf.php"
       target="_blank"
       class="bg-red-600 text-white px-4 py-2 rounded">
       📄 PDF
    </a>

    <a href="reporte_ventas_excel.php"
       class="bg-green-600 text-white px-4 py-2 rounded">
       📊 Excel
    </a>

</div>

<?php if (isset($_GET['ok'])): ?>
<div class="bg-green-500 text-white p-3 rounded mb-4">
✔ Venta registrada con éxito
</div>
<?php endif; ?>

<div class="bg-white rounded shadow overflow-x-auto mt-4">

<table class="w-full">
<thead class="bg-gray-200">
<tr>
<th class="p-3 text-left">ID</th>
<th class="p-3 text-left">Fecha</th>
<th class="p-3 text-left">Usuario</th>
<th class="p-3 text-left">Subtotal</th>
<th class="p-3 text-left">IVA</th>
<th class="p-3 text-left">Total</th>
<th class="p-3 text-center">Acciones</th>
</tr>
</thead>

<tbody>

<?php if (empty($ventas)): ?>
<tr>
    <td colspan="7" class="text-center p-5 text-gray-500">
        No hay ventas registradas aún.
    </td>
</tr>
<?php else: ?>
<?php foreach ($ventas as $v): ?>
<tr class="border-b hover:bg-gray-50">
<td class="p-3"><?= $v['idVenta'] ?></td>
<td class="p-3"><?= $v['fecha'] ?></td>
<td class="p-3"><?= $v['Nombre_usuario'] . " " . $v['Apellido_usuario'] ?></td>
<td class="p-3">$<?= number_format($v['subtotal'], 0, ',', '.') ?></td>
<td class="p-3">$<?= number_format($v['iva'], 0, ',', '.') ?></td>
<td class="p-3 font-semibold">$<?= number_format($v['total'], 0, ',', '.') ?></td>

<td class="p-3 text-center">
    <a 
        href="ventas_ver.php?id=<?= $v['idVenta'] ?>" 
        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
    >
        Ver
    </a>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

</tbody>

</table>
</div>

</main>
</body>
</html>
