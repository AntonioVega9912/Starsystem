<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

if (!tienePermiso("Ventas")) {
    header("Location: dashboard.php");
    exit();
}

if (empty($_POST['data'])) {
    die("No se recibieron datos de la venta.");
}

$detalle = json_decode($_POST['data'], true);
if (!$detalle || count($detalle) === 0) {
    die("La venta no tiene productos.");
}

try {
    $pdo->beginTransaction();

    /* ===============================
       1️⃣ VALIDAR STOCK
    =============================== */
    $stmtCheck = $pdo->prepare("
        SELECT stock 
        FROM productos 
        WHERE idProducto = :id 
        FOR UPDATE
    ");

    foreach ($detalle as $item) {
        $stmtCheck->execute(['id' => $item['idProducto']]);
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$row || $row['stock'] < $item['cantidad']) {
            throw new Exception("Stock insuficiente para el producto ID {$item['idProducto']}");
        }
    }

    /* ===============================
       2️⃣ CALCULAR TOTALES
    =============================== */
    $subtotal = 0;
    foreach ($detalle as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
    }

    $iva   = round($subtotal * 0.19, 2);
    $total = round($subtotal + $iva, 2);

    /* ===============================
       3️⃣ INSERTAR VENTA
    =============================== */
    $stmtVenta = $pdo->prepare("
        INSERT INTO ventas (fecha, idUsuario, subtotal, iva, total)
        VALUES (NOW(), :user, :sub, :iva, :tot)
    ");

    $stmtVenta->execute([
        'user' => $_SESSION['IdUsuario'],
        'sub'  => $subtotal,
        'iva'  => $iva,
        'tot'  => $total
    ]);

    $idVenta = $pdo->lastInsertId();

    /* ===============================
       4️⃣ PREPARAR STATEMENTS
    =============================== */
    $stmtDetalle = $pdo->prepare("
        INSERT INTO ventas_detalle (idVenta, idProducto, cantidad, precio, subtotal)
        VALUES (:v, :p, :c, :pr, :s)
    ");

    $stmtUpdateProducto = $pdo->prepare("
        UPDATE productos 
        SET stock = stock - :c 
        WHERE idProducto = :p
    ");

    $stmtInvSelect = $pdo->prepare("
        SELECT IdInventario, Inventario_actual 
        FROM inventario 
        WHERE IdProducto = :p 
        FOR UPDATE
    ");

    $stmtInvUpdate = $pdo->prepare("
        UPDATE inventario 
        SET Inventario_actual = :new 
        WHERE IdInventario = :idInv
    ");

    $stmtInvInsert = $pdo->prepare("
        INSERT INTO inventario (IdProducto, Inventario_actual, Inventario_minimo)
        VALUES (:p, :qty, 0)
    ");

    $stmtMov = $pdo->prepare("
        INSERT INTO movimientos (IdProducto, IdUsuario, Tipo_movimiento, Cantidad)
        VALUES (:p, :user, 'SALIDA', :c)
    ");

    /* ===============================
       5️⃣ DETALLE + STOCK + MOVIMIENTO
    =============================== */
    foreach ($detalle as $item) {

        $idProd   = (int)$item['idProducto'];
        $cantidad = (int)$item['cantidad'];
        $precio   = (float)$item['precio'];
        $subtotalLinea = round($precio * $cantidad, 2);

        // Detalle
        $stmtDetalle->execute([
            'v'  => $idVenta,
            'p'  => $idProd,
            'c'  => $cantidad,
            'pr' => $precio,
            's'  => $subtotalLinea
        ]);

        // Producto
        $stmtUpdateProducto->execute([
            'c' => $cantidad,
            'p' => $idProd
        ]);

        // Inventario
        $stmtInvSelect->execute(['p' => $idProd]);
        $inv = $stmtInvSelect->fetch(PDO::FETCH_ASSOC);

        if ($inv) {
            $stmtInvUpdate->execute([
                'new'   => max(0, $inv['Inventario_actual'] - $cantidad),
                'idInv' => $inv['IdInventario']
            ]);
        } else {
            $stmtInvInsert->execute([
                'p'   => $idProd,
                'qty' => 0
            ]);
        }

        // Movimiento
        $stmtMov->execute([
            'p'    => $idProd,
            'user' => $_SESSION['IdUsuario'],
            'c'    => $cantidad
        ]);
    }

    $pdo->commit();

    header("Location: ventas_ver.php?id=" . $idVenta);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error al guardar la venta: " . $e->getMessage());
}
