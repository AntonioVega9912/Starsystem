<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="w-full max-w-sm bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-4 text-center">Ingresar al Sistema</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-3 rounded">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="auth/validar_login.php" method="POST" class="space-y-4">

        <input type="text" name="usuario" placeholder="Usuario" required
        class="w-full border p-2 rounded">

        <input type="password" name="password" placeholder="Contraseña" required
        class="w-full border p-2 rounded">

        <button class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
            Ingresar
        </button>
    </form>
</div>
</body>
</html>
