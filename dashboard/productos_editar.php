<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php"; // $pdo disponible
include "sidebar.php";

// Validar que venga un ID
if (!isset($_GET['id'])) {
    die("ID de producto no especificado.");
}

$id = intval($_GET['id']);

// Consultar producto
$sql = "SELECT * FROM productos WHERE idProducto = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Producto</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<?php include "sidebar.php"; ?>

<!-- CONTENIDO PRINCIPAL -->
<main class="ml-64 p-10 w-full">
    <h1 class="text-3xl font-bold mb-5">Editar Producto</h1>

<!--Mensaje error -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicado'): ?>
<div class="bg-red-600 text-white p-2 mb-4 rounded">
    El código ingresado ya existe, pruebe otro.
</div>
<?php endif; ?>


    <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicado'): ?>
<div class="bg-red-500 text-white p-2 mb-4 rounded">
    El código ingresado ya existe, pruebe otro.
</div>
<?php endif; ?>


    <form action="productos_actualizar.php" method="POST" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-xl">
        
        <!-- ID oculto -->
        <input type="hidden" name="idProducto" value="<?= $producto['idProducto'] ?>">

        <label class="block mb-3">
            <span class="font-semibold">Código del Producto</span>
            <input type="text" name="codigo" value="<?= htmlspecialchars($producto['Codigo_producto']) ?>" 
                   class="w-full border p-2 rounded" required>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Nombre del Producto</span>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['Nombre_producto']) ?>" 
                   class="w-full border p-2 rounded" required>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Descripción</span>
            <textarea name="descripcion" class="w-full border p-2 rounded" rows="3"><?= htmlspecialchars($producto['Descripcion']) ?></textarea>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Precio</span>
            <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($producto['Precio']) ?>"
                   class="w-full border p-2 rounded" required>
        </label>

       
        <!-- BOTONES: Actualizar y Cancelar -->
        <div class="flex space-x-3 mt-4">
            <!-- Botón Actualizar -->
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Actualizar Producto
            </button>

            <!-- Botón Cancelar -->
            <a href="productos.php" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
               Cancelar
            </a>
        </div>
    </form>
</main>

</body>
</html>
