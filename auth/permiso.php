<?php
function tienePermiso($modulo)
{
    // Si el rol es Admin → acceso total
    if (isset($_SESSION['Rol']) && $_SESSION['Rol'] === "Admin") {
        return true;
    }

    // Validar que existan permisos cargados
    if (!isset($_SESSION['Permisos']) || !is_array($_SESSION['Permisos'])) {
        return false;
    }

    // Verificar si el módulo está en la lista de permisos
    return in_array($modulo, $_SESSION['Permisos']);
}
