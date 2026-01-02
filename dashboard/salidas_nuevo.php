<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php";

$error = "";
$msg = "";

// Traer productos para el select (siempre)
$productos = $pdo->query("SELECT IdProducto, Nombre_producto FROM productos")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Leer valores del form AL INICIO
    $producto = $_POST['producto'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 0);
    $idUsuario = $_SESSION['idUsuario'] ?? 1;

    // Validaciones básicas
    if (!$producto) {
        $error = "Debe seleccionar un producto.";
    } elseif ($cantidad <= 0) {
        $error = "La cantidad debe ser mayor a cero.";
    } else {
        // 1) Asegurar que exista fila en inventario (si no existe, crear con 0)
        $invStmt = $pdo->prepare("SELECT Inventario_actual FROM inventario WHERE IdProducto = :prod");
        $invStmt->execute(['prod' => $producto]);
        $inv = $invStmt->fetch(PDO::FETCH_ASSOC);

        if (!$inv) {
            // crear registro con 0 (no permitimos salida porque no hay stock)
            $crear = $pdo->prepare("INSERT INTO inventario (IdProducto, Inventario_actual, Inventario_minimo) VALUES(:prod, 0, 0)");
            $crear->execute(['prod' => $producto]);

            // recargar el inventario
            $invStmt->execute(['prod' => $producto]);
            $inv = $invStmt->fetch(PDO::FETCH_ASSOC);
        }

        // 2) Validar stock
        if ($inv['Inventario_actual'] < $cantidad) {
            $error = "No hay suficiente inventario para realizar la salida. Stock actual: " . intval($inv['Inventario_actual']);
        } else {
            // 3) Insertar movimiento SALIDA
            $insertMovimiento = $pdo->prepare("
                INSERT INTO movimientos (IdProducto, IdUsuario, Tipo_movimiento, Cantidad)
                VALUES (:prod, :user, 'SALIDA', :cant)
            ");
            $insertMovimiento->execute([
                'prod' => $producto,
                'user' => $idUsuario,
                'cant' => $cantidad
            ]);

            // 4) Actualizar inventario (restar)
            $updateInv = $pdo->prepare("
                UPDATE inventario
                SET Inventario_actual = Inventario_actual - :cant
                WHERE IdProducto = :prod
            ");
            $updateInv->execute([
                'cant' => $cantidad,
                'prod' => $producto
            ]);

            $msg = "Salida registrada correctamente.";
            // opcional: recargar $inv si quieres mostrar stock actualizado
            $invStmt->execute(['prod' => $producto]);
            $inv = $invStmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva Salida</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">
    <h1 class="text-3xl font-bold mb-5">Registrar Salida</h1>

    <?php if ($msg): ?>
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-xl">

        <label class="block mb-3">
            <span class="font-semibold">Producto</span>
            <select name="producto" class="w-full border p-2 rounded" required>
                <option value="">Seleccione...</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['IdProducto'] ?>"
                        <?= (isset($producto) && $producto == $p['IdProducto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['Nombre_producto']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Cantidad</span>
            <input type="number" name="cantidad" min="1" value="<?= isset($cantidad) ? intval($cantidad) : '' ?>"
                   class="w-full border p-2 rounded" required>
        </label>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Registrar Salida
            </button>

            <a href="salidas.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</a>
        </div>

        <?php if (isset($inv)): ?>
            <p class="mt-4 text-sm text-gray-600">Stock actual: <strong><?= intval($inv['Inventario_actual']) ?></strong></p>
        <?php endif; ?>
    </form>
</main>

</body>
</html>
