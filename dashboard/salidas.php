<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php";



// Mensaje opcional
$msg = $_GET['msg'] ?? null;

// Traer salidas
$sql = "SELECT m.*, p.Nombre_producto 
        FROM movimientos m
        LEFT JOIN productos p ON m.IdProducto = p.idProducto
        WHERE m.Tipo_movimiento = 'SALIDA'
        ORDER BY m.IdMovimiento DESC";

$stmt = $pdo->query($sql);
$salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Salidas</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

    <h1 class="text-3xl font-bold mb-5">Movimientos de Salida</h1>

    <?php if ($msg): ?>
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <a href="salidas_nuevo.php"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">
        + Registrar Salida
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-xl shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 border">Producto</th>
                    <th class="py-2 px-4 border">Cantidad</th>
                    <th class="py-2 px-4 border">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($salidas)): ?>
                    <?php foreach ($salidas as $row): ?>
                    <tr class="text-center border-b hover:bg-gray-100">
                        <td><?= $row['Nombre_producto'] ?></td>
                        <td><?= $row['Cantidad'] ?></td>
                        <td><?= $row['Fecha_movimiento'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="py-4 text-center">
                        No hay salidas registradas.
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
