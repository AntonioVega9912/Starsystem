<?php
if (!isset($_SESSION)) session_start();
require_once "../auth/seguridad.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<script src="https://cdn.tailwindcss.com"></script>
<title><?= $titulo ?? "STAR SYSTEM" ?></title>

<style>
    .sidebar-expanded { width: 250px; }
    .sidebar-collapsed { width: 70px; }
</style>

</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<aside id="sidebar" class="sidebar-expanded bg-gray-800 text-white h-screen p-5 transition-all duration-300 fixed">

    <!-- BOTÓN DE COLAPSAR -->
    <button 
        onclick="toggleSidebar()" 
        class="bg-gray-700 p-2 rounded mb-5 w-full text-left">
        ☰
    </button>

    <nav class="space-y-3">
        <a href="../dashboard/dashboard.php" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">🏠</span>
            <span class="text">Dashboard</span>
        </a>

        <a href="#" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">📦</span>
            <span class="text">Productos</span>
        </a>

        <a href="#" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">📥</span>
            <span class="text">Ingresos</span>
        </a>

        <a href="#" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">📤</span>
            <span class="text">Salidas</span>
        </a>

        <a href="#" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">👥</span>
            <span class="text">Usuarios</span>
        </a>

        <a href="#" class="flex items-center gap-2 p-2 hover:bg-gray-700 rounded">
            <span class="icon">🔐</span>
            <span class="text">Roles</span>
        </a>
    </nav>

    <div class="absolute bottom-5 left-5">
        <a href="../auth/logout.php" class="bg-red-500 px-3 py-1 rounded text-white">Salir</a>
    </div>
</aside>

<!-- CONTENIDO -->
<main id="content" class="ml-64 p-10 w-full transition-all duration-300">
    <?= $contenido ?? "" ?>
</main>


<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");

    sidebar.classList.toggle("sidebar-expanded");
    sidebar.classList.toggle("sidebar-collapsed");

    // Ocultar textos cuando está colapsado
    document.querySelectorAll("#sidebar .text").forEach(t => {
        t.style.display = t.style.display === "none" ? "inline" : "none";
    });

    // Ajustar contenido
    if (sidebar.classList.contains("sidebar-collapsed")) {
        content.classList.remove("ml-64");
        content.classList.add("ml-20");
    } else {
        content.classList.remove("ml-20");
        content.classList.add("ml-64");
    }
}
</script>

</body>
</html>
