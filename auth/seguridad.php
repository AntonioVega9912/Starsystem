<?php
session_start();

// Si no está autenticado, no puede estar aquí
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>
