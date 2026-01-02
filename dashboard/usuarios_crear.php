<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Usuarios")) {
    header("Location: dashboard.php");
    exit();
}

// Obtener roles
$roles = $pdo->query("SELECT Idrol, Nombre_rol FROM rol")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario  = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol      = $_POST['rol'];

    // Insertar usuario
    $sql = "INSERT INTO usuarios 
        (Nombre_usuario, Apellido_usuario, usuario, Contraseña, estado) 
        VALUES (:n, :a, :u, :p, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'n' => $nombre,
        'a' => $apellido,
        'u' => $usuario,
        'p' => $password
    ]);

    $idUsuario = $pdo->lastInsertId();

    // Asignar rol
    $sqlRol = "INSERT INTO usuario_rol (idUsuario, idRol) VALUES (:u, :r)";
    $stmtRol = $pdo->prepare($sqlRol);
    $stmtRol->execute([
        'u' => $idUsuario,
        'r' => $rol
    ]);

    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Usuario</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full max-w-xl">

<h1 class="text-2xl font-bold mb-6">Nuevo Usuario</h1>

<form method="POST" class="bg-white p-6 rounded shadow">

<label>Nombre</label>
<input type="text" name="nombre" required class="w-full border p-2 mb-3">

<label>Apellido</label>
<input type="text" name="apellido" required class="w-full border p-2 mb-3">

<label>Usuario</label>
<input type="text" name="usuario" required class="w-full border p-2 mb-3">

<label>Contraseña</label>
<input type="password" name="password" required class="w-full border p-2 mb-3">

<label>Rol</label>
<select name="rol" required class="w-full border p-2 mb-4">
<?php foreach ($roles as $r): ?>
    <option value="<?= $r['Idrol'] ?>"><?= $r['Nombre_rol'] ?></option>
<?php endforeach; ?>
</select>

<div class="flex gap-2">
<button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
<a href="usuarios.php" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>
</div>

</form>
</main>
</body>
</html>
