<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

// Seguridad por permiso
if (!tienePermiso("Usuarios")) {
    header("Location: dashboard.php");
    exit();
}

// Obtener usuarios
$sql = "
SELECT 
    u.IdUsuario,
    u.Nombre_usuario,
    u.Apellido_usuario,
    u.usuario,
    u.estado,
    r.Nombre_rol
FROM usuarios u
LEFT JOIN usuario_rol ur ON ur.idUsuario = u.IdUsuario
LEFT JOIN rol r ON r.Idrol = ur.idRol
";
$usuarios = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

<h1 class="text-3xl font-bold mb-6">Administración de Usuarios</h1>

<a href="usuarios_crear.php" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700">
    ➕ Nuevo Usuario
</a>

<div class="bg-white rounded shadow mt-4 overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-200">
<tr>
    <th class="p-2">Nombre</th>
    <th class="p-2">Usuario</th>
    <th class="p-2">Rol</th>
    <th class="p-2">Estado</th>
    <th class="p-2">Acciones</th>
</tr>
</thead>
<tbody>

<?php foreach ($usuarios as $u): ?>
<tr class="border-b">
    <td class="p-2"><?= $u['Nombre_usuario'].' '.$u['Apellido_usuario'] ?></td>
    <td class="p-2"><?= $u['usuario'] ?></td>
    <td class="p-2"><?= $u['Nombre_rol'] ?? 'Sin rol' ?></td>

    <!-- BOTÓN ACTIVO / INACTIVO -->
    <td class="p-2">

        <?php if ($u['estado']): ?>
            <a href="usuarios_estado.php?id=<?= $u['IdUsuario'] ?>"
               onclick="return confirm('¿Seguro deseas desactivar este usuario?')"
               class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">
               Activo
            </a>
        <?php else: ?>
            <a href="usuarios_estado.php?id=<?= $u['IdUsuario'] ?>"
               onclick="return confirm('¿Seguro deseas activar este usuario?')"
               class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600">
               Inactivo
            </a>
        <?php endif; ?>

    </td>

    <!-- BOTÓN EDITAR -->
    <td class="p-2">
        <a href="usuarios_editar.php?id=<?= $u['IdUsuario'] ?>"
           class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">
           Editar
        </a>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</main>
</body>
</html>
