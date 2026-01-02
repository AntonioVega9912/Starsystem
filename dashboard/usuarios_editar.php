<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Usuarios")) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

$usuario = $pdo->prepare("
SELECT u.*, ur.idRol
FROM usuarios u
LEFT JOIN usuario_rol ur ON ur.idUsuario = u.IdUsuario
WHERE u.IdUsuario = :id
");
$usuario->execute(['id' => $id]);
$u = $usuario->fetch(PDO::FETCH_ASSOC);

$roles = $pdo->query("SELECT Idrol, Nombre_rol FROM rol")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $update = $pdo->prepare("
    UPDATE usuarios SET 
        Nombre_usuario = :n, 
        Apellido_usuario = :a 
    WHERE IdUsuario = :id
    ");
    $update->execute([
        'n' => $_POST['nombre'],
        'a' => $_POST['apellido'],
        'id' => $id
    ]);

    $pdo->prepare("DELETE FROM usuario_rol WHERE idUsuario = :id")
        ->execute(['id' => $id]);

    $pdo->prepare("INSERT INTO usuario_rol (idUsuario, idRol) VALUES (:u,:r)")
        ->execute([
            'u' => $id,
            'r' => $_POST['rol']
        ]);

    header("Location: usuarios.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Editar Usuario</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">
<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full max-w-xl">

<h1 class="text-2xl font-bold mb-6">Editar Usuario</h1>

<form method="POST" class="bg-white p-6 rounded shadow">

<input type="text" name="nombre" value="<?= $u['Nombre_usuario'] ?>" class="w-full border p-2 mb-3">
<input type="text" name="apellido" value="<?= $u['Apellido_usuario'] ?>" class="w-full border p-2 mb-3">

<select name="rol" class="w-full border p-2 mb-4">
<?php foreach ($roles as $r): ?>
<option value="<?= $r['Idrol'] ?>" <?= $r['Idrol']==$u['idRol']?'selected':'' ?>>
<?= $r['Nombre_rol'] ?>
</option>
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
