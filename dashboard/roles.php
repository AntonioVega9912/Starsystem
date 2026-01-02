<?php

require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";

// Validar permiso
if (!tienePermiso('roles', 'ver')) {
    die("No tienes permiso para ver los roles.");
}

require_once "../config/configdb.php";

// Traer roles
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Roles</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main id="contenido" class="ml-64 p-10 w-full">
    <h1 class="text-3xl font-bold mb-5">Gestión de Roles</h1>

    <a href="roles_nuevo.php" 
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
       ➕ Crear Rol
    </a>

    <table class="mt-5 w-full bg-white shadow rounded">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Nombre del Rol</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($roles as $r): ?>
            <tr class="border-b">
                <td class="p-2"><?= $r['IdRol'] ?></td>
                <td class="p-2"><?= htmlspecialchars($r['NombreRol']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

</body>
</html>
