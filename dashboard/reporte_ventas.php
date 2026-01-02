<?php
require_once "../auth/seguridad.php";
require_once "../auth/permiso.php";
require_once "../config/configdb.php";

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

if (!tienePermiso("Ventas")) {
    exit("No autorizado");
}

$sql = "
SELECT v.idVenta, v.fecha, v.total,
       u.Nombre_usuario, u.Apellido_usuario
FROM ventas v
JOIN usuarios u ON u.IdUsuario = v.idUsuario
ORDER BY v.fecha DESC
";

$ventas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$html = '
<h2>Reporte de Ventas</h2>
<table border="1" width="100%" cellpadding="5">
<tr>
<th>ID</th>
<th>Fecha</th>
<th>Usuario</th>
<th>Total</th>
</tr>';

foreach ($ventas as $v) {
    $html .= "
    <tr>
        <td>{$v['idVenta']}</td>
        <td>{$v['fecha']}</td>
        <td>{$v['Nombre_usuario']} {$v['Apellido_usuario']}</td>
        <td>$ " . number_format($v['total'], 0, ',', '.') . "</td>
    </tr>";
}

$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_ventas.pdf", ["Attachment" => true]);
exit;
