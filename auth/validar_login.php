<?php
session_start();
require_once "../config/configdb.php";

// 1. Capturar datos del formulario
$usuario = $_POST['usuario'] ?? '';
$passwordIngresada = $_POST['password'] ?? '';

if (empty($usuario) || empty($passwordIngresada)) {
    $_SESSION['error'] = "Debe ingresar usuario y contraseña.";
    header("Location: ../login.php");
    exit();
}

// 2. Buscar usuario
$sql = "SELECT * FROM usuarios WHERE usuario = :u AND estado = 1 LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['u' => $usuario]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: ../login.php");
    exit();
}

$hashGuardado = $data['Contraseña'];

// 3. Validar contraseña
if (!password_verify($passwordIngresada, $hashGuardado)) {
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: ../login.php");
    exit();
}

// 4. Obtener rol del usuario
$sqlRol = "
    SELECT r.Idrol, r.Nombre_rol 
    FROM usuario_rol ur 
    INNER JOIN rol r ON r.Idrol = ur.idRol
    WHERE ur.idUsuario = :id
    LIMIT 1
";

$stmtRol = $pdo->prepare($sqlRol);
$stmtRol->execute(['id' => $data['IdUsuario']]);
$rolData = $stmtRol->fetch(PDO::FETCH_ASSOC);

if (!$rolData) {
    $_SESSION['error'] = "El usuario no tiene un rol asignado.";
    header("Location: ../login.php");
    exit();
}

$idRol = $rolData['Idrol'];
$rolNombre = $rolData['Nombre_rol'];

// 5. Cargar permisos del rol (IMPORTANTE)
$sqlPermisos = "
    SELECT m.nombre_modulo
    FROM permisos p
    INNER JOIN modulo m ON m.idModulo = p.idModulo
    WHERE p.IdRol = :idRol
";

$stmtPerm = $pdo->prepare($sqlPermisos);
$stmtPerm->execute(['idRol' => $idRol]);
$permisos = $stmtPerm->fetchAll(PDO::FETCH_COLUMN);

// Si el rol no tiene permisos asignados
if (!$permisos) {
    $permisos = [];
}

// 6. Crear sesión segura
$_SESSION['autenticado'] = true;
$_SESSION['IdUsuario'] = $data['IdUsuario'];
$_SESSION['Nombre'] = $data['Nombre_usuario'] . " " . $data['Apellido_usuario'];
$_SESSION['Rol'] = $rolNombre;
$_SESSION['IdRol'] = $idRol;
$_SESSION['Permisos'] = $permisos; // ← SE GUARDA AQUÍ


// 7. Redirigir al dashboard
header("Location: ../dashboard/dashboard.php");
exit();


