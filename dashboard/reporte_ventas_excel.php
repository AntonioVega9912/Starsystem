<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";
require_once "../vendor/phpspreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!tienePermiso("Ventas")) {
    exit("Acceso denegado");
}

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT 
        v.idVenta,
        v.fecha,
        CONCAT(u.Nombre_usuario,' ',u.Apellido_usuario) AS vendedor,
        v.subtotal,
        v.iva,
        v.total
    FROM ventas v
    INNER JOIN usuarios u ON u.IdUsuario = v.idUsuario
    WHERE DATE(v.fecha) BETWEEN :desde AND :hasta
    ORDER BY v.fecha DESC
");
$stmt->execute([
    'desde' => $desde,
    'hasta' => $hasta
]);

$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* CREAR EXCEL */
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Ventas");

/* ENCABEZADOS */
$headers = [
    'A1' => 'ID Venta',
    'B1' => 'Fecha',
    'C1' => 'Vendedor',
    'D1' => 'Subtotal',
    'E1' => 'IVA',
    'F1' => 'Total'
];

foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
    $sheet->getStyle($cell)->getFont()->setBold(true);
}

/* DATOS */
$fila = 2;
$totSub = $totIva = $totTotal = 0;

foreach ($ventas as $v) {
    $sheet->setCellValue("A{$fila}", $v['idVenta']);
    $sheet->setCellValue("B{$fila}", date('d/m/Y', strtotime($v['fecha'])));
    $sheet->setCellValue("C{$fila}", $v['vendedor']);
    $sheet->setCellValue("D{$fila}", $v['subtotal']);
    $sheet->setCellValue("E{$fila}", $v['iva']);
    $sheet->setCellValue("F{$fila}", $v['total']);

    $totSub += $v['subtotal'];
    $totIva += $v['iva'];
    $totTotal += $v['total'];

    $fila++;
}

/* TOTALES */
$sheet->setCellValue("C{$fila}", "TOTALES");
$sheet->setCellValue("D{$fila}", $totSub);
$sheet->setCellValue("E{$fila}", $totIva);
$sheet->setCellValue("F{$fila}", $totTotal);

$sheet->getStyle("C{$fila}:F{$fila}")->getFont()->setBold(true);

/* AUTO SIZE */
foreach (range('A','F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

/* DESCARGA */
$filename = "reporte_ventas_{$desde}_{$hasta}.xlsx";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
