<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

/* 1. Verificar permiso */
if (!tienePermiso("Usuarios")) {
    header("Location: dashboard.php");
    exit();
}

/* 2. Validar ID */
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: usuarios.php");
    exit();
}

/* 3. Evitar que un usuario se desactive a sí mismo */
if ($id == $_SESSION['IdUsuario']) {
    header("Location: usuarios.php");
    exit();
}

/* 4. Obtener estado actual */
$stmt = $pdo->prepare("SELECT estado FROM usuarios WHERE IdUsuario = :id");
$stmt->execute(['id' => $id]);
$estado = $stmt->fetchColumn();

/* 5. Si el usuario no existe */
if ($estado === false) {
    header("Location: usuarios.php");
    exit();
}

/* 6. Cambiar estado */
$nuevoEstado = $estado ? 0 : 1;

$update = $pdo->prepare("
    UPDATE usuarios 
    SET estado = :estado 
    WHERE IdUsuario = :id
");

$update->execute([
    'estado' => $nuevoEstado,
    'id' => $id
]);

/* 7. Volver al listado */
header("Location: usuarios.php");
exit();
