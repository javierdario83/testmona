<?php
require_once __DIR__ . '/_top.php';

$nextNumber = (int) db()->query('SELECT IFNULL(MAX(id),0)+1 FROM questions')->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['question_text'] ?? '');
    if ($text !== '') {
        $stmt = db()->prepare('INSERT INTO questions (question_text) VALUES (:q)');
        $stmt->execute(['q' => $text]);
        header('Location: /admin/questions.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nueva pregunta</title><link rel="stylesheet" href="/assets/css/styles.css"></head>
<body><div class="container" style="max-width:700px;">
<div class="card"><h1>Nueva pregunta número <?= $nextNumber ?></h1>
<form method="post">
<label>Pregunta</label>
<textarea name="question_text" rows="4" required></textarea><br><br>
<div class="inline"><button type="submit">Guardar</button><a class="btn" style="width:auto;padding:.7rem 1rem;" href="/admin/questions.php">Regresar al listado</a></div>
</form></div></div></body></html>
