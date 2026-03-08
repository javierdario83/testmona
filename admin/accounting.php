<?php
require_once __DIR__ . '/_top.php';

$history = db()->query('SELECT correlative, client_name, package_code, price, session_date FROM readings ORDER BY session_date DESC, id DESC')->fetchAll();
$monthly = db()->query('SELECT DATE_FORMAT(session_date, "%Y-%m") AS month_key, SUM(price) total FROM readings GROUP BY month_key ORDER BY month_key DESC')->fetchAll();
$weekly = db()->query('SELECT YEARWEEK(session_date, 1) AS week_key, SUM(price) total FROM readings GROUP BY week_key ORDER BY week_key DESC')->fetchAll();
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contabilidad</title><link rel="stylesheet" href="/assets/css/styles.css"></head>
<body><div class="container">
<div class="topbar"><h1>Contabilidad</h1><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/dashboard.php">Volver</a></div>
<div class="card table-wrap"><h3>Registro histórico</h3><table><thead><tr><th>Fecha</th><th>Correlativo</th><th>Cliente</th><th>Paquete</th><th>Ingreso</th></tr></thead><tbody>
<?php foreach ($history as $h): ?>
<tr><td><?= e($h['session_date']) ?></td><td><?= e($h['correlative']) ?></td><td><?= e($h['client_name']) ?></td><td><?= e($h['package_code']) ?></td><td>$<?= number_format((float)$h['price'],2) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div>

<div class="grid grid-2">
<div class="card table-wrap"><h3>Ingresos por mes</h3><table><thead><tr><th>Mes</th><th>Total</th></tr></thead><tbody><?php foreach($monthly as $m): ?><tr><td><?= e($m['month_key']) ?></td><td>$<?= number_format((float)$m['total'],2) ?></td></tr><?php endforeach; ?></tbody></table></div>
<div class="card table-wrap"><h3>Ingresos por semana</h3><table><thead><tr><th>Semana ISO</th><th>Total</th></tr></thead><tbody><?php foreach($weekly as $w): ?><tr><td><?= e($w['week_key']) ?></td><td>$<?= number_format((float)$w['total'],2) ?></td></tr><?php endforeach; ?></tbody></table></div>
</div>
</div></body></html>
