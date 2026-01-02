<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Ventas")) {
    header("Location: dashboard.php");
    exit();
}

// Obtener productos según tu tabla REAL
$sql = "
SELECT 
    IdProducto,
    Nombre_producto AS nombre,
    Precio,
    stock
FROM productos
";
$productos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva Venta</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

<h1 class="text-3xl font-bold mb-6">Registrar Nueva Venta</h1>

<div class="bg-white p-6 shadow rounded">

<form method="POST" action="ventas_guardar.php" id="formVenta">

<!-- Producto -->
<label class="block font-semibold">Producto:</label>
<select id="producto" class="border p-2 rounded w-full mb-4">
    <option value="">Seleccione...</option>
    <?php foreach ($productos as $p): ?>
        <option 
            value="<?= $p['IdProducto'] ?>" 
            data-precio="<?= $p['Precio'] ?>" 
            data-stock="<?= $p['stock'] ?>"
        >
            <?= $p['nombre'] ?> — Stock: <?= $p['stock'] ?> — $<?= number_format($p['Precio'], 0, ',', '.') ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Cantidad -->
<label class="block font-semibold">Cantidad:</label>
<input type="number" id="cantidad" class="border p-2 rounded w-full mb-4" min="1">

<button type="button" onclick="agregarItem()" class="bg-green-600 text-white px-4 py-2 rounded">
    ➕ Agregar Producto
</button>

<hr class="my-6">

<h2 class="text-xl font-bold mb-4">Detalle de Venta</h2>

<table class="w-full text-left border" id="tablaDetalle">
<thead>
<tr class="bg-gray-200">
    <th class="p-2">Producto</th>
    <th class="p-2">Cant.</th>
    <th class="p-2">Precio</th>
    <th class="p-2">Subtotal</th>
    <th class="p-2">Quitar</th>
</tr>
</thead>
<tbody></tbody>
</table>

<!-- Totales -->
<div class="mt-6">
    <p class="text-lg">Subtotal: $<span id="subtotal">0.00</span></p>
    <p class="text-lg">IVA (19%): $<span id="iva">0.00</span></p>
    <p class="text-xl font-bold">TOTAL: $<span id="total">0.00</span></p>
</div>

<!-- Datos para enviar al servidor -->
<input type="hidden" name="data" id="dataVenta">

<button class="bg-blue-600 text-white px-6 py-3 rounded mt-6">
    💾 Guardar Venta
</button>

</form>

</div>

</main>

<script>
let detalle = [];

function agregarItem() {
    let id = document.getElementById("producto").value;
    let cant = parseInt(document.getElementById("cantidad").value);

    if (!id || cant <= 0) {
        alert("Seleccione un producto y cantidad válida");
        return;
    }

    let itemSel = document.querySelector(`#producto option[value="${id}"]`);
    let nombre = itemSel.textContent.split("—")[0].trim();
    let precio = parseFloat(itemSel.dataset.precio);
    let stock = parseInt(itemSel.dataset.stock);

    if (cant > stock) {
        alert("Cantidad supera el stock disponible");
        return;
    }

    let subtotal = precio * cant;

    detalle.push({
        idProducto: id,
        nombre: nombre,
        cantidad: cant,
        precio: precio,
        subtotal: subtotal
    });

    renderTabla();
}

function renderTabla() {
    let tbody = document.querySelector("#tablaDetalle tbody");
    tbody.innerHTML = "";
    let subtotal = 0;

    detalle.forEach((item, index) => {
        subtotal += item.subtotal;

        tbody.innerHTML += `
        <tr>
            <td class="p-2">${item.nombre}</td>
            <td class="p-2">${item.cantidad}</td>
            <td class="p-2">$${item.precio}</td>
            <td class="p-2">$${item.subtotal.toFixed(2)}</td>
            <td class="p-2">
                <button type="button" onclick="quitar(${index})" class="text-red-600">X</button>
            </td>
        </tr>`;
    });

    let iva = subtotal * 0.19;
    let total = subtotal + iva;

    document.getElementById("subtotal").textContent = subtotal.toFixed(2);
    document.getElementById("iva").textContent = iva.toFixed(2);
    document.getElementById("total").textContent = total.toFixed(2);

    // Guardar JSON para backend
    document.getElementById("dataVenta").value = JSON.stringify(detalle);
}

function quitar(index) {
    detalle.splice(index, 1);
    renderTabla();
}
</script>

</body>
</html>
