<?php
require_once __DIR__ . '/_top.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = db()->prepare('DELETE FROM questions WHERE id = :id');
    $stmt->execute(['id' => (int) $_POST['delete_id']]);
    header('Location: /admin/questions.php');
    exit;
}

$rows = db()->query('SELECT id, question_text FROM questions ORDER BY id ASC')->fetchAll();
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Preguntas</title><link rel="stylesheet" href="/assets/css/styles.css"></head>
<body><div class="container">
<div class="topbar"><h1>Preguntas disponibles</h1><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/dashboard.php">Volver</a></div>
<a class="btn" href="/admin/question_new.php">Agregar pregunta</a><br><br>
<div class="card table-wrap"><table>
<thead><tr><th>N°</th><th>Pregunta</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $q): ?>
<tr><td><?= (int)$q['id'] ?></td><td><?= e($q['question_text']) ?></td><td>
<form method="post" onsubmit="return confirm('¿Eliminar pregunta?')">
<input type="hidden" name="delete_id" value="<?= (int)$q['id'] ?>">
<button type="submit">Eliminar</button>
</form></td></tr>
<?php endforeach; ?>
</tbody></table></div></div></body></html>
