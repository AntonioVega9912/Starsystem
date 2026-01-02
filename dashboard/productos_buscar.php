<?php
require_once "../config/configdb.php";

$texto = $_POST['texto'] ?? '';

$sql = "SELECT * FROM productos 
        WHERE Codigo_producto LIKE ? 
        OR Nombre_producto LIKE ?
        OR Descripcion LIKE ?
        ORDER BY idProducto DESC";

$stmt = $pdo->prepare($sql);
$buscar = "%$texto%";
$stmt->execute([$buscar, $buscar, $buscar]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="min-w-full bg-white rounded-xl shadow">
    <thead>
        <tr class="bg-gray-200 text-gray-700">
            <th class="py-2 px-4 border">Código</th>
            <th class="py-2 px-4 border">Nombre</th>
            <th class="py-2 px-4 border">Descripción</th>
            <th class="py-2 px-4 border">Precio</th>
            <th class="py-2 px-4 border">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($productos): ?>
            <?php foreach ($productos as $row): ?>
            <tr class="text-center border-b hover:bg-gray-100">
                <td class="py-2 px-4"><?= $row['Codigo_producto'] ?></td>
                <td class="py-2 px-4"><?= $row['Nombre_producto'] ?></td>
                <td class="py-2 px-4"><?= $row['Descripcion'] ?></td>
                <td class="py-2 px-4"><?= number_format($row['Precio'], 2) ?></td>
                <td class="py-2 px-4 space-x-2">
                    <a href="productos_editar.php?id=<?= $row['idProducto'] ?>" class="bg-yellow-500 px-2 py-1 rounded text-white hover:bg-yellow-600">Editar</a>

                    <a href="productos_eliminar.php?id=<?= $row['idProducto'] ?>" class="bg-red-500 px-2 py-1 rounded text-white hover:bg-red-600"
                       onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr><td colspan="5" class="py-4 text-center">No hay resultados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
