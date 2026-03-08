<?php
require_once __DIR__ . '/_top.php';
$admin = current_admin();
?>
<!doctype html>
<html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Panel</title><link rel="stylesheet" href="/assets/css/styles.css"></head>
<body><div class="container">
<div class="topbar"><h1>Hola, <?= e($admin['display_name']) ?></h1><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/logout.php">Salir</a></div>
<div class="grid grid-2">
  <a class="card btn" href="/admin/readings.php">Lecturas realizadas</a>
  <a class="card btn" href="/admin/questions.php">Preguntas disponibles</a>
  <a class="card btn" href="/admin/new_reading.php">Registrar nueva lectura</a>
  <a class="card btn" href="/admin/accounting.php">Contabilidad</a>
</div>
</div></body></html>
