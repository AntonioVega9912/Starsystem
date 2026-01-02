<?php
require_once "../auth/seguridad.php";
require_once "../config/configdb.php";

// Obtener roles
$roles = $pdo->query("SELECT Idrol, Nombre_rol FROM rol")->fetchAll(PDO::FETCH_ASSOC);

// Obtener módulos
$modulos = $pdo->query("SELECT idModulo, nombre_modulo FROM modulo")->fetchAll(PDO::FETCH_ASSOC);

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idRol = $_POST['rol'] ?? 0;
    $modPermisos = $_POST['modulos'] ?? [];

    if ($idRol > 0) {

        // Borrar permisos actuales
        $delete = $pdo->prepare("DELETE FROM permisos WHERE IdRol = :r");
        $delete->execute(['r' => $idRol]);

        // Insertar nuevos permisos
        $insert = $pdo->prepare("INSERT INTO permisos (IdRol, idModulo) VALUES (:r, :m)");

        foreach ($modPermisos as $mod) {
            $insert->execute(['r' => $idRol, 'm' => $mod]);
        }

        $msg = "Permisos actualizados correctamente.";
    }
}

// Recargar permisos del rol
$rolSel = $_GET['rol'] ?? 0;
$rolPermisos = [];

if ($rolSel) {
    $sqlPerm = $pdo->prepare("SELECT idModulo FROM permisos WHERE IdRol = :r");
    $sqlPerm->execute(['r' => $rolSel]);
    $rolPermisos = array_column($sqlPerm->fetchAll(PDO::FETCH_ASSOC), 'idModulo');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Roles y Permisos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<?php include "sidebar.php"; ?>

<main class="ml-64 p-10 w-full">

    <h1 class="text-3xl font-bold mb-6">Administrar Roles y Permisos</h1>

    <?php if (!empty($msg)): ?>
        <div class="bg-green-200 text-green-700 p-3 rounded mb-4"><?= $msg ?></div>
    <?php endif; ?>

    <form method="GET" class="mb-6">
        <label class="font-semibold">Seleccionar Rol:</label>
        <select name="rol" class="border p-2 ml-2" onchange="this.form.submit()">
            <option value="">Seleccione rol...</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['Idrol'] ?>" <?= ($rolSel == $r['Idrol']) ? 'selected' : '' ?>>
                    <?= $r['Nombre_rol'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($rolSel): ?>
    <form method="POST">
        <input type="hidden" name="rol" value="<?= $rolSel ?>">

        <div class="bg-white p-6 rounded shadow max-w-lg">
            <h2 class="text-xl font-semibold mb-4">Permisos del Rol</h2>

            <?php foreach ($modulos as $m): ?>
                <label class="flex items-center mb-2">
                    <input type="checkbox" 
                           name="modulos[]" 
                           value="<?= $m['idModulo'] ?>"
                        <?= in_array($m['idModulo'], $rolPermisos) ? 'checked' : '' ?>>
                    <span class="ml-2"><?= $m['nombre_modulo'] ?></span>
                </label>
            <?php endforeach; ?>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">
                Guardar Permisos
            </button>
            <a href="roles_permisos.php" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancelar</a>
        </div>
    </form>
    <?php endif; ?>

</main>
</body>
</html>
