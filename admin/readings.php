<?php
require_once __DIR__ . '/_top.php';

$stmt = db()->query('SELECT r.correlative, r.client_name, p.label AS package_label FROM readings r JOIN packages p ON p.code=r.package_code ORDER BY r.id DESC');
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Lecturas</title><link rel="stylesheet" href="/assets/css/styles.css"></head>
<body><div class="container">
<div class="topbar"><h1>Lecturas realizadas</h1><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/dashboard.php">Volver</a></div>
<div class="card table-wrap">
<table>
<thead><tr><th>Correlativo</th><th>Cliente</th><th>Paquete</th><th></th></tr></thead>
<tbody>
<?php foreach ($rows as $r): ?>
<tr>
<td><?= e($r['correlative']) ?></td><td><?= e($r['client_name']) ?></td><td><?= e($r['package_label']) ?></td>
<td><a class="btn" style="width:auto;padding:.35rem .7rem;" href="/public/client_view.php?code=<?= urlencode($r['correlative']) ?>">Ver</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div></div></body></html>
