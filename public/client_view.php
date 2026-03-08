<?php
require_once __DIR__ . '/../includes/helpers.php';

$code = trim($_GET['code'] ?? '');
$reading = null;
$cards = [];
$answers = [];

if ($code !== '') {
    $stmt = db()->prepare('SELECT r.*, p.label AS package_label FROM readings r JOIN packages p ON p.code = r.package_code WHERE r.correlative = :c LIMIT 1');
    $stmt->execute(['c' => $code]);
    $reading = $stmt->fetch();

    if ($reading) {
        $stmtCards = db()->prepare('SELECT c.name, c.image_path FROM reading_cards rc JOIN cards c ON c.id = rc.card_id WHERE rc.reading_id = :id ORDER BY rc.position ASC');
        $stmtCards->execute(['id' => $reading['id']]);
        $cards = $stmtCards->fetchAll();

        $stmtAns = db()->prepare('SELECT q.question_text, ra.answer_text, ra.audio_path FROM reading_answers ra JOIN questions q ON q.id = ra.question_id WHERE ra.reading_id = :id ORDER BY ra.id ASC');
        $stmtAns->execute(['id' => $reading['id']]);
        $answers = $stmtAns->fetchAll();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Tu lectura</title>
  <link rel="stylesheet" href="<?= e(app_url('assets/css/styles.css')) ?>">
</head>
<body>
<div class="container">
  <div class="topbar">
    <div class="logo">🔮 Brujosa Tarot</div>
    <a class="btn" style="width:auto;padding:.5rem 1rem;" href="<?= e(app_url('public/index.php')) ?>">Volver</a>
  </div>

  <?php if (!$reading): ?>
    <div class="card error">No encontramos una lectura para el correlativo <b><?= e($code) ?></b>.</div>
  <?php else: ?>
    <div class="card">
      <h2><?= e(greeting_by_gender($reading['gender'])) ?> <?= e($reading['client_name']) ?>, esta es tu lectura del día <?= e(date('d/m/Y', strtotime($reading['session_date']))) ?></h2>
      <p class="small">Paquete: <?= e($reading['package_label']) ?></p>
      <h3>Cartas levantadas</h3>
      <div class="cards-grid">
        <?php foreach ($cards as $card): ?>
          <div class="tarot-card">
            <img src="<?= e($card['image_path'] ?: 'https://via.placeholder.com/140x230?text=Tarot') ?>" alt="<?= e($card['name']) ?>">
            <div><?= e($card['name']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <h3>Lectura de Heidy</h3>
      <p><?= nl2br(e($reading['reading_text'])) ?></p>

      <h3>Preguntas y respuestas</h3>
      <?php foreach ($answers as $item): ?>
        <div class="card">
          <b>Pregunta:</b> <?= e($item['question_text']) ?><br>
          <b>Respuesta:</b> <?= nl2br(e($item['answer_text'])) ?><br>
          <?php if (!empty($item['audio_path'])): ?>
            <audio controls src="<?= e(app_url($item['audio_path'])) ?>"></audio>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
