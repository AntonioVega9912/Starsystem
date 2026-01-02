<?php
require_once "../auth/seguridad.php";
include "sidebar.php";
include "../config/configdb.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

    <h1 class="text-3xl font-bold mb-5">Gestión de Productos</h1>

    <a href="productos_agregar.php"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-6 inline-block">
       + Agregar Producto
    </a>

    <?php
    // CONSULTA CORREGIDA A MYSQLI
    $sql = "SELECT * FROM productos ORDER BY idProducto DESC";
    $resultado = $conexion->query($sql);
    ?>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">Código</th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Precio</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = $resultado->fetch_assoc()) { ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="p-3"><?= $row['Codigo_producto'] ?></td>
                    <td class="p-3"><?= $row['Nombre_producto'] ?></td>
                    <td class="p-3">$<?= number_format($row['Precio'], 2) ?></td>

                    <td class="p-3">
                        <a href="productos_editar.php?id=<?= $row['idProducto'] ?>"
                           class="text-blue-600 font-semibold">Editar</a>

                        <a href="productos_eliminar.php?id=<?= $row['idProducto'] ?>"
                           class="text-red-600 font-semibold ml-4"
                           onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

</main>
</body>
</html>
