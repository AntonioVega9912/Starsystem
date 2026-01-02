<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";

// Validar permiso
if (!tienePermiso('roles', 'crear')) {
    die("No tienes permiso para crear roles.");
}

require_once "../config/configdb.php";

$msg = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');

    if ($nombre == "") {
        $error = "Debe escribir un nombre de rol.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO rol (NombreRol) VALUES (:n)");
        $stmt->execute(['n' => $nombre]);

        header("Location: roles.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Rol</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main id="contenido" class="ml-64 p-10 w-full">

    <h1 class="text-3xl font-bold mb-5">Crear Nuevo Rol</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow max-w-xl">

        <label class="block mb-3">
            <span class="font-semibold">Nombre del rol</span>
            <input type="text" name="nombre" class="w-full border p-2 rounded">
        </label>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
        <a href="roles.php" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancelar</a>

    </form>
</main>

</body>
</html>
