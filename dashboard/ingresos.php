<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php";


$msg = "";
$error = "";

// Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idProducto = $_POST['producto'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 0);

    // Verificar si existe inventario del producto
$invStmt = $pdo->prepare("SELECT Inventario_actual FROM inventario WHERE IdProducto = :prod");
$invStmt->execute(['prod' => $idProducto]);
$inv = $invStmt->fetch(PDO::FETCH_ASSOC);

// Si no existe inventario, lo creamos automáticamente
if (!$inv) {
    $crear = $pdo->prepare("
        INSERT INTO inventario (IdProducto, Inventario_actual, Inventario_minimo)
        VALUES(:prod, :cant, 0)
    ");
    $crear->execute([
        'prod' => $idProducto,
        'cant' => $cantidad
    ]);
} else {
    // Si sí existe, lo actualizamos normalmente
    $updateInv = $pdo->prepare("
        UPDATE inventario
        SET Inventario_actual = Inventario_actual + :cant
        WHERE IdProducto = :prod
    ");
    $updateInv->execute([
        'cant' => $cantidad,
        'prod' => $idProducto
    ]);
}


    // Seguridad: prevenir error
    $idUsuario = $_SESSION['idUsuario'] ?? 1;

    if ($idProducto && $cantidad > 0) {

        // Registrar movimiento
        $insertMovimiento = $pdo->prepare("
            INSERT INTO movimientos(IdProducto, IdUsuario, Tipo_movimiento, Cantidad)
            VALUES(:prod, :user, 'INGRESO', :cant)
        ");
        $insertMovimiento->execute([
            'prod' => $idProducto,
            'user' => $idUsuario,
            'cant' => $cantidad
        ]);

        // Actualizar inventario (tabla inventario)
        $updateInv = $pdo->prepare("
            UPDATE inventario
            SET Inventario_actual = Inventario_actual + :cant
            WHERE IdProducto = :prod
        ");
        $updateInv->execute([
            'cant' => $cantidad,
            'prod' => $idProducto
        ]);

        $msg = "Ingreso registrado correctamente.";
    } else {
        $error = "Debe seleccionar un producto y cantidad válida.";
    }
}

// Consultar productos
$productos = $pdo->query("
    SELECT IdProducto, Nombre_producto 
    FROM productos
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ingresos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main id="contenido" class="ml-64 p-10 w-full">
    <h1 class="text-3xl font-bold mb-5">Registro de Ingresos</h1>

    <?php if ($msg): ?>
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
        <?= $msg ?>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <?= $error ?>
    </div>
    <?php endif; ?>

    <form action="" method="POST" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-xl">

        <label class="block mb-3">
            <span class="font-semibold">Producto</span>
            <select name="producto" class="w-full border p-2 rounded" required>
                <option value="">Seleccione...</option>
                <?php foreach($productos as $p): ?>
                    <option value="<?= $p['IdProducto'] ?>"><?= $p['Nombre_producto'] ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Cantidad</span>
            <input type="number" name="cantidad" min="1" class="w-full border p-2 rounded" required>
        </label>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Registrar Ingreso
        </button>

        <a href="dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Cancelar
        </a>

    </form>
</main>

</body>
</html>
