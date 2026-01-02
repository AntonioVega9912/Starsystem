<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php"; // $pdo disponible

$error = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;

    if ($codigo && $nombre) {
        // Verificar si el código ya existe
        $check = $pdo->prepare("SELECT idProducto FROM productos WHERE Codigo_producto = :codigo LIMIT 1");
        $check->execute(['codigo' => $codigo]);
        
        if ($check->fetch()) {
            $error = "El código de producto ya existe. Usa otro código.";
        } else {
            // Insertar producto
            $sql = "INSERT INTO productos (Codigo_producto, Nombre_producto, Descripcion, Precio) 
                    VALUES (:codigo, :nombre, :descripcion, :precio)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'codigo' => $codigo,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio
            ]);

            header("Location: productos.php?msg=guardado");
            exit();
        }
    } else {
        $error = "Debe ingresar código y nombre del producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Producto</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<?php include "sidebar.php"; ?>

<!-- CONTENIDO -->
<main class="ml-64 p-10 w-full">
    <h1 class="text-3xl font-bold mb-5">Nuevo Producto</h1>

    <!-- Mensaje de error -->
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-xl">
        <label class="block mb-3">
            <span class="font-semibold">Código del Producto</span>
            <input type="text" name="codigo" value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>" 
                   class="w-full border p-2 rounded" required>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Nombre del Producto</span>
            <input type="text" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" 
                   class="w-full border p-2 rounded" required>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Descripción</span>
            <textarea name="descripcion" class="w-full border p-2 rounded" rows="3"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
        </label>

        <label class="block mb-3">
            <span class="font-semibold">Precio</span>
            <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>"
                   class="w-full border p-2 rounded" required>
        </label>

        <!-- BOTONES: Guardar y Cancelar -->
        <div class="flex gap-3 mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Guardar Producto
            </button>

            <a href="productos.php" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-flex items-center">
               Cancelar
            </a>
        </div>
    </form>
</main>

</body>
</html>
