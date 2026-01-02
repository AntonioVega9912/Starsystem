<?php
require_once "../auth/seguridad.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Producto</title>
<script src="https://cdn.tailwindcss.com"></script>

<?php
if (!empty($_SESSION["error_producto"])) {
    echo '<div class="bg-red-100 text-red-700 p-3 rounded mb-4">' .
         $_SESSION["error_producto"] .
         '</div>';
    unset($_SESSION["error_producto"]);
}

if (!empty($_SESSION["success_producto"])) {
    echo '<div class="bg-green-100 text-green-700 p-3 rounded mb-4">' .
         $_SESSION["success_producto"] .
         '</div>';
    unset($_SESSION["success_producto"]);
}
?>


<style>
.sidebar { transition: width 0.3s ease; }
</style>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<?php include "sidebar.php"; ?>

<!-- CONTENIDO -->
<main id="contenido" class="ml-64 p-10 w-full transition-all duration-300">

    <h1 class="text-3xl font-bold mb-6">➕ Nuevo Producto</h1>

    <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-2xl">

        <form action="productos_guardar.php" method="POST" class="space-y-4">

            <!-- Código del Producto -->
            <div>
                <label class="font-semibold">Código del Producto</label>
                <input type="text" name="Codigo_producto" required
                    class="w-full border p-2 rounded">
            </div>

            <!-- Nombre -->
            <div>
                <label class="font-semibold">Nombre del Producto</label>
                <input type="text" name="Nombre_producto" required
                    class="w-full border p-2 rounded">
            </div>

            <!-- Descripción -->
            <div>
                <label class="font-semibold">Descripción</label>
                <textarea name="Descripcion" rows="3" class="w-full border p-2 rounded"></textarea>
            </div>

            <!-- Precio -->
            <div>
                <label class="font-semibold">Precio</label>
                <input type="number" step="0.01" min="0" name="Precio" required
                    class="w-full border p-2 rounded">
            </div>

            <!-- Botones -->
            <div class="flex gap-4 mt-6">
                <a href="productos.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    ← Volver
                </a>

                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Guardar Producto
                </button>
            </div>

        </form>

    </div>

</main>

</body>
</html>
