<?php
require_once __DIR__ . '/_top.php';

$step = $_GET['step'] ?? '1';

if ($step === '1') {
    $correlative = generate_correlative();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['draft_reading'] = [
            'correlative' => $_POST['correlative'],
            'client_name' => trim($_POST['client_name']),
            'gender' => $_POST['gender'] ?? 'F',
            'age' => (int) ($_POST['age'] ?? 0),
            'package_code' => $_POST['package_code'] ?? 'P1',
        ];
        header('Location: /admin/new_reading.php?step=2');
        exit;
    }
    ?>
    <!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nueva lectura</title><link rel="stylesheet" href="/assets/css/styles.css"></head><body><div class="container" style="max-width:820px;">
    <div class="card"><h1>Registrar nueva lectura</h1>
      <form method="post">
      <label>Correlativo</label><input name="correlative" value="<?= e($correlative) ?>" readonly>
      <label>Nombre del cliente</label><input name="client_name" required>
      <label>Sexo</label>
      <div class="inline"><label><input type="radio" name="gender" value="M" required> Hombre</label><label><input type="radio" name="gender" value="F" checked> Mujer</label></div>
      <label>Edad</label><input type="number" name="age" min="1" max="120" required>
      <p>Selecciona paquete:</p>
      <div class="grid grid-2">
        <button name="package_code" value="P1" type="submit">1 pregunta</button>
        <button name="package_code" value="P2" type="submit">2 preguntas</button>
        <button name="package_code" value="P3" type="submit">3 preguntas</button>
        <button name="package_code" value="P5" type="submit">5 preguntas</button>
      </div>
      </form>
    </div><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/dashboard.php">Volver</a>
    </div></body></html>
    <?php
    exit;
}

$draft = $_SESSION['draft_reading'] ?? null;
if (!$draft) {
    header('Location: /admin/new_reading.php');
    exit;
}

$cards = db()->query('SELECT id,name FROM cards ORDER BY name ASC')->fetchAll();
$questions = db()->query('SELECT id,question_text FROM questions ORDER BY id ASC')->fetchAll();
$pkg = package_meta($draft['package_code']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('INSERT INTO readings (correlative,client_name,gender,age,package_code,price,session_date,reading_text) VALUES (:c,:n,:g,:a,:p,:price,CURDATE(),:rt)');
        $stmt->execute([
            'c' => $draft['correlative'],
            'n' => $draft['client_name'],
            'g' => $draft['gender'],
            'a' => $draft['age'],
            'p' => $draft['package_code'],
            'price' => $pkg['price'],
            'rt' => trim($_POST['reading_text'] ?? ''),
        ]);
        $readingId = (int) $pdo->lastInsertId();

        for ($i = 1; $i <= 10; $i++) {
            if (!isset($_POST['card_enabled'][$i])) {
                continue;
            }
            $cardId = (int) ($_POST['card_id'][$i] ?? 0);
            if ($cardId > 0) {
                $ins = $pdo->prepare('INSERT INTO reading_cards (reading_id, card_id, position) VALUES (:r,:c,:p)');
                $ins->execute(['r' => $readingId, 'c' => $cardId, 'p' => $i]);
            }
        }

        for ($q = 1; $q <= $pkg['questions']; $q++) {
            $questionId = (int) ($_POST['question_id'][$q] ?? 0);
            if ($questionId <= 0) {
                continue;
            }
            $answer = trim($_POST['answer_text'][$q] ?? '');
            $audioPath = null;
            if (!empty($_FILES['audio_file']['name'][$q])) {
                $tmp = $_FILES['audio_file']['tmp_name'][$q];
                $name = time() . '_' . basename($_FILES['audio_file']['name'][$q]);
                $target = __DIR__ . '/../uploads/audio/' . $name;
                if (move_uploaded_file($tmp, $target)) {
                    $audioPath = '/uploads/audio/' . $name;
                }
            }
            $insA = $pdo->prepare('INSERT INTO reading_answers (reading_id, question_id, answer_text, audio_path) VALUES (:r,:q,:a,:au)');
            $insA->execute(['r' => $readingId, 'q' => $questionId, 'a' => $answer, 'au' => $audioPath]);
        }

        $pdo->commit();
        unset($_SESSION['draft_reading']);
        header('Location: /public/client_view.php?code=' . urlencode($draft['correlative']));
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Seleccionar cartas</title><link rel="stylesheet" href="/assets/css/styles.css"></head><body><div class="container">
<div class="topbar"><h1>Lectura <?= e($draft['correlative']) ?> (<?= e($pkg['label']) ?>)</h1><a class="btn" style="width:auto;padding:.5rem 1rem;" href="/admin/new_reading.php">Cancelar</a></div>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<div class="card">
<form method="post" enctype="multipart/form-data">
<h3>Cartas a seleccionar (hasta 10)</h3>
<div class="table-wrap"><table><thead><tr><th>Habilitar</th><th>Carta</th></tr></thead><tbody>
<?php for ($i=1;$i<=10;$i++): ?>
<tr>
<td><input type="checkbox" name="card_enabled[<?= $i ?>]" value="1"></td>
<td><select name="card_id[<?= $i ?>]"><option value="">-- Selecciona carta --</option><?php foreach($cards as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></td>
</tr>
<?php endfor; ?>
</tbody></table></div>

<h3>Lectura general</h3>
<textarea name="reading_text" rows="5" required></textarea>

<h3>Preguntas a seleccionar</h3>
<?php for ($q=1;$q<=$pkg['questions'];$q++): ?>
<div class="card">
<label>Pregunta #<?= $q ?></label>
<select name="question_id[<?= $q ?>]" required>
<option value="">-- Selecciona pregunta --</option>
<?php foreach($questions as $quest): ?>
<option value="<?= (int)$quest['id'] ?>"><?= e($quest['question_text']) ?></option>
<?php endforeach; ?>
</select>
<label>Respuesta escrita</label>
<textarea name="answer_text[<?= $q ?>]" rows="3" placeholder="Respuesta de Heidy" required></textarea>
<label>Audio respuesta (archivo)</label>
<input type="file" name="audio_file[<?= $q ?>]" accept="audio/*">
</div>
<?php endfor; ?>

<button type="submit">Guardar lectura</button>
</form></div></div></body></html>
