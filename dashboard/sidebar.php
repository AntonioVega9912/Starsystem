<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
?>



<aside id="sidebar" class="sidebar w-64 bg-gray-800 text-white h-screen p-5 fixed transition-all duration-300">

    <!-- BOTÓN PARA CONTRAER -->
    <button id="btnToggle" 
        onclick="toggleSidebar()"
        class="absolute -right-3 top-5 bg-blue-600 text-white px-2 py-1 rounded-full">
        ☰
    </button>

    <h2 class="text-2xl font-bold mb-6 whitespace-nowrap">STAR SYSTEM</h2>

    <nav class="space-y-3">
        
        <?php if (tienePermiso('dashboard')): ?>
        <a href="dashboard.php" class="block p-2 hover:bg-gray-700 rounded">
            🏠 Dashboard
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('productos')): ?>
        <a href="productos.php" class="block p-2 hover:bg-gray-700 rounded">
            📦 Productos
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('ingresos')): ?>
        <a href="ingresos.php" class="block p-2 hover:bg-gray-700 rounded">
            📥 Ingresos
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('salidas')): ?>
        <a href="salidas.php" class="block p-2 hover:bg-gray-700 rounded">
            📤 Salidas
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('usuarios')): ?>
        <a href="usuarios.php" class="block p-2 hover:bg-gray-700 rounded">
            👥 Usuarios
        </a>
        <?php endif; ?>

        <?php if (tienePermiso("Ventas")) : ?>
        <a href="ventas_lista.php" class="block p-2 hover:bg-gray-700 rounded">🧾 Ventas</a>
        <?php endif; ?>


        <?php if (tienePermiso('roles_permisos')): ?>
        <a href="roles_permisos.php" class="block p-2 hover:bg-gray-700 rounded">
            🔐 Roles & Permisos
        </a>
        <?php endif; ?>

    </nav>

    <div class="absolute bottom-5 left-5">
        <a href="../auth/logout.php" class="bg-red-500 px-3 py-1 rounded text-white">Cerrar sesión</a>
    </div>

</aside>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const contenido = document.getElementById("contenido");

    sidebar.classList.toggle("w-64");
    sidebar.classList.toggle("w-16");

    contenido.classList.toggle("ml-64");
    contenido.classList.toggle("ml-16");
}
</script>
