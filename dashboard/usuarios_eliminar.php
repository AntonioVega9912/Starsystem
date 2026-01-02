<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso('usuarios', 'eliminar')) {
    header("Location: sin_permiso.php");
    exit;
}

$id = $_GET["id"];

$stmt = $pdo->prepare("DELETE FROM usuario WHERE idUsuario = :id");
$stmt->execute(['id' => $id]);

header("Location: usuarios.php");
exit;
