<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php"; // ← ya trae $pdo


// Mensaje opcional (seguro)
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

// Obtener productos
try {
    $sql = "SELECT * FROM productos ORDER BY idProducto DESC";
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // En caso de error en DB, evitamos que rompa la vista
    $productos = [];
    $msg = "Error al cargar productos.";
}
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

    <h1 class="text-3xl font-bold mb-5">Productos</h1>

    <?php if (!empty($msg)): ?>
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="flex items-center gap-3 mb-4">
        <a href="productos_nuevo.php"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-block">
            + Nuevo Producto
        </a>

        <!-- opcional: botón cancelar vuelve al dashboard -->
        
    </div>

    <!-- BUSCADOR -->
    <input id="buscador"
           type="text"
           placeholder="Buscar producto por código, nombre o descripción..."
           class="border px-3 py-2 mb-4 rounded w-1/2 shadow"
           autocomplete="off">

    <!-- TABLA -->
    <div id="tabla-productos" class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-xl shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 border text-left">Código</th>
                    <th class="py-2 px-4 border text-left">Nombre</th>
                    <th class="py-2 px-4 border text-left">Descripción</th>
                    <th class="py-2 px-4 border text-right">Precio</th>
                    <th class="py-2 px-4 border text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $row): ?>
                    <tr class="text-gray-700 border-b hover:bg-gray-50">
                        <td class="py-2 px-4"><?= htmlspecialchars($row['Codigo_producto']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($row['Nombre_producto']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($row['Descripcion']) ?></td>
                        <td class="py-2 px-4 text-right"><?= number_format($row['Precio'], 2) ?></td>
                        <td class="py-2 px-4 text-center">
                            <a href="productos_editar.php?id=<?= urlencode($row['idProducto']) ?>"
                               class="bg-yellow-500 text-white px-2 py-1 rounded inline-block mr-2">Editar</a>

                            <a href="productos_eliminar.php?id=<?= urlencode($row['idProducto']) ?>"
                               class="bg-red-500 text-white px-2 py-1 rounded inline-block"
                               onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
// Debounce helper para evitar muchas peticiones mientras se escribe
function debounce(fn, delay) {
    let t;
    return function(...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), delay);
    };
}

const buscador = document.getElementById('buscador');
const tablaDiv = document.getElementById('tabla-productos');

const doSearch = debounce(() => {
    const texto = buscador.value.trim();

    fetch('productos_buscar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'texto=' + encodeURIComponent(texto)
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la búsqueda');
        return response.text();
    })
    .then(html => {
        tablaDiv.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        // opcional: mostrar feedback al usuario si falla la búsqueda
    });
}, 250); // 250ms de retardo

buscador.addEventListener('input', doSearch);
</script>

</body>
</html>
