<?php
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correlative = trim($_POST['correlative'] ?? '');
    header('Location: ' . app_url('public/client_view.php') . '?code=' . urlencode($correlative));
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cliente | Brujosa Tarot</title>
  <link rel="stylesheet" href="<?= e(app_url('assets/css/styles.css')) ?>">
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div class="logo">🔮 Brujosa Tarot</div>
      <a class="btn" style="width:auto;padding:.6rem 1rem;" href="<?= e(app_url('public/admin_login.php')) ?>">Acceso Heidy</a>
    </div>
    <div class="card" style="max-width:500px;margin:2rem auto;">
      <h1>Acceso de cliente</h1>
      <p>Ingresa tu correlativo para revisar tu lectura.</p>
      <form method="post">
        <label>Correlativo</label>
        <input name="correlative" required>
        <br><br>
        <button type="submit">Ver mi lectura</button>
      </form>
    </div>
  </div>
</body>
</html>
