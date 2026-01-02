<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Principal</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
.sidebar {
    transition: width 0.3s ease;
}
</style>
</head>

<body class="bg-gray-100 flex">

<!-- ▬▬▬▬▬▬▬▬▬ SIDEBAR ▬▬▬▬▬▬▬▬▬ -->
<aside id="sidebar" class="sidebar w-64 bg-gray-800 text-white h-screen p-5 fixed">

    <button id="toggleBtn" class="mb-4 bg-gray-700 px-3 py-1 rounded hover:bg-gray-600">
        ☰
    </button>

    <h2 id="sidebarTitle" class="text-2xl font-bold mb-6">STAR SYSTEM</h2>

    <nav id="menuOptions" class="space-y-3">

    <a href="dashboard.php" class="block p-2 bg-gray-700 rounded">🏠 Dashboard</a>

    <?php if (tienePermiso("Productos")) : ?>
        <a href="productos.php" class="block p-2 hover:bg-gray-700 rounded">📦 Productos</a>
    <?php endif; ?>

    <?php if (tienePermiso("Ingresos")) : ?>
        <a href="ingresos.php" class="block p-2 hover:bg-gray-700 rounded">📥 Ingresos</a>
    <?php endif; ?>

    <?php if (tienePermiso("Salidas")) : ?>
        <a href="salidas.php" class="block p-2 hover:bg-gray-700 rounded">📤 Salidas</a>
    <?php endif; ?>

    <?php if (tienePermiso("Usuarios")) : ?>
        <a href="usuarios.php" class="block p-2 hover:bg-gray-700 rounded">👥 Usuarios</a>
    <?php endif; ?>

    <?php if (tienePermiso("RolesPermisos")) : ?>
        <a href="roles_permisos.php" class="block p-2 hover:bg-gray-700 rounded">🔐 Roles & Permisos</a>
    <?php endif; ?>

</nav>


    <div class="absolute bottom-5 left-5">
        <a href="../auth/logout.php" class="bg-red-500 px-3 py-1 rounded text-white">Cerrar sesión</a>
    </div>
</aside>

<!-- ▬▬▬▬▬▬▬▬▬ CONTENIDO ▬▬▬▬▬▬▬▬▬ -->
<main id="contenido" class="ml-64 p-10 w-full transition-all duration-300">

    <h1 class="text-3xl font-bold mb-2">Bienvenido, <?= $_SESSION['Nombre'] ?></h1>
    <p class="text-gray-600 mb-8">Rol: <b><?= $_SESSION['Rol'] ?></b></p>

</main>

<script>
// ───────────────────────────────
// OCULTAR / MOSTRAR SIDEBAR
// ───────────────────────────────
const sidebar = document.getElementById("sidebar");
const contenido = document.getElementById("contenido");
const btn = document.getElementById("toggleBtn");
const title = document.getElementById("sidebarTitle");
const menu = document.getElementById("menuOptions");

btn.addEventListener("click", () => {

    if (sidebar.classList.contains("w-64")) {
        sidebar.classList.remove("w-64");
        sidebar.classList.add("w-16");

        contenido.classList.remove("ml-64");
        contenido.classList.add("ml-16");

        title.style.display = "none";
        menu.querySelectorAll("a").forEach(a => {
            a.textContent = a.textContent.substring(0, 2);
        });

    } else {
        sidebar.classList.remove("w-16");
        sidebar.classList.add("w-64");

        contenido.classList.remove("ml-16");
        contenido.classList.add("ml-64");

        title.style.display = "block";

        menu.innerHTML = `
            <a href="dashboard.php" class="block p-2 bg-gray-700 rounded">🏠 Dashboard</a>
            <a href="productos.php" class="block p-2 hover:bg-gray-700 rounded">📦 Productos</a>
            <a href="ingresos.php" class="block p-2 hover:bg-gray-700 rounded">📥 Ingresos</a>
            <a href="salidas.php" class="block p-2 hover:bg-gray-700 rounded">📤 Salidas</a>
            <?= $_SESSION['Rol'] === "Administrador" ? '<a href="usuarios.php" class="block p-2 hover:bg-gray-700 rounded">👥 Usuarios</a>' : '' ?>
            <a href="roles_permisos.php" class="block p-2 hover:bg-gray-700 rounded">🔐 Roles & Permisos</a>
        `;
    }
});
</script>

</body>
</html>
