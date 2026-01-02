<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


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

$totSub = $totIva = $totTotal = 0;
foreach ($ventas as $v) {
    $totSub += $v['subtotal'];
    $totIva += $v['iva'];
    $totTotal += $v['total'];
}

/* HTML DEL PDF */
$html = '
<style>
body { font-family: DejaVu Sans; font-size: 12px; }
h1 { text-align:center; }
table { width:100%; border-collapse: collapse; }
th, td { border:1px solid #000; padding:6px; }
th { background:#eee; }
.totales { margin-top:15px; text-align:right; }
</style>

<h1>Reporte de Ventas</h1>
<p><b>Desde:</b> '.$desde.' &nbsp;&nbsp; <b>Hasta:</b> '.$hasta.'</p>

<table>
<thead>
<tr>
<th>ID</th>
<th>Fecha</th>
<th>Vendedor</th>
<th>Subtotal</th>
<th>IVA</th>
<th>Total</th>
</tr>
</thead>
<tbody>';

foreach ($ventas as $v) {
    $html .= '
    <tr>
        <td>'.$v['idVenta'].'</td>
        <td>'.date('d/m/Y', strtotime($v['fecha'])).'</td>
        <td>'.$v['vendedor'].'</td>
        <td>$'.number_format($v['subtotal'],0,',','.').'</td>
        <td>$'.number_format($v['iva'],0,',','.').'</td>
        <td>$'.number_format($v['total'],0,',','.').'</td>
    </tr>';
}

$html .= '
</tbody>
</table>

<div class="totales">
<p><b>Subtotal:</b> $'.number_format($totSub,0,',','.').'</p>
<p><b>IVA:</b> $'.number_format($totIva,0,',','.').'</p>
<p style="font-size:16px"><b>TOTAL:</b> $'.number_format($totTotal,0,',','.').'</p>
</div>
';

/* GENERAR PDF */
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_ventas.pdf", ["Attachment" => false]);
exit;
